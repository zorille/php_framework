<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualDeviceDeviceBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualDeviceDeviceBackingInfo extends VirtualDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $deviceName = "";
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $useAutoDetect = false;


	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	 public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap(true);

		$liste_proprietes ["deviceName"] = $this->getDeviceName ();
		if ( $this->getUseAutoDetect () ) {
			$liste_proprietes ["useAutoDetect"] = $this->getUseAutoDetect ();
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
	public function getDeviceName() {
		return $this->deviceName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeviceName($deviceName) {
		$this->deviceName = $deviceName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUseAutoDetect() {
		return $this->useAutoDetect;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUseAutoDetect($useAutoDetect) {
		$this->useAutoDetect = $useAutoDetect;
		return $this;
	}
	
/************************* Accesseurs ***********************/
}

?>
