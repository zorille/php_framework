<?php
/**
 * Gestion de HPOM.
 * @author dvargas
 */
/**
 * class hpom_client
 *
 * @package Lib
 * @subpackage HP
 */
class hpom_client extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $hpom_client_cmd = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $application = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $msg_grp = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $node = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $objet = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $severite = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $msg_text = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $append_msg_text = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $instances = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type hpom_client.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return hpom_client
	 * @throws Exception
	 */
	static function &creer_hpom_client(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new hpom_client ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_hpom_client_param ();
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 *
	 * @return boolean True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_hpom_client_param() {
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"hpom",
				"client",
				"cmd" 
		) ) === false) {
			return $this->onError ( "Il manque les parametres clients hpom.", "", 5200 );
		}
		
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"hpom",
				"client",
				"dossier" 
		) ) === false) {
			$hpom_client_dossier = "";
		} else {
			$hpom_client_dossier = $this->getListeOptions ()
				->renvoi_variables_standard ( array (
					"hpom",
					"client",
					"dossier" 
			) ) . "/";
		}
		
		$hpom_client_cmd = $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"hpom",
				"client",
				"cmd" 
		) );
		
		$this->AjouteOption ( "source_host", $this->getListeOptions ()
			->getOption ( "netname" ) );
		$this->AjouteOption ( "alert_time", time () );
		
		$this->onDebug ( $hpom_client_dossier . $hpom_client_cmd, 1 );
		return $this->setHpomClientCmd ( $hpom_client_dossier . $hpom_client_cmd );
	}

	/**
	 * On convertie les niveaux d'alerter en niveau HPOM
	 * @param string $donnees
	 * @return boolean
	 */
	public function gestion_severite($severite) {
		switch (strtolower ( $severite )) {
			case "normal" :
			case "major" :
			case "critical" :
			case "minor" :
			case "unknown" :
				$this->setSeverite ( strtolower ( $severite ) );
				break;
			case "good" :
			case "informational" :
				$this->setSeverite ( "normal" );
				break;
			case "warning" :
				$this->setSeverite ( "major" );
				break;
			case "error" :
				$this->setSeverite ( "critical" );
				break;
			default :
				$this->setSeverite ( "minor" );
		}
		
		return $this;
	}

	public function creerCommandeHpomClient($instance = "") {
		$msg_text = "";
		
		$CMD = $this->getHpomClientCmd ();
		$CMD .= " -id";
		$CMD .= " msg_grp=" . strtoupper ( $this->getMsgGrp () );
		$CMD .= " node=\"" . strtoupper ( $this->getNode () ) . "\"";
		$CMD .= " severity=" . $this->getSeverite ();
		$CMD .= " application=" . strtoupper ( $this->getApplication () );
		$CMD .= " object='" . strtoupper ( $this->getObjet () ) . "'";
		
		if ($this->getMsgText () !== "") {
			$msg_text = " msg_text=\"" . str_replace ( "\"", "'", $this->getMsgText () );
		} else {
			if ($instance != "") {
				$msg_text = " msg_text=\"" . $this->getObjet () . " [" . str_replace ( "\"", "'", $instance ) . "] erreur sur " . $this->getNode ();
				$CMD .= " -option instance=\"" . str_replace ( "\"", "'", $instance ) . "\"";
			} else {
				$msg_text = " msg_text=\"" . $this->getObjet () . " erreur sur " . $this->getNode ();
			}
		}
		
		if ($this->getAppendMsgText () != "") {
			$msg_text .= " : " . str_replace ( "\"", "'", $this->getAppendMsgText () );
		}
		$CMD .= $msg_text . "\"";
		
		foreach ( $this->getOptions () as $nom => $valeur ) {
			$CMD .= " -option " . $nom . "=\"" . str_replace ( "\"", "'", $valeur ) . "\"";
		}
		
		// @codeCoverageIgnoreStart
		if (strtoupper ( substr ( PHP_OS, 0, 3 ) ) === 'WIN') {
			$this->onInfo ( "conversion d'encodage : " . PHP_OS );
			$CMD = mb_convert_encoding ( $CMD, "Windows-1252" );
			$CMD = str_replace ( "\n", "<br/>", $CMD );
		}
		// @codeCoverageIgnoreEnd
		
		$this->onDebug ( $CMD, 1 );
		return $CMD;
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $instance
	 * @return boolean
	 */
	private function _appliqueCommandeHpom($instance = "") {
		$CMD = $this->creerCommandeHpomClient ( $instance );
		if ($this->getListeOptions ()
			->getOption ( "dry-run" ) !== false) {
			$this->onWarning ( "DRY RUN : CMD : " . $CMD );
			$retour = array (
					0 => "0",
					1 => "dry-run" 
			);
		} else {
			$retour = fonctions_standards::applique_commande_systeme ( $CMD, false );
		}
		$this->onDebug ( $retour, 2 );
		if ($retour [0] != 0) {
			return $this->onError ( "FAILED;" . $CMD, $retour );
		}
		
		$this->onInfo ( "SENT; " . $CMD );
		$this->onInfo ( "HPOM_id=" . $retour [1] );
		
		return true;
	}

	/**
	 * Execute la commande hpom_client (opcmsg)
	 * @return boolean
	 */
	public function envoi_hpom_datas() {
		if (count ( $this->getInstances () ) == 0) {
			return $this->_appliqueCommandeHpom ( "" );
		}
		
		foreach ( $this->getInstances () as $instance ) {
			//transmission du message via opcmsg
			$this->_appliqueCommandeHpom ( $instance );
		}
		
		return true;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getHpomClientCmd() {
		return $this->hpom_client_cmd;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHpomClientCmd($hpom_client_cmd) {
		$this->hpom_client_cmd = $hpom_client_cmd;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getApplication() {
		return $this->application;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setApplication($application) {
		$this->application = $application;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNode() {
		return $this->node;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNode($node) {
		$this->node = $node;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getObjet() {
		return $this->objet;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjet($objet) {
		$this->objet = str_replace ( "\"", "", $objet );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSeverite() {
		return $this->severite;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSeverite($severite) {
		$this->severite = $severite;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getInstances() {
		return $this->instances;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInstances($instances) {
		if (! is_array ( $instances )) {
			$this->instances = array (
					$instances 
			);
		} else {
			$this->instances = $instances;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMsgGrp() {
		return $this->msg_grp;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMsgGrp($msg_grp) {
		$this->msg_grp = $msg_grp;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMsgText() {
		return $this->msg_text;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMsgText($msg_text) {
		$this->msg_text = $msg_text;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAppendMsgText() {
		return $this->append_msg_text;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAppendMsgText($append_msg_text) {
		$this->append_msg_text = $append_msg_text;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOptions($options) {
		$this->options = $options;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function AjouteOption($nom_option, $option) {
		$this->options [$nom_option] = $option;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Parametres de gestion de l'agent HPOM";
		$help [__CLASS__] ["text"] [] .= "\t--hpom_client_cmd\t\t{chemin complet du client}";
		$help [__CLASS__] ["text"] [] .= "\t--hpom_client_dossier\t\t{chemin complet du client}";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run\t\t\tAffiche la commande mais ne l'applique pas";
		
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
