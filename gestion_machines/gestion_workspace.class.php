<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class dossier_standard<br>
 *
 * Permet de creer les dossiers standard.
 * @package Lib
 * @subpackage Gestion_Machine
 */
class gestion_workspace extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_workspace = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type gestion_workspace.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return gestion_workspace
	 */
	static function &creer_gestion_workspace(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new gestion_workspace ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return gestion_workspace
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->creer_workspace ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur
	 * @param string $entete Entete lors de l'affichage.
	 * @return <type>
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		return true;
	}

	/**
	 * Set le dossier du workspace.<br>
	 * Si l'option workspace existe en ligne de commade, elle sera utilisee.<br>
	 * Sinon la partie xml et enfin par defaut '.'.
	 *
	 * @return true
	 */
	public function creer_workspace() {
		$this->onDebug ( "creer_workspace : ", 1 );
		//Si le workspace existe dans les options
		if ($this->getListeOptions ()
			->verifie_option_existe ( "workspace", true ) !== false) {
			$workspace = $this->getListeOptions ()
				->getOption ( "workspace" );
			if (! is_array ( $workspace )) {
				//Par ligne de commande en priorite
				$this->setAllWorkspace ( $workspace );
			} elseif (is_array ( $workspace ) && isset ( $workspace ['liste_workspace'] )) {
				//par donnees xml
				foreach ( $workspace ['liste_workspace'] as $type_workspace => $data_workspace ) {
					$this->setTypeWorkspace($type_workspace, $data_workspace ["dossier_source"]);
				}
			} else {
				return $this->onError ( "La definition du workspace est fausse.", $workspace );
			}
		} else {
			//Par defaut le workspace est '.'
			$this->setAllWorkspace ( posix_getcwd () );
		}
		
		$this->onDebug ( $this->getWorkspace(), 2 );
		return $this;
	}

	/**
	 * Supprime la liste complete des workspaces.<br>
	 * 
	 * @return bool True si OK, False sinon.
	 */
	public function supprime_workspace() {
		$this->onDebug ( "supprime_workspace : ", 1 );
		foreach ( $this->getWorkspace() as $type => $donnee ) {
			$this->onDebug ( "Suppression du dossier : " . $donnee ["dir"], 1 );
			//$sup=repertoire::supprimer_repertoire($donnee["dir"]);
			$cmd = "rm -Rf " . $donnee ["dir"]." 2> /dev/null";
			$this->onDebug ( "commande : " . $cmd, 1 );
			$sup = fonctions_standards::applique_commande_systeme ( $cmd, false );
			if ($sup [0] !== 0) {
				return $this->onError ( "Le dossier : " . $donnee ["dir"] . " ne peut pas etre supprime." );
			}
		}
		
		return $this;
	}

	/*************** ACCESSEURS *******************/
	/**
	 * Renvoi une liste des workspaces.
	 * @codeCoverageIgnore
	 * @param string $type Pour avoir le type de workspace.
	 * @return string|false Dossier workspace, false sinon.
	 */
	public function getTypeWorkspace($type) {
		if (isset ( $this->liste_workspace [$type] )) {
			return $this->liste_workspace [$type] ["dir"];
		} elseif (isset ( $this->liste_workspace ["all"] )) {
			return $this->getAllWorkspace();
		}
		
		return false;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setTypeWorkspace($type,$workspace) {
		$this->liste_workspace [$type] ["dir"] = $workspace;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAllWorkspace() {
		return $this->liste_workspace ["all"] ["dir"];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAllWorkspace($workspace) {
		if ($workspace != "") {
			$this->liste_workspace ["all"] ["dir"] = $workspace;
		}
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getWorkspace() {
		return $this->liste_workspace;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setWorkspace($workspaces) {
		$this->liste_workspace  = $workspaces;
		return $this;
	}
	
	

	/*************** ACCESSEURS *******************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "\t--workspace //Force un dossier";
		$help [__CLASS__] ["text"] [] .= "\t--nettoie_workspace Force le nettoyage du workspace";
		
		return $help;
	}
}

?>
