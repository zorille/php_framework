<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class CURL<br>
 * 
 * Gere une connexion curl.
 * @package Lib
 * @subpackage Flux
 */
class curl extends abstract_log {
	/**
	 * @access protected
	 * @var resource
	 */
	private $connexion;
	/**
	 * @access protected
	 * @var string
	 */
	private $curl;
	/**
	 * @access protected
	 * @var string
	 */
	private $code_retour_curl=200;
	/**
	 * @access protected
	 * @var boolean
	 */
	private $valide_code_retour=true;
	/**
	 * @access protected
	 * @var string
	 */
	private $UAgent = "Mozilla/5.0 (Windows NT 6.0; rv:5.0) Gecko/20100101 Firefox/5.0";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type curl.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return curl
	 */
	static function &creer_curl(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new curl ( $sort_en_erreur, $entete  );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return curl
	 */
	public function &_initialise($liste_class) {
		parent::_initialise($liste_class);
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	/**
	 * @codeCoverageIgnore
	 * @param string $nom_module
	 * @param string $sort_en_erreur
	 */
	public function __construct($sort_en_erreur = "non", $nom_module = "curl" ) {
		parent::__construct ( $sort_en_erreur, $nom_module  );
		
	}

	/**
	 * Se connecte au Web Service
	 * @param options $liste_option  Pointeur sur la liste d'option standard.
	 * @return curl
	 */
	public function &connectService($url) {
		$this->setCurl ( $url );
		return $this->setConnexion ( curl_init ( $this->getCurl () ) );
	}

	/**
	 * Transmet la requete Curl
	 * @codeCoverageIgnore
	 * @return mixed|false en cas d'erreur
	 * @throws Exception
	 */
	public function send_curl() {
		$this->onDebug ( __METHOD__, 1 );
		$this->setCurlUserAgent ();
		$retour = curl_exec ( $this->getConnexion () );
		$this->onDebug($retour,2);
		if ($retour === false) {
			$curl_no = curl_errno ( $this->getConnexion () );
			switch ($curl_no) {
				case 51 :
					$this->onWarning ( "cURL[" . curl_errno ( $this->getConnexion () ) . "] " . curl_error ( $this->getConnexion () ) );
					break;
				default :
					return $this->onError ( "cURL[" . curl_errno ( $this->getConnexion () ) . "] " . curl_error ( $this->getConnexion () ), curl_getinfo($this->getConnexion ()), curl_errno ( $this->getConnexion () ) );
			}
		}
		/* Test des codes retour HTTP. */
		if($this->getValideCodeErreur()){
			$this->test_code_retour($retour);
		}
		
		return $retour;
	}
	
	/**
	 * Test les code retour d'erreur standard.
	 * @return false|curl
	 */
	public function test_code_retour($retour){
		/* Test des codes retour HTTP. */
		$httpCode = $this ->curl_getinfo ();
		if ($httpCode == 404) {
			return $this ->onError ( "Erreur HTTP : " . $httpCode, $retour, $httpCode );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $nom_local
	 * @param boolean $mode_ascii
	 * @param string $chmod
	 * @return mixed|false
	 * @throws Exception
	 */
	public function ftp_curl_put($nom_local, $mode_ascii = FALSE, $chmod = FALSE) {
		$this->onInfo ( "On envoi le fichier : " . $nom_local . " " . filesize ( $nom_local ) );
		$ret = FALSE;
		
		if (is_file ( $nom_local )) {
			$fp = fopen ( $nom_local, 'r' );
			curl_setopt ( $this->getConnexion (), CURLOPT_INFILE, $fp );
			curl_setopt ( $this->getConnexion (), CURLOPT_INFILESIZE, filesize ( $nom_local ) );
			curl_setopt ( $this->getConnexion (), CURLOPT_UPLOAD, TRUE );
			if ($mode_ascii) {
				curl_setopt ( $this->getConnexion (), CURLOPT_TRANSFERTEXT, TRUE );
			}
			if ($chmod) {
				$path = parse_url ( $this->getCurl (), PHP_URL_PATH );
				curl_setopt ( $this->getConnexion (), CURLOPT_POSTQUOTE, array (
						"SITE CHMOD $chmod $path" 
				) );
			}
			$ret = $this->send_curl ();
			fclose ( $fp );
		}
		
		return $ret;
	}

	/**
	 * Telecharge un fichier
	 * @codeCoverageIgnore
	 * @param string $sortie Fichier de sortie des donnees telechargees
	 * @return mixed|false
	 * @throws Exception
	 */
	public function ftp_curl_get($sortie) {
		if ($fp = fopen ( $sortie, 'w' )) {
			curl_setopt ( $this->getConnexion (), CURLOPT_FILE, $fp );
			$ret = $this->send_curl ();
			fclose ( $fp );
			return $ret;
		}
		return FALSE;
	}

	/**
	 * Lister les fichiers d'un ftp
	 * @codeCoverageIgnore
	 * @return mixed|false
	 * @throws Exception
	 */
	public function ftp_curl_list() {
		$this->setReturnTransfert(TRUE);
		curl_setopt ( $this->getConnexion (), CURLOPT_FTPLISTONLY, TRUE );
		
		return $this->send_curl ();
	}
	
	/**
	 * Lister les fichiers d'un ftp
	 * @codeCoverageIgnore
	 * @return mixed|false
	 * @throws Exception
	 */
	public function curl_getinfo() {
		$code_retour=curl_getinfo ( $this->getConnexion (), CURLINFO_HTTP_CODE );
		$this->setCodeRetourCurl($code_retour);
		return $code_retour;
	}

	/**
	 * Ferme la connexion
	 * @codeCoverageIgnore
	 * @return curl
	 */
	public function close() {
		curl_close ( $this->getConnexion () );
		return $this;
	}

	/******************** Accesseurs *****************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getConnexion() {
		return $this->connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnexion($connexion) {
		$this->connexion = $connexion;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCurl() {
		return $this->curl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCurl($url) {
		$this->curl = $url;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getCodeRetourCurl() {
		return $this->code_retour_curl;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCodeRetourCurl($code_retour_curl) {
		$this->code_retour_curl = $code_retour_curl;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUAgent() {
		return $this->UAgent;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUAgent($UAgent) {
		$this->UAgent = $UAgent;
		return $this;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function &setEpsv($use = true) {
		curl_setopt ( $this->getConnexion (), CURLOPT_FTP_USE_EPSV, $use );
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSslVerifyPeerAndHost($actif) {
		curl_setopt ( $this->getConnexion (), CURLOPT_SSL_VERIFYPEER, $actif );
		curl_setopt ( $this->getConnexion (), CURLOPT_SSL_VERIFYHOST, $actif );
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setHttpHAuth($type = "any") {
		switch ($type) {
			case "any" :
				curl_setopt ( $this->getConnexion (), CURLOPT_HTTPAUTH, CURLAUTH_ANY );
				break;
		}

		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setHttpHeader($headers) {
		curl_setopt ( $this->getConnexion (), CURLOPT_HTTPHEADER, $headers );
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setHeader($actif) {
		if (is_bool ( $actif )) {
			curl_setopt ( $this->getConnexion (), CURLOPT_HEADER, $actif );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setReturnTransfert($actif) {
		if (is_bool ( $actif )) {
			curl_setopt ( $this->getConnexion (), CURLOPT_RETURNTRANSFER, $actif );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setUseDns($actif) {
		if (is_bool ( $actif )) {
			curl_setopt ( $this->getConnexion (), CURLOPT_DNS_USE_GLOBAL_CACHE, $actif );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCookie($cookie) {
		if ($cookie != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_COOKIE, $cookie );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCurlUserAgent() {
		curl_setopt ( $this->getConnexion (), CURLOPT_USERAGENT, $this->getUAgent () );
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setUserPasswd($user, $passwd) {
		if ($user != "" && $passwd != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_USERPWD, $user . ":" . $passwd );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setReferer($referer) {
		if ($referer != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_REFERER, $referer );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setProxy($adresse_proxy, $port_proxy, $login_proxy, $passwd_proxy, $type_proxy) {
		$this->onDebug("adresse_proxy:".$adresse_proxy.",port_proxy:".$port_proxy.",login_proxy:". $login_proxy.",passwd_proxy:". $passwd_proxy.",type_proxy:". $type_proxy, 2);
		curl_setopt ( $this->getConnexion (), CURLOPT_HTTPPROXYTUNNEL, true );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXY, $adresse_proxy );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXYPORT, $port_proxy );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXYTYPE, $type_proxy );
		//CURLOPT_PROXYUSERPWD "[username]:[password]"
		//CURLOPT_PROXYAUTH  CURLAUTH_BASIC et CURLAUTH_NTLM
		//CURLOPT_PROXYTYPE Soit CURLPROXY_HTTP (0 par défaut), soit CURLPROXY_SOCKS4 (4), soit CURLPROXY_SOCKS5 (5), soit CURLPROXY_SOCKS5_HOSTNAME (7 but no constant defined)
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setNoProxy() {
		$this->onDebug("setNoProxy",2);
		curl_setopt ( $this->getConnexion (), CURLOPT_HTTPPROXYTUNNEL, false );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXY, "" );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXYPORT, "" );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXYTYPE, "CURLPROXY_HTTP" );
		//CURLOPT_PROXYUSERPWD "[username]:[password]"
		//CURLOPT_PROXYAUTH  CURLAUTH_BASIC et CURLAUTH_NTLM
		//CURLOPT_PROXYTYPE Soit CURLPROXY_HTTP (par défaut), soit CURLPROXY_SOCKS5 (5), soit CURLPROXY_SOCKS5_HOSTNAME (7 but no constant defined)
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setPostData($postData) {
		if ($postData != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_POST, true );
			curl_setopt ( $this->getConnexion (), CURLOPT_POSTFIELDS, $postData );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimeout($timeout) {
		if ($timeout != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_TIMEOUT, $timeout );
			curl_setopt ( $this->getConnexion (), CURLOPT_CONNECTTIMEOUT, $timeout );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setRequest($request) {
		if ($request != "") {
			$this->setReturnTransfert(TRUE);
			curl_setopt ( $this->getConnexion (), CURLOPT_CUSTOMREQUEST, $request );
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVerbose() {
		curl_setopt ( $this->getConnexion (), CURLOPT_VERBOSE, TRUE );
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setOptionArray($option_array) {
		curl_setopt_array ( $this->getConnexion (), $option_array );
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getValideCodeErreur() {
		return $this->valide_code_retour;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setValideCodeErreur($valide_code_erreur) {
		$this->valide_code_retour = $valide_code_erreur;
		return $this;
	}
	/******************** Accesseurs *****************/
	
	/******************** HELP **********************/
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
	/******************** HELP **********************/
}
?>