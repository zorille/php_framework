<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class copie_donnees<br>
 *
 * Gere le transfert de fichier.
 * @package Lib
 * @subpackage Gestion_Machine
 */
class relation_fichier_machine extends definition_fichier {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_machines = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type relation_fichier_machine.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return relation_fichier_machine
	 */
	static function &creer_relation_fichier_machine(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new relation_fichier_machine ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return relation_fichier_machine
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * @codeCoverageIgnore
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		
		return true;
	}

	/**
	 * @param string $nom_machine Nom de la machine dans les arguments.
	 * @return true
	 */
	public function prepare_variables_machines($nom_machine) {
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"netname_source" 
		), "not_used" );
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"netname" 
		), "localhost" );
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"type_connexion" 
		), "ssh" );
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"option_ssh" 
		), "-r" );
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"user" 
		), "user" );
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"dossier_source" 
		), "/tmp/" );
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"dossier" 
		), "/tmp/" );
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"nom_source" 
		), "" );
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"nom" 
		), "" );
		
		return $this;
	}
	
	/**
	 * 
	 * @param string $nom_machine
	 * @param string $type
	 * @param string $regexpr
	 * @return boolean
	 */
	public function recupere_variables_par_machine($nom_machine,$type,$regexpr){
		//La regexpre fonctionne aussi bien pour le nom que pour un noeud
		$structure_fichier=$this->getStructureFichier();
		if ($regexpr !== false && (preg_match ( $regexpr, $structure_fichier ["nom"] ) === 1 || preg_match ( $regexpr, $this->getListeOptions()->getOption ( "noeud" ) ) === 1)) {
			$this->prepare_variables_machines ( $this->getListeOptions(), $nom_machine );
			$liste_machines=$this->getListeMachines();
			$position = count ( $liste_machines );
			$liste_machines [$position] = array ();
			$liste_machines [$position] ["type"] = $type;
			$liste_machines [$position] ["regexpr"] = $regexpr;
		
			$liste_machines [$position] ["netname_source"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"netname_source"
			) );
		
			$liste_machines [$position] ["netname"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"netname"
			) );
		
			$liste_machines [$position] ["type_connexion"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"type_connexion"
			) );
		
			$liste_machines [$position] ["option_ssh"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"option_ssh"
			) );
		
			$liste_machines [$position] ["user"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"user"
			) );
		
			$liste_machines [$position] ["dossier_source"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"dossier_source"
			) );
		
			$liste_machines [$position] ["dossier"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"dossier"
			) );
		
			$liste_machines [$position] ["nom_source"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"nom_source"
			) );
		
			$liste_machines [$position] ["nom"] = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"nom"
			) );
			
			$this->setListeMachines($liste_machines);
			
			return true;
		} 
		
		return false;
	}

	/**
	 * Verifie et set les donnees obligatoires des machines.
	 *
	 * @param string $nom_machine Nom de la machine dans les arguments.
	 * @return TRUE
	 */
	public function structure_variable_machines($nom_machine) {
		
		$this->getListeOptions()->prepare_variable_standard ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"type" 
		), "serial" );
		$type = $this->getListeOptions()->getOption ( array (
				"filer",
				"liste_machines",
				$nom_machine,
				"type" 
		) );
		
		//On valide le type de fichier (type_fichier etc ...)
		$structure_fichier=$this->getStructureFichier();
		if ($type == $structure_fichier ["type"]) {
			$this->getListeOptions()->prepare_variable_standard ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"regexpr" 
			), "/.*/" );
			$regexpr = $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines",
					$nom_machine,
					"regexpr" 
			) );
			
			return $this->recupere_variables_par_machine($nom_machine,$type,$regexpr);
			
		}
		
		return false;
	}

	/**
	 * Remplace le variables definie par l'utilisateur.
	 *
	 * @return relation_fichier_machine
	 */
	public function &remplace_donnees_standard() {
		$liste_machines=$this->getListeMachines();
		for($i = 0; $i < count ( $liste_machines ); $i ++) {
			//Si le nom du fichier est force, on met ce nom
			if ($this->getListeOptions()->verifie_option_existe ( "nom_fichier_force", true ) !== false) {
				$liste_machines [$i] ["nom"] = $this->getListeOptions()->getOption ( "nom_fichier_force" );
			} else {
				//sinon on traite les variables standards contenues dans le nom
				$liste_machines [$i] ["nom"] = $this->remplacement_standard ( $liste_machines [$i] ["nom_source"] );
			}
			$liste_machines [$i] ["dossier"] = $this->remplacement_standard ( $liste_machines [$i] ["dossier_source"] );
			if ($liste_machines [$i] ["netname_source"] !== "not_used") {
				$liste_machines [$i] ["netname"] = $this->remplacement_standard ( $liste_machines [$i] ["netname_source"] );
			}
		}
		
		$this->setListeMachines($liste_machines);
		return $this;
	}

	/**
	 *
	 * @return true
	 */
	public function verifie_surcharge_global() {
		if ($this->getListeOptions()->verifie_option_existe ( "type_connexion_donnees", true ) !== false) {
			$liste_machines=$this->getListeMachines();
			for($i = 0; $i < count ( $liste_machines ); $i ++) {
				$liste_machines [$i] ["type_connexion"] = $this->getListeOptions()->getOption ( "type_connexion_donnees" );
			}
			$this->setListeMachines($liste_machines);
		}
		return $this;
	}

	/**
	 * Verifie la presence des variables "filer" necessaires au traitement.<br>
	 *
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function prepare_liste_machine( $serial, $date) {
		$liste_machine = array ();
		
		//gestion du filer en ligne de commande
		if ($this->getListeOptions()->verifie_option_existe ( "filer_liste_machines", true )) {
			//On reset la liste dans le cas d'une liste par arguments
			$this->onDebug ( "filer_liste_machines mode", 2 );
			if ($this->getListeOptions()->verifie_option_existe ( array (
					"filer",
					"liste_machines" 
			), true )) {
				$this->getListeOptions()->setOption ( array (
						"filer",
						"liste_machines" 
				), array () );
			}
			
			$liste_machine = $this->getListeOptions()->getOption ( "filer_liste_machines" );
			if (! is_array ( $liste_machine )) {
				$liste_machine = explode ( " ", $liste_machine );
			}
			
			for($i = 0; $i < count ( $liste_machine ); $i ++)
				$this->structure_variable_machines ( $liste_machine [$i] );
		} elseif ($this->getListeOptions()->verifie_option_existe ( array (
				"filer",
				"liste_machines" 
		), true ) && is_array ( $this->getListeOptions()->getOption ( array (
				"filer",
				"liste_machines" 
		) ) )) {
			$this->onDebug ( "xml filer,liste_machines mode", 2 );
			foreach ( $this->getListeOptions()->getOption ( array (
					"filer",
					"liste_machines" 
			) ) as $nom => $machine ) {
				$machine;
				$this->structure_variable_machines ( $nom );
			}
		} else {
			//On creer un filer de donnees avec le valeurs standards
			$this->structure_variable_machines ( "machine1" );
		}
		
		$this->prepare_remplacement ( $serial, $date );
		$this->remplace_donnees_standard (  );
		
		$this->verifie_surcharge_global (  );
		
		if (count ( $this->getListeMachines() ) > 0) {
			return true;
		}
		
		return false;
	}

	/**
	 * Renvoi un parametre de la definition standard du fichier (par rapport a une machine donnee).
	 *
	 * @param string $parametre Parametre recherche.
	 * @param int $numero Position de la machine de reference.
	 * @return string[false Parametre retourne,false sinon.
	 */
	public function renvoi_parametre_machine($parametre, $numero = 0) {
		$liste_machines=$this->getListeMachines();
		if (isset ( $liste_machines [$numero] [$parametre] )) {
			return $liste_machines [$numero] [$parametre];
		} 
		return false;
	}

	/**
	 * Modifie un champ existant de la structure.
	 *
	 * @return Bool true si OK, false sinon.
	 */
	public function modifie_donnees_structure_machine($parametre, $valeur, $numero = 0) {
		$liste_machines=$this->getListeMachines();
		if (isset ( $liste_machines [$numero] )) {
			$liste_machines [$numero] [$parametre] = $valeur;
			return $this->setListeMachines($liste_machines);
		}
		
		return false;
	}

	/**
	 * Renvoi un affichage de la structure.
	 * @return relation_fichier_machine
	 */
	public function affiche_donnees_machine($niveau_debug = 1) {
		$this->onDebug ( "Liste des machines :", $niveau_debug );
		$this->onDebug ( $this->getListeMachines(), $niveau_debug );
		return $this;
	}

	/*************** ACCESSEURS *******************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeMachines() {
		return $this->liste_machines;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeMachines($liste_machines) {
		$this->liste_machines  = $liste_machines;
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
		$help [__CLASS__] ["text"] [] .= "Par XML :";
		$help [__CLASS__] ["text"] [] .= " <filer>";
		$help [__CLASS__] ["text"] [] .= "  <liste_machines>";
		$help [__CLASS__] ["text"] [] .= "   <machine_type_fichier>";
		$help [__CLASS__] ["text"] [] .= "    <type>type_fichier</type>";
		$help [__CLASS__] ["text"] [] .= "    <regexpr>/.*/</regexpr>";
		$help [__CLASS__] ["text"] [] .= "    <netname_source>{NOMMACHINE}</netname_source> ou <netname>toto.fqdn</netname>";
		$help [__CLASS__] ["text"] [] .= "    <type_connexion>rsync/ssh/ftp</type_connexion>";
		$help [__CLASS__] ["text"] [] .= "    <option_ssh>-r -l 16000</option_ssh>";
		$help [__CLASS__] ["text"] [] .= "    <user>echo</user>";
		$help [__CLASS__] ["text"] [] .= "    <dossier_source>/tmp/</dossier_source>";
		$help [__CLASS__] ["text"] [] .= "    <nom_source>{UUID}.gz</nom_source>";
		$help [__CLASS__] ["text"] [] .= "   </machine_type_fichier>";
		$help [__CLASS__] ["text"] [] .= "  </liste_machines>";
		$help [__CLASS__] ["text"] [] .= " </filer>";
		$help [__CLASS__] ["text"] [] .= "Les textes type \"-1 day\" peuvent etre utilises";
		
		return $help;
	}
}