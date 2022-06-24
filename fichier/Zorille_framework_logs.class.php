<?php
/**
 * @author dvargas
 * @package Lib    
 */
namespace Zorille\framework;
/**
 * class logs<br>
 * Gere les logs.
 * Necessite l'objet fichier ou fichier_gz
 *
 * @package Lib
 * @subpackage Fichier
 */
class logs {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $fichier;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $verbose;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $nom_fichier;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $rep_fichier;
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
	private $using_log_file = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var bool
	 */
	private $compresse = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var bool
	 */
	private $append = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $exit = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $is_web = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $os = "LINUX";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $is_error = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $is_error_stdout = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $erreur_liste = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $resultat = array ( 
			"message" => "" );

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type logs.
	 * @codeCoverageIgnore
	 * Parse les options passees en ligne de commande ou par xml et creer un objet logs.<br>
	 * Include : $INCLUDE_FONCTIONS<br>
	 * Arguments reconnus :<br>
	 * --create_log_file[=oui] <br>
	 * --dossier_log=/tmp <br>
	 * --fichier_log=log.txt <br>
	 * --fichier_log_unique=\"oui/non\" <br>
	 * --fichier_log_sort_en_erreur=\"oui/non\" <br>
	 * --fichier_log_compresse=\"oui/non\" <br>
	 * --fichier_log_append <br>
	 * --verbose[=int]--mail_using=oui/non <br>
	 *
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return logs
	 */
	static public function creer_logs(&$liste_option) {
		if ($liste_option ->verifie_option_existe ( "create_log_file", true ) !== false) {
			$create_log_file = $liste_option ->getOption ( "create_log_file" );
		}
		
		if ($liste_option ->verifie_option_existe ( "dossier_log", true ) !== false)
			$dossier_log = $liste_option ->getOption ( "dossier_log" );
		
		if ($liste_option ->verifie_option_existe ( "fichier_log", true ) !== false)
			$fichier_log = $liste_option ->getOption ( "fichier_log" );
		
		if ($liste_option ->verifie_option_existe ( "fichier_log_unique", true ) !== false)
			$fichier_log_unique = $liste_option ->getOption ( "fichier_log_unique" );
		
		if ($liste_option ->verifie_option_existe ( "fichier_log_sort_en_erreur", true ) !== false)
			$fichier_log_sort_en_erreur = $liste_option ->getOption ( "fichier_log_sort_en_erreur" );
		
		if ($liste_option ->verifie_option_existe ( "fichier_log_compresse" ) !== false)
			$fichier_log_compresse = "oui";
		
		if ($liste_option ->verifie_option_existe ( "fichier_log_append" ) !== false)
			$fichier_log_append = "oui";
		
		$info_log = $liste_option ->getOption ( "log", true );
		if ($info_log !== false && is_array ( $info_log ) && isset ( $info_log ["create_log_file"] )) {
			if (! isset ( $create_log_file ))
				$create_log_file = $info_log ["create_log_file"];
			
			if (! isset ( $dossier_log ) && isset ( $info_log ["dossier_log"] ) && $info_log ["dossier_log"] !== "")
				$dossier_log = $info_log ["dossier_log"];
			
			if (! isset ( $fichier_log ) && isset ( $info_log ["fichier_log"] ) && $info_log ["fichier_log"] !== "")
				$fichier_log = $info_log ["fichier_log"];
			
			if (! isset ( $fichier_log_unique ) && isset ( $info_log ["fichier_log_unique"] ) && $info_log ["fichier_log_unique"] !== "")
				$fichier_log_unique = $info_log ["fichier_log_unique"];
			
			if (! isset ( $fichier_log_sort_en_erreur ) && isset ( $info_log ["sort_en_erreur"] ) && $info_log ["sort_en_erreur"] == "non")
				$fichier_log_sort_en_erreur = "non";
			
			if (! isset ( $fichier_log_compresse ) && isset ( $info_log ["compresse"] ) && $info_log ["compresse"] == "oui")
				$fichier_log_compresse = "oui";
			
			if (! isset ( $fichier_log_append ) && isset ( $info_log ["append"] ) && $info_log ["append"] == "oui")
				$fichier_log_append = "oui";
		}
		
		if (! isset ( $create_log_file ))
			$create_log_file = "non";
		if (! isset ( $dossier_log ))
			$dossier_log = ".";
		if (! isset ( $fichier_log ))
			$fichier_log = "log.gz";
		if (! isset ( $fichier_log_unique ))
			$fichier_log_unique = "oui";
		if (! isset ( $fichier_log_sort_en_erreur ))
			$fichier_log_sort_en_erreur = "non";
		if (! isset ( $fichier_log_compresse ))
			$fichier_log_compresse = "non";
		if (! isset ( $fichier_log_append ))
			$fichier_log_append = "non";
		
		if ($liste_option ->verifie_option_existe ( "verbose" ) !== false) {
			$verbose = $liste_option ->getOption ( "verbose" );
		} else {
			$verbose = - 1;
		}
		
		$log = new logs ( $verbose, $create_log_file, $dossier_log, $fichier_log, $fichier_log_unique, $fichier_log_sort_en_erreur, $fichier_log_compresse, $fichier_log_append );
		abstract_log::$logs = &$log;
		
		$log ->ouvre_fichier_log ( $liste_option );
		
		$log ->_initialise ( array ( 
				"options" => $liste_option ) );
		abstract_log::onDebug_standard ( "Liste Options", 2 );
		$liste_option ->debug_options ();
		abstract_log::onDebug_standard ( "Gestion des Logs", 2 );
		abstract_log::onDebug_standard ( $log, 2 );
		
		return $log;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return logs
	 */
	public function &_initialise($liste_class) {
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Charge les variables de l'objet logs.
	 *
	 * @param int $verbose Niveau de verbose.
	 * @param string $create_log_file Creer un fichier de log oui/non.
	 * @param string $dossier Chemin complet du fichier de log.
	 * @param string $fichier Nom du fichier de log.
	 * @param string $unique Le nom du fichier de log doit etre unique oui/non.
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 * @param string $compresse Compresse le fichier de log.
	 * @param string $append Ajoute les donnees au fichier de log.
	 */
	public function __construct($verbose = -1, $create_log_file = "non", $dossier = ".", $fichier = "log", $unique = "oui", $sort_en_erreur = "non", $compresse = "non", $append = "non") {
		$this ->setVerbose ( $verbose );
		if ($dossier == "")
			$dossier = ".";
		$this ->setRepFichier ( $dossier );
		if ($fichier == "")
			$fichier = "log";
		if ($unique == "oui")
			$fichier .= "_" . getmypid ();
		$this ->setNomFichier ( $fichier );
		if ($create_log_file == "oui")
			$this ->setUsingLogFile ( true );
		if ($sort_en_erreur == "oui")
			$this ->setSortEnErreur ( true );
		if ($compresse == "oui")
			$this ->setCompresse ( true );
		if ($append == "oui")
			$this ->setAppend ( true );
		$this ->setExit ( 0 );
		
		if (strtoupper ( substr ( PHP_OS, 0, 3 ) ) === 'WIN') {
			// @codeCoverageIgnoreStart
			$this ->setOs ( "WIN" );
		}
		// @codeCoverageIgnoreEnd
		

		return true;
	}

	/**
	 * Permet d'ouvrir un fichier de log.<br>
	 *
	 * @param options $liste_option
	 * @return Bool Renvoi TRUE si OK, FALSE si aucun fichier n'est ouvert.
	 */
	public function ouvre_fichier_log(&$liste_option) {
		if ($this ->getUsingLogFile ()) {
			if ($this ->getCompresse ()) {
				$this ->setFichier ( fichier_gz::creer_fichier_gz ( $liste_option, $this ->getRepFichier () . "/" . $this ->getNomFichier (), "oui" ) );
			} else {
				$this ->setFichier ( fichier::creer_fichier ( $liste_option, $this ->getRepFichier () . "/" . $this ->getNomFichier (), "oui" ) );
			}
			
			// @codeCoverageIgnoreStart
			if (! $this ->getFichier ()) {
				$this ->setUsingLogFile ( false );
				return $this ->logs_onError ( "le fichier : " . $this ->getNomFichier () . " n'a pas ete cree." );
			}
			// @codeCoverageIgnoreEnd
			

			if ($this ->getAppend ()) {
				$this ->getFichier () 
					->ouvrir ( "a" );
			} else {
				$this ->getFichier () 
					->ouvrir ( "w" );
			}
			
			return true;
		}
		
		return false;
	}

	/**
	 * Ecrit une ligne dans un fichier.
	 * Si il a un probleme d'ecriture le message part sur la sortie standard.
	 *
	 * @param string $message Ligne a ecrire.
	 */
	public function ajouter_fichier_log($message) {
		if ($this ->getUsingLogFile () && ($this ->getFichier () instanceof fichier_gz || $this ->getFichier () instanceof fichier)) {
			$this ->getFichier () 
				->ecrit ( $message . $this ->_finDeLigne () );
			return true;
		}
		
		return false;
	}

	/**
	 * Ferme le fichier de log.
	 *
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function ferme_fichier_log() {
		if ($this ->getUsingLogFile () && ($this ->getFichier () instanceof fichier || $this ->getFichier () instanceof fichier_gz)) {
			$this ->getFichier () 
				->close ();
			$this ->setUsingLogFile ( false );
			return true;
		}
		return false;
	}

	/**
	 * Renvoi le code de sortie : 0 pour OK, 1 pour EREEUR.<br>
	 * On peut force un autre valeur grace au parametres.<br>
	 * Ajoute l'affichage du [Exit].
	 *
	 * @param Bool $force_retour Pour force un valeur.
	 * @param int $retour Valeur forcee.
	 * @return int Renvoi la valeur du exit.
	 */
	public function renvoiExit() {
		// Sortie Perso
		$exit = $this ->getExit ();
		if ($exit === false)
			$exit = 1;
		elseif ($exit === true)
			$exit = 0;
		elseif ($exit == "")
			$exit = 0;
		$this ->verbose ( "[Exit]" . $exit, 0 );
		
		$this ->valide_exit_web ();
		return $exit;
	}

	/**
	 * Renvoi un json  'success'=>Boolean, 'return_code'=integer et 'message'=>liste d'erreur
	 */
	public function valide_exit_web() {
		if ($this ->getIsWeb () === true) {
			
			if ($this ->getExit () != 0) {
				$this ->AjouteMessageResultat ( false, 'success' );
				$this ->AjouteMessageResultat ( $this ->getexit (), 'return_code' );
				$msg = $this ->getexit ();
				foreach ( $this ->getErrorList () as $value ) {
					$msg .= ", " . $value;
				}
				$this ->AjouteMessageResultat ( $msg, 'message' );
			} else {
				$this ->AjouteMessageResultat ( true, 'success' );
				$this ->AjouteMessageResultat ( 0, 'return_code' );
			}
			
			echo json_encode ( $this ->getResultat () );
		}
	}
	
	// FIN de la gestion du fichier de log
	public function valideVerbose($niveau) {
		$verbose = $this ->getVerbose ();
		if ($verbose == "") {
			$verbose = 2;
		}
		if ($niveau <= $verbose) {
			return true;
		}
		return false;
	}

	/**
	 * Prepare la ligne d'affichage en fonction du message (objet, array ou string)
	 * @param string $message
	 * @return string message pour l'affichage
	 */
	public function prepare_affichage($message) {
		if (is_object ( $message )) {
			return print_r ( $message, true ) . $this ->_finDeLigne ();
		} elseif (is_array ( $message )) {
			return print_r ( $message, true ) . $this ->_finDeLigne ();
		}
		
		return $message . $this ->_finDeLigne ();
	}
	
	// Gestion du verbose
	/**
	 * Affiche un debug suivant le niveau de verbose et l'ajoute dans un fichier s'il existe.
	 *
	 * @param string $message Ligne a affiche.
	 * @param int $niveau Niveau de verbose.
	 * @return int Renvoi 0 si OK, 1 sinon.
	 */
	public function verbose($message, $niveau = 0) {
		$this ->ajouter_fichier_log ( $message );
		
		if ($this ->getIsError () === true) {
			$this ->affiche_erreur ( $this ->prepare_affichage ( $message ) );
		} else {
			if ($this ->valideVerbose ( $niveau )) {
				echo $this ->prepare_affichage ( $message );
			}
		}
		
		return true;
	}
	// FIN de la gestion du verbose
	

	/**
	 * Affiche une erreur sur stderr ou stdout en fonction de getIsWeb() et getIsErrorStdout()
	 * @param string $message
	 * @return logs
	 */
	public function affiche_erreur($message) {
		$this ->setErrorList ( $message );
		
		if ($this ->getIsWeb () === false && $this ->getIsErrorStdout () === false) {
			// @codeCoverageIgnoreStart
			fwrite ( STDERR, $message . $this ->_finDeLigne () );
		} else {
			// @codeCoverageIgnoreEnd
			//On affiche le message sur STDOUT
			echo $message . $this ->_finDeLigne ();
			//Si c'est du web, on affiche aussi le stderr dans les logs Apache
			if ($this ->getIsWeb () === true) {
				// @codeCoverageIgnoreStart
				fwrite ( STDERR, $message . $this ->_finDeLigne () );
			}
			// @codeCoverageIgnoreEnd
		}
		return $this;
	}

	/**
	 * Cette fonction sort si $sort_en_erreur est a TRUE.
	 *
	 * @param string $message Message d'erreur a afficher
	 * @return false
	 */
	public function logs_onError($message) {
		$message = "(logs) " . $message;
		// En cas de web, on envoi l'affichage sur le stdout (navigateur)
		$this ->setIsError ( true );
		$this ->setExit ( 1 );
		$this ->verbose ( "[Error] : " . $message, 0 );
		
		if ($this ->getSortEnErreur ()) {
			// @codeCoverageIgnoreStart
			$this ->verbose ( "[Exit]" . $this ->getExit (), 0 );
			exit ( $this ->renvoiExit () );
			// @codeCoverageIgnoreEnd
		}
		$this ->setIsError ( false );
		
		return false;
	}

	/**
	 * Prepare la fin de ligne
	 * @return string
	 */
	private function _finDeLigne() {
		if ($this ->getIsWeb () === false) {
			switch ($this ->getOs ()) {
				case "WIN" :
					// @codeCoverageIgnoreStart
					return "\n\r";
			}
			// @codeCoverageIgnoreEnd
			

			return "\n";
		}
		
		return "<br/>\n";
	}

	/**
	 * ****************** Accesseurs ************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	function getExit() {
		return $this->exit;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setExit($code, $force = false) {
		if ($this->exit == 0) {
			$this->exit = $code;
		} elseif ($force) {
			$this->exit = $code;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getIsWeb() {
		return $this->is_web;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setIsWeb($is_web) {
		$this->is_web = $is_web;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getOs() {
		return $this->os;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setOs($os) {
		$this->os = $os;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getNomFichier() {
		return $this->nom_fichier;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setNomFichier($nom_fichier) {
		$this->nom_fichier = $nom_fichier;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getRepFichier() {
		return $this->rep_fichier;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setRepFichier($rep_fichier) {
		$this->rep_fichier = $rep_fichier;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &getFichier() {
		return $this->fichier;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setFichier(&$fichier) {
		$this->fichier = $fichier;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getCompresse() {
		return $this->compresse;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setCompresse($compresse) {
		$this->compresse = $compresse;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getAppend() {
		return $this->append;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setAppend($append) {
		$this->append = $append;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getUsingLogFile() {
		return $this->using_log_file;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setUsingLogFile($using_log_file) {
		$this->using_log_file = $using_log_file;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getSortEnErreur() {
		return $this->sort_en_erreur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setSortEnErreur($sort_en_erreur) {
		$this->sort_en_erreur = $sort_en_erreur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getIsError() {
		return $this->is_error;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setIsError($is_error) {
		$this->is_error = $is_error;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getIsErrorStdout() {
		return $this->is_error_stdout;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setIsErrorStdout($is_error_stdout) {
		$this->is_error_stdout = $is_error_stdout;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getVerbose() {
		return $this->verbose;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setVerbose($verbose) {
		$this->verbose = $verbose;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getErrorList() {
		return $this->erreur_liste;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setErrorList($erreur) {
		$this->erreur_liste [] .= $erreur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getResultat() {
		return $this->resultat;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setResultat($resultat) {
		$this->resultat = $resultat;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &AjouteMessageResultat($message, $champ = "message") {
		$this->resultat [$champ] = $message;
		return $this;
	}

	/**
	 * ****************** Accesseurs ************************
	 */
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = array ( 
				__CLASS__ => array () );
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Options de gestion des logs";
		$help [__CLASS__] ["text"] [] .= "\t--create_log_file \"oui/non\"\t\tPar defaut : non";
		$help [__CLASS__] ["text"] [] .= "\t--dossier_log /tmp\t\t\tPar defaut : .";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_log log.txt\t\t\tPar defaut : ";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_log_unique \"oui/non\"\t\tPar defaut : oui";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_log_sort_en_erreur \"oui/non\"\tPar defaut : oui";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_log_compresse \"oui/non\"\tPar defaut : non";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_log_append \"oui/non\"\t\tPar defaut : non";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "Si aucune option n'est precisee, alors on prend les valeurs par defaut suivante :";
		$help [__CLASS__] ["text"] [] .= "./log_{pid} unique=\"oui\"";
		
		return $help;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function __destruct() {
		$this ->ferme_fichier_log ();
		return true;
	}
}
?>
