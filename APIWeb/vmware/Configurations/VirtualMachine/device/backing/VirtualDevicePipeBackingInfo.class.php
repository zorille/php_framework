<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class VirtualDevicePipeBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualDevicePipeBackingInfo extends VirtualDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $pipeName = "";


	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	 public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap(true);
		if (empty ( $this->getPipeName () )) {
			return $this->onError("Il faut un pipeName");
		}
		$liste_proprietes ["pipeName"] = $this->getPipeName ();
	
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
	public function getPipeName() {
		return $this->pipeName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPipeName($pipeName) {
		$this->pipeName = $pipeName;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
