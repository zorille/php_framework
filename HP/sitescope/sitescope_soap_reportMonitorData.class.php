<?php
/**
 * Gestion de SiteScope.
 * @author dvargas
 */
/**
 * class sitescope_soap_reportMonitorData
 *
 * @package Lib
 * @subpackage SiteScope
 */
class sitescope_soap_reportMonitorData extends sitescope_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_prefs = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $wsdl = "reportMonitorData";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type sitescope_soap_reportMonitorData.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return sitescope_soap_reportMonitorData
	 */
	static function &creer_sitescope_soap_reportMonitorData(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new sitescope_soap_reportMonitorData ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return sitescope_soap_reportMonitorData
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de sitescope_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Connexion au soap sitescope de sitescope (APIPreference)
	 *
	 * @param string $nom nom du sitescope a connecter
	 * @return bool TRUE si connexion ok, FALSE sinon
	 */
	public function connect($nom = "") {
		return $this->connexion ( $nom, $this->getWsdlNom () );
	}

	/**
	 * Envoi un report
	 * @param string $systemId
	 * @param array $data DataMessage { 
	 * 		string key; 
	 * 		string value; 
	 * 		}
	 * @return array
	 */
	public function reportData($systemId, $data) {
		$this->onDebug ( "reportData", 1 );
		$liste_datas = $this->applique_requete_soap ( "reportData", array (
				$systemId,
				$data 
		) );
		$this->onDebug ( $liste_datas, 2 );
		return $liste_datas;
	}

	/**
	 * Envoi un event
	 * @param string $systemId
	 * @param array $data DataMessage { 
	 * 		string acknowledgedBy;
 	 * 		string attr1;
  	 * 		string attr2;
  	 * 		string attr3;
 	 * 		string attr4;
  	 * 		string attr5;
  	 * 		string dataSource;
  	 * 		string description;
  	 * 		string eventId;
  	 * 		string instance;
  	 * 		string logicalGroup;
  	 * 		string monitorGroup;
  	 * 		string object;
  	 * 		string origSeverityName;
  	 * 		string owner;
  	 * 		int severity;
  	 * 		string status;
  	 * 		string subject;
  	 * 		string targetIp;
  	 * 		double value;
  	 * 		}
	 * @return array
	 */
	public function reportEvent($event) {
		$this->onDebug ( "reportEvent", 1 );
		$liste_datas = $this->applique_requete_soap ( "reportEvent", array (
				$event 
		) );
		$this->onDebug ( $liste_datas, 2 );
		return $liste_datas;
	}

	/**
	 * Envoi une liste d'events
	 * @param array $events tableau d'events
	 * @return array
	 */
	public function reportEventsArray($events) {
		$this->onDebug ( "reportEventsArray", 1 );
		$liste_datas = $this->applique_requete_soap ( "reportEventsArray", array (
				$events 
		) );
		$this->onDebug ( $liste_datas, 2 );
		return $liste_datas;
	}

	/**
	 * Envoi un metric
	 * @param array $metric DataMessage { 
	 * 		 string measurementCIHint;
 	 * 		 string measurementETI;
	 * 		 string measurementName;
 	 * 		 double measurementValue;
 	 * 		 string monitorName;
 	 * 		 string monitorState;
 	 * 		 string monitorType;
 	 * 		 int quality;
	 * 		 }
	 * @return array
	 */
	public function reportMetricObject($metric) {
		$this->onDebug ( "reportMetricObject", 1 );
		$liste_datas = $this->applique_requete_soap ( "reportMetricObject", array (
				$metric 
		) );
		$this->onDebug ( $liste_datas, 2 );
		return $liste_datas;
	}

	/**
	 * Envoi une liste de metrics
	 * @param array $metrics tableau de metric
	 * @return array
	 */
	public function reportMetricsArray($metrics) {
		$this->onDebug ( "reportMetricsArray", 1 );
		$liste_datas = $this->applique_requete_soap ( "reportMetricsArray", array (
				$metrics 
		) );
		$this->onDebug ( $liste_datas, 2 );
		return $liste_datas;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlNom() {
		return $this->wsdl;
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
		
		return $help;
	}
}
?>
