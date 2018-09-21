<?php
/**
 * @author dvargas
 * @package Lib
 * 
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class pipe<br>
 * 
 * Gere un pipe.
 * @package Lib
 * @subpackage Flux
 */
class pipe extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	var $nom_pipe;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	var $mode_systeme;
	/**
	 * var privee
	 * @access private
	 * @var resource
	 */
	var $file_desc;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type pipe.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $nom_pipe Nom du pipe.
	 * @param string $mode Droit Unix du pipe.
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return pipe
	 */
	static function &creer_pipe(&$liste_option, $nom_pipe = "/tmp/zpipe.pipe", $mode = 0600, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new pipe ( $nom_pipe, $mode, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return pipe
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string $nom_pipe Nom du pipe.
	 * @param string $mode Droit Unix du pipe.
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($nom_pipe = "/tmp/zpipe.pipe", $mode = 0600, $sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->nom_pipe = $nom_pipe;
		$this->mode_systeme = $mode;
	}

	/**
	 * Cree et ouvre le pipe.<br>
	 * les mode sont standard : r,w,a,r+,w+,a+
	 * @codeCoverageIgnore
	 * @param string $mode Mode d'ouverture du fichier.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function init_serveur($mode = "r") {
		if (file_exists ( $this->nom_pipe ))
			@unlink ( $this->nom_pipe );
		
		umask ( 0 );
		$CODE_RETOUR = posix_mkfifo ( $this->nom_pipe, $this->mode_systeme );
		if ($CODE_RETOUR) {
			$this->file_desc = @fopen ( $this->nom_pipe, $mode );
			if (! $this->file_desc) {
				return $this->onError ( "Erreur lors de l'ouverture du pipe" );
				$CODE_RETOUR = false;
			} else {
				$CODE_RETOUR = true;
			}
		} else {
			return $this->onError ( "Erreur sur la creation du pipe" );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Ouvre le pipe.<br>
	 * les mode sont standard : r,w,a,r+,w+,a+
	 * @codeCoverageIgnore
	 * @param string $mode Mode d'ouverture du fichier.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function init_client($mode = "w") {
		if (! @file_exists ( $this->nom_pipe )) {
			return $this->onError ( "Erreur le pipe n'existe pas" );
		} else {
			$this->file_desc = @fopen ( $this->nom_pipe, $mode );
			if (! $this->file_desc) {
				return $this->onError ( "Erreur lors de l'ouverture du pipe" );
			}
		}
		
		return true;
	}

	/**
	 * Permet de lire le pipe.
	 * @codeCoverageIgnore
	 * @return string Renvoi les donnees lue dans le pipe.
	 */
	public function lire($timeout_s = NULL, $timeout_usec = NULL) {
		$read = array (
				$this->file_desc 
		);
		$write = NULL;
		$except = NULL;
		$num_changed_streams = stream_select ( $read, $write, $except, $timeout_s, $timeout_usec );
		
		if ($num_changed_streams != 0 && $num_changed_streams !== false) {
			while ( $char != "\n" ) {
				$char = @fgetc ( $this->file_desc );
				$donnee_recu .= $char;
			}
			$donnee_recu = trim ( $donnee_recu );
		} elseif ($num_changed_streams === false) {
			return $this->onError ( "stream_select a fini en erreur", "" );
		} else {
			$donnee_recu = "";
		}
		
		return $donnee_recu;
	}

	/**
	 * Ecrit dans le pipe.
	 * @codeCoverageIgnore
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function ecrit($message) {
		$CODE_RETOUR = @fwrite ( $this->file_desc, $message . "\n" );
		if ($CODE_RETOUR === false)
			return $this->onError ( "Impossible d'ecrire sur le pipe." );
		
		return $CODE_RETOUR;
	}

	/**
	 * Permet de ne pas bloquer la lecture/ecriture
	 * @codeCoverageIgnore
	 * @param bool $block false debloque la lecture/ecriture.
	 * @return true
	 */
	public function set_blocking($block = false) {
		// prevent fread / fwrite blocking
		stream_set_blocking ( $this->file_desc, $block );
		
		return true;
	}

	/**
	 * Ferme le pipe.
	 * @codeCoverageIgnore
	 */
	public function close() {
		@fclose ( $this->file_desc );
	}

	/**
	 * Ferme le pipe et le supprime.
	 * @codeCoverageIgnore
	 */
	public function close_serveur() {
		@fclose ( $this->file_desc );
		@unlink ( $this->nom_pipe );
		
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct() {
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