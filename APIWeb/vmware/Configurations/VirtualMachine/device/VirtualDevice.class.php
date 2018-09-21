<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class VirtualDevice<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualDevice extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var VirtualDeviceBackingInfo
	 */
	private $backing = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualDeviceConnectInfo
	 */
	private $connectable = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $controllerKey = 0;
	/**
	 * var privee
	 * @access private
	 * @var Vmware_Description
	 */
	private $deviceInfo = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $key = 0;
	/**
	 * var privee
	 * @access private
	 * @var VirtualDeviceBusSlotInfo
	 */
	private $slotInfo = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $unitNumber = "";

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if ( $this->getKey () ) {
			$liste_proprietes ["key"] = $this->getKey ();
		}
		if ( $this->getBacking () ) {
			$liste_proprietes ["backing"] = $this->getBacking ()
				->renvoi_objet_soap ( false );
		}
		if ( $this->getConnectable () ) {
			$liste_proprietes ["connectable"] = $this->getConnectable ()
				->renvoi_objet_soap ( false );
		}
		if ( $this->getControllerKey () ) {
			$liste_proprietes ["controllerKey"] = $this->getControllerKey ();
		}
		if ( $this->getDeviceInfo () ) {
			$liste_proprietes ["deviceInfo"] = $this->getDeviceInfo ()
				->renvoi_donnees_soap ( false );
		}
		if ( $this->getSlotInfo () ) {
			$liste_proprietes ["slotInfo"] = $this->getSlotInfo ()
				->renvoi_donnees_soap ( false );
		}
		if (is_numeric ( $this->getUnitNumber () )) {
			$liste_proprietes ["unitNumber"] = $this->getUnitNumber ();
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
	 * @return VirtualDeviceBackingInfo
	 */
	public function &getBacking() {
		return $this->backing;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBacking(&$backing) {
		$this->backing = $backing;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualDeviceConnectInfo
	 */
	public function &getConnectable() {
		return $this->connectable;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnectable(&$connectable) {
		$this->connectable = $connectable;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getControllerKey() {
		return $this->controllerKey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setControllerKey($controllerKey) {
		$this->controllerKey = $controllerKey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return Vmware_Description
	 */
	public function &getDeviceInfo() {
		return $this->deviceInfo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeviceInfo(&$deviceInfo) {
		$this->deviceInfo = $deviceInfo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setKey($key) {
		$this->key = $key;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualDeviceBusSlotInfo
	 */
	public function &getSlotInfo() {
		return $this->slotInfo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSlotInfo(&$slotInfo) {
		$this->slotInfo = $slotInfo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUnitNumber() {
		return $this->unitNumber;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUnitNumber($unitNumber) {
		$this->unitNumber = $unitNumber;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
