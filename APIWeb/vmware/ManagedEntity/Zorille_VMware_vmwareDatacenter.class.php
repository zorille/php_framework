<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework\options as options;
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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareDatacenter
	 * @throws Exception
	 */
	static function &creer_vmwareDatacenter(options &$liste_option, vmwareWsclient &$ObjectVmwareWsclient, bool|string $sort_en_erreur = false, string $entete = __CLASS__): vmwareDatacenter
	{
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
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
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
	 * @param string|DrsBehavior $OverrideAutomationLevel Default value: current behavior ou fullyAutomated/partiallyAutomated/manual
	 * @param boolean $ReserveResources
	 * @return bool
	 */
	public function PowerOnMultiVM_Task(array $VMs, DrsBehavior|string $OverrideAutomationLevel = "current behavior ", bool $ReserveResources = false): bool
	{
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
	 * @param string|DrsBehavior $OverrideAutomationLevel Default value: current behavior ou fullyAutomated/partiallyAutomated/manual
	 * @param boolean $ReserveResources
	 * @return array (OptionValue)
	 */
	public function prepare_ClusterPowerOnVmOption(DrsBehavior|string $OverrideAutomationLevel = "current behavior ", bool $ReserveResources = false): array
	{
		$options = array ();
		if ($OverrideAutomationLevel != "current behavior ") {
			$options[] = array(
				"key" => "OverrideAutomationLevel",
				"value" => $OverrideAutomationLevel
			);
		}
		if ($ReserveResources) {
			$options[] = array(
				"key" => "ReserveResources",
				"value" => $ReserveResources
			);
		}
		return $options;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return stdClass
	 */
	public function &getDatacenter(): stdClass
	{
		return $this->getManagedObject();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatacenter($Datacenter): vmwareDatacenter
	{
		return $this->setManagedObject($Datacenter);
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
