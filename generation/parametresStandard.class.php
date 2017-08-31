<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class parametresStandard<br>
 *
 * Prepare certain parametres Standard.
 * @package Lib
 * @subpackage Generation
 */
class parametresStandard extends CommandLine {
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $date = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $serial = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $dbhost = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $database = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $dbuser = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $dbpasswd = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $step = 10000;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mergeds_dir = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mergeds_files = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $pattern = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type parametresStandard.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return parametresStandard
	 */
	static function &creer_parametresStandard(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new parametresStandard ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return parametresStandard
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Prepare les parametres de la ligne de commande
	 * @param string $mandatory
	 * @return parametresStandard
	 * @throws Exception
	 */
	public function ajouteDbParam($mandatory = true) {
		//Gestion de la connexion a la base
		$this->addToCmd ( $this->AddParam ( "--host", $this->getDbHost (), $mandatory ) );
		$this->addToCmd ( $this->AddParam ( "--base", $this->getDatabase (), $mandatory ) );
		$this->addToCmd ( $this->AddParam ( "--user", $this->getDbUser (), $mandatory ) );
		$this->addToCmd ( $this->AddParam ( "--password", $this->getDbPasswd (), $mandatory ) );
		
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDate($date) {
		if ($date != "") {
			$this->date = $date;
		} else {
			return $this->onError ( "La date est obligatoire." );
		}
		return $this;;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSerial() {
		return $this->serial;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSerial($serial) {
		$this->serial = $serial;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbHost() {
		return $this->dbhost;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbHost($dbhost) {
		$this->dbhost = $dbhost;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDatabase() {
		return $this->database;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatabase($database) {
		$this->database = $database;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbUser() {
		return $this->dbuser;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbUser($dbuser) {
		$this->dbuser = $dbuser;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbPasswd() {
		return $this->dbpasswd;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbPasswd($dbpasswd) {
		$this->dbpasswd = $dbpasswd;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getStep() {
		return $this->step;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setStep($step) {
		$this->step = $step;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMergedsDir() {
		return $this->mergeds_dir;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMergedsDir($mergeds_dir) {
		$this->mergeds_dir = $mergeds_dir;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMergedsPattern() {
		return $this->pattern;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMergedsPattern($pattern) {
		$this->pattern = $pattern;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMergedsFiles() {
		return $this->mergeds_files;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMergedsFiles($mergeds_files) {
		if (! is_array ( $mergeds_files )) {
			$mergeds_files = array (
					$mergeds_files 
			);
		}
		$this->mergeds_files = $this->creerListeFichiers ( $mergeds_files );
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>
