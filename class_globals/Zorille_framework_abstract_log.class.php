<?php

/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;

use Exception as Exception;
use Zorille\framework\abstract_log as abstract_log;
use Zorille\framework\logs as logs;

/**
 * class abstract_log<br> Gere les fonction standard pour afficher les logs.
 *
 * @package Lib
 * @subpackage class_global
 */
abstract class abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var bool
	 */
	private $sort_en_erreur = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var bool
	 */
	private $throw_exception = true;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $log_module = "LOGS";
	/**
	 * @staticvar Utiliser pour l'affichage des logs d'erreur
	 * @var logs
	 */
	static $logs;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $message_erreur = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var options
	 */
	private $liste_option_local = "";

	/**
	 * ********************* Initialisation de l'objet ********************
	 */
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise(
			$liste_class) {
		$this->onDebug ( __METHOD__, 1 );
		if (! isset ( $liste_class ["options"] )) {
			return $this->onError ( "Il faut un objet de type options" );
		}
		$this->setListeOptions ( $liste_class ["options"] );
		return $this;
	}

	/**
	 * ********************* Initialisation de l'objet ********************
	 */
	/**
	 * set la valeur du sort_en_erreur et le nom du module appelant.
	 *
	 * @param string $nom_module Nom du module lors de l'affichage.
	 * @param string|bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 */
	function __construct(
			$sort_en_erreur = false,
			$nom_module = "abstract_log") {
		if (is_bool ( $sort_en_erreur )) {
			$this->setSortEnErreur ( $sort_en_erreur );
		} else {
			if ($sort_en_erreur == "oui")
				$this->setSortEnErreur ( true );
			else
				$this->setSortEnErreur ( false );
		}
		$this->setEntete ( $nom_module );
	}

	/**
	 * @param string $message Message de debug a afficher
	 * @return object $this
	 */
	public function onDebug(
			$message,
			$niveau) {
		if (! is_array ( $message ) && ! is_object ( $message )) {
			$message = "(" . $this->getEntete () . ") " . $message;
		}
		abstract_log::onDebug_standard ( $message, $niveau );
		return $this;
	}

	/**
	 * @param string $message Message d'info a afficher
	 * @return object $this
	 */
	public function onInfo(
			$message) {
		if (! is_array ( $message ) && ! is_object ( $message )) {
			$message = "(" . $this->getEntete () . ") " . $message;
		}
		abstract_log::onInfo_standard ( $message );
		return $this;
	}

	/**
	 * @param string $message Message de warning a afficher
	 * @return false
	 */
	public function onWarning(
			$message) {
		if (! is_array ( $message ) && ! is_object ( $message )) {
			$message = "(" . $this->getEntete () . ") " . $message;
		}
		abstract_log::onWarning_standard ( $message );
		return false;
	}

	/**
	 * Cette fonction sort si $sort_en_erreur est a TRUE.
	 *
	 * @param string $message Message d'erreur a afficher
	 * @return false
	 * @throws Exception
	 */
	public function onError(
			$message,
			$donnee_sup = "",
			$code_retour = 1) {
		if (! is_array ( $message ) && ! is_object ( $message )) {
			$message = "(" . $this->getEntete () . ") " . $message;
		}
		if ($code_retour == 0) {
			$code_retour = 1;
		}
		if (! $this->getThrowException ()) {
			abstract_log::onError_standard ( $message, $donnee_sup, $code_retour );
		}
		if ($this->getSortEnErreur ()) {
			$this->onWarning ( "SortEnErreur TRUE/OUI" );
			// @codeCoverageIgnoreStart
			if (abstract_log::$logs instanceof logs) {
				abstract_log::$logs->renvoiExit ();
			}
			exit ( $code_retour );
			// @codeCoverageIgnoreEnd
		}
		// Le tableau se retrouve dans le tableau_output
		if ($donnee_sup !== "") {
			if (is_object ( $donnee_sup )) {
				$message .= " \n" . print_r ( $donnee_sup, true );
			} elseif (is_array ( $donnee_sup )) {
				$message .= " \n" . print_r ( $donnee_sup, true );
			} elseif (is_string ( $donnee_sup )) {
				$message .= " \n" . $donnee_sup;
			}
		}
		$this->setMessage ( $message );
		if ($this->getThrowException ()) {
			throw new Exception ( $message, $code_retour );
		}
		return false;
	}

	/**
	 * Affiche une erreur suivant le verbose et l'ajoute dans un fichier s'il existe.
	 *
	 * @param string $ligne Ligne a affiche.
	 * @param array $tableau_output Tableau de ligne d'erreur a affiche.
	 * @param Bool $verbose Affichage en cas d'objet logs inexistant.
	 * @param string $tab Tabulation entre les affichages.
	 * @return true Renvoi TRUE.
	 */
	static public function onError_standard(
			$ligne,
			$tableau_output = "",
			$code_retour = 1,
			$verbose = true) {
		if (abstract_log::$logs instanceof logs) {
			abstract_log::$logs->setExit ( $code_retour );
			// Permet d'afficher l'erreur sur stderr dans la methode verbose
			abstract_log::$logs->setIsError ( true );
		}
		$entete = abstract_log::prepare_entete ( "Error" );
		if (abstract_log::affiche_object ( $ligne, $entete, 0, $verbose )) {
		} elseif (abstract_log::affiche_tableau ( $ligne, $entete, 0, $verbose )) {
		} elseif (abstract_log::affiche_ligne ( $entete . $ligne, 0, $verbose )) {
		}
		// Le tableau se retrouve dans le tableau_output
		if ($tableau_output !== "") {
			if (abstract_log::affiche_object ( $tableau_output, $entete, 0, $verbose )) {
			} elseif (abstract_log::affiche_tableau ( $tableau_output, $entete, 0, $verbose )) {
			} elseif (abstract_log::affiche_ligne ( $entete . $tableau_output, 0, $verbose )) {
			}
		}
		if (abstract_log::$logs instanceof logs) {
			// Reset l'affichage de la methode verbose
			abstract_log::$logs->setIsError ( false );
		}
		return false;
	}

	/**
	 * Affiche un warning suivant le verbose et l'ajoute dans un fichier s'il existe.<br>
	 *
	 * @param string $ligne Ligne a affiche.
	 * @param Bool $verbose Affichage en cas d'objet logs inexistant.
	 * @param string $tab Tabulation entre les affichages.
	 * @return true Renvoi TRUE.
	 */
	static public function onWarning_standard(
			$ligne,
			$verbose = true) {
		$entete = abstract_log::prepare_entete ( "Warning" );
		if (abstract_log::affiche_object ( $ligne, $entete, 0, $verbose )) {
		} elseif (abstract_log::affiche_tableau ( $ligne, $entete, 0, $verbose )) {
		} elseif (abstract_log::affiche_ligne ( $entete . $ligne, 0, $verbose )) {
		}
		return true;
	}

	/**
	 * Affiche une info suivant le verbose et l'ajoute dans un fichier s'il existe.<br>
	 *
	 * @param string $ligne Ligne a affiche.
	 * @param Bool $verbose Affichage en cas d'objet logs inexistant.
	 * @param string $tab Tabulation entre les affichages.
	 * @return true Renvoi TRUE.
	 */
	static public function onInfo_standard(
			$ligne,
			$verbose = false) {
		$entete = abstract_log::prepare_entete ( "Info" );
		if (abstract_log::affiche_object ( $ligne, $entete, 0, $verbose )) {
		} elseif (abstract_log::affiche_tableau ( $ligne, $entete, 0, $verbose )) {
		} elseif (abstract_log::affiche_ligne ( $entete . $ligne, 0, $verbose )) {
		}
		return true;
	}

	/**
	 * Affiche un debug suivant le niveau de verbose et l'ajoute dans un fichier s'il existe.<br>
	 *
	 * @param string $ligne Ligne a affiche.
	 * @param int $niveau Niveau de verbose.
	 * @param Bool $verbose Affichage en cas d'objet logs inexistant.
	 * @return true Renvoi TRUE.
	 */
	static public function onDebug_standard(
			$ligne,
			$niveau = 2,
			$verbose = false) {
		if (abstract_log::verifie_niveau_verbose ( $niveau )) {
			$entete = abstract_log::prepare_entete ( "Debug" );
			if (abstract_log::affiche_object ( $ligne, $entete, $niveau, $verbose )) {
			} elseif (abstract_log::affiche_tableau ( $ligne, $entete, $niveau, $verbose )) {
			} elseif (abstract_log::affiche_ligne ( $entete . $ligne, $niveau, $verbose )) {
			}
		}
		return true;
	}

	/**
	 * Valide le niveau de verbose.
	 *
	 * @param int $niveau niveau de verbose demende
	 */
	static function verifie_niveau_verbose(
			$niveau) {
		$retour = false;
		if (abstract_log::$logs instanceof logs) {
			if (abstract_log::$logs->valideVerbose ( $niveau )) {
				$retour = true;
			}
		}
		return $retour;
	}

	/**
	 * Cree une entete standard.
	 *
	 * @static
	 *
	 * @param string $type type d'entete (Info/Warning/Error/Debug).
	 * @return string entete complete.
	 */
	static function prepare_entete(
			$type) {
		return "[" . $type . "] " . date ( "H:i:s", time () ) . " (" . getmypid () . ") : ";
	}

	/**
	 * Affiche une ligne soit grace a la fonction verbose soit grace au verbose=true.
	 *
	 * @param string $ligne
	 * @param int $niveau
	 * @param bool $verbose
	 * @return bool true si affiche,false sinon.
	 */
	static public function affiche_ligne(
			$ligne,
			$niveau = 0,
			$verbose = false) {
		// Dans ce cas la ligne ne peut etre qu'un simple ligne
		if (is_bool ( $ligne )) {
			if ($ligne === false) {
				$ligne = "FALSE";
			} else {
				$ligne = "TRUE";
			}
		}
		if (abstract_log::$logs instanceof logs) {
			abstract_log::$logs->verbose ( $ligne, $niveau );
			return true;
		} elseif ($verbose) {
			echo $ligne . "\n";
			return true;
		}
		return false;
	}

	/**
	 * Affiche un tableau.
	 *
	 * @param array $tableau tableau a afficher.
	 * @param string $entete entete d'affichage (Info/Warning/Error/Debug)
	 * @param int $niveau
	 * @param bool $verbose
	 * @return bool true si affiche,false sinon.
	 */
	static public function affiche_tableau(
			$tableau,
			$entete,
			$niveau = 0,
			$verbose = false) {
		// Dans ce cas on a un tableau
		$retour = false;
		if (is_array ( $tableau )) {
			if (isset ( $tableau ['password'] )) {
				$tableau ['password'] = '*********************';
			}
			abstract_log::affiche_ligne ( $entete . print_r ( $tableau, true ), $niveau, $verbose );
			$retour = true;
		}
		return $retour;
	}

	/**
	 * Affiche une classe.
	 *
	 * @param object $class objet a afficher.
	 * @param string $entete entete d'affichage (Info/Warning/Error/Debug)
	 * @param int $niveau
	 * @param bool $verbose
	 * @return bool true si affiche,false sinon.
	 */
	static public function affiche_object(
			$class,
			$entete,
			$niveau = 0,
			$verbose = false) {
		// Dans ce cas on a un object
		$retour = false;
		if (is_object ( $class )) {
			abstract_log::affiche_ligne ( $entete . print_r ( $class, true ), $niveau, $verbose );
			$retour = true;
		}
		return $retour;
	}

	/**
	 * Colorise la ligne affichee
	 * @codeCoverageIgnore
	 * @param string $text
	 * @param string $couleur
	 * @throws Exception
	 * @return string
	 */
	static function colorize(
			$text,
			$couleur) {
		$out = abstract_log::retrouve_couleur ( $couleur );
		return chr ( 27 ) . $out . $text . chr ( 27 ) . "[0m";
	}

	/**
	 * @codeCoverageIgnore
	 * # Reset
	 * Color_Off='\e[0m'       # Text Reset
	 *
	 * # Regular Colors
	 * Black='\e[0;30m'        # Black
	 * Red='\e[0;31m'          # Red
	 * Green='\e[0;32m'        # Green
	 * Yellow='\e[0;33m'       # Yellow
	 * Blue='\e[0;34m'         # Blue
	 * Purple='\e[0;35m'       # Purple
	 * Cyan='\e[0;36m'         # Cyan
	 * White='\e[0;37m'        # White
	 *
	 * # Bold
	 * BBlack='\e[1;30m'       # Black
	 * BRed='\e[1;31m'         # Red
	 * BGreen='\e[1;32m'       # Green
	 * BYellow='\e[1;33m'      # Yellow
	 * BBlue='\e[1;34m'        # Blue
	 * BPurple='\e[1;35m'      # Purple
	 * BCyan='\e[1;36m'        # Cyan
	 * BWhite='\e[1;37m'       # White
	 *
	 * # Underline
	 * UBlack='\e[4;30m'       # Black
	 * URed='\e[4;31m'         # Red
	 * UGreen='\e[4;32m'       # Green
	 * UYellow='\e[4;33m'      # Yellow
	 * UBlue='\e[4;34m'        # Blue
	 * UPurple='\e[4;35m'      # Purple
	 * UCyan='\e[4;36m'        # Cyan
	 * UWhite='\e[4;37m'       # White
	 *
	 * # Background
	 * On_Black='\e[40m'       # Black
	 * On_Red='\e[41m'         # Red
	 * On_Green='\e[42m'       # Green
	 * On_Yellow='\e[43m'      # Yellow
	 * On_Blue='\e[44m'        # Blue
	 * On_Purple='\e[45m'      # Purple
	 * On_Cyan='\e[46m'        # Cyan
	 * On_White='\e[47m'       # White
	 *
	 * # High Intensity
	 * IBlack='\e[0;90m'       # Black
	 * IRed='\e[0;91m'         # Red
	 * IGreen='\e[0;92m'       # Green
	 * IYellow='\e[0;93m'      # Yellow
	 * IBlue='\e[0;94m'        # Blue
	 * IPurple='\e[0;95m'      # Purple
	 * ICyan='\e[0;96m'        # Cyan
	 * IWhite='\e[0;97m'       # White
	 *
	 * # Bold High Intensity
	 * BIBlack='\e[1;90m'      # Black
	 * BIRed='\e[1;91m'        # Red
	 * BIGreen='\e[1;92m'      # Green
	 * BIYellow='\e[1;93m'     # Yellow
	 * BIBlue='\e[1;94m'       # Blue
	 * BIPurple='\e[1;95m'     # Purple
	 * BICyan='\e[1;96m'       # Cyan
	 * BIWhite='\e[1;97m'      # White
	 *
	 * # High Intensity backgrounds
	 * On_IBlack='\e[0;100m'   # Black
	 * On_IRed='\e[0;101m'     # Red
	 * On_IGreen='\e[0;102m'   # Green
	 * On_IYellow='\e[0;103m'  # Yellow
	 * On_IBlue='\e[0;104m'    # Blue
	 * On_IPurple='\e[0;105m'  # Purple
	 * On_ICyan='\e[0;106m'    # Cyan
	 * On_IWhite='\e[0;107m'   # White
	 * @param string $couleur
	 * @return string
	 */
	static function retrouve_couleur(
			$couleur) {
		switch ($couleur) {
			# Reset
			case 'Color_Off' :
				return '[0m'; # Text Reset
			case 'Black' :
				return '[0;30m'; # Black
			case 'Red' :
				return '[0;31m'; # Red
			case 'Green' :
				return '[0;32m'; # Green
			case 'Yellow' :
				return '[0;33m'; # Yellow
			case 'Blue' :
				return '[0;34m'; # Blue
			case 'Purple' :
				return '[0;35m'; # Purple
			case 'Cyan' :
				return '[0;36m'; # Cyan
			case 'White' :
				return '[0;37m'; # White
			case 'BBlack' :
				return '[1;30m'; # Black
			case 'BRed' :
				return '[1;31m'; # Red
			case 'BGreen' :
				return '[1;32m'; # Green
			case 'BYellow' :
				return '[1;33m'; # Yellow
			case 'BBlue' :
				return '[1;34m'; # Blue
			case 'BPurple' :
				return '[1;35m'; # Purple
			case 'BCyan' :
				return '[1;36m'; # Cyan
			case 'BWhite' :
				return '[1;37m'; # White
			case 'UBlack' :
				return '[4;30m'; # Black
			case 'URed' :
				return '[4;31m'; # Red
			case 'UGreen' :
				return '[4;32m'; # Green
			case 'UYellow' :
				return '[4;33m'; # Yellow
			case 'UBlue' :
				return '[4;34m'; # Blue
			case 'UPurple' :
				return '[4;35m'; # Purple
			case 'UCyan' :
				return '[4;36m'; # Cyan
			case 'UWhite' :
				return '[4;37m'; # White
			case 'On_Black' :
				return '[40m'; # Black
			case 'On_Red' :
				return '[41m'; # Red
			case 'On_Green' :
				return '[42m'; # Green
			case 'On_Yellow' :
				return '[43m'; # Yellow
			case 'On_Blue' :
				return '[44m'; # Blue
			case 'On_Purple' :
				return '[45m'; # Purple
			case 'On_Cyan' :
				return '[46m'; # Cyan
			case 'On_White' :
				return '[47m'; # White
			case 'IBlack' :
				return '[0;90m'; # Black
			case 'IRed' :
				return '[0;91m'; # Red
			case 'IGreen' :
				return '[0;92m'; # Green
			case 'IYellow' :
				return '[0;93m'; # Yellow
			case 'IBlue' :
				return '[0;94m'; # Blue
			case 'IPurple' :
				return '[0;95m'; # Purple
			case 'ICyan' :
				return '[0;96m'; # Cyan
			case 'IWhite' :
				return '[0;97m'; # White
			case 'BIBlack' :
				return '[1;90m'; # Black
			case 'BIRed' :
				return '[1;91m'; # Red
			case 'BIGreen' :
				return '[1;92m'; # Green
			case 'BIYellow' :
				return '[1;93m'; # Yellow
			case 'BIBlue' :
				return '[1;94m'; # Blue
			case 'BIPurple' :
				return '[1;95m'; # Purple
			case 'BICyan' :
				return '[1;96m'; # Cyan
			case 'BIWhite' :
				return '[1;97m'; # White
			case 'On_IBlack' :
				return '[0;100m'; # Black
			case 'On_IRed' :
				return '[0;101m'; # Red
			case 'On_IGreen' :
				return '[0;102m'; # Green
			case 'On_IYellow' :
				return '[0;103m'; # Yellow
			case 'On_IBlue' :
				return '[0;104m'; # Blue
			case 'On_IPurple' :
				return '[0;105m'; # Purple
			case 'On_ICyan' :
				return '[0;106m'; # Cyan
			case 'On_IWhite' :
				return '[0;107m'; # White
		}
		return '[0m'; # Text Reset
	}

	/**
	 * ************ Accesseurs ***************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getEntete() {
		return $this->log_module;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setEntete(
			$entete) {
		$this->log_module = $entete;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSortEnErreur() {
		return $this->sort_en_erreur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setSortEnErreur(
			$sort_en_erreur) {
		$this->sort_en_erreur = $sort_en_erreur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getThrowException() {
		return $this->throw_exception;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setThrowException(
			$throw_exception) {
		$this->throw_exception = $throw_exception;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMessage() {
		return $this->message_erreur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setMessage(
			$message_erreur) {
		$this->message_erreur = $message_erreur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function &getListeOptions() {
		return $this->liste_option_local;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeOptions(
			&$ListeOptions) {
		$this->liste_option_local = $ListeOptions;
		return $this;
	}

	/**
	 * *********** Accesseurs ***************
	 */
	/**
	 * @static
	 * @codeCoverageIgnore
	 *
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = logs::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "affiche du texte au format standard";
		$help [__CLASS__] ["text"] [] .= "\t--verbose 0/1/2\t\t\t\tPar defaut : 0";
		return $help;
	}
}
?>