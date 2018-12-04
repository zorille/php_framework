<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
use \SimpleXMLElement as SimpleXMLElement;
/**
 * class librenms_wsclient<br>
 *
 * Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage librenms
 */
class librenms_wsclient extends wsclient {
	/**
	 * var privee
	 * @access private
	 * @var librenms_datas
	 */
	private $librenms_datas = null;
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

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type librenms_wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param librenms_datas &$librenms_datas Reference sur un objet librenms_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return librenms_wsclient
	 */
	static function &creer_librenms_wsclient(&$liste_option, &$librenms_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new librenms_wsclient ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"librenms_datas" => $librenms_datas ) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return librenms_wsclient
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["librenms_datas"] )) {
			$this ->onError ( "il faut un objet de type librenms_datas" );
			return false;
		}
		$this ->setObjetlibrenmsDatas ( $liste_class ["librenms_datas"] );
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
		//Gestion de wsclient
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au librenms nomme $nom
	 * @param string $nom
	 * @return boolean|librenms_wsclient
	 * @throws Exception
	 */
	public function prepare_connexion($nom) {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_data_librenms = $this ->getObjetlibrenmsDatas () 
			->valide_presence_librenms_data ( $nom );
		if ($liste_data_librenms === false) {
			return $this ->onError ( "Aucune definition de librenms pour " . $nom );
		}
		
		if (! isset ( $liste_data_librenms ["url"] )) {
			return $this ->onError ( "Il faut une url dans la liste des parametres librenms" );
		}
		
		$this ->getGestionConnexionUrl () 
			->retrouve_connexion_params ( $liste_data_librenms ) 
			->prepare_prepend_url ( $liste_data_librenms ["url"] );
		
		return $this;
	}

	/**
	 * Http Librenms header creator
	 *
	 * @return  string Http Header
	 */
	public function prepare_html_entete() {
		$this ->onDebug ( __METHOD__, 1 );
		
		if ( $this ->getAuth () ) {
			return array ( 
					"Content-Type: application/json-rpc", 
					"X-Auth-Token: " . $this ->getAuth () );
		}
		
		return array ( 
				"Content-Type: application/json-rpc" );
	}

	/**
	 * Convert return data to array
	 *
	 * @return array
	 * @throws Exception
	 */
	public function prepare_retour($retour_wsclient) {
		$this ->onDebug ( __METHOD__, 1 );
		
		return simplexml_load_string ( $retour_wsclient );
	}

	/**
	 * Sends are prepare_requete_json to the librenms API and returns the response as object.
	 *
	 * @return  string    API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete() {
		$this ->onDebug ( __METHOD__, 1 );
		
		if ($this ->getListeOptions () 
				->verifie_option_existe ( "dry-run" ) && ($this ->getHttpMethod () == 'POST' || $this ->getHttpMethod () == 'PUT' || $this ->getHttpMethod () == 'DELETE')) {
			$this ->onInfo ( "DRY RUN :" . $this ->getUrl () );
			$this ->onInfo ( "DRY RUN :" . print_r ( $this ->getParams (), true ) );
		} else {
			$header = $this ->prepare_html_entete ();
			
			$retour_wsclient = $this ->envoi_requete ( $header );
			
			$retour = $this ->prepare_retour ( $retour_wsclient );
			$this ->onDebug ( $retour, 2 );
			
			return $retour;
		}
		
		return "";
	}

	/************************* API librenms ***********************/
	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function getMethod($resource, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this ->getDefaultParams (), $params );
		$this ->setUrl ( $resource ) 
			->setHttpMethod ( "GET" ) 
			->setParams ( $full_params );
		
		return $this ->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function postMethod($resource, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this ->getDefaultParams (), $params );
		$this ->setUrl ( $resource ) 
			->setHttpMethod ( "POST" ) 
			->setPostDatas ( http_build_query ( $full_params ) );
		
		return $this ->prepare_requete ();
	}
	
	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function putMethod($resource, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this ->getDefaultParams (), $params );
		$this ->setUrl ( $resource )
		->setHttpMethod ( "PUT" )
		->setPostDatas ( http_build_query ( $full_params ) );
		
		return $this ->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function deleteMethod($resource, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this ->getDefaultParams (), $params );
		$this ->setUrl ( $resource ) 
			->setHttpMethod ( "DELETE" ) 
			->setParams ( $full_params );
		
		return $this ->prepare_requete ();
	}

	/************************* API librenms ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return librenms_datas
	 */
	public function &getObjetlibrenmsDatas() {
		return $this->librenms_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetlibrenmsDatas(&$librenms_datas) {
		$this->librenms_datas = $librenms_datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getAuth() {
		return $this->auth;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuth($auth) {
		$this->auth = $auth;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Returns the default params.
	 *
	 * @retval  array   Array with default params.
	 */
	public function getDefaultParams() {
		return $this->defaultParams;
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Sets the default params.
	 *
	 * @param   $defaultParams  Array with default params.
	 *
	 * @retrun librenms_wsclient
	 *
	 * @throws  Exception
	 */
	public function setDefaultParams($defaultParams) {
		if (is_array ( $defaultParams ))
			$this->defaultParams = $defaultParams;
		else
			return $this ->onError ( 'The argument defaultParams on setDefaultParams() has to be an array.' );
		
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
		$help [__CLASS__] ["text"] [] .= "librenms Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, librenms_datas::help () );
		
		return $help;
	}
}

?>
