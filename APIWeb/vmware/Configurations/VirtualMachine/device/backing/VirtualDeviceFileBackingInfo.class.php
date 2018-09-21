<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class VirtualDeviceFileBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class VirtualDeviceFileBackingInfo extends VirtualDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $backingObjectId = "";
	/**
	 * var privee
	 * ManagedObjectReference to a Datastore
	 * @access private
	 * @var array
	 */
	private $datastore=array();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $fileName = "";

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	 public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap(true);
		if (empty ( $this->getFileName () )) {
			return $this->onError("Il faut un fileName");
		}
		$liste_proprietes ["fileName"] = $this->getFileName ();
		if ( $this->getDatastore () ) {
			$liste_proprietes ["datastore"] = VirtualMachineCommun::retrouve_valeur_MOR($this->getDatastore ());
		}
		if ( $this->getBackingObjectId () ) {
			$liste_proprietes ["backingObjectId"] = $this->getBackingObjectId ();
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
	public function getBackingObjectId() {
		return $this->backingObjectId;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setBackingObjectId($backingObjectId) {
		$this->backingObjectId = $backingObjectId;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getDatastore() {
		return $this->datastore;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatastore($datastore) {
		$this->datastore = $datastore;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFileName($fileName) {
		$this->fileName = $fileName;
		return $this;
	}

/************************* Accesseurs ***********************/
}

?>
