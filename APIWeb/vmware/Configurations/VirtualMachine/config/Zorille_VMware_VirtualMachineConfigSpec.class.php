<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework\options as options;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualMachineConfigSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineConfigSpec extends VirtualMachineConfig {
	/**
	 * var privee
	 * tableau de VirtualDeviceConfigSpec
	 * @access private
	 * @var array
	 */
	private $deviceChange = array ();
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $memoryMB = 2048;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $npivWorldWideNameOp = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $numCoresPerSocket = 0;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $numCPUs = 1;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineDefaultPowerOpInfo
	 */
	private $powerOpInfo = NULL;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $vAppConfigRemoved = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $virtualICH7MPresent = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $virtualSMCPresent = false;
	/**
	 * var privee
	 * Liste de VirtualMachineProfileSpec
	 * @access private
	 * @var boolean
	 */
	private $vmProfile = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineConfigSpec.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineConfigSpec
	 * @throws Exception
	 */
	static function &creer_VirtualMachineConfigSpec(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): VirtualMachineConfigSpec
	{
		
		$objet = new static( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineConfigSpec
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
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @throws Exception
	 */
	public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false): ArrayObject|array
	{
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if ( $this->getDeviceChange () ) {
			$liste_proprietes ["deviceChange"] = $this->getDeviceChange ();
		}
		if ( $this->getMemoryMB () ) {
			$liste_proprietes ["memoryMB"] = $this->getMemoryMB ();
		}
		if ( $this->getNpivWorldWideNameOp () ) {
			$liste_proprietes ["npivWorldWideNameOp"] = $this->getNpivWorldWideNameOp ();
		}
		if ( $this->getNumCoresPerSocket () ) {
			$liste_proprietes ["numCoresPerSocket"] = $this->getNumCoresPerSocket ();
		}
		if ( $this->getNumCPUs () ) {
			$liste_proprietes ["numCPUs"] = $this->getNumCPUs ();
		}
		if ( $this->getPowerOpInfo () ) {
			//Objet
			$liste_proprietes ["powerOpInfo"] = $this->getPowerOpInfo ();
		}
		if ( $this->getVAppConfigRemoved () ) {
			$liste_proprietes ["vAppConfigRemoved"] = $this->getVAppConfigRemoved ();
		}
		if ( $this->getVirtualICH7MPresent () ) {
			$liste_proprietes ["virtualICH7MPresent"] = $this->getVirtualICH7MPresent ();
		}
		if ( $this->getVirtualSMCPresent () ) {
			$liste_proprietes ["virtualSMCPresent"] = $this->getVirtualSMCPresent ();
		}
		if ( $this->getVmProfile () ) {
			$liste_proprietes ["vmProfile"] = $this->getVmProfile ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap(bool $arrayObject = false): soapvar
	{
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualMachineConfigSpec" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDeviceChange(): array
	{
		return $this->deviceChange;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeviceChange($deviceChange): static
	{
		$this->deviceChange = $deviceChange;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMemoryMB(): int
	{
		return $this->memoryMB;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMemoryMB($memoryMB): static
	{
		$this->memoryMB = $memoryMB;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivWorldWideNameOp(): string
	{
		return $this->npivWorldWideNameOp;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivWorldWideNameOp($npivWorldWideNameOp): static
	{
		$this->npivWorldWideNameOp = $npivWorldWideNameOp;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNumCoresPerSocket(): int
	{
		return $this->numCoresPerSocket;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNumCoresPerSocket($numCoresPerSocket): static
	{
		$this->numCoresPerSocket = $numCoresPerSocket;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNumCPUs(): int
	{
		return $this->numCPUs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNumCPUs($numCPUs): static
	{
		$this->numCPUs = $numCPUs;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineDefaultPowerOpInfo|null
	 */
	public function &getPowerOpInfo(): ?VirtualMachineDefaultPowerOpInfo
	{
		return $this->powerOpInfo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPowerOpInfo(&$powerOpInfo): static
	{
		$this->powerOpInfo = $powerOpInfo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVAppConfigRemoved(): bool
	{
		return $this->vAppConfigRemoved;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVAppConfigRemoved($vAppConfigRemoved): static
	{
		$this->vAppConfigRemoved = $vAppConfigRemoved;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVirtualICH7MPresent(): bool
	{
		return $this->virtualICH7MPresent;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVirtualICH7MPresent($virtualICH7MPresent): static
	{
		$this->virtualICH7MPresent = $virtualICH7MPresent;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVirtualSMCPresent(): bool
	{
		return $this->virtualSMCPresent;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVirtualSMCPresent($virtualSMCPresent): static
	{
		$this->virtualSMCPresent = $virtualSMCPresent;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVmProfile(): bool|array
	{
		return $this->vmProfile;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVmProfile($vmProfile): static
	{
		$this->vmProfile = $vmProfile;
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
