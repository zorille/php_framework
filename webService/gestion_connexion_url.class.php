<?php
/**
 * Gestion de WebService.
 * @author dvargas
 */
/**
 * class gestion_connexion_url
 * Gere la definition d'un tunnel ou d'un proxy (+ gestion SSL du proxy) 
 * @package Lib
 * @subpackage WebService
 */
class gestion_connexion_url extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $host = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $port = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $url = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $uri = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $tunnel = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $proxy = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var boolean
	 */
	private $https = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var utilisateurs
	 */
	private $class_utilisateurs = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type gestion_connexion_url.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return gestion_connexion_url
	 */
	static function &creer_gestion_connexion_url(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new gestion_connexion_url ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return gestion_connexion_url
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjetUtilisateurs ( utilisateurs::creer_utilisateurs ( $liste_class ["options"] ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * @param array $serveur_data
	 * @return gestion_connexion_url Reference sur gestion_connexion_url.
	 * @throws Exception
	 */
	public function retrouve_connexion_params($serveur_data) {
		$this->onDebug ( __METHOD__, 1 );
		if (! isset ( $serveur_data ["host"] ) || ! isset ( $serveur_data ["port"] )) {
			return $this->onError ( "Il faut un Host ou Port pour creer une connexion.", "", 5100 );
		}
		$this->setHost ( $serveur_data ["host"] );
		$this->setPort ( $serveur_data ["port"] );
		
		//gestion du https
		if (isset ( $serveur_data ["useSSL"] ) && $serveur_data ["useSSL"] == "oui") {
			$this->setHttps ( true );
		}
		
		//Si on force l'utilisation du tunnel ssh via --use_tunnel
		if ($this->getListeOptions ()
			->verifie_option_existe ( "use_tunnel" ) !== false && isset ( $serveur_data ["tunnel"] )) {
			$this->setTunnel ( $serveur_data ["tunnel"] )
				->utilise_tunnel ();
			// @codeCoverageIgnoreStart
			return $this;
			// @codeCoverageIgnoreEnd
		}
		
		// Le proxy est par defaut
		if (isset ( $serveur_data ["proxy"] )) {
			$this->setProxy ( $serveur_data ["proxy"] );
		}
		
		// Le proxy est par defaut
		if (isset ( $serveur_data ["uri"] )) {
			$this->setUri ( $serveur_data ["uri"] );
		}
		
		return $this;
	}

	/**
	 * Valide qu'un tunnel existe pour ce serveur.
	 * 
	 * @param string $serveur        	
	 * @return Bool
	 */
	public function valide_tunnel_existe() {
		$this->onDebug ( __METHOD__, 1 );
		if (count ( $this->getTunnel () ) == 0) {
			return false;
		}
		return true;
	}

	/**
	 * Active le tunnel s'il existe.
	 *      	
	 * @return boolean True un tunnel est actif, false il n'y a pas de tunnel
	 */
	public function utilise_tunnel() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_tunnel_existe ()) {
			// @codeCoverageIgnoreStart
			//On ne peut pas tester une methode static
			$tunnel = $this->getTunnel ();
			if (! isset ( $tunnel ["host"] ) || ! isset ( $tunnel ["port"] )) {
				return $this->onError ( "Il manque des parametres obligatoire dans la definition du tunnel (host ou port)" );
			}
			if (! isset ( $tunnel ["timeoutssh"] )) {
				$tunnel ["timeoutssh"] = 1200;
			}
			if (! isset ( $tunnel ["ssh_option"] ) || empty ( $tunnel ["ssh_option"] )) {
				$tunnel ["ssh_option"] = "";
			}
			if (ssh_z::creer_tunnel_temporaire ( $tunnel ["host"], $tunnel ["port"], $this->getHost (), $this->getPort (), $tunnel ["timeoutssh"], $tunnel ["ssh_option"] ) === false) {
				return $this->onError ( "La connexion a la machine " . $this->getHost () . " par tunnel est en erreur", "", 5102 );
			}
			$this->setPort ( $tunnel ["port"] );
			$this->setHost ( "127.0.0.1" );
			return $this;
			// @codeCoverageIgnoreEnd
		}
		
		return false;
	}

	/**
	 * Valide qu'un proxy existe pour ce serveur.
	 * 
	 * @param string $serveur        	
	 * @return Bool
	 */
	public function valide_proxy_existe() {
		$this->onDebug ( __METHOD__, 1 );
		if (count ( $this->getProxy () ) == 0) {
			return false;
		}
		return true;
	}

	/**
	 * Active le proxy au format soap s'il existe.
	 * 
	 * @param array	
	 * @return array
	 */
	public function utilise_proxy() {
		$this->onDebug ( __METHOD__, 1 );
		$serveur_data = array ();
		if ($this->valide_proxy_existe ()) {
			$proxy = $this->getProxy ();
			if (isset ( $proxy ["host"] )) {
				$serveur_data ["proxy_host"] = $proxy ["host"];
				if (isset ( $proxy ["port"] )) {
					$serveur_data ["proxy_port"] = $proxy ["port"];
				} else {
					$serveur_data ["proxy_port"] = "80";
				}
				if (isset ( $proxy ["login"] ) && $proxy ["login"] != "") {
					$serveur_data ["proxy_login"] = $proxy ["login"];
					$serveur_data ["proxy_password"] = $proxy ["password"];
				} else {
					$this->getObjetUtilisateurs ()
						->retrouve_utilisateurs_array ( $serveur_data );
					$serveur_data ["proxy_login"] = $this->getObjetUtilisateurs ()
						->getUsername ();
					$serveur_data ["proxy_password"] = $this->getObjetUtilisateurs ()
						->getPassword ();
				}
				if (! empty ( $proxy ["type"] )) {
					$serveur_data ["proxy_type"] = $proxy ["type"];
				} else {
					$serveur_data ["proxy_type"] = CURLPROXY_HTTP;
				}
			}
		}
		
		return $serveur_data;
	}

	/**
	 * ajoute l'utilisation de https en cas de useSSL dans les parametres
	 * @param array &$serveur_data
	 * @return string
	 */
	public function utilise_SSL() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getHttps ()) {
			return "https://";
		}
		
		return "http://";
	}

	/**
	 * Creer le prepend de l'url (http://host:port$url)
	 * @return gestion_connexion_url
	 */
	public function prepare_prepend_url($url = "") {
		$this->onDebug ( __METHOD__, 1 );
		//mise au propre appel URL
		$http = $this->utilise_SSL ();
		$this->setPrependUrl ( $http . $this->getHost () . ":" . $this->getPort () . $url );
		$this->onDebug ( "URL : " . $this->getPrependUrl (), 1 );
		
		return $this;
	}

	/**
	 * Active un tunnel ou declare un proxy
	 * @return gestion_connexion_url
	 */
	public function reset_datas() {
		$this->onDebug ( __METHOD__, 1 );
		$this->setTunnel ( array () )
			->setProxy ( array () )
			->setHttps ( false )
			->setPrependUrl ( "" )
			->setHost ( "" )
			->setPort ( 0 );
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function __clone() {
		// Force la copie de this->class_utilisateurs, sinon
		// il pointera vers le meme objet.
		if(is_object($this->class_utilisateurs))
		$this->class_utilisateurs = clone $this->getObjetUtilisateurs();
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHost($host) {
		$this->host = $host;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPort($port) {
		$this->port = $port;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrependUrl() {
		return $this->url;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPrependUrl($url) {
		$this->url = $url;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUri($uri) {
		$this->uri = $uri;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTunnel() {
		return $this->tunnel;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTunnel($Tunnels) {
		if (is_array ( $Tunnels )) {
			$this->tunnel = $Tunnels;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getProxy() {
		return $this->proxy;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProxy($Proxys) {
		if (is_array ( $Proxys )) {
			$this->proxy = $Proxys;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHttps() {
		return $this->https;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHttps($https) {
		$this->https = $https;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return utilisateurs
	 */
	public function &getObjetUtilisateurs() {
		return $this->class_utilisateurs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetUtilisateurs(&$class_utilisateurs) {
		$this->class_utilisateurs = $class_utilisateurs;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "\t--use_tunnel Force l'utilisation du tunnel SSH";
		$help [__CLASS__] ["text"] [] .= "Au format XML :";
		$help [__CLASS__] ["text"] [] .= "\t<useSSL>oui/non</useSSL>";
		$help [__CLASS__] ["text"] [] .= "\t<tunnel>";
		$help [__CLASS__] ["text"] [] .= "\t\t<host>Tunnel_HostName/IP</host>";
		$help [__CLASS__] ["text"] [] .= "\t\t<port>10080</port>";
		$help [__CLASS__] ["text"] [] .= "\t\t<ssh_option>''</ssh_option>";
		$help [__CLASS__] ["text"] [] .= "\t\t<timeoutssh>1200</timeoutssh>";
		$help [__CLASS__] ["text"] [] .= "\t</tunnel>";
		$help [__CLASS__] ["text"] [] .= "\t<proxy>";
		$help [__CLASS__] ["text"] [] .= "\t\t<host>Proxy_HostName/IP</host>";
		$help [__CLASS__] ["text"] [] .= "\t\t<port>443</port>";
		$help [__CLASS__] ["text"] [] .= "\t\t<username></username>";
		$help [__CLASS__] ["text"] [] .= "\t\t<crypt_password></crypt_password>";
		$help [__CLASS__] ["text"] [] .= "\t\t<!-- <password></password> -->";
		$help [__CLASS__] ["text"] [] .= "\t</proxy>";
		$help=array_merge($help,utilisateurs::help());
		
		return $help;
	}
}
?>
