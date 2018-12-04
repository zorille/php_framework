<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
use \SoapClient as SoapClient;
/**
 * class soap<br>
 * Gere une connexion curl.
 *
 * @package Lib
 * @subpackage WebService
 */
class soap extends abstract_log {
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $headers = "";
	/**
	 *
	 * @access protected
	 * @var curl
	 */
	private $curl = null;
	/**
	 *
	 * @access protected
	 * @var SoapClient
	 */
	private $soapClient = null;
	/**
	 *
	 * @access protected
	 * @var int
	 */
	private $soap_version = SOAP_1_1;
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $host = "";
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $port = "";
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $url = "";
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $methode = "soap";
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $wsdl = "";
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $login = "";
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $password = "";
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $force_url = "non";
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $connection = "oui";
	/**
	 *
	 * @access protected
	 * @var int
	 */
	private $connection_timeout = 120;
	/**
	 *
	 * @access protected
	 * @var int
	 */
	private $cache_wsdl = WSDL_CACHE_NONE;
	/**
	 *
	 * @access protected
	 * @var gestion_connexion_url
	 */
	private $gestion_connexion_url = null;
	/**
	 *
	 * @access protected
	 * @var string
	 */
	private $trace = 0;
	/**
	 *
	 * @access protected
	 * @var array
	 */
	private $soap_added_params = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type soap.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet 
	 * @return soap
	 */
	static function &creer_soap (
			&$liste_option, 
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new soap ( $sort_en_erreur, $entete );
		return $objet ->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise (
			$liste_class) {
		parent::_initialise ( $liste_class );
		if ($liste_class ["options"] ->getOption ( "verbose" ) > 0) {
			$this ->setTrace ( 1 );
		}
		$this ->setGestionConnexionUrl ( gestion_connexion_url::creer_gestion_connexion_url ( $liste_class ["options"] ) ) 
			->setCurlObjet ( curl::creer_curl ( $liste_class ["options"] ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Constructeur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Sort en erreur.
	 * @param string $nom_module Nom du module.
	 */
	public function __construct (
			$sort_en_erreur = "non", 
			$nom_module = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $nom_module );
	}

	/**
	 * Valide la presence des variables obligatoires dans le serveurData.
	 * @return soap|false l'objet soap si OK, False sinon
	 */
	public function valide_presence_variables () {
		$this ->onDebug ( __METHOD__, 1 );
		if ($this ->getGestionConnexionUrl () 
			->getHost () == "") {
			return $this ->onError ( "Il faut un champ host dans la definition du soap", "", 5100 );
		}
		if ($this ->getGestionConnexionUrl () 
			->getPort () == "") {
			return $this ->onError ( "Il faut un champ port dans la definition du soap", "", 5100 );
		}
		if ($this ->getUrl () == "") {
			return $this ->onError ( "Il faut un champ url dans la definition du soap", "", 5100 );
		}
		if ($this ->getWsdl () == "") {
			return $this ->onError ( "Il faut un champ wsdl dans la definition du soap", "", 5100 );
		}
		if ($this ->getLogin () == "") {
			$this ->onWarning ( "Il faut un champ username dans la definition du soap" );
		}
		if ($this ->getPassword () == "") {
			$this ->onWarning ( "Il faut un champ password dans la definition du soap" );
		}
		if ($this ->getConnexionTimeout () == "") {
			return $this ->onError ( "Il faut un champ RequestTimeout dans la definition du soap", "", 5100 );
		}
		return $this;
	}

	/**
	 * Valide la presence des variables obligatoires dans un tableau de definition du serveur.
	 * @return soap|false l'objet soap si OK, False sinon
	 */
	public function retrouve_variables_tableau (
			$serveur_data) {
		$this ->onDebug ( __METHOD__, 1 );
		if (! is_array ( $serveur_data )) {
			return $this ->onError ( "Il faut un tableau de definition du serveur", "", 5100 );
		}
		if (! isset ( $serveur_data ["url"] )) {
			return $this ->onError ( "Il faut un champ url dans la definition du serveur", "", 5100 );
		} else {
			$this ->setUrl ( $serveur_data ["url"] );
		}
		if (! isset ( $serveur_data ["wsdl"] )) {
			return $this ->onError ( "Il faut un champ wsdl dans la definition du serveur", "", 5100 );
		} else {
			$this ->setWsdl ( $serveur_data ["wsdl"] );
		}
		if (! isset ( $serveur_data ["methode"] )) {
			return $this ->onError ( "Il faut un champ methode (soap/curl) dans la definition du serveur", "", 5100 );
		} else {
			$this ->setMethode ( $serveur_data ["methode"] );
		}
		if (! isset ( $serveur_data ["username"] )) {
			return $this ->onError ( "Il faut un champ username dans la definition du serveur", "", 5100 );
		} else {
			$this ->setLogin ( $serveur_data ["username"] );
		}
		if (! isset ( $serveur_data ["password"] )) {
			return $this ->onError ( "Il faut un champ password dans la definition du serveur", "", 5100 );
		} else {
			$this ->setPassword ( $serveur_data ["password"] );
		}
		if (isset ( $serveur_data ["force_url"] )) {
			$this ->setForceUrl ( $serveur_data ["force_url"] );
		}
		if (isset ( $serveur_data ["connexion"] )) {
			$this ->setConnexion ( $serveur_data ["connexion"] );
		}
		if (! isset ( $serveur_data ["RequestTimeout"] )) {
			return $this ->onError ( "Il faut un champ RequestTimeout dans la definition du serveur", "", 5100 );
		} else {
			$this ->setRequestTimeout ( $serveur_data ["RequestTimeout"] );
			$this ->setConnexionTimeout ( $serveur_data ["RequestTimeout"] );
		}
		$this ->getGestionConnexionUrl () 
			->reset_datas () 
			->retrouve_connexion_params ( $serveur_data );
		return $this;
	}

	/**
	 * Valide la presence des variables obligatoires dans l'objet liste_options (argument et/ou conf xml).
	 * @return soap|false l'objet soap si OK, False sinon
	 */
	public function retrouve_variables_liste_options () {
		$this ->onDebug ( __METHOD__, 1 );
		if ($this ->getListeOptions () 
			->verifie_parametre_standard ( "soap[@sort_en_erreur='oui']" )) {
			$this ->setSortEnErreur ( true );
		} else {
			$this ->setSortEnErreur ( false );
		}
		if ($this ->getListeOptions () 
			->verifie_parametre_standard ( "soap[@using='oui']" ) !== false) {
			$this ->setUrl ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"url" 
			) ) );
			$this ->setLogin ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"username" 
			) ) );
			$this ->setPassword ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"password" 
			) ) );
			$this ->setForceUrl ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"force_url" 
			), "non" ) );
			$this ->setConnexion ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"connexion" 
			), "oui" ) );
			$this ->setConnexionTimeout ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"RequestTimeout" 
			) ) );
			$this ->setRequestTimeout ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"RequestTimeout" 
			) ) );
			$this ->setMethode ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"methode" 
			) ) );
			$this ->setWsdl ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap",
					"wsdl" 
			) ) );
			$this ->getGestionConnexionUrl () 
				->reset_datas () 
				->retrouve_connexion_params ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"soap" 
			) ) );
			return $this;
		}
		return null;
	}

	/******************* Gestion via SOAP ************************/
	/**
	 * Prepare les valeurs necessaire a une connexion soap via SoapClient
	 * @return string
	 */
	public function prepare_donnees_connexion () {
		$this ->onDebug ( __METHOD__, 1 );
		//Enfin on construit la liste des parametres Soap
		$liste_props = array (
				'soap_version' => $this ->getSoapVersion (),
				'exceptions' => true,
				'login' => $this ->getLogin (),
				'password' => $this ->getPassword (),
				'connection_timeout' => $this ->getConnexionTimeout (),
				'cache_wsdl' => $this ->getCacheWsdl (),
				'stream_context' => stream_context_create ( array (
						'ssl' => array (
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true 
						),
						'https' => array (
								'curl_verify_ssl_peer' => false,
								'curl_verify_ssl_host' => false 
						) 
				) ) 
		);
		$liste_props = array_merge ( $liste_props, $this ->getGestionConnexionUrl () 
			->utilise_proxy () );
		$liste_props ['trace'] = $this ->getTrace ();
		if (count ( $this ->getSoapAddedParams () ) != 0) {
			$liste_props = array_merge ( $liste_props, $this ->getSoapAddedParams () );
		}
		$this ->onDebug ( $liste_props, 2 );
		return $liste_props;
	}

	/**
	 * Connection d'un client SOAP
	 * @return soap|false
	 * @throws Exception
	 */
	public function connect () {
		$this ->onDebug ( __METHOD__, 1 );
		$this ->valide_presence_variables ();
		switch ($this ->getMethode ()) {
			case "soap" :
				$this ->getGestionConnexionUrl () 
					->prepare_prepend_url ( $this ->getUrl () . $this ->getWsdl () );
				return $this ->connect_soap ();
				break;
			case "curl" :
				$this ->getGestionConnexionUrl () 
					->prepare_prepend_url ( $this ->getUrl () );
				return $this ->connect_curl ();
				break;
			default :
		}
		return $this ->onError ( "Methode inconnue : " . $this ->getMethode () );
	}

	/**
	 * Connection d'un client SOAP
	 * @codeCoverageIgnore
	 * @return soap|false
	 */
	public function connect_soap () {
		$this ->onDebug ( __METHOD__, 1 );
		try {
			$liste_props = $this ->prepare_donnees_connexion ();
			if ($this ->getConnexion () == "oui") {
				$this ->setSoapClient ( @new SoapClient ( $this ->getGestionConnexionUrl () 
					->getPrependUrl (), $liste_props ) );
				if ($this ->getForceUrl () == "oui") {
					$this ->force_Url ();
				}
			} else {
				$liste_props ["location"] = $this ->getGestionConnexionUrl () 
					->getPrependUrl ();
				$liste_props ["uri"] = $this ->getGestionConnexionUrl () 
					->getUri ();
				$this ->setSoapClient ( @new SoapClient ( NULL, $liste_props ) );
			}
		} catch ( Exception $e ) {
			return $this ->onError ( $e ->getMessage (), "", $e ->getCode () );
		}
		return $this;
	}

	/**
	 * Force l'addresse Url dans l'objet SoapClient (setLocation)
	 * @codeCoverageIgnore
	 * @return soap
	 */
	public function force_Url () {
		$this ->getSoapClient () 
			->__setLocation ( $this ->getGestionConnexionUrl () 
			->getPrependUrl () );
		return $this;
	}

	/**
	 * Reset l'addresse Url dans l'objet SoapClient (setLocation)
	 * @codeCoverageIgnore
	 * @return soap
	 */
	public function reset_Url () {
		$this ->getSoapClient () 
			->__setLocation ();
		return $this;
	}

	/******************* Gestion via CURL ************************/
	/**
	 * Permet de creer l'entete en cas d'utilisation de CURL.
	 * @codeCoverageIgnore
	 * @param int $Content_length Taille des donnees xml du post
	 */
	private function _creerHeaderViaCurl (
			$Content_length) {
		$this ->onDebug ( __METHOD__, 1 );
		$this ->setHeader ( array (
				"Content-type: text/xml;charset=\"utf-8\"",
				"Accept: text/xml",
				"Cache-Control: no-cache",
				"Pragma: no-cache",
				"SOAPAction: " . $this ->getGestionConnexionUrl () 
					->getPrependUrl (),
				"Content-length: " . $Content_length 
		) );
	}

	/**
	 * Creer un connection Curl pour transmettre des requetes soap.
	 *
	 * @return soap|False
	 */
	public function connect_curl () {
		$this ->onDebug ( __METHOD__, 1 );
		// PHP cURL for https connection with auth
		$this ->getCurlObjet () 
			->connectService ( $this ->getGestionConnexionUrl () 
			->getPrependUrl () );
		$this ->getCurlObjet () 
			->setSslVerifyPeerAndHost ( false );
		$this ->getCurlObjet () 
			->setReturnTransfert ( true );
		if ($this ->getLogin () != "") {
			$this ->getCurlObjet () 
				->setUserPasswd ( $this ->getLogin (), $this ->getPassword () );
		}
		$this ->getCurlObjet () 
			->setHttpHAuth ( "any" );
		$this ->getCurlObjet () 
			->setTimeout ( $this ->getConnexionTimeout () );
		$proxy = $this ->getGestionConnexionUrl () 
			->utilise_proxy ();
		if (count ( $proxy ) > 0) {
			$this ->getCurlObjet () 
				->setProxy ( $proxy ["proxy_host"], $proxy ["proxy_port"], $proxy ["proxy_login"], $proxy ["proxy_password"], $proxy ["proxy_type"] );
		}
		return $this;
	}

	/**
	 * Fait une requete Soap en utilisant CuRl prepare par connect_curl.
	 * @param string $xml_post_string Xml a transmettre.
	 * @return string|False retour de la requete Curl
	 */
	public function send_curl_soap_requete (
			$xml_post_string) {
		$this ->onDebug ( __METHOD__, 1 );
		$this ->_creerHeaderViaCurl ( strlen ( $xml_post_string ) );
		$this ->getCurlObjet () 
			->setPostData ( $xml_post_string ); // the SOAP request
		$this ->getCurlObjet () 
			->setHttpHeader ( $this ->getHeader () );
		$response = $this ->getCurlObjet () 
			->send_curl ();
		$this ->onDebug ( $response, 2 );
		return $response;
	}

	/******************** Accesseurs *****************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getHeader () {
		return $this ->headers;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setHeader (
			$data) {
		$this ->headers = $data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getCurlObjet () {
		return $this ->curl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCurlObjet (
			$curl) {
		$this ->curl = $curl;
		return $this;
	}

	/******************** Accesseurs *****************/
	/******************* Gestion via CURL ************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function __clone () {
		// Force la copie de this->xxx, sinon
		// il pointera vers le meme objet.
		if (is_object ( $this ->curl ))
			$this ->curl = clone $this ->getCurlObjet ();
		if (is_object ( $this ->soapClient ))
			$this ->soapClient = clone $this ->getSoapClient ();
		if (is_object ( $this ->gestion_connexion_url ))
			$this ->gestion_connexion_url = clone $this ->getGestionConnexionUrl ();
	}

	/******************** Accesseurs *****************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getSoapClient () {
		return $this ->soapClient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSoapClient (
			$soapClient) {
		$this ->soapClient = $soapClient;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSoapVersion () {
		return $this ->soap_version;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSoapVersion (
			$soap_version) {
		$this ->soap_version = $soap_version;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setRequestTimeout (
			$request_timeout) {
		if (is_numeric ( $request_timeout ) && $request_timeout > 0)
			ini_set ( 'default_socket_timeout', $request_timeout );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCacheWsdl () {
		return $this ->cache_wsdl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCacheWsdl (
			$cache_wsdl) {
		$this ->cache_wsdl = $cache_wsdl;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getGestionConnexionUrl () {
		return $this ->gestion_connexion_url;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGestionConnexionUrl (
			&$gestion_connexion_url) {
		$this ->gestion_connexion_url = $gestion_connexion_url;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTrace () {
		return $this ->trace;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTrace (
			$trace) {
		$this ->trace = $trace;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUrl () {
		return $this ->url;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUrl (
			$url) {
		$this ->url = $url;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMethode () {
		return $this ->methode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMethode (
			$methode) {
		if ($methode == "soap" || $methode == "curl" || $methode == "TEST") {
			$this ->methode = $methode;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdl () {
		return $this ->wsdl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWsdl (
			$wsdl) {
		$this ->wsdl = $wsdl;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLogin () {
		return $this ->login;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLogin (
			$login) {
		$this ->login = $login;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassword () {
		return $this ->password;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword (
			$password) {
		$this ->password = $password;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getForceUrl () {
		return $this ->force_url;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setForceUrl (
			$force_url) {
		$this ->force_url = $force_url;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getConnexion () {
		return $this ->connection;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnexion (
			$connection) {
		$this ->connection = $connection;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getConnexionTimeout () {
		return $this ->connection_timeout;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnexionTimeout (
			$connection_timeout) {
		$this ->connection_timeout = $connection_timeout;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSoapAddedParams () {
		return $this ->soap_added_params;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSoapAddedParams (
			$soap_added_params) {
		$this ->soap_added_params = $soap_added_params;
		return $this;
	}

	/******************** Accesseurs *****************/
	/******************* Gestion via SOAP ************************/
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help () {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "La definition d'un serveur doit contenir, au format XML :";
		$help [__CLASS__] ["text"] [] .= "<soap using='oui' sort_en_erreur='oui'>";
		$help [__CLASS__] ["text"] [] .= " <host>host</host>";
		$help [__CLASS__] ["text"] [] .= " <port>80</port>";
		$help [__CLASS__] ["text"] [] .= " <url>/webservice/ws</url>";
		$help [__CLASS__] ["text"] [] .= " <methode>soap/curl</methode>";
		$help [__CLASS__] ["text"] [] .= " <username>hostname</username>";
		$help [__CLASS__] ["text"] [] .= " <password>passwd</password>";
		$help [__CLASS__] ["text"] [] .= " <RequestTimeout>12000</RequestTimeout>";
		$help [__CLASS__] ["text"] [] .= " <wsdl>APISiteScopeImpl</wsdl>";
		$help [__CLASS__] ["text"] [] .= " <connexion>oui/non</connexion> //Doit charger le Wsdl ou non";
		$help [__CLASS__] ["text"] [] .= "</soap>";
		$help = array_merge ( $help, gestion_connexion_url::help () );
		return $help;
	}
/******************** HELP **********************/
}
?>