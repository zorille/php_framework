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
 * class vmwareVirtualMachine<br>
 * Methodes valide en 5.5 :
 *  AcquireMksTicket, AcquireTicket, AnswerVM, CheckCustomizationSpec, CloneVM_Task,
 *  ConsolidateVMDisks_Task, CreateScreenshot_Task, CreateSecondaryVM_Task, CreateSnapshot_Task,
 *  CustomizeVM_Task, DefragmentAllDisks, DisableSecondaryVM_Task, EnableSecondaryVM_Task,
 *  EstimateStorageForConsolidateSnapshots_Task, ExportVm, ExtractOvfEnvironment,
 *  MakePrimaryVM_Task, MarkAsTemplate, MarkAsVirtualMachine, MigrateVM_Task, MountToolsInstaller,
 *  PowerOffVM_Task, PowerOnVM_Task, PromoteDisks_Task, QueryChangedDiskAreas, QueryFaultToleranceCompatibility,
 *  QueryUnownedFiles, RebootGuest, ReconfigVM_Task, RefreshStorageInfo, reloadVirtualMachineFromPath_Task,
 *  RelocateVM_Task, RemoveAllSnapshots_Task, ResetGuestInformation, ResetVM_Task, RevertToCurrentSnapshot_Task,
 *  SetDisplayTopology, SetScreenResolution, ShutdownGuest, StandbyGuest, StartRecording_Task, StartReplaying_Task,
 *  StopRecording_Task, StopReplaying_Task, SuspendVM_Task, TerminateFaultTolerantVM_Task, TerminateVM,
 *  TurnOffFaultToleranceForVM_Task, UnmountToolsInstaller, UnregisterVM, UpgradeTools_Task, UpgradeVM_Task
 * @package Lib
 * @subpackage VMWare
 */
class vmwareVirtualMachine extends vmwareManagedEntity {
	/**
	 * var privee, MoID de la machine virtuel
	 * @access private
	 * @var array
	 */
	private $VirtualMachine = "";
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineConfigInfo
	 */
	private $config = NULL;
	/**
	 * var privee
	 * Liste de ManagedObjectReference to a Datastore
	 * @access private
	 * @var array
	 */
	private $datastore=array();
	/**
	 * var privee
	 * @access private
	 * @var EnvironmentBrowser
	 */
	private $environmentBrowser = NULL;
	/**
	 * var privee
	 * @access private
	 * @var GuestInfo
	 */
	private $guest = NULL;
	/**
	 * var privee
	 * @access private
	 * @var ManagedEntityStatus
	 */
	private $guestHeartbeatStatus = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineFileLayoutEx
	 */
	private $layoutEx = NULL;
	/**
	 * var privee
	 * Liste de ManagedObjectReference to a Network
	 * @access private
	 * @var array
	 */
	private $network=array();
	/**
	 * var privee
	 * ManagedObjectReference to a ManagedEntity
	 * @access private
	 * @var array
	 */
	private $parentVApp=array();
	/**
	 * var privee
	 * @access private
	 * @var ResourceConfigSpec
	 */
	private $resourceConfig = NULL;
	/**
	 * var privee
	 * ManagedObjectReference to a ResourcePool
	 * @access private
	 * @var array
	 */
	private $resourcePool = array();
	/**
	 * var privee
	 * Liste de ManagedObjectReference to a VirtualMachineSnapshot
	 * @access private
	 * @var array
	 */
	private $rootSnapshot=array();
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineRuntimeInfo
	 */
	private $runtime = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineSnapshotInfo
	 */
	private $snapshot = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineStorageInfo
	 */
	private $storage = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineSummary
	 */
	private $summary = NULL;
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareVirtualMachine.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareVirtualMachine
	 * @throws Exception
	 */
	static function &creer_vmwareVirtualMachine(options &$liste_option, vmwareWsclient &$ObjectVmwareWsclient, bool|string $sort_en_erreur = false, string $entete = __CLASS__): vmwareVirtualMachine
	{
		$objet = new vmwareVirtualMachine ( $sort_en_erreur, $entete );
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
	 * @return vmwareVirtualMachine
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
	 * Fait un CloneVM sur la VM
	 * @param string $name
	 * @param vmwareFolder $folder
	 * @param VirtualMachineCloneSpec $spec
	 * @return bool
	 */
	public function CloneVM_Task(string $name, vmwareFolder $folder, VirtualMachineCloneSpec $spec): bool
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this ();
		$request->folder = $folder;
		$request->name = $name;
		$request->spec = $spec;
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "CloneVM_Task", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}
	
	/**
	 * Fait un ExportVm sur la VM
	 * @return bool
	 */
	public function ExportVm(): bool
	{
		$this->onDebug ( __METHOD__, 1 );
	
		$request = $this->creer_entete_ManagedObject_this ();
	
		$resultat = $this->getObjectVmwareWsclient ()
		->applique_requete_soap ( "ExportVm", array (
				$request
		) );
	
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}
	
	/**
	 * Fait un ExtractOvfEnvironment sur la VM
	 * @return array|false
	 */
	public function ExtractOvfEnvironment(): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
	
		$request = $this->creer_entete_ManagedObject_this ();
	
		$resultat = $this->getObjectVmwareWsclient ()
		->applique_requete_soap ( "ExtractOvfEnvironment", array (
				$request
		) );
	
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Fait un MarkAsVirtualMachine sur la VM
	 * @param string $ResourcePool
	 * @param string $host
	 * @return bool
	 */
	public function MarkAsVirtualMachine(string $ResourcePool="", string $host=""): bool
	{
		$this->onDebug ( __METHOD__, 1 );
	
		$request = $this->creer_entete_ManagedObject_this ();
		$request->pool = $ResourcePool;
		$request->host = $host;
	
		$resultat = $this->getObjectVmwareWsclient ()
		->applique_requete_soap ( "MarkAsVirtualMachine", array (
				$request
		) );
	
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Fait un PowerOff sur la VM
	 * @return array|false
	 */
	public function PowerOffVM_Task(): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this ();
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "PowerOffVM_Task", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Fait un PowerOn sur la VM
	 * @param string|vmwareHostSystem $host (optional) The host where the virtual machine is to be powered on
	 * @return array|false
	 */
	public function PowerOnVM_Task(vmwareHostSystem|string $host = ""): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this ();
		if ($host != "") {
			$request->host = $host;
		}
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "PowerOnVM_Task", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Applique une mise a jour de la configuration
	 * @param array $VirtualMachineConfigSpec
	 * @return array|false
	 */
	public function ReconfigVM_Task(array $VirtualMachineConfigSpec): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this ();
		$request->spec = $VirtualMachineConfigSpec;
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "ReconfigVM_Task", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Fait un ShutdownGuest sur la VM
	 * @return array|false
	 */
	public function ShutdownGuest(): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this ();
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "ShutdownGuest", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Remplace les annotations d'une VM
	 * @param string $annotation
	 * @return bool|array
	 */
	public function remplace_annotation($annotation): bool|array
	{
		return $this->ReconfigVM_Task ( array (
				"annotation" => $annotation 
		) );
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return stdClass
	 */
	public function &getMoIDVirtualMachine(): stdClass
	{
		return $this->getManagedObject ();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMoIDVirtualMachine($VirtualMachine): vmwareVirtualMachine
	{
		return $this->setManagedObject ( $VirtualMachine );
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineConfigInfo|null
	 */
	public function &getConfig(): ?VirtualMachineConfigInfo
	{
		return $this->config;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setConfig(&$config): static
	{
		$this->config=$config;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getDatastore(): array
	{
		return $this->datastore;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatastore($datastore): static
	{
		$this->datastore=$datastore;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return EnvironmentBrowser
	 */
	public function &get_environmentBrowser(): ?EnvironmentBrowser
	{
		return $this->environmentBrowser;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &set_environmentBrowser(&$environmentBrowser): static
	{
		$this->environmentBrowser=$environmentBrowser;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return GuestInfo
	 */
	public function &getGuest(): ?GuestInfo
	{
		return $this->guest;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setGuest(&$guest): static
	{
		$this->guest=$guest;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return ManagedEntityStatus|null
	 */
	public function &getGuestHeartbeatStatus(): ?ManagedEntityStatus
	{
		return $this->guestHeartbeatStatus;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setGuestHeartbeatStatus(&$guestHeartbeatStatus): static
	{
		$this->guestHeartbeatStatus=$guestHeartbeatStatus;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineFileLayoutEx|null
	 */
	public function &get_layoutEx(): ?VirtualMachineFileLayoutEx
	{
		return $this->layoutEx;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &set_layoutEx(&$layoutEx): static
	{
		$this->layoutEx=$layoutEx;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getNetwork(): array
	{
		return $this->network;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setNetwork($network): static
	{
		$this->network=$network;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getParentVApp(): array
	{
		return $this->parentVApp;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setParentVApp($parentVApp): static
	{
		$this->parentVApp=$parentVApp;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return ResourceConfigSpec|null
	 */
	public function &getRessourceConfig(): ?ResourceConfigSpec
	{
		return $this->resourceConfig;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setRessourceConfig(&$resourceConfig): static
	{
		$this->resourceConfig=$resourceConfig;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getRessourcePool(): array
	{
		return $this->resourcePool;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setRessourcePool($resourcePool): static
	{
		$this->resourcePool=$resourcePool;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getRootSnapshot(): array
	{
		return $this->rootSnapshot;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setRootSnapshot($rootSnapshot): static
	{
		$this->rootSnapshot=$rootSnapshot;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineRuntimeInfo|null
	 */
	public function &getRuntime(): ?VirtualMachineRuntimeInfo
	{
		return $this->runtime;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setRuntime(&$runtime): static
	{
		$this->runtime=$runtime;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineSnapshotInfo|null
	 */
	public function &getSnapshot(): ?VirtualMachineSnapshotInfo
	{
		return $this->snapshot;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnapshot(&$snapshot): static
	{
		$this->snapshot=$snapshot;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineStorageInfo|null
	 */
	public function &getStorage(): ?VirtualMachineStorageInfo
	{
		return $this->storage;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setStorage(&$storage): static
	{
		$this->storage=$storage;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineSummary|null
	 */
	public function &getSummary(): ?VirtualMachineSummary
	{
		return $this->summary;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSummary(&$summary): static
	{
		$this->summary=$summary;
		return $this;
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
