<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

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
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param aws_datas &$aws_datas Reference sur un objet aws_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return aws_wsclient
	 */
	static function &creer_aws_wsclient(&$liste_option, &$aws_datas, $sort_en_erreur = false, $entete = __CLASS__) {
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
	 * @return aws_wsclient
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["aws_datas"] )) {
			return $this->onError ( "il faut un objet de type aws_datas" );
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
	 */
	public function prepare_connexion($nom) {
		$liste_data_aws = $this->getObjetAwsDatas ()
			->valide_presence_aws_data ( $nom );
		if ($liste_data_aws === false) {
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
	 */
	public function execute_requete_aws() {
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
	 * @return string
	 */
	public function getAWSAccessKeyId() {
		return $this->AWSAccessKeyId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAWSAccessKeyId(&$AWSAccessKeyId) {
		$this->AWSAccessKeyId = $AWSAccessKeyId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getAWSSecretKeyId() {
		return $this->AWSSecretKeyId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAWSSecretKeyId(&$AWSSecretKeyId) {
		$this->AWSSecretKeyId = $AWSSecretKeyId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getAWSSignature($http_get_string) {
		return base64_encode ( hash_hmac ( 'sha256', $http_get_string, $this->getAWSSecretKeyId (), true ) );
	}

	/**
	 * @codeCoverageIgnore
	 * @return aws_datas
	 */
	public function &getObjetAwsDatas() {
		return $this->aws_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetAwsDatas(&$aws_datas) {
		$this->aws_datas = $aws_datas;
		return $this;
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

?>
