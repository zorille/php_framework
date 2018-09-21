<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class VirtualController<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualController extends VirtualDevice {
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $busNumber = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $device = 0;

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	 public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap(true);
		if (! is_numeric($this->getBusNumber ())){
			return $this->onError("Il faut un busNumber");
		}
		$liste_proprietes ["busNumber"] = $this->getBusNumber ();

		if ( $this->getDevice () ) {
			$liste_proprietes ["device"] = $this->getDevice ();
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
	 public function &renvoi_objet_soap(){
		$soap_var= new soapvar ( $this->renvoi_donnees_soap(true), SOAP_ENC_OBJECT, 'VirtualController' );
		return $soap_var;
	}
	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getBusNumber() {
		return $this->busNumber;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public  function &setBusNumber($busNumber) {
		$this->busNumber = $busNumber;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	 public function getDevice() {
		return $this->device;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	 public function &setDevice($device) {
		$this->device = $device;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
