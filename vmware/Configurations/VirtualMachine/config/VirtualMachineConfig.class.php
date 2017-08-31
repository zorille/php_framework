<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualMachineConfig<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualMachineConfig extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $alternateGuestName = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $annotation = "";
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineBootOptions
	 */
	private $bootOptions = NULL;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $changeTrackingEnabled = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $changeVersion = "";
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineConsolePreferences
	 */
	private $consolePreferences = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineAffinityInfo
	 */
	private $cpuAffinity = NULL;
	/**
	 * var privee
	 * @access private
	 * @var ResourceAllocationInfo
	 */
	private $cpuAllocation = NULL;
	/**
	 * var privee
	 * tableau de HostCpuIdInfo
	 * @access private
	 * @var array
	 */
	private $cpuFeatureMask = array ();
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $cpuHotAddEnabled = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $cpuHotRemoveEnabled = false;
	/**
	 * var privee
	 * tableau de OptionValue
	 * @access private
	 * @var array
	 */
	private $extraConfig = array ();
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineFileInfo
	 */
	private $files = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $firmware = "";
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineFlagInfo
	 */
	private $flags = NULL;
	/**
	 * var privee
	 * @access private
	 * @var FaultToleranceConfigInfo
	 */
	private $ftInfo = NULL;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $guestAutoLockEnabled = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $guestId = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $instanceUuid = "";
	/**
	 * var privee
	 * @access private
	 * @var LatencySensitivity
	 */
	private $latencySensitivity = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $locationId = "";
	/**
	 * var privee
	 * @access private
	 * @var ManagedByInfo
	 */
	private $managedBy = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $maxMksConnections = 0;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineAffinityInfo
	 */
	private $memoryAffinity = NULL;
	/**
	 * var privee
	 * @access private
	 * @var ResourceAllocationInfo
	 */
	private $memoryAllocation = NULL;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $memoryHotAddEnabled = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $memoryReservationLockedToMax = false;
	/**
	 * var privee
	 * Display name of the VM
	 * @access private
	 * @var string
	 */
	private $name = "";
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $nestedHVEnabled = false;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineNetworkShaperInfo
	 */
	private $networkShaper = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $npivDesiredNodeWwns = 0;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $npivDesiredPortWwns = 0;
	/**
	 * var privee
	 * tableau de long
	 * @access private
	 * @var array
	 */
	private $npivNodeWorldWideName = array ();
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $npivOnNonRdmDisks = false;
	/**
	 * var privee
	 * tableau de long
	 * @access private
	 * @var array
	 */
	private $npivPortWorldWideName = array ();
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $npivTemporaryDisabled = false;
	/**
	 * var privee
	 * Display name of the VM
	 * @access private
	 * @var string
	 */
	private $npivWorldWideNameType = "";
	/**
	 * var privee
	 * @access private
	 * @var ScheduledHardwareUpgradeInfo
	 */
	private $scheduledHardwareUpgradeInfo = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $swapPlacement = "";
	/**
	 * var privee
	 * @access private
	 * @var ToolsConfigInfo
	 */
	private $tools = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $uuid = "";
	/**
	 * var privee
	 * @access private
	 * @var VmConfigInfo
	 */
	private $vAppConfig = NULL;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $vAssertsEnabled = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $version = "";
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $vPMCEnabled = false;

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @todo
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if ( $this->getAlternateGuestName () ) {
			$liste_proprietes ["alternateGuestName"] = $this->getAlternateGuestName ();
		}
		if ( $this->getAnnotation () ) {
			$liste_proprietes ["annotation"] = $this->getAnnotation ();
		}
		if ( $this->getBootOptions () ) {
			$liste_proprietes ["bootOptions"] = $this->getBootOptions ()
				->renvoi_donnees_soap ( false );
		}
		if ( $this->getChangeTrackingEnabled () ) {
			$liste_proprietes ["changeTrackingEnabled"] = $this->getChangeTrackingEnabled ();
		}
		if ( $this->getChangeVersion () ) {
			$liste_proprietes ["changeVersion"] = $this->getChangeVersion ();
		}
		if ( $this->getConsolePreferences () ) {
			//objet
			$liste_proprietes ["consolePreferences"] = $this->getConsolePreferences ();
		}
		if ( $this->getCpuAffinity () ) {
			//objet
			$liste_proprietes ["cpuAffinity"] = $this->getCpuAffinity ();
		}
		if ( $this->getCpuAllocation () ) {
			//objet
			$liste_proprietes ["cpuAllocation"] = $this->getCpuAllocation ();
		}
		if ( $this->getCpuFeatureMask () ) {
			$liste_proprietes ["cpuFeatureMask"] = $this->getCpuFeatureMask ();
		}
		if ( $this->getCpuHotAddEnabled () ) {
			$liste_proprietes ["cpuHotAddEnabled"] = $this->getCpuHotAddEnabled ();
		}
		if ( $this->getCpuHotRemoveEnabled () ) {
			$liste_proprietes ["cpuHotRemoveEnabled"] = $this->getCpuHotRemoveEnabled ();
		}
		if ( $this->getExtraConfig () ) {
			$liste_proprietes ["extraConfig"] = $this->getExtraConfig ();
		}
		if ( $this->getFiles () ) {
			$liste_proprietes ["files"] = $this->getFiles ()
				->renvoi_donnees_soap ( false );
		}
		if ( $this->getFirmware () ) {
			$liste_proprietes ["firmware"] = $this->getFirmware ();
		}
		if ( $this->getFlags () ) {
			//objet
			$liste_proprietes ["flags"] = $this->getFlags ();
		}
		if ( $this->getFtInfo () ) {
			//objet
			$liste_proprietes ["ftInfo"] = $this->getFtInfo ();
		}
		if ( $this->getGuestAutoLockEnabled () ) {
			$liste_proprietes ["guestAutoLockEnabled"] = $this->getGuestAutoLockEnabled ();
		}
		if ( $this->getGuestId () ) {
			$liste_proprietes ["guestId"] = $this->getGuestId ();
		}
		if ( $this->getInstanceUuid () ) {
			$liste_proprietes ["instanceUuid"] = $this->getInstanceUuid ();
		}
		if ( $this->getLatencySensitivity () ) {
			//objet
			$liste_proprietes ["latencySensitivity"] = $this->getLatencySensitivity ();
		}
		if ( $this->getLocationId () ) {
			$liste_proprietes ["locationId"] = $this->getLocationId ();
		}
		if ( $this->getManagedBy () ) {
			//objet
			$liste_proprietes ["managedBy"] = $this->getManagedBy ();
		}
		if ( $this->getMaxMksConnections () ) {
			$liste_proprietes ["maxMksConnections"] = $this->getMaxMksConnections ();
		}
		if ( $this->getMemoryAffinity () ) {
			//objet
			$liste_proprietes ["memoryAffinity"] = $this->getMemoryAffinity ();
		}
		if ( $this->getMemoryAllocation () ) {
			//objet
			$liste_proprietes ["memoryAllocation"] = $this->getMemoryAllocation ();
		}
		if ( $this->getMemoryHotAddEnabled () ) {
			$liste_proprietes ["memoryHotAddEnabled"] = $this->getMemoryHotAddEnabled ();
		}
		if ( $this->getMemoryReservationLockedToMax () ) {
			$liste_proprietes ["memoryReservationLockedToMax"] = $this->getMemoryReservationLockedToMax ();
		}
		if ( $this->getName () ) {
			$liste_proprietes ["name"] = $this->getName ();
		}
		if ( $this->getNestedHVEnabled () ) {
			$liste_proprietes ["nestedHVEnabled"] = $this->getNestedHVEnabled ();
		}
		if ( $this->getNetworkShaper () ) {
			//objet
			$liste_proprietes ["networkShaper"] = $this->getNetworkShaper ();
		}
		if ( $this->getNpivDesiredNodeWwns () ) {
			$liste_proprietes ["npivDesiredNodeWwns"] = $this->getNpivDesiredNodeWwns ();
		}
		if ( $this->getNpivDesiredPortWwns () ) {
			$liste_proprietes ["npivDesiredPortWwns"] = $this->getNpivDesiredPortWwns ();
		}
		if ( $this->getNpivNodeWorldWideName () ) {
			$liste_proprietes ["npivNodeWorldWideName"] = $this->getNpivNodeWorldWideName ();
		}
		if ( $this->getNpivOnNonRdmDisks () ) {
			$liste_proprietes ["npivOnNonRdmDisks"] = $this->getNpivOnNonRdmDisks ();
		}
		if ( $this->getNpivPortWorldWideName () ) {
			$liste_proprietes ["npivPortWorldWideName"] = $this->getNpivPortWorldWideName ();
		}
		if ( $this->getNpivTemporaryDisabled () ) {
			$liste_proprietes ["npivTemporaryDisabled"] = $this->getNpivTemporaryDisabled ();
		}
		if ( $this->getNpivWorldWideNameType () ) {
			$liste_proprietes ["npivWorldWideNameType"] = $this->getNpivWorldWideNameType ();
		}
		if ( $this->etScheduledHardwareUpgradeInfo () ) {
			//objet
			$liste_proprietes ["scheduledHardwareUpgradeInfo"] = $this->etScheduledHardwareUpgradeInfo ();
		}
		if ( $this->getSwapPlacement () ) {
			$liste_proprietes ["swapPlacement"] = $this->getSwapPlacement ();
		}
		if ( $this->getTools () ) {
			//objet
			$liste_proprietes ["tools"] = $this->getTools ();
		}
		if ( $this->getUuid () ) {
			$liste_proprietes ["uuid"] = $this->getUuid ();
		}
		if ( $this->getVAppConfig () ) {
			//objet
			$liste_proprietes ["vAppConfig"] = $this->getVAppConfig ();
		}
		if ( $this->getVAssertsEnabled () ) {
			$liste_proprietes ["vAssertsEnabled"] = $this->getVAssertsEnabled ();
		}
		if ( $this->getVersion () ) {
			$liste_proprietes ["version"] = $this->getVersion ();
		}
		if ( $this->getVPMCEnabled () ) {
			$liste_proprietes ["vPMCEnabled"] = $this->getVPMCEnabled ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getAlternateGuestName() {
		return $this->alternateGuestName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAlternateGuestName($alternateGuestName) {
		$this->alternateGuestName = $alternateGuestName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAnnotation() {
		return $this->annotation;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAnnotation($annotation) {
		$this->annotation = $annotation;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineBootOptions
	 */
	public function &getBootOptions() {
		return $this->bootOptions;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBootOptions(&$bootOptions) {
		$this->bootOptions = $bootOptions;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getChangeTrackingEnabled() {
		return $this->changeTrackingEnabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setChangeTrackingEnabled($changeTrackingEnabled) {
		$this->changeTrackingEnabled = $changeTrackingEnabled;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getChangeVersion() {
		return $this->changeVersion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setChangeVersion($changeVersion) {
		$this->changeVersion = $changeVersion;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineConsolePreferences
	 */
	public function &getConsolePreferences() {
		return $this->consolePreferences;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConsolePreferences(&$consolePreferences) {
		$this->consolePreferences = $consolePreferences;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineAffinityInfo
	 */
	public function &getCpuAffinity() {
		return $this->cpuAffinity;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCpuAffinity(&$cpuAffinity) {
		$this->cpuAffinity = $cpuAffinity;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return ResourceAllocationInfo
	 */
	public function &getCpuAllocation() {
		return $this->cpuAllocation;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCpuAllocation(&$cpuAllocation) {
		$this->cpuAllocation = $cpuAllocation;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCpuFeatureMask() {
		return $this->cpuFeatureMask;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCpuFeatureMask($cpuFeatureMask) {
		$this->cpuFeatureMask = $cpuFeatureMask;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCpuHotAddEnabled() {
		return $this->cpuHotAddEnabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCpuHotAddEnabled($cpuHotAddEnabled) {
		$this->cpuHotAddEnabled = $cpuHotAddEnabled;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCpuHotRemoveEnabled() {
		return $this->cpuHotRemoveEnabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCpuHotRemoveEnabled($cpuHotRemoveEnabled) {
		$this->cpuHotRemoveEnabled = $cpuHotRemoveEnabled;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getExtraConfig() {
		return $this->extraConfig;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setExtraConfig($extraConfig) {
		$this->extraConfig = $extraConfig;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineFileInfo
	 */
	public function &getFiles() {
		return $this->files;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFiles(&$files) {
		$this->files = $files;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFirmware() {
		return $this->firmware;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFirmware($firmware) {
		$this->firmware = $firmware;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineFlagInfo
	 */
	public function &getFlags() {
		return $this->flags;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFlags(&$flags) {
		$this->flags = $flags;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return FaultToleranceConfigInfo
	 */
	public function &getFtInfo() {
		return $this->ftInfo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFtInfo(&$ftInfo) {
		$this->ftInfo = $ftInfo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGuestAutoLockEnabled() {
		return $this->guestAutoLockEnabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGuestAutoLockEnabled($guestAutoLockEnabled) {
		$this->guestAutoLockEnabled = $guestAutoLockEnabled;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGuestId() {
		return $this->guestId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGuestId($guestId) {
		$this->guestId = $guestId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getInstanceUuid() {
		return $this->instanceUuid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInstanceUuid($instanceUuid) {
		$this->instanceUuid = $instanceUuid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return LatencySensitivity
	 */
	public function &getLatencySensitivity() {
		return $this->latencySensitivity;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLatencySensitivity(&$latencySensitivity) {
		$this->latencySensitivity = $latencySensitivity;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLocationId() {
		return $this->locationId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLocationId($locationId) {
		$this->locationId = $locationId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return ManagedByInfo
	 */
	public function &getManagedBy() {
		return $this->managedBy;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setManagedBy(&$managedBy) {
		$this->managedBy = $managedBy;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxMksConnections() {
		return $this->maxMksConnections;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxMksConnections($maxMksConnections) {
		$this->maxMksConnections = $maxMksConnections;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineAffinityInfo
	 */
	public function &getMemoryAffinity() {
		return $this->memoryAffinity;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMemoryAffinity(&$memoryAffinity) {
		$this->memoryAffinity = $memoryAffinity;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return ResourceAllocationInfo
	 */
	public function &getMemoryAllocation() {
		return $this->memoryAllocation;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMemoryAllocation(&$memoryAllocation) {
		$this->memoryAllocation = $memoryAllocation;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMemoryHotAddEnabled() {
		return $this->memoryHotAddEnabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMemoryHotAddEnabled($memoryHotAddEnabled) {
		$this->memoryHotAddEnabled = $memoryHotAddEnabled;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMemoryReservationLockedToMax() {
		return $this->memoryReservationLockedToMax;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMemoryReservationLockedToMax($memoryReservationLockedToMax) {
		$this->memoryReservationLockedToMax = $memoryReservationLockedToMax;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNestedHVEnabled() {
		return $this->nestedHVEnabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNestedHVEnabled($nestedHVEnabled) {
		$this->nestedHVEnabled = $nestedHVEnabled;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineNetworkShaperInfo
	 */
	public function &getNetworkShaper() {
		return $this->networkShaper;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNetworkShaper(&$networkShaper) {
		$this->networkShaper = $networkShaper;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivDesiredNodeWwns() {
		return $this->npivDesiredNodeWwns;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivDesiredNodeWwns($npivDesiredNodeWwns) {
		$this->npivDesiredNodeWwns = $npivDesiredNodeWwns;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivDesiredPortWwns() {
		return $this->npivDesiredPortWwns;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivDesiredPortWwns($npivDesiredPortWwns) {
		$this->npivDesiredPortWwns = $npivDesiredPortWwns;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivNodeWorldWideName() {
		return $this->npivNodeWorldWideName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivNodeWorldWideName($npivNodeWorldWideName) {
		$this->npivNodeWorldWideName = $npivNodeWorldWideName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivOnNonRdmDisks() {
		return $this->npivOnNonRdmDisks;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivOnNonRdmDisks($npivOnNonRdmDisks) {
		$this->npivOnNonRdmDisks = $npivOnNonRdmDisks;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivPortWorldWideName() {
		return $this->npivPortWorldWideName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivPortWorldWideName($npivPortWorldWideName) {
		$this->npivPortWorldWideName = $npivPortWorldWideName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivTemporaryDisabled() {
		return $this->npivTemporaryDisabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivTemporaryDisabled($npivTemporaryDisabled) {
		$this->npivTemporaryDisabled = $npivTemporaryDisabled;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivWorldWideNameType() {
		return $this->npivWorldWideNameType;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivWorldWideNameType($npivWorldWideNameType) {
		$this->npivWorldWideNameType = $npivWorldWideNameType;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return ScheduledHardwareUpgradeInfo
	 */
	public function &etScheduledHardwareUpgradeInfo() {
		return $this->scheduledHardwareUpgradeInfo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setScheduledHardwareUpgradeInfo(&$scheduledHardwareUpgradeInfo) {
		$this->scheduledHardwareUpgradeInfo = $scheduledHardwareUpgradeInfo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSwapPlacement() {
		return $this->swapPlacement;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSwapPlacement($swapPlacement) {
		$this->swapPlacement = $swapPlacement;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return ToolsConfigInfo
	 */
	public function &getTools() {
		return $this->tools;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTools(&$tools) {
		$this->tools = $tools;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUuid($uuid) {
		$this->uuid = $uuid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VmConfigInfo
	 */
	public function &getVAppConfig() {
		return $this->vAppConfig;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVAppConfig(&$vAppConfig) {
		$this->vAppConfig = $vAppConfig;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVAssertsEnabled() {
		return $this->vAssertsEnabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVAssertsEnabled($vAssertsEnabled) {
		$this->vAssertsEnabled = $vAssertsEnabled;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVersion($version) {
		$this->version = $version;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVPMCEnabled() {
		return $this->vPMCEnabled;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVPMCEnabled($vPMCEnabled) {
		$this->vPMCEnabled = $vPMCEnabled;
		return $this;
	}

	/************************* Accesseurs ***********************/
}

?>
