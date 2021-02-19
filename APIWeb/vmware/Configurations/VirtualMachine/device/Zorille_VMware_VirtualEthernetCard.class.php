<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualEthernetCard<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualEthernetCard extends VirtualDevice {
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $addressType = "Assigned";
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $macAddress = "";
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $wakeOnLanEnabled = false;

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	 public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if ( $this->getAddressType () ) {
			$liste_proprietes ["addressType"] = $this->getAddressType ();
		}
		if ( $this->getMacAddress () ) {
			$liste_proprietes ["macAddress"] = $this->getMacAddress ();
		}
		if ( $this->getWakeOnLanEnabled () ) {
			$liste_proprietes ["wakeOnLanEnabled"] = $this->getWakeOnLanEnabled ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @return soapvar
	 */
	 public function &renvoi_objet_soap() {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualEthernetCard' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	 public function getAddressType() {
		return $this->addressType;
	}

	/**
	 * Manual/Generated/Assigned
	 * @codeCoverageIgnore
	 */
	 public function &setAddressType($addressType) {
		switch ($addressType) {
			case 'Manual' :
			case 'Generated' :
				$this->addressType = $addressType;
				break;
			case 'Assigned' :
			default :
				$this->addressType = "Assigned";
		}
		
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	 public function getMacAddress() {
		return $this->macAddress;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	 public function &setMacAddress($macAddress) {
		$this->macAddress = $macAddress;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	 public function getWakeOnLanEnabled() {
		return $this->wakeOnLanEnabled;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	 public function &setWakeOnLanEnabled($wakeOnLanEnabled) {
		$this->wakeOnLanEnabled = $wakeOnLanEnabled;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
