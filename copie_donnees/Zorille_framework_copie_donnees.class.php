<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class copie_donnees<br>
 * @codeCoverageIgnore
 * Gere le transfert de fichier.
 * @package Lib
 * @subpackage Copie_Donnees
 */
class copie_donnees extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var ftp|ssh_z
	 */
	var $connexion = false;
	/**
	 * var privee
	 * @access private
	 * @var relation_fichier_machine
	 */
	var $fichier_a_traiter;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	var $structure_machine;
	/**
	 * var privee
	 * @access private
	 * @var fonctions_standards
	 */
	private $class_standard;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type copie_donnees.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param ssh_z|ftp $connexion connexion ftp/ssh existante.
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return copie_donnees
	 */
	static function &creer_copie_donnees(&$liste_option, $connexion = false, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new copie_donnees ( $connexion, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return copie_donnees
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$fctsStandards = fonctions_standards::creer_fonctions_standards ( $liste_class ["options"] );
		$this->setFonctionsStandards ( $fctsStandards );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 *
	 * @param options $liste_options Pointeur sur les arguments.
	 * @param ssh_z|ftp $connexion connexion ftp/ssh existante.
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($connexion = false, $sort_en_erreur = false, $entete = __CLASS__) {
		$this->connexion = $connexion;
		
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		return true;
	}

	/**
	 * Retrouve le chemin complet des commandes shells necessaire au traitement.
	 *
	 * @return TRUE
	 */
	private function _commandesShellCopieDonnees() {
		//traitement des commandes
		if ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"commande",
				"rmdir" 
		), true ) === false)
			$this->getListeOptions ()
				->setOption ( array (
					"commande",
					"rmdir" 
			), $this->getFonctionsStandards ()
				->recupere_chemin_commande ( "rmdir" ) );
		if ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"commande",
				"rm" 
		), true ) === false)
			$this->getListeOptions ()
				->setOption ( array (
					"commande",
					"rm" 
			), $this->getFonctionsStandards ()
				->recupere_chemin_commande ( "rm" ) );
		if ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"commande",
				"ls" 
		), true ) === false)
			$this->getListeOptions ()
				->setOption ( array (
					"commande",
					"ls" 
			), $this->getFonctionsStandards ()
				->recupere_chemin_commande ( "ls" ) );
		if ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"commande",
				"mkdir" 
		), true ) === false)
			$this->getListeOptions ()
				->setOption ( array (
					"commande",
					"mkdir" 
			), $this->getFonctionsStandards ()
				->recupere_chemin_commande ( "mkdir" ) );
		if ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"commande",
				"ssh" 
		), true ) === false)
			$this->getListeOptions ()
				->setOption ( array (
					"commande",
					"ssh" 
			), $this->getFonctionsStandards ()
				->recupere_chemin_commande ( "ssh" ) );
		if ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"commande",
				"scp" 
		), true ) === false)
			$this->getListeOptions ()
				->setOption ( array (
					"commande",
					"scp" 
			), $this->getFonctionsStandards ()
				->recupere_chemin_commande ( "scp" ) );
		if ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"commande",
				"zcat" 
		), true ) === false)
			$this->getListeOptions ()
				->setOption ( array (
					"commande",
					"zcat" 
			), $this->getFonctionsStandards ()
				->recupere_chemin_commande ( "zcat" ) );
		if ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"commande",
				"pwd" 
		), true ) === false)
			$this->getListeOptions ()
				->setOption ( array (
					"commande",
					"pwd" 
			), $this->getFonctionsStandards ()
				->recupere_chemin_commande ( "pwd" ) );
		
		$this->onDebug ( "Liste des commandes shells : ", 2 );
		$this->onDebug ( $this->getListeOptions ()
			->getOption ( "commande" ), 2 );
		
		return true;
	}

	/**
	 * Retrouve la liste des date en fonction du type de donnee.<br>
	 * si --type_donnees=report_cumul_month => on prend les dates de type mois.<br>
	 * si --type_donnees=report_cumul_week => on prend les dates de type week.<br>
	 * sinon on prend les dates de type day.<br>
	 *
	 * @return TRUE
	 */
	private function _retrouveDatesCopie() {
		//On creer la liste des dates a creer
		$this->liste_dates_tableau = array ();
		$liste_dates = dates::creer_dates ( $this->getListeOptions () );
		if ($liste_dates === false)
			$this->liste_dates_tableau [0] = false;
		else {
			switch ($this->getListeOptions ()
				->getOption ( "type_donnees" )) {
				case "report_cumul_month" :
					$this->liste_dates_tableau = $liste_dates->getListeMonth ();
					break;
				case "report_cumul_week" :
					$this->liste_dates_tableau = $liste_dates->getListeWeek ();
					break;
				default :
					$this->liste_dates_tableau = $liste_dates->getListeDates ();
			}
		}
		
		$this->onDebug ( "Liste des dates pour la copie : ", 2 );
		$this->onDebug ( $this->liste_dates_tableau, 2 );
		
		return true;
	}

	/**
	 * Creer (si elle n'existe pas) ou verifie une structure de fichier.
	 * @param relation_fichier_machine|false $structure_fichier_origine Structure du fichier d'origine.
	 * @return TRUE
	 */
	private function _creerStructureFichier($structure_fichier_origine) {
		$this->onDebug ( "On creer la structure du fichier.", 1 );
		
		if ($structure_fichier_origine === false) {
			$flag_erreur = $this->_verifieVariablesNecessaire ();
			if (! $flag_erreur) {
				$this->fichier_a_traiter = relation_fichier_machine::creer_relation_fichier_machine ( $this->getListeOptions (), true );
				$this->fichier_a_traiter->_creerStructureFichier ( $this->getListeOptions () );
			}
		} else {
			if ($structure_fichier_origine instanceof relation_fichier_machine) {
				$this->fichier_a_traiter = $structure_fichier_origine;
				$flag_erreur = false;
			} else
				$flag_erreur = true;
		}
		
		$this->onDebug ( "Structure cree :", 1 );
		$this->onDebug ( $this->fichier_a_traiter->affiche_donnees_fichier (), 1 );
		
		return $flag_erreur;
	}

	/**
	 * Verifie et charge les variable obligatoire.
	 *
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	private function _verifieVariablesNecessaire() {
		$flag_erreur = false;
		//on ajoute la definition passe en argument dans --conf_copie
		if ($this->getListeOptions ()
			->verifie_option_existe ( "conf_copie", true ) === true) {
			$this->getListeOptions ()
				->ajouter_fichier_conf ( $this->getListeOptions ()
				->getOption ( "conf_copie" ) );
		}
		
		$this->_commandesShellCopieDonnees ();
		//On recupere le nom de la machine courante.
		if ($this->getListeOptions ()
			->verifie_option_existe ( "netname", true ) === false) {
			$this->getListeOptions ()
				->setOption ( "netname", php_uname ( "n" ) );
		}
		
		//Il faut une machine source ou dest (donnee obligatoire)
		if ($this->getListeOptions ()
			->verifie_option_existe ( "filer_liste_machines", true ) === false && $this->getListeOptions ()
			->verifie_option_existe ( array (
				"filer",
				"liste_machines" 
		), true ) === false) {
			$flag_erreur = true;
		}
		$this->onDebug ( "_verifieVariablesNecessaire->flag erreur : " . $flag_erreur, 2 );
		
		//Il faut un type de copie
		if ($this->getListeOptions ()
			->verifie_option_existe ( "type_copie", true ) === false) {
			$this->getListeOptions ()
				->setOption ( "type_copie", "get" );
		}
		
		return $flag_erreur;
	}

	/**
	 * selectionne entre get et put, et envoi la bonne fonction de copie.
	 *
	 * @param string $nom_fichier_standard Nom du fichier ou dossier de depart.
	 * @param string $nom_fichier_final Nom du fichier ou dossier final.
	 * @param string $type_copie get ou put.
	 * @param Bool $is_dir Si le $nom_fichier_standard et $nom_fichier_final sont des dossier ou non.
	 * @return Bool true si copie OK, false sinon.
	 */
	private function _choisiTypeCopie($nom_fichier_standard, $nom_fichier_final, $type_copie, $is_dir) {
		$this->onDebug ( "Nom fichier standard :" . $nom_fichier_standard, 2 );
		$this->onDebug ( "Nom fichier final :" . $nom_fichier_final, 2 );
		$this->onDebug ( "Type de copie :" . $type_copie, 2 );
		
		$fonctions_standards_flux = fonctions_standards_flux::creer_fonctions_standards_flux ( $liste_option );
		$fonctions_standards_flux->setConnexion ( $this->connexion );
		if ($type_copie == "get") {
			$resultat = $fonctions_standards_flux->copie_donnee_standard ( $this->getListeOptions (), $this->fichier_a_traiter->renvoi_parametre_machine ( "netname" ), $nom_fichier_standard, $nom_fichier_final, $this->fichier_a_traiter->renvoi_parametre_machine ( "user" ), $this->fichier_a_traiter->renvoi_parametre_machine ( "type_connexion" ), $this->fichier_a_traiter->renvoi_parametre_machine ( "option_ssh" ), $this->fichier_a_traiter->renvoi_parametre_fichier ( "mandatory" ), $is_dir );
		} elseif ($type_copie == "put") {
			$resultat = $fonctions_standards_flux->envoi_donnee_standard ( $this->getListeOptions (), $this->fichier_a_traiter->renvoi_parametre_machine ( "netname" ), $nom_fichier_final, $nom_fichier_standard, $this->fichier_a_traiter->renvoi_parametre_machine ( "user" ), $this->fichier_a_traiter->renvoi_parametre_machine ( "type_connexion" ), $this->fichier_a_traiter->renvoi_parametre_machine ( "option_ssh" ), $this->fichier_a_traiter->renvoi_parametre_fichier ( "mandatory" ), $is_dir );
		} else
			$resultat = false;
		
		return $resultat;
	}

	/**
	 * renvoi le type de copie.
	 *
	 * @return String Type de copie
	 */
	public function renvoieTypeCopie() {
		$type_copie = $this->fichier_a_traiter->renvoi_parametre_fichier ( "type_copie" );
		if ($type_copie == "") {
			$type_copie = $this->getListeOptions ()
				->getOption ( "type_copie" );
		}
		
		if ($type_copie == "") {
			return $this->onError ( "Il manque le type de copie" );
		}
		
		return $type_copie;
	}

	/**
	 * Fait la copie d'un (et d'un seul) fichier.
	 *
	 * @return true
	 */
	private function _copieStandardFichier() {
		$this->onInfo ( "On copie les donnees." );
		
		if ($this->fichier_a_traiter->renvoi_parametre_fichier ( "nom" ) == "") {
			$nom_fichier_final = $this->fichier_a_traiter->renvoi_parametre_fichier ( "dossier" ) . "/" . $this->fichier_a_traiter->renvoi_parametre_machine ( "nom" );
			$this->fichier_a_traiter->modifie_donnees_structure_fichier ( "nom", $this->fichier_a_traiter->renvoi_parametre_machine ( "nom" ) );
		} else {
			$nom_fichier_final = $this->fichier_a_traiter->renvoi_parametre_fichier ( "dossier" ) . "/" . $this->fichier_a_traiter->renvoi_parametre_fichier ( "nom" );
		}
		
		$type_copie = $this->renvoieTypeCopie ();
		
		$nom_fichier_standard = $this->fichier_a_traiter->renvoi_parametre_machine ( "dossier" ) . "/" . $this->fichier_a_traiter->renvoi_parametre_machine ( "nom" );
		
		//Si le fichier est en local, on dit renvoi OK
		if ($type_copie == "get" && fichier::tester_fichier_existe ( $nom_fichier_final ) === true) {
			$this->onInfo ( "On utilise la copie local du fichier." );
			$resultat = true;
		} else {
			//Sinon on copie
			$resultat = $this->_choisiTypeCopie ( $nom_fichier_standard, $nom_fichier_final, $type_copie, false );
		}
		
		$this->fichier_a_traiter->modifie_donnees_structure_fichier ( "telecharger", $resultat );
		
		if ($this->fichier_a_traiter->renvoi_parametre_fichier ( "telecharger" ) === false && $this->fichier_a_traiter->renvoi_parametre_fichier ( "mandatory" ) === true)
			return $this->onError ( "Probleme avec la copie du fichier : " . $nom_fichier_standard . " vers " . $nom_fichier_final . " en mode " . $type_copie );
		
		return true;
	}

	/**
	 * Fait la copie d'un (et d'un seul) dossier.
	 *
	 * @return true
	 */
	private function _copieStandardDossier() {
		$this->onInfo ( "On copie le dossier." );
		//$fichier_log=self::$logs;
		$flag_erreur = false;
		
		$type_copie = $this->renvoieTypeCopie ();
		
		$type_connexion = $this->fichier_a_traiter->renvoi_parametre_machine ( "type_connexion" );
		$this->onDebug ( "Type_connexion : " . $type_connexion, 2 );
		if ($type_connexion != 'ssh' && $type_connexion != 'rsync') {
			return $this->onError ( "Le type de copie (" . $type_connexion . ") ne permet pas de copie un dossier" );
			$flag_erreur = true;
		}
		
		$nom_dossier_final = $this->fichier_a_traiter->renvoi_parametre_fichier ( "dossier" );
		$nom_dossier_standard = $this->fichier_a_traiter->renvoi_parametre_machine ( "dossier" );
		
		//Si il n'y a pas d'erreur
		if (! $flag_erreur) {
			$resultat = $this->_choisiTypeCopie ( $nom_dossier_standard, $nom_dossier_final, $type_copie, true );
		} else {
			$resultat = false;
		}
		
		$this->fichier_a_traiter->modifie_donnees_structure_fichier ( "telecharger", $resultat );
		
		if ($this->fichier_a_traiter->renvoi_parametre_fichier ( "telecharger" ) === false && $this->fichier_a_traiter->renvoi_parametre_fichier ( "mandatory" ) === true)
			return $this->onError ( "Probleme avec la copie du fichier : " . $nom_dossier_standard . " vers " . $nom_dossier_final . " en mode " . $type_copie );
		
		return true;
	}

	/**
	 * Fait la copie d'un ou plusieurs fichier(s).
	 *
	 * @param string $uuid uuid au format standard.
	 * @param relation_fichier_machine|false $structure_fichier_origine Structure du fichier a telecharger ou false si la programme cree la structure.
	 * @return array Liste des structure de fichier(s) telecharge(s).
	 */
	public function copie_donnees($uuid = false, $structure_fichier_origine = false) {
		//On creer la liste des dates a creer
		$this->_retrouveDatesCopie ();
		
		$flag_erreur = $this->_creerStructureFichier ( $structure_fichier_origine );
		
		$this->onDebug ( "Les options sont chargees.", 1 );
		$this->onDebug ( "copie_donnees->flag erreur : " . $flag_erreur, 2 );
		
		if (! $flag_erreur) {
			foreach ( $this->liste_dates_tableau as $date ) {
				$this->onDebug ( "uuid : " . $uuid . " Date : " . $date, 2 );
				$machine_ok = $this->fichier_a_traiter->prepare_liste_machine ( $uuid, $date );
				$this->fichier_a_traiter->affiche_donnees_machine ( 2 );
				
				if ($machine_ok) {
					if ($this->fichier_a_traiter->renvoi_parametre_fichier ( "format" ) == "f") {
						$this->_copieStandardFichier ();
					} elseif ($this->fichier_a_traiter->renvoi_parametre_fichier ( "format" ) == "d") {
						$this->_copieStandardDossier ();
					} else {
						return $this->onError ( "Le format a copier est inconnu !", $this->fichier_a_traiter->renvoi_parametre_fichier ( "format" ) );
					}
				} else {
					return $this->onError ( "Pas de machine dans la liste.", $machine_ok );
				}
				$this->fichier_a_traiter->affiche_donnees_fichier ( 2 );
			}
		}
		
		return $this->fichier_a_traiter->renvoi_structure_fichier ();
	}

	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Fais un copie en get ou en put";
		$help [__CLASS__] ["text"] [] .= "Utilise la class relation_fichier_machine";
		
		return $help;
	}

	/**
	 * (non-PHPdoc)
	 * @codeCoverageIgnore
	 * @see lib/fork/message#__destruct()
	 */
	public function __destruct() {
	}

	/***************** ACCESSEURS *********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getFonctionsStandards() {
		return $this->class_standard;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFonctionsStandards(&$class_standard) {
		$this->class_standard = $class_standard;
		
		return $this;
	}
/***************** ACCESSEURS *********************/
}
?>