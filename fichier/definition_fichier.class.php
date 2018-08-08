<?php
/**
 * @author dvargas
 * @package Lib
 * 
 */

/**
 * class copie_donnees<br>

 *
 * Creer un fichier au format standard.
 * @package Lib
 * @subpackage Fichier
 */
class definition_fichier extends variables_standards {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	var $structure_fichier;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type definition_fichier.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return definition_fichier
	 */
	static function &creer_definition_fichier(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new definition_fichier ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return definition_fichier
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * @codeCoverageIgnore
	 * @return definition_fichier
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->structure_fichier = false;
		return $this;
	}

	/**
	 *
	 * @param options $liste_options Pointeur sur les arguments.
	 * @param $var
	 * @param $valeur_defaut
	 * @return string
	 */
	public function verifie_variables(&$liste_options, $var, $valeur_defaut = "NOVAL") {
		if ($liste_options->verifie_option_existe ( $var, true ) !== false) {
			return $liste_options->getOption ( $var );
		} elseif ($valeur_defaut != "NOVAL") {
			return $valeur_defaut;
		}
		
		return "";
	}

	/**
	 * Creer (si elle n'existe pas) ou verifie une structure de fichier.
	 *
	 * @param options $liste_options Pointeur sur les arguments.
	 * @return definition_fichier.
	 */
	public function creer_structure_fichier(&$liste_options) {
		$this->onInfo ( "On creer la structure du fichier." );
		//type du fichier telecharge (donnee obligatoire)
		if ($liste_options->verifie_option_existe ( "type_donnees", true ) === false) {
			return $this->onError ( "Il manque le type de donnee." );
		} else {
			
			$this->onDebug ( "On cree la structure a partir des arguments.", 1 );
			
			//Il faut un nom au donnees
			$nom_donnees = $this->verifie_variables ( $liste_options, "nom_donnees", "" );
			//Il faut un dossier pour les donnees
			$dossier_donnees = $this->verifie_variables ( $liste_options, "dossier_donnees", "/tmp" );
			//le fichier telecharge est Mandatory ou pas
			$mandatory_donnees = $this->verifie_variables ( $liste_options, "mandatory_donnees", true );
			//le fichier telecharge est Mandatory ou pas
			$format_donnees = $this->verifie_variables ( $liste_options, "format_donnees", "f" );
			
			//le fichier telecharge doit matcher cette regexpr
			$this->structure_fichier_standard ( $nom_donnees, $liste_options->getOption ( "type_donnees" ), $dossier_donnees, $format_donnees, $mandatory_donnees, false );
			$this->onDebug ( $this->structure_fichier, 2 );
			$this->onInfo ( "La structure est cree." );
		}
		
		return $this;
	}

	/**
	 * Renvoi une structure standardise d'un fichier.<br>
	 * Format : f pour fichier, d pour dossier.<br>
	 *
	 * @param string $nom Nom du fichier.
	 * @param string $type Type du fichier : merged, split ... etc .
	 * @param string $dossier Dossier du fichier.
	 * @param string $format format du fichier : fichier/dossier.
	 * @param Bool $mandatory Necessaire ou non.
	 * @param Bool $telecharger Telecharger oui ou non.
	 * @return definition_fichier.
	 */
	public function structure_fichier_standard($nom, $type, $dossier = "", $format = "f", $mandatory = false, $telecharger = false) {
		$this->structure_fichier = array ();
		$this->structure_fichier ["nom"] = $nom;
		$this->structure_fichier ["dossier"] = $dossier;
		$this->structure_fichier ["type"] = $type;
		$this->structure_fichier ["format"] = $format;
		$this->structure_fichier ["mandatory"] = $mandatory;
		$this->structure_fichier ["telecharger"] = $telecharger;
		
		return $this;
	}

	/**
	 * Verifie si la structure est correcte.
	 *
	 * @return bool true si OK, false sinon.
	 */
	public function verifie_structure() {
		if (isset ( $this->structure_fichier ["nom"] ) && isset ( $this->structure_fichier ["dossier"] ) && isset ( $this->structure_fichier ["type"] ) && $this->structure_fichier ["type"] != "" && isset ( $this->structure_fichier ["mandatory"] ) && $this->structure_fichier ["mandatory"] !== "" && isset ( $this->structure_fichier ["telecharger"] ) && isset ( $this->structure_fichier ["format"] )) {
			return true;
		}
		return false;
	}

	/**
	 * Renvoi un affichage de la structure.
	 * @codeCoverageIgnore
	 * @return definition_fichier
	 */
	public function affiche_donnees_fichier($niveau_debug = 1) {
		$this->onDebug ( "Donnees du fichier :", $niveau_debug );
		$this->onDebug ( $this->structure_fichier, $niveau_debug );
		return $this;
	}

	/**
	 * Renvoi un affichage (toString) de la structure.
	 * @return string
	 */
	public function renvoi_parametre_fichier($parametre) {
		if (isset ( $this->structure_fichier [$parametre] )) {
			return $this->structure_fichier [$parametre];
		} 
		return false;
	}

	/**
	 * Ajoute un champ a la structure.
	 * 
	 * @return Bool true si OK, false sinon.
	 */
	public function ajoute_donnees_structure_fichier($type, $valeur) {
		if (! isset ( $this->structure_fichier [$type] )) {
			$this->structure_fichier [$type] = $valeur;
			return true;
		} 
		return false;
	}

	/**
	 * charge le champ type_copie avec get ou put.
	 * 
	 * @return Bool true si OK, false sinon.
	 */
	public function prepare_copie_fichier($valeur) {
		return $this->ajoute_donnees_structure_fichier("type_copie", $valeur);
	}

	/**
	 * Modifie un champ existant de la structure.
	 * 
	 * @return Bool true si OK, false sinon.
	 */
	public function modifie_donnees_structure_fichier($type, $valeur) {
		if (isset ( $this->structure_fichier [$type] )) {
			$CODE_RETOUR = true;
		} else {
			$CODE_RETOUR = false;
		}
		$this->structure_fichier [$type] = $valeur;
		
		return $CODE_RETOUR;
	}

	/**
	 * Renvoi la structure complete.
	 * 
	 * @return array|false La structure, false sinon.
	 */
	public function renvoi_structure_fichier() {
		if (is_array ( $this->structure_fichier )) {
			return $this->structure_fichier;
		}
		return false;
	}
	
	/*************** ACCESSEURS *******************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getStructureFichier() {
		return $this->structure_fichier;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setStructureFichier($structure_fichier) {
		$this->structure_fichier  = $structure_fichier;
		return $this;
	}
	/*************** ACCESSEURS *******************/

	/**
	 * Affiche le help.<br>
	 * Cette fonction fait un exit.
	 * Arguments reconnus :<br>
	 * --help
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "en ligne de commande :";
		$help [__CLASS__] ["text"] [] .= "\t--nom_donnees";
		$help [__CLASS__] ["text"] [] .= "  (faculatif) pas necessaire si on copie un dossier";
		$help [__CLASS__] ["text"] [] .= "\t--dossier_donnees";
		$help [__CLASS__] ["text"] [] .= "  cible pour le recupere, source pour le envoi. Par defaut:/tmp";
		$help [__CLASS__] ["text"] [] .= "\t--type_donnees";
		$help [__CLASS__] ["text"] [] .= "  perso : donnees sans type connu";
		$help [__CLASS__] ["text"] [] .= "  merged : donnees de type merged";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "\t--mandatory_donnees true/false";
		$help [__CLASS__] ["text"] [] .= "\t--format_donnees f/d    : f pour fichier, d pour dossier";
		
		return $help;
	}

	/**
	 * (non-PHPdoc)
	 * @codeCoverageIgnore
	 * @see lib/fork/message#__destruct()
	 */
	public function __destruct() {
	}
}
?>