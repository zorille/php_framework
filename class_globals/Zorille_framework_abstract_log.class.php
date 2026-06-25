<?php

/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;

use Exception as Exception;
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
     * @throws Exception
     */
	public function &_initialise(
		array $liste_class): static {
		$this->onDebug ( __METHOD__, 1 );
		if (! isset ( $liste_class ["options"] )) {
			$this->onError ( "Il faut un objet de type options" );
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
	 * @param bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 */
	function __construct(
        bool|string $sort_en_erreur = false,
        string      $nom_module = "abstract_log") {
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
     * @param mixed $message Message de debug a afficher
     * @param $niveau
     * @param null $entete
     * @return self
     */
	public function onDebug(
		mixed $message,
              $niveau,
              $entete = null): static {
		if (! is_array ( $message ) && ! is_object ( $message )) {
			$message = "(" . (empty($entete) ? $this->getEntete () : $entete) . ") " . $message;
		}
		abstract_log::onDebug_standard ( $message, $niveau );
		return $this;
	}

    /**
     * @param mixed $message Message d'info a afficher
     * @param null $entete
     * @return self
     */
	public function onInfo(
        mixed $message,
               $entete = null): static {
		if (! is_array ( $message ) && ! is_object ( $message )) {
			$message = "(" . (empty($entete) ? $this->getEntete () : $entete) . ") " . $message;
		}
		abstract_log::onInfo_standard ( $message );
		return $this;
	}

	/**
	 * @param mixed $message Message de warning a afficher
	 * @return false
	 */
	public function onWarning(
        mixed $message,
               $entete = null): bool {
		if (! is_array ( $message ) && ! is_object ( $message )) {
			$message = "(" . (empty($entete) ? $this->getEntete () : $entete) . ") " . $message;
		}
		abstract_log::onWarning_standard ( $message );
		return false;
	}

	/**
	 * Cette fonction sort si $sort_en_erreur est a TRUE.
	 *
	 * @param mixed $message Message d'erreur a afficher
	 * @return false
	 * @throws Exception
	 */
	public function onError(
        mixed $message,
              $donnee_sup = "",
              $code_retour = 1,
              $entete = null): bool {
		if (! is_array ( $message ) && ! is_object ( $message )) {
			$message = "(" . (empty($entete) ? $this->getEntete () : $entete) . ") " . $message;
		}
		if ($code_retour == 0) {
			$code_retour = 1;
		}
		if (! $this->getThrowException ()) {
			abstract_log::onError_standard ( $message, $donnee_sup, $code_retour );
		}
		if ($this->getSortEnErreur ()) {
			$this->onWarning ( "SortEnErreur TRUE/OUI : {$message}" );
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
	 * @param mixed $ligne Ligne a affiche.
	 * @param string|array $tableau_output Tableau de ligne d'erreur a affiche.
	 * @param int $code_retour
	 * @param Bool $verbose Affichage en cas d'objet logs inexistant.
	 * @return false Renvoi TRUE.
	 */
	static public function onError_standard(
        mixed $ligne,
        string|array $tableau_output = "",
        int    $code_retour = 1,
        bool   $verbose = true): bool {
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
     * @param mixed $ligne Ligne a affiche.
     * @param Bool $verbose Affichage en cas d'objet logs inexistant.
     * @return true Renvoi TRUE.
     */
	static public function onWarning_standard(
        mixed $ligne,
        bool   $verbose = true): bool {
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
     * @param mixed $ligne Ligne a affiche.
     * @param Bool $verbose Affichage en cas d'objet logs inexistant.
     * @return true Renvoi TRUE.
     */
	static public function onInfo_standard(
        mixed $ligne,
        bool   $verbose = false): bool {
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
	 * @param mixed $ligne Ligne a affiche.
	 * @param int $niveau Niveau de verbose.
	 * @param Bool $verbose Affichage en cas d'objet logs inexistant.
	 * @return true Renvoi TRUE.
	 */
	static public function onDebug_standard(
		mixed $ligne,
		int $niveau = 2,
		bool $verbose = false): bool {
		if (abstract_log::verifie_niveau_verbose ( $niveau )) {
			$entete = abstract_log::prepare_entete ( "Debug" );
            if (is_object($ligne)) {
                abstract_log::affiche_object ( $ligne, $entete, $niveau, $verbose );
            } elseif (is_array($ligne)) {
                abstract_log::affiche_tableau ( $ligne, $entete, $niveau, $verbose );
            } elseif (is_string($ligne)) {
                abstract_log::affiche_ligne ( $entete . $ligne, $niveau, $verbose );
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
        int $niveau): bool {
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
        string $type): string {
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
        string $ligne,
        int    $niveau = 0,
        bool   $verbose = false): bool {
		// Dans ce cas la ligne ne peut etre qu'un simple ligne
		if (is_bool ( $ligne )) {
            $ligne = !$ligne ? "FALSE" : "TRUE";
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
	 * @param array|string $tableau tableau a afficher.
	 * @param string $entete entete d'affichage (Info/Warning/Error/Debug)
	 * @param int $niveau
	 * @param bool $verbose
	 * @return bool true si affiche,false sinon.
	 */
	static public function affiche_tableau(
        array|string  $tableau,
        string $entete,
        int    $niveau = 0,
        bool   $verbose = false): bool {
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
	 * @param object|string $class objet a afficher.
	 * @param string $entete entete d'affichage (Info/Warning/Error/Debug)
	 * @param int $niveau
	 * @param bool $verbose
	 * @return bool true si affiche,false sinon.
	 */
	static public function affiche_object(
        object|string|array $class,
        string $entete,
        int    $niveau = 0,
        bool   $verbose = false): bool {
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
	 * @return string
	 *@throws Exception
	 */
	static function colorize(
        string $text,
        string $couleur): string {
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
        string $couleur): string {
		return match ($couleur) {
			'Black' => '[0;30m',
			'Red' => '[0;31m',
			'Green' => '[0;32m',
			'Yellow' => '[0;33m',
			'Blue' => '[0;34m',
			'Purple' => '[0;35m',
			'Cyan' => '[0;36m',
			'White' => '[0;37m',
			'BBlack' => '[1;30m',
			'BRed' => '[1;31m',
			'BGreen' => '[1;32m',
			'BYellow' => '[1;33m',
			'BBlue' => '[1;34m',
			'BPurple' => '[1;35m',
			'BCyan' => '[1;36m',
			'BWhite' => '[1;37m',
			'UBlack' => '[4;30m',
			'URed' => '[4;31m',
			'UGreen' => '[4;32m',
			'UYellow' => '[4;33m',
			'UBlue' => '[4;34m',
			'UPurple' => '[4;35m',
			'UCyan' => '[4;36m',
			'UWhite' => '[4;37m',
			'On_Black' => '[40m',
			'On_Red' => '[41m',
			'On_Green' => '[42m',
			'On_Yellow' => '[43m',
			'On_Blue' => '[44m',
			'On_Purple' => '[45m',
			'On_Cyan' => '[46m',
			'On_White' => '[47m',
			'IBlack' => '[0;90m',
			'IRed' => '[0;91m',
			'IGreen' => '[0;92m',
			'IYellow' => '[0;93m',
			'IBlue' => '[0;94m',
			'IPurple' => '[0;95m',
			'ICyan' => '[0;96m',
			'IWhite' => '[0;97m',
			'BIBlack' => '[1;90m',
			'BIRed' => '[1;91m',
			'BIGreen' => '[1;92m',
			'BIYellow' => '[1;93m',
			'BIBlue' => '[1;94m',
			'BIPurple' => '[1;95m',
			'BICyan' => '[1;96m',
			'BIWhite' => '[1;97m',
			'On_IBlack' => '[0;100m',
			'On_IRed' => '[0;101m',
			'On_IGreen' => '[0;102m',
			'On_IYellow' => '[0;103m',
			'On_IBlue' => '[0;104m',
			'On_IPurple' => '[0;105m',
			'On_ICyan' => '[0;106m',
			'On_IWhite' => '[0;107m',
			default => '[0m',
		};
		# Text Reset
	}

	/**
	 * ************ Accesseurs ***************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getEntete(): string {
		return $this->log_module;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setEntete(
		$entete): static {
		$this->log_module = $entete;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSortEnErreur(): bool {
		return $this->sort_en_erreur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setSortEnErreur(
		$sort_en_erreur): static {
		$this->sort_en_erreur = $sort_en_erreur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getThrowException(): bool {
		return $this->throw_exception;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setThrowException(
        $throw_exception): static {
		$this->throw_exception = $throw_exception;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMessage(): string {
		return $this->message_erreur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setMessage(
        $message_erreur): static {
		$this->message_erreur = $message_erreur;
		return $this;
	}

    /**
     * @codeCoverageIgnore
     * @return options|string
     */
	public function &getListeOptions(): options|string {
		return $this->liste_option_local;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeOptions(
        &$ListeOptions): static {
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
     * @return array|string Renvoi le help
     */
	static function help(): array|string {
		$help = logs::help ();
		$help [__CLASS__] ["text"] = [
            "affiche du texte au format standard",
            "\t--verbose 0/1/2\t\t\t\tPar defaut : 0"
        ];
		return $help;
	}
}
