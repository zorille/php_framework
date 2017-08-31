<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class aws_cloudwatch<br>
 *
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage Aws
 */
class aws_cloudwatch extends aws_wsclient {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type aws_cloudwatch.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param aws_datas &$aws_datas Reference sur un objet aws_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return aws_cloudwatch
	 */
	static function &creer_aws_cloudwatch(&$liste_option, &$aws_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new aws_cloudwatch ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"aws_datas" => $aws_datas 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return aws_cloudwatch
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["aws_datas"] )) {
			return $this->onError ( "il faut un objet de type aws_datas" );
		}
		$this->setObjetAwsDatas ( $liste_class ["aws_datas"] );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		return true;
	}

	/************************* GESTION Aws HOST ****************************/
	/**
	 * 
	 * @param array $reponse
	 * @return Ambigous <false, boolean>|boolean
	 */
	public function valide_requete($reponse) {
		if (isset ( $reponse ["Error"] )) {
			return $this->onError ( $reponse ["Error"] ["Code"], $reponse ["Error"] ["Message"] );
		}
		
		if (isset ( $reponse ["RequestId"] )) {
			$requestId = $reponse ["RequestId"];
		} elseif (isset ( $reponse ["ResponseMetadata"] ) && isset ( $reponse ["ResponseMetadata"] ["RequestId"] )) {
			$requestId = $reponse ["ResponseMetadata"] ["RequestId"];
		} else {
			return $this->onError ( "Le Requestid est absent" );
		}
		if (is_null ( $requestId ) or empty ( $requestId )) {
			return $this->onError ( "Le Requestid est faux", $requestId );
		}
		
		return true;
	}

	/**
	 * Recupere la liste des types de metrique
	 * @return array|boolean resultat ou false
	 */
	public function ListMetrics() {
		$retour = array ();
		
		$this->setParams ( 'Action', "ListMetrics" );
		$this->setParams ( 'Version', "2010-08-01", true );
		$resultat = $this->execute_requete_aws ();
		if ($this->valide_requete ( $resultat ) == false) {
			return false;
		}
		
		foreach ( $resultat ["ListMetricsResult"] ["Metrics"] ["member"] as $metrics ) {
			$pos = "N/A";
			foreach ( $metrics ["Dimensions"] ["member"] as $metric ) {
				if (! isset ( $metric ["Name"] )) {
					continue;
				}
				switch ($metric ["Name"]) {
					case "ServiceName" :
						$retour [$metric ["Value"]] = array ();
						$pos = $metric ["Value"];
						break;
					default :
						$retour [$pos] [$metric ["Name"]] = $metric ["Value"];
				}
			}
			$retour [$pos] [$metrics ["Namespace"]] = $metrics ["MetricName"];
		}
		
		$this->onDebug ( $retour, 2 );
		return $retour;
	}

	/**
	 * Recupere la liste des types de metrique
	 * @return array|boolean resultat ou false
	 */
	public function DescribeAlarms() {
		$retour = array ();
		
		$this->setParams ( 'Action', "DescribeAlarms" );
		$this->setParams ( 'Version', "2010-08-01", true );
		$resultat = $this->execute_requete_aws ();
		if ($this->valide_requete ( $resultat ) == false) {
			return false;
		}
		
		foreach ( $resultat ["DescribeAlarmsResult"] ["MetricAlarms"] ["member"] as $name => $metrics ) {
			if (is_integer ( $name )) {
				//pas de test pour le moment
			} else {
				$retour [$name] = $metrics;
			}
		}
		
		$this->onDebug ( $retour, 2 );
		return $retour;
	}

	/**
	 * Recupere la liste des types de metrique
	 * @return array|boolean resultat ou false
	 */
	public function GetMetricStatistics() {
		$retour = array ();
		
		/**
		 * The parameter Namespace is required.
The parameter MetricName is required.
The parameter StartTime is required.
The parameter EndTime is required.
The parameter Period is required.
The parameter Statistics is required.
		 */
		$this->setParams ( 'Action', "GetMetricStatistics" );
		$this->setParams ( 'Version', "2010-08-01", true );
		$this->setParams ( 'Namespace', 'AWS/Billing', true );
		$this->setParams ( 'MetricName', 'EstimatedCharges', true );
		$this->setParams ( 'StartTime', gmdate ( 'Y-m-d\TH:i:s\Z', mktime () - 30 ), true );
		$this->setParams ( 'EndTime', gmdate ( 'Y-m-d\TH:i:s\Z', mktime () ), true );
		$this->setParams ( 'Period', "21600", true );
		//Statistique : Average | Sum | SampleCount | Maximum | Minimum
		$this->setParams ( 'Statistics', 'Maximum', true );
		$resultat = $this->execute_requete_aws ();
		if ($this->valide_requete ( $resultat ) == false) {
			return false;
		}
		$retour = &$resultat;
		$this->onDebug ( $retour, 2 );
		return $retour;
	}

	/************************* Accesseurs ***********************/
	
	/************************* Accesseurs ***********************/
	
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
