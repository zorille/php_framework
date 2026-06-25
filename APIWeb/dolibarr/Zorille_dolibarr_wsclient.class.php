<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\dolibarr;

use stdClass;
use Zorille\framework as Core;
use Exception as Exception;
use Zorille\framework\options;

/**
 * class wsclient<br> Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage dolibarr
 */
class wsclient extends Core\wsclient {
	/**
	 * var privee
	 * @access private
	 * @var datas
	 */
	private $datas = null;
	/**
	 * var privee
	 * @access private
	 * @var array.
	 */
	private $defaultParams = array ();
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $auth = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param object|null $datas &$datas Reference sur un objet datas
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return wsclient
	 * @throws Exception
	 */
	static function &creer_wsclient(
		options     &$liste_option,
		object      &$datas = null,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): wsclient {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new wsclient ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"datas" => $datas
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return self|bool
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		if (! isset ( $liste_class ["datas"] )) {
			$r = $this->onError ( "il faut un objet de type datas" );
			return $r;
		}
		$this->setObjetdolibarrDatas ( $liste_class ["datas"] )
			->setContentType ( 'application/x-www-form-urlencoded' )
			->setAccept ( 'application/json' );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de wsclient
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au dolibarr nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): wsclient|bool|static {
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_dolibarr = $this->getObjetdolibarrDatas ()
			->valide_presence_data ( $nom );
		if (!$liste_data_dolibarr) {
			return $this->onError ( "Aucune definition de dolibarr pour " . $nom );
		}
		if (! isset ( $liste_data_dolibarr ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres dolibarr" );
		}
		if (! isset ( $liste_data_dolibarr ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres dolibarr" );
		}
		if (! isset ( $liste_data_dolibarr ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres dolibarr" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_dolibarr )
			->prepare_prepend_url ( $liste_data_dolibarr ["url"] );
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->userLogin ( array (
				'login' => $liste_data_dolibarr ["username"],
				'password' => $liste_data_dolibarr ["password"]
		) );
		return $this;
	}

	/**
	 * Http dolibarr header creator
	 *
	 * @return wsclient Http Header
	 */
	public function prepare_html_entete(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getAuth ()) {
			return $this->setHttpHeader ( array (
					"Content-Type: " . $this->getContentType (),
					"DOLAPIKEY: " . $this->getAuth ()
			) );
		}
		return $this->setHttpHeader ( array (
				"Content-Type: " . $this->getContentType ()
		) );
	}

	/**
	 * Http dolibarr params creator
	 *
	 * @return wsclient
	 */
	public function prepare_params(): static {
		$this->onDebug ( __METHOD__, 1 );
		// if ( $this ->getAuth () ) {
		// $this ->setParams ( 'output_mode', 'xml', true );
		// }
		return $this;
	}

	/**
	 * Convert return data to array
	 *
	 * @param $retour_wsclient
	 * @return array|stdClass
	 */
	public function prepare_retour(
			$retour_wsclient): array|stdClass {
		$this->onDebug ( __METHOD__, 1 );
		return $this->traite_retour_json ( $retour_wsclient );
	}

	/**
	 * Sends are prepare_requete_json to the dolibarr API and returns the response as object.
	 *
	 * @return array|string API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete(): array|string {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) && ($this->getHttpMethod () == 'POST' || $this->getHttpMethod () == 'PUT' || $this->getHttpMethod () == 'DELETE')) {
			$this->onInfo ( "DRY RUN :" . $this->getUrl () );
			$this->onInfo ( "DRY RUN :" . print_r ( $this->getParams (), true ) );
			$this->onInfo ( "DRY RUN : HTTP METHOD : " . $this->getHttpMethod () );
			if ($this->getHttpMethod () == 'POST') {
				$this->onInfo ( "DRY RUN :" . print_r ( $this->getPostDatas (), true ) );
			}
		} else {
			$retour_wsclient = $this->prepare_html_entete ()
				->envoi_requete ();
			$retour = $this->prepare_retour ( $retour_wsclient );
			$this->onDebug ( $retour, 2 );
			return $retour;
		}
		return "";
	}

	/**
	 * @param string $param
	 * @param boolean|integer|string $valeur
	 * @return wsclient
	 * @throws Exception
	 */
	public function modifie_default_param(
		string          $param,
		bool|int|string $valeur): static {
		$default_params = $this->getDefaultParams ();
		$default_params [$param] = $valeur;
		return $this->setDefaultParams ( $default_params );
	}

	/**
	 * *********************** API dolibarr **********************
	 */
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	final public function userLogin(
		array $params = array ()): bool|static {
		$this->onDebug ( __METHOD__, 1 );
		// $resultat = $this ->postMethod ( 'login', $params );
		// if (isset ( $resultat["success"] ) && isset($resultat["success"]['code']) && $resultat["success"]['code']=='200') {
		// return $this ->setAuth ( ( string ) $resultat['success']['token'] );
		// }
		if (isset ( $params ['password'] )) {
			return $this->setAuth ( $params ['password'] );
		}
		return $this->onError ( "Erreur durant l'autentification", $resultat );
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return array|string
	 * @throws Exception
	 */
	public function getMethod(
		string $resource,
		array  $params = array ()): array|string {
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "GET" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return array|string
	 * @throws Exception
	 */
	public function postMethod(
		string $resource,
		array  $params = array ()): bool|array|string|stdClass {
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "POST" )
			->setPostDatas ( http_build_query ( $full_params ) );
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return array|string
	 * @throws Exception
	 */
	public function putMethod(
		string $resource,
		array  $params = array ()): array|string {
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "PUT" )
			->setPostDatas ( http_build_query ( $full_params ) );
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return array|string
	 * @throws Exception
	 */
	public function deleteMethod(
		string $resource,
		array  $params = array ()): array|string {
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "DELETE" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * *********************** API dolibarr **********************
	 */
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas|null
	 */
	public function &getObjetDolibarrDatas(): ?datas {
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetDolibarrDatas(
			&$datas): static {
		$this->datas = $datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getAuth(): string {
		return $this->auth;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuth(
			$auth): static {
		$this->auth = $auth;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Returns the default params.
	 *
	 * @retval  array   Array with default params.
	 */
	public function getDefaultParams(): array {
		return $this->defaultParams;
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Sets the default params.
	 *
	 * @param $defaultParams array with default params.
	 * @retrun wsclient
	 *
	 * @throws Exception
	 */
	public function setDefaultParams(
		array $defaultParams): bool|static {
		if (is_array ( $defaultParams ))
			$this->defaultParams = $defaultParams;
		else
			return $this->onError ( 'The argument defaultParams on setDefaultParams() has to be an array.' );
		return $this;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = [
			'dolibarr Wsclient :',
			"\t--dry-run n'applique pas les changements"
		];
		return array_merge ( $help, datas::help () );
	}
}
