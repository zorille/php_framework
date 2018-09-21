<?php
/**
 * Gestion de SiteScope.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class sitescope_soap_configuration
 *
 * @package Lib
 * @subpackage SiteScope
 */
class sitescope_soap_configuration extends sitescope_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $wsdl = "APIConfiguration";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type sitescope_soap_configuration.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet 
	 * @return sitescope_soap_configuration
	 */
	static function &creer_sitescope_soap_configuration(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new sitescope_soap_configuration ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return sitescope_soap_configuration
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
	 * Connexion au soap configuration de sitescope (APIConfiguration)
	 *
	 * @param string $nom nom du sitescope a connecter
	 * @return bool TRUE si connexion ok, FALSE sinon
	 * @throws Exception
	 */
	public function connect($nom = "") {
		return $this->connexion ( $nom, $this->getWsdlNom () );
	}

	/**
	 * Recupere le FullConfiguationSnapshot
	 *     	
	 * @return array|false false en cas d'erreur
	 */
	public function retrouve_FullConfiguration_sitescope() {
		$this->onDebug ( "retrouve Full Configuration de sitescope", 1 );
		$full_confs = $this->applique_requete_soap ( "getFullConfigurationSnapshot", array (
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		$this->onDebug ( $full_confs, 2 );
		return $full_confs;
	}

	/**
	 * Recupere le getSiteScopeMonitoringStatus
	 *
	 * @return array|false false en cas d'erreur
	 */
	public function retrouve_MonitoringStatus_sitescope() {
		$this->onDebug ( "retrouve Monitoring Status de sitescope", 1 );
		$liste_MonitoringStatus = $this->applique_requete_soap ( "getSiteScopeMonitoringStatus", array (
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		$this->onDebug ( $liste_MonitoringStatus, 2 );
		return $liste_MonitoringStatus;
	}

	/******************************* Alertes ********************************/
	/**
	 * Desactive les alertes durant une periode
	 *
	 * @return Bool
	 */
	public function disableAssociatedAlerts($fullpathgroup, $diff_start_ms, $diff_end_ms, $description) {
		$this->onDebug ( "disableAssociatedAlerts de sitescope", 1 );
		$this->onDebug ( $fullpathgroup, 2 );

		$this->applique_requete_soap ( "disableAssociatedAlerts", array (
				$fullpathgroup,
				$diff_start_ms,
				$diff_end_ms,
				$description,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword (),
				"" 
		) );
		
		return true;
	}

	/**
	 * active les alertes durant une periode
	 *
	 * @return Bool
	 */
	public function enableAssociatedAlerts($fullpathgroup, $description) {
		$this->onDebug ( "enableAssociatedAlerts de sitescope", 1 );
		$this->onDebug ( $fullpathgroup, 2 );
		
		$this->applique_requete_soap ( "enableAssociatedAlerts", array (
				$fullpathgroup,
				$description,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword (),
				"" 
		) );
		
		return true;
	}

	/******************************* Alertes ********************************/
	
	/******************************* Groupes ********************************/
	/**
	 * supprime un groupe
	 *
	 * @return Bool
	 */
	public function deleteGroupEx($fullpathgroup) {
		$this->onDebug ( "deleteGroupEx de sitescope", 1 );
		$this->onDebug ( $fullpathgroup, 2 );
		
		$this->applique_requete_soap ( "deleteGroupEx", array (
				$fullpathgroup,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	/**
	 * Desactive un groupe
	 *
	 * @return Bool
	 */
	public function disableGroupFullPathEx($fullpathgroup, $periode_en_seconde) {
		$this->onDebug ( "disableGroupFullPathEx de sitescope", 1 );
		$this->onDebug ( $fullpathgroup, 2 );
		
		$this->applique_requete_soap ( "disableGroupFullPathEx", array (
				$fullpathgroup,
				$periode_en_seconde,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	/**
	 * Desactive un groupe
	 *
	 * @return Bool
	 */
	public function disableGroupWithDescription($fullpathgroup, $diff_start_ms, $diff_end_ms, $description) {
		$this->onDebug ( "disableGroupWithDescription de sitescope", 1 );
		$this->onDebug ( $fullpathgroup, 2 );
		
		$this->applique_requete_soap ( "disableGroupWithDescription", array (
				$fullpathgroup,
				$diff_start_ms,
				$diff_end_ms,
				$description,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	/**
	 * enable un groupe
	 *
	 * @return Bool
	 */
	public function enableGroupEx($fullpathgroup, $description) {
		$this->onDebug ( "enableGroupEx de sitescope", 1 );
		$this->onDebug ( $fullpathgroup, 2 );
		
		$this->applique_requete_soap ( "enableGroupEx", array (
				$fullpathgroup,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	/******************************* Groupes ********************************/
	
	/******************************* Moniteurs ********************************/
	/**
	 * supprime un moniteur
	 *
	 * @return Bool
	 */
	public function deleteMonitorEx($fullpathMonitor) {
		$this->onDebug ( "deleteMonitorEx de sitescope", 1 );
		$this->onDebug ( $fullpathMonitor, 2 );
		
		$this->applique_requete_soap ( "deleteMonitorEx", array (
				$fullpathMonitor,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	/**
	 * Desactive un moniteur
	 *
	 * @return Bool
	 */
	public function disableMonitorEx($fullpathMonitor, $periode_en_seconde) {
		$this->onDebug ( "disableMonitorEx de sitescope", 1 );
		$this->onDebug ( $fullpathMonitor, 2 );
		
		$this->applique_requete_soap ( "disableMonitorEx", array (
				$fullpathMonitor,
				$periode_en_seconde,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	/**
	 * Desactive un moniteur
	 *
	 * @return Bool
	 */
	public function disableMonitorWithDescription($fullpathMonitor, $diff_start_ms, $diff_end_ms, $description) {
		$this->onDebug ( "disableMonitorWithDescription de sitescope", 1 );
		$this->onDebug ( $fullpathMonitor, 2 );
		
		$this->applique_requete_soap ( "disableMonitorWithDescription", array (
				$fullpathMonitor,
				$diff_start_ms,
				$diff_end_ms,
				$description,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	/**
	 * Desactive un moniteur
	 *
	 * @return Bool
	 */
	public function enableMonitorWithDescription($fullpathMonitor, $description) {
		$this->onDebug ( "enableMonitorWithDescription de sitescope", 1 );
		$this->onDebug ( $fullpathMonitor, 2 );
		
		$this->applique_requete_soap ( "enableMonitorWithDescription", array (
				$fullpathMonitor,
				$description,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword (),
				"" 
		) );
		
		return true;
	}

	/**
	 * Desactive un moniteur
	 *
	 * @return Bool
	 */
	public function getMonitorSnapshots($fullpathMonitor, $propertiesFilter) {
		$this->onDebug ( "getMonitorSnapshots de sitescope", 1 );
		$this->onDebug ( $fullpathMonitor, 1 );
		
		$path = "";
		if (is_array ( $fullpathMonitor )) {
			foreach ( $fullpathMonitor as $valeur ) {
				$path = $path . $this->getSeparateur () . $valeur;
			}
		} else {
			$path = $fullpathMonitor;
		}
		
		$path = html_entity_decode ( $path, ENT_COMPAT, 'UTF-8' );
		$monitor = $this->applique_requete_soap ( "getMonitorSnapshots", array (
				array (
						$path 
				),
				$propertiesFilter,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword (),
				"" 
		) );
		
		$this->onDebug ( $monitor, 2 );
		return $monitor;
	}

	/******************************* Moniteurs ********************************/
	
	/******************************* Remote Server ********************************/
	/**
	 * supprime un remote server
	 *
	 * @return Bool
	 */
	public function deleteRemote($OS, $remoteName) {
		$this->onDebug ( "deleteRemote de sitescope", 1 );
		$this->onDebug ( $OS, 2 );
		$this->onDebug ( $remoteName, 2 );
		if ($OS != "WINDOWS" && $OS != "UNIX") {
			return $this->onError ( "L'OS doit etre WINDOWS ou UNIX" );
		}
		
		$this->applique_requete_soap ( "deleteRemote", array (
				$OS,
				$remoteName,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	/******************************* Remote Server ********************************/
	
	/******************************* Templates ********************************/
	public function deploySingleTemplateEx($fullPathToTemplateName, $actualVariablesValuesHashMap, $pathToTargetGroup) {
		//void deploySingleTemplateEx(String[] fullPathToTemplateName, HashMap actualVariablesValuesHashMap, String[] pathToTargetGroup, String username, String password)
		$this->onDebug ( "deploySingleTemplateEx de sitescope", 1 );
		$this->onDebug ( $fullPathToTemplateName, 2 );
		$this->onDebug ( $actualVariablesValuesHashMap, 2 );
		$this->onDebug ( $pathToTargetGroup, 2 );
		
		$this->applique_requete_soap ( "deploySingleTemplateEx", array (
				$fullPathToTemplateName,
				$actualVariablesValuesHashMap,
				$pathToTargetGroup,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword () 
		) );
		
		return true;
	}

	public function deploySingleTemplateWithResult($fullPathToTemplateName, $actualVariablesValuesHashMap, $pathToTargetGroup, $connectToServer = false, $testRemotes = false) {
		//HashMap deploySingleTemplateWithResult(String[] fullPathToTemplateName, HashMap actualVariablesValuesHashMap, String[] pathToTargetGroup, boolean connectToServer, boolean testRemotes, String username, String password, String identifier)
		$this->onDebug ( "deploySingleTemplateWithResult de sitescope", 1 );
		$this->onDebug ( $fullPathToTemplateName, 2 );
		$this->onDebug ( $actualVariablesValuesHashMap, 2 );
		$this->onDebug ( $pathToTargetGroup, 2 );
		
		try {
			$retour_sis = $this->applique_requete_soap ( "deploySingleTemplateWithResult", array (
					$fullPathToTemplateName,
					$actualVariablesValuesHashMap,
					$pathToTargetGroup,
					$connectToServer,
					$testRemotes,
					$this->getSoapConnection ()
						->getLogin (),
					$this->getSoapConnection ()
						->getPassword (),
					"" 
			) );
		} catch ( Exception $e ) {
			if (strpos ( $e->getMessage (), "java.lang.IndexOutOfBoundsException: Index: 0, Size: 0" ) !== false) {
				$this->onWarning ( "Pas d'index trouve : " . $e->getMessage () );
				return array ();
			}
			return $this->onError ( $e->getMessage (), "", $e->getCode () );
		}
		
		$this->onDebug ( $retour_sis, 2 );
		return $retour_sis;
	}

	public function publishTemplateChanges($fullPathToTemplateName, $selectedGroupsWithVariablesHashMap, $connectToServer = false, $deleteOnUpdate = false) {
		//String publishTemplateChanges(String templatePath, HashMap selectedGroupsWithVariables, boolean connectToServer, boolean deleteOnUpdate, String username, String password, String identifier)
		$this->onDebug ( "publishTemplateChanges de sitescope", 1 );
		$this->onDebug ( $fullPathToTemplateName, 2 );
		$this->onDebug ( $selectedGroupsWithVariablesHashMap, 2 );
		$this->onDebug ( $connectToServer, 2 );
		$this->onDebug ( $deleteOnUpdate, 2 );
		
		$retour_sis = $this->applique_requete_soap ( "publishTemplateChanges", array (
				$fullPathToTemplateName,
				$selectedGroupsWithVariablesHashMap,
				$connectToServer,
				$deleteOnUpdate,
				$this->getSoapConnection ()
					->getLogin (),
				$this->getSoapConnection ()
					->getPassword (),
				"" 
		) );
		
		$this->onDebug ( $retour_sis, 2 );
		return $retour_sis;
	}

	/******************************* Templates ********************************/
	
	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlNom() {
		return $this->wsdl;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "\t--dry-run Affiche les appels sans les executer";
		
		return $help;
	}
}
?>
