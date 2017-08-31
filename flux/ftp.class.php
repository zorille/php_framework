<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class FTP<br>
 * 
 * Gere une connexion ftp.
 * @package Lib
 * @subpackage Flux
 */
class ftp extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $host;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $user;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $password;
	/**
	 * var privee
	 * @access private
	 * @var Bool
	 */
	private $connected = false;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $port;
	/**
	 * var privee
	 * @access private
	 * @var Bool
	 */
	private $passive = false;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $timeout;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $conn_id = NULL;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $nb_retry = 3;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type ftp.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $user Utilisateur pour se connecter.
	 * @param string $password Mot de passe pour se connecter.
	 * @param int $port Port de connexion.
	 * @param int $timeout Duree du timeout.
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return ftp
	 */
	static function &creer_ftp(&$liste_option, $user, $passwd, $port = '21', $timeout = 10, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new ftp ( $user, $passwd, $sort_en_erreur, $port, $timeout, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ftp
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Initialise les variables host, port et timeout.
	 * @codeCoverageIgnore
	 * @param string $user Utilisateur pour se connecter.
	 * @param string $password Mot de passe pour se connecter.
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 * @param int $port Port de connexion.
	 * @param int $timeout Duree du timeout.
	 */
	public function __construct($user, $passwd, $sort_en_erreur = false, $port = '21', $timeout = 10, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->port = $port;
		$this->timeout = $timeout;
		$this->user = $user;
		$this->password = $passwd;
	}

	/**
	 * Passe la connexion en mode passif.
	 *
	 * @param string $host Machine distante a connecter.
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function verifie_connexion($erreur = true) {
		if ($this->connected === false && $erreur) {
			return $this->onError ( "Erreur la connexion n'existe pas" );
		}
		
		return $this->connected;
	}

	/**
	 * Cree la connexion au host.
	 * @codeCoverageIgnore
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function connect($host, $port = "", $timeout = "") {
		if (! $this->verifie_connexion ( false )) {
			$this->conn_id = false;
			$this->onDebug ( "Host : " . $host, 2 );
			if ($host != "") {
				$this->host = $host;
			} else {
				return $this->onError ( "Il faut un serveur pour se connecter." );
			}
			if ($port != "")
				$this->port = $port;
			if ($timeout != "")
				$this->timeout = $timeout;
			
			$essai = 0;
			$sleep = 0;
			//NbRetry d'essai de connexion
			while ( $this->conn_id === false && $essai < $this->getNbRetry () ) {
				sleep ( $sleep );
				$this->conn_id = ftp_connect ( $this->host, $this->port, $this->timeout );
				$essai ++;
				$sleep ++;
			}
			if ($this->conn_id) {
				$essai = 0;
				$sleep = 0;
				while ( $this->connected === false && $essai < $this->getNbRetry () ) {
					sleep ( $sleep );
					$this->connected = ftp_login ( $this->conn_id, $this->user, $this->password );
					$essai ++;
					$sleep ++;
				}
				if ($this->connected === false) {
					return $this->onError ( "Erreur durant le login sur la connexion" );
				} else {
					//Si il y a une connexion on valide (ou non) le mode passif
					$this->passiv_mode ();
				}
			} else
				return $this->onError ( "Erreur durant la creation de la connexion" );
			
			$this->onDebug ( $this, 2 );
		}
		
		return $this->connected;
	}

	/**
	 * re cree la connexion au host.
	 * @codeCoverageIgnore
	 */
	private function _reconnect() {
		$this->disconnect ();
		$this->connect ( $this->host, $this->port, $this->timeout );
	}

	/**
	 * Passe la connexion en mode passif.
	 * @codeCoverageIgnore
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function passiv_mode() {
		$CODE_RETOUR = false;
		if ($this->verifie_connexion ()) {
			$CODE_RETOUR = ftp_pasv ( $this->conn_id, $this->getPassive () );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Ferme la connexion au host.
	 * @codeCoverageIgnore
	 * @return true
	 */
	public function disconnect() {
		$CODE_RETOUR = false;
		if ($this->verifie_connexion ( false )) {
			if (! ftp_close ( $this->conn_id )) {
				return $this->onError ( "Erreur durant la fermeture de la connexion" );
			} else {
				$this->connected = false;
				$this->conn_id = false;
				$CODE_RETOUR = true;
			}
		} else {
			//$this->onWarning("Il n'y a pas de connexion a fermer.");
			$this->connected = false;
			$this->conn_id = false;
			$CODE_RETOUR = true;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Execute une commande shell sur le ftp.
	 * @codeCoverageIgnore
	 * @return Bool  TRUE si OK, FALSE sinon.
	 */
	public function exec($command) {
		$CODE_RETOUR = false;
		if ($this->verifie_connexion ())
			$CODE_RETOUR = ftp_exec ( $this->conn_id, $command );
		return $CODE_RETOUR;
	}

	/**
	 * Execute une commande FTP sur le ftp.
	 * @codeCoverageIgnore
	 * @return Bool  TRUE si OK, FALSE sinon.
	 */
	public function exec_ftp_commande($command) {
		$CODE_RETOUR = false;
		if ($this->verifie_connexion ())
			$CODE_RETOUR = ftp_raw ( $this->conn_id, $command );
		return $CODE_RETOUR;
	}

	/**
	 * Creer un repertoire sur le ftp.
	 * Attention cette fonction fait un mkdir -p
	 * @codeCoverageIgnore
	 * @return Bool  TRUE si OK, FALSE sinon.
	 */
	public function creer_dossier($dossier, $mode = false) {
		$CODE_RETOUR = false;
		if ($this->verifie_connexion ()) {
			$CODE_RETOUR = true;
			$dir = explode ( "/", $dossier );
			$path = "";
			for($i = 0; $i < count ( $dir ); $i ++) {
				$path .= "/" . $dir [$i];
				//Si on arrive pas a changer de repertoire
				if (! @ftp_chdir ( $this->conn_id, $path )) {
					//On se met a la racine
					@ftp_chdir ( $this->conn_id, "/" );
					//et on creer le repertoire voulu
					if (! ftp_mkdir ( $this->conn_id, $path )) {
						$this->onInfo ( "Erreur sur le dossier " . $path );
						$this->_reconnect ();
						return false;
					} else {
						if ($mode !== false) {
							$mode = octdec ( str_pad ( $mode, 4, '0', STR_PAD_LEFT ) );
							@ftp_chmod ( $this->conn_id, ( int ) $mode, $path );
						}
					}
				}
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Recupere un fichier distant.
	 * Les modes sont FTP_BINARY ou FTP_ASCII .
	 * @codeCoverageIgnore
	 * @return Bool  TRUE si OK, FALSE sinon.
	 */
	public function recupere($source, $destination, $mode = FTP_BINARY) {
		$CODE_RETOUR = false;
		if ($this->verifie_connexion ()) {
			$essai = 0;
			$sleep = 0;
			while ( $CODE_RETOUR === false && $essai < $this->getNbRetry () ) {
				sleep ( $sleep );
				$CODE_RETOUR = ftp_get ( $this->conn_id, $destination, $source, $mode );
				$essai ++;
				$sleep ++;
				if ($CODE_RETOUR === false) {
					$this->_reconnect ();
				}
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Envoi un fichier sur le serveur distant.
	 * Les modes sont FTP_BINARY ou FTP_ASCII .
	 * @codeCoverageIgnore
	 * @return Bool  TRUE si OK, FALSE sinon.
	 */
	public function envoi($source, $destination, $mode = FTP_BINARY) {
		$CODE_RETOUR = false;
		
		if ($this->verifie_connexion ()) {
			$essai = 0;
			$sleep = 0;
			while ( $CODE_RETOUR === false && $essai < $this->getNbRetry () ) {
				sleep ( $sleep );
				$CODE_RETOUR = ftp_put ( $this->conn_id, $destination, $source, $mode );
				$essai ++;
				$sleep ++;
				if ($CODE_RETOUR === false) {
					$this->_reconnect ();
				}
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Supprime un fichier sur le serveur distant.
	 * @codeCoverageIgnore
	 * @return Bool  TRUE si OK, FALSE sinon.
	 */
	public function supprime($fichier) {
		$CODE_RETOUR = false;
		if ($this->verifie_connexion ()) {
			$CODE_RETOUR = ftp_delete ( $this->conn_id, $fichier );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Retrouve la liste des fichiers du dossier distant.
	 * @codeCoverageIgnore
	 * @param string $dossier dossier distant a lire.
	 * @return array|false  liste des fichiers du dossier, FALSE sinon.
	 */
	public function liste($dossier) {
		$CODE_RETOUR = false;
		if ($this->verifie_connexion ()) {
			$CODE_RETOUR = ftp_nlist ( $this->conn_id, $dossier );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Verifie presence fichier.
	 * @codeCoverageIgnore
	 * @param string $dossier_distant dossier distant a lire.
	 * @param string $nom_fichier Nom du fichier a trouver.
	 * @return Bool|-1 TRUE si OK, FALSE le fichier n'est pas present, -1 si une erreur est apparue.
	 */
	public function verifie_presence_fichier($dossier_distant, $nom_fichier) {
		if ($this->verifie_connexion ()) {
			$liste_fichier = $this->liste ( $dossier_distant );
			if ($liste_fichier) {
				if (in_array ( $nom_fichier, $liste_fichier ))
					$CODE_RETOUR = true;
				else
					$CODE_RETOUR = false;
			} else
				$CODE_RETOUR = - 1;
			$CODE_RETOUR = ftp_nlist ( $this->conn_id, $dossier );
		} else
			$CODE_RETOUR = - 1;
		
		return $CODE_RETOUR;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct() {
		// cleanup resources
		$this->disconnect ();
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getPassive() {
		return $this->passive;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setPassive($passive) {
		if (is_bool ( $passive )) {
			$this->passive = $passive;
		}
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNbRetry() {
		return $this->nb_retry;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setNbRetry($nbretry) {
		$this->nb_retry = $nbretry;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des connexions SSH";
		$help [__CLASS__] ["text"] [] .= "\t--ftp=oui";
		$help [__CLASS__] ["text"] [] .= "\t--user=xx";
		$help [__CLASS__] ["text"] [] .= "\t--passwd=xx";
		$help [__CLASS__] ["text"] [] .= "\t--serveur=xx (facultatif) si utilise, l'objet cree est connecte par defaut sur ce host.";
		$help [__CLASS__] ["text"] [] .= "\t--port=xx";
		$help [__CLASS__] ["text"] [] .= "\t--timeout=xx";
		$help [__CLASS__] ["text"] [] .= "\t--ftp_sort_en_erreur=xx";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "<ftp using=\"oui\" sort_en_erreur=\"oui\">";
		$help [__CLASS__] ["text"] [] .= " <user>echo</user>";
		$help [__CLASS__] ["text"] [] .= " <passwd>echo</passwd>";
		$help [__CLASS__] ["text"] [] .= " <serveur>xx</serveur>";
		$help [__CLASS__] ["text"] [] .= " <port></port>";
		$help [__CLASS__] ["text"] [] .= " <timeout></timeout>";
		$help [__CLASS__] ["text"] [] .= "</ftp>";
		
		return $help;
	}
}
?>