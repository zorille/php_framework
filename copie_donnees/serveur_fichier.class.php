<?php
/**
 * @author dvargas
 * @package Lib
 * 
 */

/**
 * class gestion_fichier<br>
 * @codeCoverageIgnore
 * Gere le transfert de fichier.
 * @package Lib
 * @subpackage Copie_Donnees
*/
class serveur_fichier extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	*/
	var $fichier_traite;
	/**
	 * var privee
	 * @access private
	 * @var int
	*/
	var $message_key;
	/**
	 * var privee
	 * @access private
	 * @var message
	*/
	var $message_idle;
	/**
	 * var privee
	 * @access private
	 * @var array
	*/
	var $liste_reponse;
	/**
	 * var privee
	 * @access private
	 * @var options
	*/
	var $liste_options_local;

	/**
	 * Contructeur
	 *
	 * @param int $key Identifiant numerique du message.
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	function __construct($key = "", $sort_en_erreur = false) {
		$this->fichier_traite = array ();
		$this->liste_reponse = array ();
		
		$this->message_key = $key;
		$this->message_idle = mem_message::creer_mem_message ( $this->getListeOptions (), $this->message_key, $sort_en_erreur );
		$this->message_idle->ouvrir ();
		
		//Gestion de abstract_log
		parent::__construct ( "SERVEUR FICHIER", $sort_en_erreur );
		
		return true;
	}

	/**
	 * Met le serveur en attente d'une copie.
	 * 
	 * @return TRUE
	 */
	public function attente_message($liste_option) {
		$this->liste_options_local = $liste_option;
		$message = "no_mesg";
		
		while ( $message != "close" ) {
			@usleep ( 10 );
			$message = "";
			$message = $this->message_idle->lire ( 100000 );
			if (trim ( $message ) != "close" && $message != "")
				$this->_traiteMessage ( $message );
		}
		
		return true;
	}

	/**
	 * Renvoi un message de reponse.
	 * 
	 * @param $position
	 * @return TRUE
	 */
	private function _renvoiReponse($position) {
		foreach ( $this->liste_reponse [$position] ["retour"] as $id_retour ) {
			$serveur = mem_message::creer_mem_message ( $this->getListeOptions (), $id_retour );
			$serveur->ouvrir ();
			$serveur->ecrit ( $this->liste_reponse [$position] ["telecharger"] );
			unset ( $serveur );
		}
		
		return true;
	}

	/**
	 * Prepare les donnees recues a etre traite.
	 * 
	 * @param string $message Message lu.
	 * @return true
	 */
	private function _traiteMessage($message) {
		$liste_donnees = explode ( "|", $message );
		if (count ( $liste_donnees ) == 6) {
			$liste_donnees [4] = unserialize ( $liste_donnees [4] );
			$liste_donnees [5] = unserialize ( $liste_donnees [5] );
			$this->_gereFichierStandard ( $liste_donnees );
		} else
			return $this->onError ( "Erreur dans la liste : " . $message );
		
		return true;
	}

	/**
	 * Traite les donnees recues.
	 * 
	 * @param array $liste_donnees Donnees preparees.
	 * @return Bool TRUE si ok, FALSE sinon.
	 */
	private function _gereFichierStandard($liste_donnees) {
		//On creer la structure du fichier
		$structure_en_cours = $this->_structureFichier ( $liste_donnees [1], $liste_donnees [2], $liste_donnees [3], $liste_donnees [4], $liste_donnees [5] );
		
		//On verifie qu'il n'existe pas deja
		$position = $this->_retrouveIdDuFichier ( $structure_en_cours );
		//s'il n'existe pas on le cree
		if ($position === false)
			$position = $this->_structureFichierTraite ( $structure_en_cours );
			
			//On prepare son module de gestion
		$this->_prepareGestionFichier ( $position, $liste_donnees [0] );
		
		//Enfin on lance la copie
		if ($this->_verifieFichierCopie ( $position ))
			$this->_renvoiReponse ( $position );
		else
			$this->copie_donnees ( $position );
		
		return true;
	}

	/**
	 * Retrouve l'id d'un fichier.
	 * 
	 * @return int|FALSE l'id s'il existe, FALSE sinon.
	 */
	private function _retrouveIdDuFichier($structure_standard) {
		$CODE_RETOUR = array_search ( $structure_standard, $this->fichier_traite );
		
		return $CODE_RETOUR;
	}

	/**
	 * Structure en memoire pour un fichier.
	 * 
	 * @return TRUE.
	 */
	private function _structureFichierTraite(&$structure_standard) {
		$position = count ( $this->fichier_traite );
		$this->fichier_traite [$position] = $structure_standard;
		
		return $position;
	}

	/**
	 * Structure en memoire pour un fichier.
	 * 
	 * @return TRUE.
	 */
	private function _structureFichier($date, $type_copie, $machine, $structure_final, $structure_standard) {
		$structure = array ();
		$structure ["date"] = $date;
		$structure ["type_copie"] = $type_copie;
		$structure ["machine"] = $machine;
		$structure ["nom_temporaire"] = $structure_final ["nom"];
		$structure ["dossier_temporaire"] = $structure_final ["dossier"];
		$structure_finale = array_merge ( $structure, $structure_standard );
		if (isset ( $structure_finale ["telecharger"] ))
			unset ( $structure_finale ["telecharger"] );
		
		return $structure_finale;
	}

	/**
	 * Prepare la partie gestion du fichier
	 * 
	 * @param $position
	 * @param $id_retour
	 * @return unknown_type
	 */
	private function _prepareGestionFichier($position, $id_retour) {
		if (! isset ( $this->liste_reponse [$position] )) {
			$this->liste_reponse [$position] = array ();
			$this->liste_reponse [$position] ["retour"] = array (
					$id_retour 
			);
			$this->liste_reponse [$position] ["telecharger"] = false;
		} else {
			$id = array_search ( $id_retour, $this->liste_reponse [$position] ["retour"] );
			if ($id === false)
				array_push ( $this->liste_reponse [$position] ["retour"], $id_retour );
		}
	}

	/**
	 * verifie si un fichier a deja etait traite.
	 * 
	 * @param $position
	 * @return Bool TRUE si ok, FALSE sinon.
	 */
	private function _verifieFichierCopie($position) {
		if (isset ( $this->liste_reponse [$position] )) {
			$CODE_RETOUR = $this->liste_reponse [$position] ["telecharger"];
		} else
			$CODE_RETOUR = false;
		
		return $CODE_RETOUR;
	}

	/********************** PARTIE COPIE DES DONNEES *************************/
	private function _copieDonnees($position) {
		//-1 veux dire en cours de telechargement
		$this->liste_reponse [$position] ["telecharger"] = - 1;
		
		//On fork pour telecharger
		//dans le processus fils on telecharge
		//dans le processus pere, on attend la fin des forks ou une nouvelle demande
		//Un fois la copie termine, on renvoi la reponse.
		$this->_renvoiReponse ( $position );
		return true;
	}

	/********************** PARTIE COPIE DES DONNEES *************************/
	
	/**
	 * (non-PHPdoc)
	 * @codeCoverageIgnore
	 * @see lib/fork/message#__destruct()
	 */
	public function __destruct() {
		//$this->supprime ();
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
		$help [__CLASS__] ["text"] [] .= "?";
		
		return $help;
	}
}
?>