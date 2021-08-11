<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

use Exception as Exception;

/**
 * class wsclient<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage WebService
 */
class wsclient extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $url = "";
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $params = array ();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $post_datas = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $http_method = "GET";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $http_entete = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $content_type = "text/plain";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $accept = "text/plain";
	/**
	 * @access protected
	 * @var int
	 */
	private $connection_timeout = 120;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $no_connexion = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $validSSLcert = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $httpAuth = 'any';
	/**
	 * @access private
	 * @var gestion_connexion_url
	 */
	private $gestion_connexion_url = null;
	/**
	 * @access private
	 * @var curl
	 */
	private $objet_curl = null;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $force_param_url = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $collect_header = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $header_data = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $curl_info = "";

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param object $datas NULL
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return wsclient
	 */
	static function &creer_wsclient(
			&$liste_option,
			&$datas = null,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new wsclient ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return wsclient
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		$this->setGestionConnexionUrl ( gestion_connexion_url::creer_gestion_connexion_url ( $liste_class ["options"] ) )
			->setObjetCurl ( curl::creer_curl ( $liste_class ["options"] ) );
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
	 * @return true
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		return true;
	}

	/**
	 * Valide la presence des variables obligatoires dans un tableau de definition du serveur.
	 * @return wsclient|false l'objet wsclient si OK, False sinon
	 */
	public function retrouve_variables_tableau(
			$serveur_data) {
		$this->onDebug ( __METHOD__, 1 );
		if (! isset ( $serveur_data ["url"] )) {
			return $this->onError ( "Il faut un champ url dans la definition du serveur", "", 5100 );
		} else {
			$this->setUrl ( $serveur_data ["url"] );
		}
		if (isset ( $serveur_data ["RequestTimeout"] )) {
			$this->setConnexionTimeout ( $serveur_data ["RequestTimeout"] );
		}
		$this->getGestionConnexionUrl ()
			->reset_datas ()
			->retrouve_connexion_params ( $serveur_data );
		return $this;
	}

	/**
	 * Prepare la liste des variables specifique au wsclient.
	 * @return wsclient
	 */
	public function retrouve_variables_liste_options() {
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"wsclient",
				"url"
		) ) );
		$this->setConnexionTimeout ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"wsclient",
				"RequestTimeout"
		), 120 ) );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "no_wsclient" )) {
			$this->setNoconnexion ( true );
		} else {
			$this->setNoconnexion ( false );
		}
		$this->getGestionConnexionUrl ()
			->reset_datas ()
			->retrouve_connexion_params ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"wsclient"
		) ) );
		return $this;
	}

	/**
	 * Creation d'entete HTTP standard
	 * @return wsclient
	 */
	public function prepare_html_entete() {
		$this->onDebug ( __METHOD__, 1 );
		return $this->setHttpHeader ( array (
				"Content-Type: " . $this->getContentType (),
				"Accept: " . $this->getAccept ()
		) );
	}

	/**
	 * Nettoie le retour JSon contenant {"message":"","success":true,"return_code":0}
	 * @param string $retour_json
	 * @param boolean $return_array
	 * @return array
	 */
	public function traite_retour_json(
			$retour_json,
			$return_array = true) {
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( "Retour JSON selectionne", 2 );
		$this->onDebug ( $retour_json, 2 );
		// si le retour est en JSON, on le decode
		// Si le json contient l'ajout du framework, on le traite separement
		if (strpos ( $retour_json, '{"message":"","success":true,"return_code":0}' ) !== false) {
			$retour_json = str_replace ( '{"message":"","success":true,"return_code":0}', "", $retour_json );
			$tableau_resultat = json_decode ( $retour_json, true );
			$tableau_resultat ["success"] = true;
			$tableau_resultat ["return_code"] = 0;
			$tableau_resultat ["message"] = "";
		} else {
			$tableau_resultat = json_decode ( $retour_json, $return_array );
		}
		$this->onDebug ( "tableau JSON de retour :", 2 );
		$this->onDebug ( $tableau_resultat, 2 );
		return $tableau_resultat;
	}

	/**
	 * Envoi la requete de type CuRL par defaut et attend un retour CuRL.
	 *
	 * @return array|boolean resultat du json ou false en cas d'erreur
	 * @throws Exception
	 */
	public function envoi_requete() {
		$this->onDebug ( __METHOD__, 1 );
		$url = $this->prepare_url_standard ();
		$this->onDebug ( "Url = " . $url, 1 );
		// Si la connexion est desactive par le parametre no_wsclient
		if ($this->getNoconnexion () === true) {
			$this->onInfo ( "Connexion desactivee pour l'url : " . $url );
			return array ();
		}
		// On prepare le Curl
		$this->getObjetCurl ()
			->connectService ( $url );
		try {
			$this->gere_curl_options ();
			// On applique la requete
			$retour_curl = $this->getObjetCurl ()
				->send_curl ();
			if ($this->getCollectHeader ()) {
				$this->setCurlInfo ( $this->getObjetCurl ()
					->getCurlInfos () );
				$header_size = $this->getCurlInfo () ['header_size'];
				$this->setHeaderData ( substr ( $retour_curl, 0, $header_size ) );
				$retour_curl = substr ( $retour_curl, $header_size );
			}
		} catch ( Exception $e ) {
			return $this->onError ( "Requete " . $url . " en erreur", $e->getMessage (), 4500 );
		}
		// On ferme la connexion
		$this->getObjetCurl ()
			->close ();
		$this->onDebug ( "Retour de CURL", 2 );
		$this->onDebug ( $retour_curl, 2 );
		return $retour_curl;
	}

	/**
	 * Ajoute un hearder HTTP. Par defaut : Content-Type: application/json Necessite un connexion curl active
	 * @param string $header
	 * @return wsclient
	 */
	public function gere_header() {
		// On ajoute les donnees sur une connexion active uniquement
		$header = $this->getHttpHeader ();
		if ($header == '') {
			$header = "Content-Type: application/json";
		}
		if (! is_array ( $header )) {
			$header = array (
					$header
			);
		}
		$this->getObjetCurl ()
			->setHttpHeader ( $header );
		return $this;
	}

	/**
	 * Valide les options fournit en argument
	 * @return wsclient
	 */
	public function gere_curl_options() {
		// On gere les differents parmetres d'une requete
		// le besoin d'avoir le header dans la reponse
		if ($this->getCollectHeader ()) {
			$this->getObjetCurl ()
				->setHeader ( true )
				->setReturnTransfert ( true );
		}
		// le Verbose
		if ($this->getListeOptions ()
			->getOption ( "verbose" ) == 3) {
			$this->getObjetCurl ()
				->setVerbose ();
		}
		// le Connect Time Out
		if ($this->getListeOptions ()
			->verifie_option_existe ( "curl_connecttimeout" ) !== false) {
			$this->getObjetCurl ()
				->curl_connecttimeout ( $this->getListeOptions ()
				->getOption ( "curl_connecttimeout" ) );
		}
		// le Follow header Location:
		if ($this->getListeOptions ()
			->verifie_option_existe ( "curl_nofollowlocation" ) === false) {
			$this->getObjetCurl ()
				->setLocation ( true );
		}
		//gestion des utilisateurs et du proxy
		$this->gere_request ()
			->gere_utilisateurs ()
			->gere_proxy ()
			->gere_header ();
		// On invalide le check SSL du certificat si necessaire
		if ($this->getValidSSL () === false) {
			$this->getObjetCurl ()
				->setSslVerifyPeerAndHost ( false );
		}
		return $this;
	}

	/**
	 * Gere le type de request (GET, POST, PUt ou DELETE) En cas de request differente de GET : ajoute les donnees en mode POSTDATA Necessite un connexion curl active
	 * @return wsclient
	 */
	public function gere_request() {
		$this->getObjetCurl ()
			->setRequest ( $this->getHttpMethod () );
		if ($this->getHttpMethod () != "GET") {
			$this->gere_post_data ();
		}
		return $this;
	}

	/**
	 * Ajoute les donnees en mode POSTDATA si la request est de type POST Necessite un connexion curl active
	 * @return wsclient
	 */
	public function gere_post_data() {
		if ($this->getHttpMethod () == "POST" || $this->getHttpMethod () == "PUT" || $this->getHttpMethod () == "PATCH") {
			if ($this->getPostDatas () != "") {
				$this->getObjetCurl ()
					->setPostData ( $this->getPostDatas () );
			} else {
				$this->getObjetCurl ()
					->setPostData ( $this->getParams () );
			}
		}
		return $this;
	}

	/**
	 * Ajoute l'utilisateur et son mon de passe dans le header HTTP Necessite un connexion curl active
	 * @return wsclient
	 */
	public function gere_utilisateurs() {
		// Si un User/Pass est defini
		if ($this->getGestionConnexionUrl ()
			->getObjetUtilisateurs ()
			->getUsername () !== "") {
			$this->getObjetCurl ()
				->setUserPasswd ( $this->getGestionConnexionUrl ()
				->getObjetUtilisateurs ()
				->getUsername (), $this->getGestionConnexionUrl ()
				->getObjetUtilisateurs ()
				->getPassword () )
				->setHttpHAuth ( $this->getHttpAuth () );
		}
		return $this;
	}

	/**
	 * Ajoute les donnees du proxy, s'il existe Necessite un connexion curl active
	 * @return wsclient
	 */
	public function gere_proxy() {
		// Si un proxy est defini
		if ($this->getGestionConnexionUrl ()
			->valide_proxy_existe ()) {
			$proxy_data = $this->getGestionConnexionUrl ()
				->utilise_proxy ();
			// On prepare le CuRL avec les parametres du proxy
			$this->getObjetCurl ()
				->setProxy ( $proxy_data ["proxy_host"], $proxy_data ["proxy_port"], $proxy_data ["proxy_login"], $proxy_data ["proxy_password"], $proxy_data ["proxy_type"] );
		}
		return $this;
	}

	/**
	 * Construit une url standard a partir du getHost et getUrl.<br/> Choisi entre la methode GET ou POST
	 * @return string url construite
	 */
	public function prepare_url_standard() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getHttpMethod () == "GET" || $this->getForceParamInUrl ()) {
			return $this->prepare_url_get ();
		}
		// On ajoute les donnees post sur une connexion active uniquement
		return $this->getGestionConnexionUrl ()
			->getPrependUrl () . $this->getUrl ();
	}

	/**
	 * Construit une url standard a partir du getHost et getUrl + la liste des parametres en GET.
	 * @return string url construite
	 */
	public function prepare_url_get() {
		$this->onDebug ( __METHOD__, 1 );
		$url = $this->getGestionConnexionUrl ()
			->getPrependUrl () . $this->getUrl ();
		if (count ( $this->getParams () ) !== 0) {
			$url .= "?" . http_build_query ( $this->getParams () );
		}
		return $url;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUrl(
			$url) {
		$this->url = $url;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNoconnexion() {
		return $this->no_connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNoconnexion(
			$no_connexion) {
		if (is_bool ( $no_connexion )) {
			$this->no_connexion = $no_connexion;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Param est obligatoirement un array, en cas de string type url utiliser setPostDatas
	 * @codeCoverageIgnore
	 */
	public function &setParams(
			$param,
			$value = "",
			$add = false) {
		if ($add) {
			$this->params [$param] = $value;
		} else {
			if (is_array ( $param )) {
				$this->params = $param;
			} else {
				$this->params = array (
						$param => $value
				);
			}
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPostDatas() {
		return $this->post_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPostDatas(
			$post_datas) {
		$this->post_datas = $post_datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHttpMethod() {
		return $this->http_method;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHttpMethod(
			$http_method) {
		$this->http_method = strtoupper ( $http_method );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHttpHeader() {
		return $this->http_entete;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHttpHeader(
			$http_entete) {
		$this->http_entete = $http_entete;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getContentType() {
		return $this->content_type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setContentType(
			$content_type) {
		$this->content_type = $content_type;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAccept() {
		return $this->accept;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAccept(
			$accept) {
		$this->accept = $accept;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getConnexionTimeout() {
		return $this->connection_timeout;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnexionTimeout(
			$connection_timeout) {
		$this->connection_timeout = $connection_timeout;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValidSSL() {
		return $this->validSSLcert;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValidSSL(
			$validSSLcert) {
		$this->validSSLcert = $validSSLcert;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHttpAuth() {
		return $this->httpAuth;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHttpAuth(
			$httpAuth) {
		$this->httpAuth = $httpAuth;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return gestion_connexion_url
	 */
	public function &getGestionConnexionUrl() {
		return $this->gestion_connexion_url;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGestionConnexionUrl(
			&$gestion_connexion_url) {
		$this->gestion_connexion_url = $gestion_connexion_url;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return curl
	 */
	public function &getObjetCurl() {
		return $this->objet_curl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetCurl(
			&$curl) {
		$this->objet_curl = $curl;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getForceParamInUrl() {
		return $this->force_param_url;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setForceParamInUrl(
			$force_param_url) {
		if (is_bool ( $force_param_url )) {
			$this->force_param_url = $force_param_url;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getCollectHeader() {
		return $this->collect_header;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCollectHeader(
			$collect_header) {
		$this->collect_header = $collect_header;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getHeaderData() {
		return $this->header_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHeaderData(
			$header_data) {
		$this->header_data = $header_data;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getCurlInfo() {
		return $this->curl_info;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCurlInfo(
			$curl_info) {
		$this->curl_info = $curl_info;
		return $this;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Generique aux webservices";
		$help [__CLASS__] ["text"] [] .= "\t--wsclient_host host de connexion (http://127.0.0.1:8080)";
		$help [__CLASS__] ["text"] [] .= "\t--wsclient_url url de connexion (/test/ws)";
		$help [__CLASS__] ["text"] [] .= "\t--no_wsclient n'envoi pas les requetes sur le webservice";
		$help [__CLASS__] ["text"] [] .= "\t--curl_connecttimeout {time in second} Temps de connection Time Out";
		$help [__CLASS__] ["text"] [] .= "\t--curl_nofollowlocation Desactive le Follow header Location:";
		$help [__CLASS__] ["text"] [] .= "\t--verbose 3 pour active le verbose de CuRL";
		$help = array_merge ( $help, gestion_connexion_url::help () );
		$help = array_merge ( $help, curl::help () );
		return $help;
	}
}
?>
