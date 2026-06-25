<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use Exception;

/**
 * class aws_wsclient<br>
 *
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage Aws
 */
class aws_wsclient extends wsclient {
	/**
	 * var privee
	 * @access private
	 * @var aws_datas
	 */
	private $aws_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $AWSAccessKeyId = null;
	/**
	* var privee
	* @access private
	* @var string
	*/
	private $AWSSecretKeyId = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type aws_wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param aws_datas &$aws_datas Reference sur un objet aws_datas
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return aws_wsclient
	 * @throws Exception
	 */
	static function &creer_aws_wsclient(
		options     &$liste_option,
		aws_datas   &$aws_datas,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): aws_wsclient {
		$objet = new aws_wsclient ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"aws_datas" => $aws_datas 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return bool|self
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["aws_datas"] )) {
			$r = $this->onError ( "il faut un objet de type aws_datas" );
			return $r;
		}
		$this->setObjetAwsDatas ( $liste_class ["aws_datas"] );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		return true;
	}

	/**
	 * Prepare l'url de connexion au aws nomme $nom
	 * @param string $nom
	 * @return boolean|aws_wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(string $nom): aws_wsclient|bool|static {
		$liste_data_aws = $this->getObjetAwsDatas ()
			->valide_presence_aws_data ( $nom );
		if (!$liste_data_aws) {
			return $this->onError ( "Aucune definition de aws pour " . $nom );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_aws )
			->prepare_prepend_url ();
		
		return $this;
	}

	/**
	 * Execute la requete
	 * @return array|false tableau de resultat, FALSE sinon
	 * @throws Exception
	 */
	public function execute_requete_aws(): bool|array {
		$query_time = time ();
		
		$this->setParams ( 'AWSAccessKeyId', $this->getAWSAccessKeyId (), true );
		$this->setParams ( 'Timestamp', gmdate ( 'Y-m-d\TH:i:s\Z' ), true );
		$this->setParams ( 'SignatureVersion', 2, true );
		$this->setParams ( 'SignatureMethod', "HmacSHA256", true );
		
		// EC2 API necessite un ordre alphabetique dans les parametres
		$tampon = $this->getParams ();
		uksort ( $tampon, 'strnatcmp' );
		$this->setParams ( $tampon );
		
		//On fabrique la signature en HmacSHA256
		$http_get_string_wsclient = "GET\n" . $this->getGestionConnexionUrl ()
			->getHost () . "\n" . "/\n" . http_build_query ( $this->getParams () );
		$this->setParams ( 'Signature', $this->getAWSSignature ( $http_get_string_wsclient ), true );
		
		$http_request_result = $this->envoi_requete (  );
		$this->onDebug ( $http_request_result, 2 );
		
		//Validation du retour
		if (is_null ( $http_request_result ) or empty ( $http_request_result )) {
			return $this->onError ( "Pas de resultat de AWS", $http_request_result );
		}
		
		//On converti le resultat en tableau
		$xml_object = $this->getListeOptions ()
			->simpleXmlElement_to_array ( simplexml_load_string ( $http_request_result ) );
		$this->onDebug ( $xml_object, 2 );
		
		return $xml_object;
	}

	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return string|null
	 */
	public function getAWSAccessKeyId(): ?string {
		return $this->AWSAccessKeyId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAWSAccessKeyId(&$AWSAccessKeyId): static {
		$this->AWSAccessKeyId = $AWSAccessKeyId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string|null
	 */
	public function getAWSSecretKeyId(): ?string {
		return $this->AWSSecretKeyId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAWSSecretKeyId(&$AWSSecretKeyId): static {
		$this->AWSSecretKeyId = $AWSSecretKeyId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @param $http_get_string
	 * @return string
	 */
	public function getAWSSignature($http_get_string): string {
		return base64_encode ( hash_hmac ( 'sha256', $http_get_string, $this->getAWSSecretKeyId (), true ) );
	}

	/**
	 * @codeCoverageIgnore
	 * @return aws_datas|null
	 */
	public function &getObjetAwsDatas(): ?aws_datas {
		return $this->aws_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetAwsDatas(&$aws_datas): static {
		$this->aws_datas = $aws_datas;
		return $this;
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = [];
		
		return $help;
	}
}
