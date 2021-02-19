<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
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
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineConfigSpec
	 */
	static function &creer_VirtualMachineConfigSpec(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualMachineConfigSpec ( $sort_en_erreur, $entete );
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
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
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
	public function &renvoi_objet_soap($arrayObject = false) {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualMachineConfigSpec" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDeviceChange() {
		return $this->deviceChange;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeviceChange($deviceChange) {
		$this->deviceChange = $deviceChange;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMemoryMB() {
		return $this->memoryMB;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMemoryMB($memoryMB) {
		$this->memoryMB = $memoryMB;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNpivWorldWideNameOp() {
		return $this->npivWorldWideNameOp;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNpivWorldWideNameOp($npivWorldWideNameOp) {
		$this->npivWorldWideNameOp = $npivWorldWideNameOp;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNumCoresPerSocket() {
		return $this->numCoresPerSocket;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNumCoresPerSocket($numCoresPerSocket) {
		$this->numCoresPerSocket = $numCoresPerSocket;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNumCPUs() {
		return $this->numCPUs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNumCPUs($numCPUs) {
		$this->numCPUs = $numCPUs;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineDefaultPowerOpInfo
	 */
	public function &getPowerOpInfo() {
		return $this->powerOpInfo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPowerOpInfo(&$powerOpInfo) {
		$this->powerOpInfo = $powerOpInfo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVAppConfigRemoved() {
		return $this->vAppConfigRemoved;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVAppConfigRemoved($vAppConfigRemoved) {
		$this->vAppConfigRemoved = $vAppConfigRemoved;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVirtualICH7MPresent() {
		return $this->virtualICH7MPresent;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVirtualICH7MPresent($virtualICH7MPresent) {
		$this->virtualICH7MPresent = $virtualICH7MPresent;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVirtualSMCPresent() {
		return $this->virtualSMCPresent;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVirtualSMCPresent($virtualSMCPresent) {
		$this->virtualSMCPresent = $virtualSMCPresent;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVmProfile() {
		return $this->vmProfile;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVmProfile($vmProfile) {
		$this->vmProfile = $vmProfile;
		return $this;
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
