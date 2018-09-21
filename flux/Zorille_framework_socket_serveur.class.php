<?php
/**
 * @author dvargas
 * @package Lib
 * 
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class socket<br>
 * 
 * Gere une socket reseau.
 * @package Lib
 * @subpackage Flux
 */
class socket_serveur extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var resource
	 */
	private $connexion;
	/**
	 * var privee
	 * @access private
	 * @var resource
	 */
	private $socket;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $nom_socket;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $port_socket;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $type_socket;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $identite;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type socket_serveur.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $nom_socket Nom de la socket.
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return socket_serveur
	 */
	static function &creer_socket_serveur(&$liste_option, $nom_socket = "/tmp/zsocket.sock", $port_socket = "", $type_socket = "unix", $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new socket_serveur ( $nom_socket, $port_socket, $type_socket, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return socket_serveur
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string $nom_socket Nom de la socket.
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($nom_socket = "/tmp/zsocket.sock", $port_socket = "", $type_socket = "unix", $sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->nom_socket = $nom_socket;
		$this->port_socket = $port_socket;
		$this->type_socket = $type_socket;
	}
	
	//accept client ou serveur
	/**
	* Cree et ouvre la socket.<br>
	* @codeCoverageIgnore
	* @return int Renvoi 1 si OK, 0 si not OK, -1 si l'adresse est deja utilise.
	*/
	public function init() {
		$errno = "";
		$errstr = "";
		
		//Si la socket est un fichier
		if (file_exists ( $this->nom_socket )) {
			@unlink ( $this->nom_socket );
		}
		
		$nom_complet = $this->type_socket . "://" . $this->nom_socket;
		if ($this->port_socket != "") {
			$nom_complet .= ":" . $this->port_socket;
		}
		$this->socket = stream_socket_server ( $nom_complet, $errno, $errstr );
		$this->onDebug ( $errstr . " (" . $errno . ") sur " . $nom_complet, 1 );
		
		if (! $this->socket) {
			return $this->onError ( $errstr . " (" . $errno . ") sur " . $nom_complet );
			if ($errstr == "Address already in use") {
				$CODE_RETOUR = 2;
			} else {
				$CODE_RETOUR = 0;
			}
		} else {
			$CODE_RETOUR = 1;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Active ou desactive le "No Hang Up" sur la socket.
	 * @codeCoverageIgnore
	 * @param Bool $active TRUE active le NOHANGUP, FALSE le des-active.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function active_NOHUP($active = true) {
		if ($active) {
			$CODE_RETOUR = stream_set_blocking ( $this->socket, 0 );
		} else {
			$CODE_RETOUR = stream_set_blocking ( $this->socket, 1 );
		}
		
		if ($CODE_RETOUR === false) {
			return $this->onError ( "Erreur lors du set_blocking" );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Active ou desactive le "No Hang Up" sur la socket.
	 * @codeCoverageIgnore
	 * @param Bool $active TRUE active le NOHANGUP, FALSE le des-active.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function setTimeout($timeout) {
		stream_setTimeout ( $this->socket, $timeout );
		
		return true;
	}

	/**
	 * Active ou desactive le "No Hang Up" sur la socket.
	 * @codeCoverageIgnore
	 * @param Bool $active TRUE active le NOHANGUP, FALSE le des-active.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function renvoi_infos() {
		$info = @stream_get_meta_data ( $this->connexion );
		
		return $info;
	}

	/**
	 * Attend une connexion.<br>
	 * Si NOHANNGUP est active cette attente est non bloquante.
	 * @codeCoverageIgnore
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	function attendre_connexion() {
		$this->connexion = @stream_socket_accept ( $this->socket );
		if ($this->connexion === false) {
			$CODE_RETOUR = false;
		} else {
			$CODE_RETOUR = true;
			
			//Nom de la socket local
			$this->identite = @stream_socket_get_name ( $this->connexion, false );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Lit les donnees sur la socket.
	 * @codeCoverageIgnore
	 * @return string Donnees lue.
	 */
	function lire() {
		$donnee_recu = "";
		$char = "";
		if (is_resource ( $this->connexion )) {
			while ( $char != "\n" ) {
				$char = stream_get_contents ( $this->connexion, 1 );
				$donnee_recu .= $char;
			}
			$donnee_recu = trim ( $donnee_recu );
		}
		
		return $donnee_recu;
	}

	/**
	 * Ecrit des donnees sur la socket.
	 * @codeCoverageIgnore
	 * @param string $message Donnees a ecrire.
	 */
	function ecrit($message) {
		$retour = fputs ( $this->connexion, $message . "\n" );
		if ($retour === false) {
			return $this->onError ( "Impossible d'ecrire sur la socket." );
		}
		@ob_flush ();
		@flush ();
		
		return $retour;
	}

	/**
	 * Ferme une connexion.
	 * @codeCoverageIgnore
	 */
	function close_connexion() {
		if (is_resource ( $this->connexion )) {
			fclose ( $this->connexion );
			$this->connexion = false;
		}
		
		return $this;
	}

	/**
	 * Ferme et supprime une socket.
	 * @codeCoverageIgnore
	 */
	function close($delete = false) {
		if ($this->socket) {
			stream_socket_shutdown ( $this->socket, STREAM_SHUT_WR );
		}
		if ($delete)
			@unlink ( $this->nom_socket );
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function __destruct() {
	}

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
}
?>