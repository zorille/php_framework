<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class fonctions_standards<br>
 * Fonctions generales communes.
 *
 * @package Lib
 * @subpackage Fonctions_Standards
 */
class fonctions_standards extends abstract_log {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fonctions_standards.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standards
	 */
	static function &creer_fonctions_standards(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fonctions_standards ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standards
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet.
	 * @codeCoverageIgnore
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml
	 * et creer le chemin vers le sqlite.<br>
	 * Arguments reconnus :<br>
	 * --dossier_rangement=/tmp<br>
	 *
	 * @param options &$liste_option Pointeur sur les arguments
	 * @return string Renvoi le chemin physique du serial.
	 */
	public function creer_report_path(&$liste_option) {
		// voir help report_path
		if ($liste_option->verifie_option_existe ( "dossier_rangement", true ) !== false) {
			$dossier_par_defaut = $liste_option->getOption ( "dossier_rangement" );
		} else {
			$dossier_par_defaut = "/tmp";
		}
		
		$this->onDebug ( $dossier_par_defaut, 2 );
		return $dossier_par_defaut;
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml
	 * et renvoi un taille en octet.<br>
	 * Arguments reconnus :<br>
	 * --taille_disque_mini=200Mo <br>
	 * format supporte : ko Mo Go To et par defaut aucun identifiant=octet
	 *
	 * @param options &$pointeur_liste_option
	 * @return int Taille Transforme en octet.
	 */
	public function renvoi_taille_octet(&$liste_option) {
		// voir help taille_octet
		$CODE_RETOUR = 209715200;
		if ($liste_option->verifie_option_existe ( "taille_disque_mini", true ) !== false) {
			$taille = $liste_option->getOption ( "taille_disque_mini" );
			$format = substr ( trim ( $taille ), count ( $taille ) - 3 );
			$facteur = $this->renvoi_coef_octet ( $format );
			if ($facteur === 1)
				$CODE_RETOUR = $taille;
			else
				$CODE_RETOUR = floor ( trim(substr ( $taille, 0, count ( $taille ) - 3 )) / $facteur );
		}
		return $CODE_RETOUR;
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml
	 * et renvoi un coef pour calculer en octet.<br>
	 * format supporte : o ko Mo Go To et par defaut aucun identifiant=octet
	 *
	 * @param string $format Format de depart.
	 * @return int Coeficient pour retrouve la valeur en octet.
	 */
	public function renvoi_coef_octet($format) {
		// format = o (octet), ko, Mo, Go, To
		$facteur = 1;
		switch ($format) {
			case "To" :
				$facteur *= 1024;
			case "Go" :
				$facteur *= 1024;
			case "Mo" :
				$facteur *= 1024;
			case "ko" :
				$facteur *= 1024;
			case "o" :
				$facteur *= 1;
		}
		
		return $facteur;
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml
	 * et renvoi un chemin contenant le nom du fichier.<br>
	 * Arguments reconnus :<br>
	 * --fichier_nom="/test" <br>
	 * nom du/des fichiers optionnel si prevu dans le code<br>
	 * --fichier_extension="/.csv" <br>
	 * extension du/des fichiers<br>
	 * --fichier_repertoire="/tmp" <br>
	 * optionnel repertoire ou se situe les fichiers<br>
	 * --fichier_affiche_date="oui/non" <br>
	 * affiche la date dans le nom du/des fichiers
	 *
	 * @param dates $liste_date Liste de dates.
	 * @param string $nom_fichier Surcharge du nom de fichier.
	 * @return string Chemin complet du fichier.
	 */
	public function creer_nom_fichier(&$liste_option, $liste_date = false, $nom_fichier = "non") {
		$repertoire = "";
		$this->trouve_nom_fichier ( $liste_option, $nom_fichier )
			->trouve_extension_fichier ( $liste_option, $nom_fichier )
			->trouve_dossier_fichier ( $liste_option, $repertoire, $nom_fichier )
			->trouve_dates_fichier ( $liste_option, $nom_fichier, $liste_date );
		
		$this->onDebug ( $repertoire . $nom_fichier, 2 );
		return $repertoire . $nom_fichier;
	}

	/**
	 * Trouve le nom du fichier dans les parametres
	 * @param options $liste_option
	 * @param string $nom_fichier
	 * @return fonctions_standards
	 */
	public function trouve_nom_fichier(&$liste_option, &$nom_fichier) {
		// Si un nom de fichier est en argument, alors il est prioritaire sur celui passe dans le code
		if ($liste_option->verifie_option_existe ( "fichier_nom", true ) !== false) {
			$nom_fichier = $liste_option->getOption ( "fichier_nom" );
		} elseif ($liste_option->verifie_option_existe ( array (
				"fichier",
				"nom" 
		) ) !== false) {
			$nom_fichier = $liste_option->getOption ( array (
					"fichier",
					"nom" 
			) );
		} elseif ($nom_fichier == "non")
			$nom_fichier = false;
		
		return $this;
	}

	/**
	 * Trouve l'extension du fichier dans les parametres
	 * @param options $liste_option
	 * @param string $nom_fichier
	 * @return fonctions_standards
	 */
	public function trouve_extension_fichier(&$liste_option, &$nom_fichier) {
		// Si on a une extension
		if ($liste_option->verifie_option_existe ( "fichier_extension", true ) !== false) {
			$nom_fichier = $nom_fichier . $liste_option->getOption ( "fichier_extension" );
		} elseif ($liste_option->verifie_option_existe ( array (
				"fichier",
				"extension" 
		), true ) !== false) {
			$nom_fichier = $nom_fichier . $liste_option->getOption ( array (
					"fichier",
					"extension" 
			) );
		}
		
		return $this;
	}

	/**
	 * Trouve le dossier du fichier dans les parametres
	 * @param options $liste_option
	 * @param string $repertoire
	 * @param string $nom_fichier
	 * @return fonctions_standards
	 */
	public function trouve_dossier_fichier(&$liste_option, &$repertoire, $nom_fichier) {
		// si on a un dossier/repertoire
		if ($liste_option->verifie_option_existe ( "fichier_repertoire", true ) !== false) {
			$repertoire = $liste_option->getOption ( "fichier_repertoire" ) . "/";
		} elseif ($liste_option->verifie_option_existe ( array (
				"fichier",
				"repertoire" 
		), true ) !== false) {
			$repertoire = $liste_option->getOption ( array (
					"fichier",
					"repertoire" 
			) ) . "/";
			if ($liste_option->verifie_option_existe ( "rep_scripts" ) !== false) {
				$repertoire = str_replace ( "{home_dir}", $liste_option->getOption ( "rep_scripts" ), $repertoire );
			}
		} elseif ($liste_option->verifie_option_existe ( "rep_scripts" ) !== false) {
			$repertoire = $liste_option->getOption ( "rep_scripts" ) . "/";
		} elseif ($nom_fichier)
			$repertoire = "./";
		else
			$repertoire = false;
		
		return $this;
	}

	/**
	 * Trouve les dates du fichier dans les parametres
	 * @param options $liste_option
	 * @param string $nom_fichier
	 * @param dates $liste_date
	 * @return fonctions_standards
	 */
	public function trouve_dates_fichier(&$liste_option, &$nom_fichier, &$liste_date) {
		if ($liste_date && (($liste_option->getOption ( "fichier_affiche_date", true ) == "oui") || ($liste_option->verifie_option_existe ( "fichier[@affiche_date='oui']", true ) !== false))) {
			if ($liste_option->verifie_option_existe ( "cumul_month" ) !== false) {
				$liste_date_tempo = $liste_date->getListeMonth ();
			} elseif ($liste_option->verifie_option_existe ( "cumul_week" ) !== false) {
				$liste_date_tempo = $liste_date->getListeWeek ();
			} else
				$liste_date_tempo = $liste_date->getListeDates ();
			
			$nb_case = count ( $liste_date_tempo );
			if ($nb_case == 1)
				$nom_fichier = $liste_date_tempo [0] . "_" . $nom_fichier;
			else
				$nom_fichier = $liste_date_tempo [0] . "_" . $liste_date_tempo [$nb_case - 1] . "_" . $nom_fichier;
		}
		
		return $this;
	}

	/**
	 * Affiche le differents help on demand.<br>
	 * @codeCoverageIgnore

	 * @param Bool $fonctions_standards Affiche/renvoi le help de la class fonctions_standards.
	 * @param Bool $all_class Affiche/renvoi le help de chaques class deja en memoire.
	 * @param array $class_utilisees Affiche/renvoi le help de chaques class declarees dans cette liste.
	 * @return TRUE
	 */
	static public function help_fonctions_standard($fonctions_standards = false, $all_class = true, $class_utilisees = array()) {
		$class_afficher = array ();
		
		if ($fonctions_standards) {
			$class_afficher = array_merge ( $class_afficher, fonctions_standards::help () );
		}
		
		if ($all_class) {
			foreach ( get_declared_classes () as $class_locale ) {
				if ($class_locale == "fonctions_standards") {
					continue;
				}
				if (method_exists ( $class_locale, "help" )) {
					$class_afficher = array_merge ( $class_afficher, call_user_func ( $class_locale . "::help" ) );
				}
			}
		}
		
		foreach ( $class_utilisees as $class_locale ) {
			if (method_exists ( $class_locale, "help" )) {
				$class_afficher = array_merge ( $class_afficher, call_user_func ( $class_locale . "::help" ) );
			}
		}
		
		return $class_afficher;
	}

	/**
	 * Verifie si le help de la class pere a ete affiche.
	 *
	 * @param array $class_used Liste des classes deja affiches (le help).
	 * @param string $nom_class Nom de la classe a afficher (le help).
	 * @return bool true si pas affiche, false si deja affiche
	 */
	public function retrouve_class_parent(&$class_used, $nom_class) {
		$parent = "";
		$retour = true;
		while ( $parent !== false ) {
			$parent = get_parent_class ( $nom_class );
			if ($parent) {
				// Si on a deja affiche le help de la class parent, on continue
				if (isset ( $class_used [$parent] )) {
					$retour = false;
					break;
				}
				$nom_class = $parent;
			}
		}
		
		return $retour;
	}

	/**
	 * Fait un affichage standard pour les helps
	 * @codeCoverageIgnore
	 * @param array $help doit contenir ["titre"] et un tableau de ["text"]
	 */
	static public function affichage_standard_help($help) {
		if (! is_array ( $help )) {
			return false;
		}
		
		if (isset ( $help ["usage"] ) && is_array ( $help ["usage"] )) {
			echo "\r\n";
			foreach ( $help ["usage"] as $ligne ) {
				echo abstract_log::colorize ( "Usage : ", "BIWhite" ) . $ligne . "\r\n";
			}
			echo "\r\n";
		}
		
		echo abstract_log::colorize ( "Params :\r\n", "BIWhite" );
		foreach ( $help as $class ) {
			//On n'affiche pas les exemples sur chaque class
			if ($class == "exemples") {
				continue;
			}
			//echo strtoupper ( $class ) . "\r\n";
			if (isset ( $class ["text"] ) && is_array ( $class ["text"] )) {
				foreach ( $class ["text"] as $ligne ) {
					if (strpos ( $ligne, "\t" ) === 0) {
						echo $ligne . "\r\n";
					} else {
						echo abstract_log::colorize ( $ligne . "\r\n", "Green" );
					}
				}
			}
		}
		
		if (isset ( $help ["exemples"] ) && is_array ( $help ["exemples"] )) {
			echo "\r\n";
			echo abstract_log::colorize ( "Exemples :\r\n", "BIWhite" );
			foreach ( $help ["exemples"] as $ligne ) {
				echo "\t" . $ligne . "\r\n";
			}
		}
		
		return true;
	}

	/**
	 * Retrouve le chemin systeme complet d'un commande.<br>
	 *
	 * @param string $commande 	Commande a retrouver.
	 * @return string Chemin complet de la commande.
	 */
	public function recupere_chemin_commande($commande) {
		$CMD = "PATH=/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/sbin:/usr/local/bin:/usr/X11R6/bin:/home/echo/bin; which " . $commande;
		$CODE_RETOUR = $this->applique_commande_systeme ( $CMD );
		$ligne = $CODE_RETOUR [(count ( $CODE_RETOUR ) - 1)];
		$cmd = explode ( " ", $ligne );
		
		return $cmd [0];
	}

	/**
	 * Supprime un fichier sqlite.<br>
	 * @codeCoverageIgnore
	 * @param string $fichier_sqlite Chemin complet du fichier a supprimer.
	 * @return int 0 si OK, 1 sinon.
	 */
	public function supprime_sqlite($fichier_sqlite) {
		$var_return = 1;
		if (fichier::tester_fichier_existe ( $fichier_sqlite ) == TRUE) {
			// on supprime les donnees sqlite
			$this->onDebug ( "fichier SQLITE a supprimer " . $fichier_sqlite, 1 );
			$var_return = fichier::supprime_fichier ( $fichier_sqlite );
			if ($var_return == 0) {
				$this->onInfo ( $fichier_sqlite . " supprime." );
			} else {
				return $this->onError ( " erreur lors de la suppression du fichier sqlite :" . $fichier_sqlite, $output );
			}
		} else {
			$this->onWarning ( "le fichier sqlite a supprimer " . $fichier_sqlite . " n'existe pas pour ce compte." );
		}
		
		return $var_return;
	}

	/**
	 * Deplace un fichier sqlite.<br>
	 * @codeCoverageIgnore
	 * @param string $source Fichier source du fichier a deplacer.
	 * @param string $dest Fichier destination du fichier a deplacer.
	 * @return int 0 si OK, 1 sinon.
	 */
	public function deplace_sqlite($source, $dest) {
		$var_return = 1;
		$this->onDebug ( "fichier SQLITE a deplacer " . $source . "\n", 1 );
		$this->onDebug ( "vers " . $dest . "\n", 1 );
		if (fichier::tester_fichier_existe ( $source ) === TRUE) {
			// on recupere la version de depart
			$version_depart = $this->check_version_sqlite ( $source );
			// on deplace les donnees sqlite
			$var_return = fichier::deplace ( $source, $dest );
			if ($var_return == 0) {
				// on recupere la version d'arrivee
				$version_arrivee = $this->check_version_sqlite ( $dest );
				if ($version_arrivee !== FALSE && $version_arrivee == $version_depart) {
					$this->onInfo ( $source . " deplace dans " . $dest );
				} else {
					return $this->onError ( " erreur lors du check SQLITE du deplacement du fichier sqlite :" . $source, "" );
					$var_return = 1;
				}
			} else {
				return $this->onError ( " erreur lors du deplacement du fichier sqlite :" . $source, $output );
			}
		} else {
			$this->onWarning ( "le fichier sqlite a deplacer " . $source . " n'existe pas pour ce compte." );
		}
		
		return $var_return;
	}

	/**
	 * Verifie la version d'un fichier sqlite.<br>
	 * @codeCoverageIgnore
	 * @param string $sqlite Chemin complet du fichier a supprimer.
	 * @return int 0 si OK, 1 sinon.
	 */
	public function check_version_sqlite($sqlite) {
		$var_return = FALSE;
		abstract_log::onDebug_standard ( "Check de la version du fichier " . $sqlite . "\n", 1 );
		if (fichier::tester_fichier_existe ( $sqlite ) == TRUE) {
			$connexion = requete::creer_requete ( $this->getListeOptions (), "non" );
			$connexion->setDbServeur ( $sqlite )
				->setDbType ( "sqlite" )
				->setDbMaj ( "oui" )
				->prepare_connexion ();
			try {
				$resultat = $connexion->faire_requete ( "pragma user_version", "non" );
			} catch ( Exception $e ) {
				$this->onError ( $e->getMessage (), "", $e->getCode () );
			}
			if ($resultat !== false) {
				foreach ( $resultat as $row ) {
					// Si la commande a fonctionne on renvoi la versionI
					$var_return = $row ["user_version"];
				}
				abstract_log::onInfo_standard ( "Version du fichier SQLITE " . $sqlite . " : " . $var_return );
			} else {
				$var_return = FALSE;
				abstract_log::onError_standard ( "la commande sqlite sur le fichier " . $sqlite . " a fini en erreur", "" );
			}
			$connexion->close ();
		} else {
			abstract_log::onWarning_standard ( "le fichier SQLITE " . $sqlite . " n'existe pas." );
		}
		
		return $var_return;
	}

	/**
	 * Applique une commande systeme.<br>
	 *
	 * @param string $CMD Commande a appliquer.
	 * @param Bool $erreur TRUE affiche l'erreur, FALSE affiche un warning.
	 * @return array Tableau avec en case 0 le retour systeme (0 ou 1) et dans les autres case le output standard et error.
	 */
	static public function applique_commande_systeme($CMD, $erreur = true) {
		abstract_log::onDebug_standard ( "Commande : " . $CMD, 1 );
		$output = array ();
		if ($CMD != "") {
			exec ( $CMD, $output, $var_return );
			array_unshift ( $output, $var_return );
			if ($var_return != 0 && $erreur === true) {
				abstract_log::onError_standard ( "La commande " . $CMD . " a fini en erreur.", $output );
			}
			if ($var_return != 0 && $erreur === false) {
				abstract_log::onWarning_standard ( "La commande " . $CMD . " a fini en erreur." );
			}
		} else {
			$output [0] = 1;
		}
		
		abstract_log::onDebug_standard ( $output, 2 );
		return $output;
	}

	/**
	 * Calcul en pseudo-UUID.
	 * @codeCoverageIgnore
	 * @param string $valeur Champ texte optionnel pour l'UUID.
	 * @return string UUID.
	 */
	static public function uuid_perso($valeur = "optionnel") {
		$md5 = md5 ( uniqid ( $valeur, true ) );
		return substr ( $md5, 0, 8 ) . '-' . substr ( $md5, 8, 4 ) . '-' . substr ( $md5, 12, 4 ) . '-' . substr ( $md5, 16, 4 ) . '-' . substr ( $md5, 20, 12 );
	}

	/**
	 * Creer un argv utilisable par la class options en cas de GET/POST pour url.
	 *
	 * @param string $nom_prog Nom du programme PHP
	 * @param array $liste_variables_systeme Liste des valeurs lies au systeme type --conf ou autre valeur fixe "nom"=>"valeur" ou valeur peut etre un tableau de valeur
	 * @param array $liste_variables_request Liste des valeurs potentiellement passe par l'url/post "nom"=>"valeur_par_defaut"
	 * @return array liste des options dans un tableau
	 */
	public function gestion_liste_option_via_url($nom_prog, $liste_variables_systeme, $liste_variables_request) {
		// Dans le cadre des URLs on a $_REQUEST
		$argv = array ();
		$argv [0] = $nom_prog;
		
		// Gestion des valeurs lies au systeme type --conf ou autre valeur fixe
		foreach ( $liste_variables_systeme as $nom => $valeur ) {
			$argv [] .= "--" . $nom;
			if (is_array ( $valeur )) {
				$argv = array_merge ( $argv, $valeur );
			} elseif ($valeur !== "") {
				array_push ( $argv, $valeur );
			}
		}
		
		// Gestion des valeurs passe par l'url/post
		$liste_variables_request ["help"] = "";
		$liste_variables_request ["verbose"] = "";
		foreach ( $liste_variables_request as $nom => $valeur_par_defaut ) {
			if (isset ( $_REQUEST [$nom] )) {
				$argv [] .= "--" . $nom;
				$argv [] .= $_REQUEST [$nom];
			} elseif ($valeur_par_defaut !== "") {
				$argv [] .= "--" . $nom;
				$argv [] .= $valeur_par_defaut;
			}
		}
		
		return $argv;
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
		$help [__CLASS__] ["text"] [] .= "Gestion des nom de fichier";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_nom \"/test\"    nom du/des fichiers optionnel si prevu dans le code";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_extension \"/.csv\"   extension du/des fichiers";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_repertoire \"/tmp\" optionnel repertoire ou se situe les fichiers";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_affiche_date \"oui/non\" affiche la date dans le nom du/des fichiers";
		
		$help [__CLASS__] ["text"] [] .= "Gestion de la taille disque";
		$help [__CLASS__] ["text"] [] .= "\t--taille_disque_mini 200Mo";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "format supporte : ko Mo Go To et par defaut aucun identifiant=octet";
		$help [__CLASS__] ["text"] [] .= "valeur par defaut : 200Mo";
		
		$help [__CLASS__] ["text"] [] .= "Dossier de stockage des donnees";
		$help [__CLASS__] ["text"] [] .= "\t--dossier_rangement /tmp";
		
		$help [__CLASS__] ["text"] [] .= "Gestion de la liste des fichiers de sortie";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_sortie ./sortie1.exemple ./sortie2.exemple ....";
		
		return $help;
	}
}
?>