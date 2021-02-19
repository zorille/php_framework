<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \Exception as Exception;
use \stdClass as stdClass;
/**
 * class vmwareDatacenter<br>
 * Methodes valide en 5.5 :
 *  PowerOnMultiVM_Task, QueryConnectionInfo, queryDatacenterConfigOptionDescriptor, ReconfigureDatacenter_Task
 * @package Lib
 * @subpackage VMWare
 */
class vmwareDatacenter extends vmwareManagedEntity {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareDatacenter.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareDatacenter
	 */
	static function &creer_vmwareDatacenter(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwareDatacenter ( $sort_en_erreur, $entete );
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
	 * @return vmwareDatacenter
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
	 * Fait un PowerOn sur une liste de VMs de type VirtualMachine
	 * @param array $VMs liste de ManagedObjectReference de type VirtualMachine
	 * @param DrsBehavior $OverrideAutomationLevel Default value: current behavior ou fullyAutomated/partiallyAutomated/manual
	 * @param boolean $ReserveResources
	 * @return array|false
	 */
	public function PowerOnMultiVM_Task($VMs, $OverrideAutomationLevel = "current behavior ", $ReserveResources = false) {
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this();
		$request->vm = $VMs;
		
		$option = $this->prepare_ClusterPowerOnVmOption ( $OverrideAutomationLevel, $ReserveResources );
		if (count ( $option ) > 0) {
			$request->option = $option;
		}
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "PowerOnMultiVM_Task", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Prepare les optionValue pour le MultiPowerOn
	 * @param DrsBehavior $OverrideAutomationLevel Default value: current behavior ou fullyAutomated/partiallyAutomated/manual
	 * @param boolean $ReserveResources
	 * @return array (OptionValue)
	 */
	public function prepare_ClusterPowerOnVmOption($OverrideAutomationLevel = "current behavior ", $ReserveResources = false) {
		$options = array ();
		if ($OverrideAutomationLevel != "current behavior ") {
			array_push ( $options, array (
					"key" => "OverrideAutomationLevel",
					"value" => $OverrideAutomationLevel 
			) );
		}
		if ($ReserveResources != false) {
			array_push ( $options, array (
					"key" => "ReserveResources",
					"value" => $ReserveResources 
			) );
		}
		return $options;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return stdClass
	 */
	public function &getDatacenter() {
		return $this->getManagedObject();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatacenter($Datacenter) {
		return $this->setManagedObject($Datacenter);
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
