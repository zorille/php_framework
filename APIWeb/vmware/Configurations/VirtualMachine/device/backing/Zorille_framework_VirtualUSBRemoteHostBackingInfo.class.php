<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualUSBRemoteHostBackingInfo<br>
 * The VirtualUSBRemoteHostBackingInfo data object identifies a host and a USB device that is attached to the host. Use this object to configure support for persistent access to the USB device when vMotion operations migrate a virtual machine to a different host. The vCenter Server will not migrate the virtual machine to a host that does not support the USB remote host backing capability.
 * 
 * Specify remote host backing as part of the USB device configuration when you create or reconfigure a virtual machine (VirtualMachineConfigSpec.deviceChange.device.backing).
 * 
 * To identify the USB device, you specify an autoconnect pattern for the deviceName. The virtual machine can connect to the USB device if the ESX server can find a USB device described by the autoconnect pattern. The autoconnect pattern consists of name:value pairs. You can use any combination of the following fields.
 * 
 *     path - USB connection path on the host
 *     pid - idProduct field in the USB device descriptor
 *     vid - idVendor field in the USB device descriptor
 *     hostId - unique ID for the host
 *     speed - device speed (low, full, or high)
 * 
 * For example, the following pattern identifies a USB device:
 * 
 *     "path:1/3/0 hostId:44\ 45\ 4c\ 43\ 00\ 10\ 54-80\ 35\ ca\ c0\ 4f\ 4d\ 37\ 31"
 * 
 * This pattern identifies the USB device connected to port 1/3/0 on the host with the unique id 0x44454c4c430010548035cac04f4d3731.
 * 
 * Special characters for autoconnect pattern values:
 * 
 *     The name and value are separated by a colon (:).
 *     Name:value pairs are separated by spaces.
 *     The escape character is a backslash (\). Use a single backslash to embed a space in a value. Use a double blackslash to embed a single backslash in the value.
 * 
 * @package Lib
 * @subpackage VMWare
 */
class VirtualUSBRemoteHostBackingInfo extends VirtualDeviceDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $hostname = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualUSBRemoteHostBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualUSBRemoteHostBackingInfo
	 */
	static function &creer_VirtualUSBRemoteHostBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualUSBRemoteHostBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualUSBRemoteHostBackingInfo
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if (empty ( $this->getHostname () )) {
			return $this->onError("Il faut un hostname");
		}
		$liste_proprietes ["hostname"] = $this->getHostname ();
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject = false) {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualUSBRemoteHostBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getHostname() {
		return $this->hostname;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostname($hostname) {
		$this->hostname = $hostname;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
