<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
use \stdClass as stdClass;
/**
 * class vmwareHostSystem<br>
 * Methodes valide en 5.5 :
 *  AcquireCimServicesTicket, DisconnectHost_Task, EnterLockdownMode, EnterMaintenanceMode_Task, 
 *  ExitLockdownMode, ExitMaintenanceMode_Task, PowerDownHostToStandBy_Task, PowerUpHostFromStandBy_Task, 
 *  QueryHostConnectionInfo, QueryMemoryOverhead, QueryMemoryOverheadEx, RebootHost_Task, ReconfigureHostForDAS_Task, 
 *  ReconnectHost_Task, RetrieveHardwareUptime, ShutdownHost_Task, UpdateFlags, UpdateIpmi, UpdateSystemResources
 * @package Lib
 * @subpackage VMWare
 */
class vmwareHostSystem extends vmwareManagedEntity {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareHostSystem.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareHostSystem
	 */
	static function &creer_vmwareHostSystem(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new vmwareHostSystem ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareWsclient" => $ObjectVmwareWsclient 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareHostSystem
	 * @throws Exception
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
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/************************* Methodes VMWare ***********************/
	/**
	 * To Delete des qu'une autre methode apparait
	 * @return string
	 */
	public function affiche_les_Tests() {
		return "affiche";
	}
	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return stdClass
	 */
	public function &getHostSystem() {
		return $this->getManagedObject();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostSystem($HostSystem) {
		return $this->setManagedObject($HostSystem);
	}

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
