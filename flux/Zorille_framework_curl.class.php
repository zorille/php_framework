<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

use Exception as Exception;

/**
 * class CURL<br> Gere une connexion curl.
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
	private $curl_infos;
	/**
	 * @access protected
	 * @var string
	 */
	private $code_retour_curl = 200;
	/**
	 * @access protected
	 * @var boolean
	 */
	private $valide_code_retour = true;
	/**
	 * @access protected
	 * @var string
	 */
	private $UAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:90.0) Gecko/20100101 Firefox/90.0";

	// private $UAgent = "Mozilla/5.0 (Windows NT 6.0; rv:5.0) Gecko/20100101 Firefox/5.0";
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type curl.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return curl
	 * @throws Exception
	 */
	static function &creer_curl(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): curl
	{
		$objet = new curl ( $sort_en_erreur, $entete );
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
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static
	{
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @param string $nom_module
	 * @param string $sort_en_erreur
	 */
	public function __construct(
		$sort_en_erreur = "non",
		string $nom_module = "curl") {
		parent::__construct ( $sort_en_erreur, $nom_module );
	}

	/**
	 * Se connecte au Web Service
	 * @param $url
	 * @return curl
	 */
	public function &connectService(
			$url): static
	{
		$this->setCurl ( $url );
		return $this->setConnexion ( curl_init ( $this->getCurl () ) );
	}

	/**
	 * Transmet la requete Curl
	 * @codeCoverageIgnore
	 * @return mixed|false en cas d'erreur
	 * @throws Exception
	 */
	public function send_curl(): mixed
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setCurlUserAgent ();
		$retour = curl_exec ( $this->getConnexion () );
		$this->onDebug ( $retour, 2 );
		$this->curl_getinfo ();
		if ($retour === false) {
			$curl_no = curl_errno ( $this->getConnexion () );
			switch ($curl_no) {
				case 51 :
					$this->onWarning ( "cURL[" . curl_errno ( $this->getConnexion () ) . "] " . curl_error ( $this->getConnexion () ) );
					break;
				default :
					return $this->onError ( "cURL[" . curl_errno ( $this->getConnexion () ) . "] " . curl_error ( $this->getConnexion () ), curl_getinfo ( $this->getConnexion () ), curl_errno ( $this->getConnexion () ) );
			}
		}
		/* Test des codes retour HTTP. */
		if ($this->getValideCodeErreur ()) {
			$this->test_code_retour ( $retour );
		}
		return $retour;
	}

	/**
	 * Test les code retour d'erreur standard.
	 * @return false|curl
	 * @throws Exception
	 */
	public function test_code_retour(
			$retour): bool|curl|static
	{
		/* Test des codes retour HTTP. */
		$httpCode = $this->curl_getinfo ();
		if ($httpCode == 404) {
			return $this->onError ( "Erreur HTTP : " . $httpCode, $retour, $httpCode );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $nom_local
	 * @param boolean $mode_ascii
	 * @param bool|string $chmod
	 * @return mixed|false
	 * @throws Exception
	 */
	public function ftp_curl_put(
		string      $nom_local,
		bool        $mode_ascii = FALSE,
		bool|string $chmod = FALSE): mixed
	{
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
	public function ftp_curl_get(
		string $sortie): mixed
	{
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
	public function ftp_curl_list(): mixed
	{
		$this->setReturnTransfert ( TRUE );
		curl_setopt ( $this->getConnexion (), CURLOPT_FTPLISTONLY, TRUE );
		return $this->send_curl ();
	}

	/**
	 * Lister les fichiers d'un ftp
	 * @codeCoverageIgnore
	 * @return mixed|false
	 * @throws Exception
	 */
	public function curl_getinfo(): mixed
	{
		$this->setCurlInfos ( curl_getinfo ( $this->getConnexion () ) );
		$code_retour = curl_getinfo ( $this->getConnexion (), CURLINFO_HTTP_CODE );
		$this->setCodeRetourCurl ( $code_retour );
		return $code_retour;
	}

	/**
	 * Active le connect Time Out
	 * @codeCoverageIgnore
	 * @return curl
	 * @throws Exception
	 */
	public function curl_connecttimeout(
			$time = 1): static
	{
		$this->setReturnTransfert ( TRUE );
		curl_setopt ( $this->getConnexion (), CURLOPT_CONNECTTIMEOUT, $time );
		return $this;
	}

	/**
	 * Ferme la connexion
	 * @codeCoverageIgnore
	 * @return curl
	 */
	public function close(): static
	{
		curl_close ( $this->getConnexion () );
		return $this;
	}

	/**
	 * ****************** Accesseurs ****************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getConnexion() {
		return $this->connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnexion(
			$connexion): static
	{
		$this->connexion = $connexion;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCurl(): string
	{
		return $this->curl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCurl(
			$url): static
	{
		$this->curl = $url;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCodeRetourCurl(): int|string
	{
		return $this->code_retour_curl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCodeRetourCurl(
			$code_retour_curl): static
	{
		$this->code_retour_curl = $code_retour_curl;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUAgent(): string
	{
		return $this->UAgent;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUAgent(
			$UAgent): static
	{
		$this->UAgent = $UAgent;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEpsv(
			$use = true): static
	{
		curl_setopt ( $this->getConnexion (), CURLOPT_FTP_USE_EPSV, $use );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSslVerifyPeerAndHost(
			$actif): static
	{
		$this->onDebug ( "VerifyPeerAndHost : " . (($actif) ? 'Yes' : 'No'), 2 );
		curl_setopt ( $this->getConnexion (), CURLOPT_SSL_VERIFYPEER, $actif );
		curl_setopt ( $this->getConnexion (), CURLOPT_SSL_VERIFYHOST, $actif );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHttpHAuth(
			$type = "any"): static
	{
		$this->onDebug ( "Auth type : " . $type, 2 );
		switch ($type) {
			case "any" :
				curl_setopt ( $this->getConnexion (), CURLOPT_HTTPAUTH, CURLAUTH_ANY );
				break;
			case "basic" :
				curl_setopt ( $this->getConnexion (), CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
				break;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHttpHeader(
			$headers): static
	{
		$this->onDebug ( "Headers : " . print_r ( $headers, true ), 2 );
		curl_setopt ( $this->getConnexion (), CURLOPT_HTTPHEADER, $headers );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHeader(
			$actif): static
	{
		$this->onDebug ( "Header : " . (($actif) ? 'Yes' : 'No'), 2 );
		if (is_bool ( $actif )) {
			curl_setopt ( $this->getConnexion (), CURLOPT_HEADER, $actif );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setReturnTransfert(
			$actif): static
	{
		$this->onDebug ( "Return Transfert : " . (($actif) ? 'Yes' : 'No'), 2 );
		if (is_bool ( $actif )) {
			curl_setopt ( $this->getConnexion (), CURLOPT_RETURNTRANSFER, $actif );
		}
		return $this;
	}

	/**
	 * pour suivre tous les en-tetes "Location: " que le serveur envoie dans les en-tetes HTTP
	 * @codeCoverageIgnore
	 */
	public function &setLocation(
			$actif): static
	{
		$this->onDebug ( "Location : " . (($actif) ? 'Yes' : 'No'), 2 );
		if (is_bool ( $actif )) {
			curl_setopt ( $this->getConnexion (), CURLOPT_FOLLOWLOCATION, $actif );
		}
		return $this;
	}

	/**
	 * pour suivre tous les en-tetes "Location: " que le serveur envoie dans les en-tetes HTTP
	 * @codeCoverageIgnore
	 */
	public function &setFollowRedirections(): self
    {
        $this->onDebug(__METHOD__, 1);
        curl_setopt ( $this->getConnexion (), CURLOPT_POSTREDIR, CURL_REDIR_POST_ALL );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUseDns(
			$actif): static
	{
		$this->onDebug ( "DNS use global cache : " . ($actif) ? 'Yes' : 'No', 2 );
		if (is_bool ( $actif )) {
			curl_setopt ( $this->getConnexion (), CURLOPT_DNS_USE_GLOBAL_CACHE, $actif );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCookie(
			$cookie): static
	{
		$this->onDebug ( "cookie : " . $cookie, 2 );
		if ($cookie != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_COOKIE, $cookie );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCurlUserAgent(): static
	{
		curl_setopt ( $this->getConnexion (), CURLOPT_USERAGENT, $this->getUAgent () );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUserPasswd(
			$user,
			$passwd): static
	{
		$this->onDebug ( "User/pass : " . $user . "/*********", 2 );
		if ($user != "" && $passwd != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_USERPWD, $user . ":" . $passwd );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setReferer(
			$referer): static
	{
		$this->onDebug ( "referer : " . $referer, 2 );
		if ($referer != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_REFERER, $referer );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProxy(
			$adresse_proxy,
			$port_proxy,
			$login_proxy,
			$passwd_proxy,
			$type_proxy): static
	{
		$this->onDebug ( "adresse_proxy:" . $adresse_proxy . ",port_proxy:" . $port_proxy . ",login_proxy:" . $login_proxy . ",passwd_proxy:" . $passwd_proxy . ",type_proxy:" . $type_proxy, 2 );
		curl_setopt ( $this->getConnexion (), CURLOPT_HTTPPROXYTUNNEL, true );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXY, $adresse_proxy );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXYPORT, $port_proxy );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXYTYPE, $type_proxy );
		// CURLOPT_PROXYUSERPWD "[username]:[password]"
		// CURLOPT_PROXYAUTH CURLAUTH_BASIC et CURLAUTH_NTLM
		// CURLOPT_PROXYTYPE Soit CURLPROXY_HTTP (0 par défaut), soit CURLPROXY_SOCKS4 (4), soit CURLPROXY_SOCKS5 (5), soit CURLPROXY_SOCKS5_HOSTNAME (7 but no constant defined)
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNoProxy(): static
	{
		$this->onDebug ( "setNoProxy", 2 );
		curl_setopt ( $this->getConnexion (), CURLOPT_HTTPPROXYTUNNEL, false );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXY, "" );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXYPORT, "" );
		curl_setopt ( $this->getConnexion (), CURLOPT_PROXYTYPE, "CURLPROXY_HTTP" );
		// CURLOPT_PROXYUSERPWD "[username]:[password]"
		// CURLOPT_PROXYAUTH CURLAUTH_BASIC et CURLAUTH_NTLM
		// CURLOPT_PROXYTYPE Soit CURLPROXY_HTTP (par défaut), soit CURLPROXY_SOCKS5 (5), soit CURLPROXY_SOCKS5_HOSTNAME (7 but no constant defined)
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPostData(
			$postData): static
	{
		if ($postData != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_POST, true );
			curl_setopt ( $this->getConnexion (), CURLOPT_POSTFIELDS, $postData );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimeout(
			$timeout): static
	{
		if ($timeout != "") {
			curl_setopt ( $this->getConnexion (), CURLOPT_TIMEOUT, $timeout );
			curl_setopt ( $this->getConnexion (), CURLOPT_CONNECTTIMEOUT, $timeout );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRequest(
			$request): static
	{
		if ($request != "") {
			$this->setReturnTransfert ( TRUE );
			curl_setopt ( $this->getConnexion (), CURLOPT_CUSTOMREQUEST, $request );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVerbose(): static
	{
		curl_setopt ( $this->getConnexion (), CURLOPT_VERBOSE, TRUE );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOptionArray(
			$option_array): static
	{
		curl_setopt_array ( $this->getConnexion (), $option_array );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValideCodeErreur(): bool
	{
		return $this->valide_code_retour;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValideCodeErreur(
			$valide_code_erreur): static
	{
		$this->valide_code_retour = $valide_code_erreur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCurlInfos(): string
	{
		return $this->curl_infos;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCurlInfos(
			$curl_infos): static
	{
		$this->curl_infos = $curl_infos;
		return $this;
	}

	/**
	 * ****************** Accesseurs ****************
	 */
	/**
	 * ****************** HELP *********************
	 */
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string
	{
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		return $help;
	}
/**
 * ****************** HELP *********************
 */
}
