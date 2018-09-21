<?php
/**
 * @author dvargas
 * @package Lib
 * 
*/
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class serveur_pipe<br>
 * 
 * Gere un pipe.
 * @package Lib
 * @subpackage Flux
*/
class serveur_pipe extends pipe {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type serveur_pipe.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $nom_pipe Nom du pipe.
	 * @param string $mode Droit Unix du pipe.
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return serveur_pipe
	 */
	static function &creer_serveur_pipe(&$liste_option, $nom_pipe = "/tmp/zpipe.pipe", $mode = 0600, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new serveur_pipe ( $nom_pipe, $mode, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return serveur_pipe
	 */
	public function &_initialise($liste_class) {
		parent::_initialise($liste_class);
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/**
	 * Contructeur
	 * @codeCoverageIgnore
	 * @param string $nom_pipe Nom du pipe.
	 * @param string $mode Droit Unix du pipe.
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 */
	function __construct($nom_pipe = "/tmp/zpipe.pipe", $mode = 0600, $sort_en_erreur = false, $entete = __CLASS__) {
		parent::__construct ( $nom_pipe, $mode, $sort_en_erreur,$entete );
	}

	/**
	 * Permet d'ouvrir le pipe du serveur.
	 * @codeCoverageIgnore
	 * @return Bool TRUE si ok, FALSE sinon.
	 */
	function ouvrir_pipe($mode = "r") {
		if (repertoire::tester_repertoire_existe ( dirname ( $this->nom_pipe ) ) != true)
			repertoire::creer_nouveau_repertoire ( dirname ( $this->nom_pipe ) );
		$CODE_RETOUR = $this->init_serveur ( $mode );
		
		if ($CODE_RETOUR === false)
			return $this->onError ( "Le serveur du pipe ne s'initialise pas." );
		
		return $CODE_RETOUR;
	}

	/**
	 * Permet de lire le pipe du serveur.<br>
	 * Si le message = "close", le pipe se ferme.
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur les arguments.
	 * @return true
	 */
	function lire_pipe(&$liste_option) {
		$message = "no_mesg";
		//block and read from the pipe
		while ( $message != "close" ) {
			@usleep ( 10 );
			$message = "";
			$message = $this->lire ();
			if (trim ( $message ) != "close" && $message != "")
				$this->traite_message ( $liste_option, $message );
		}
		
		$this->close ();
		
		return true;
	}

	/**
	 * Permet de fermer pipe du serveur.<br>
	 * Si le message = "close", le pipe se ferme.
	 *
	 * @return true
	 */
	function close() {
		$this->close_serveur ();
		return true;
	}
}
?>