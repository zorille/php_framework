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
 * class VirtualDisk<br>
 * @package Lib
 * @subpackage VMWare
 */
/**
 * Supported virtual disk backings:
 * Sparse disk format, version 1 and 2
 *     The virtual disk backing grows when needed. Supported only for VMware Server.
 * Flat disk format, version 1 and 2
 *     The virtual disk backing is preallocated. Version 1 is supported only for VMware Server.
 * Space efficient sparse disk format
 *     The virtual disk backing grows on demand and incorporates additional space optimizations.
 * Raw disk format, version 2
 *     The virtual disk backing uses a full physical disk drive to back the virtual disk. Supported only for VMware Server.
 * Partitioned raw disk format, version 2
 *     The virtual disk backing uses one or more partitions on a physical disk drive to back a virtual disk. Supported only for VMware Server.
 * Raw disk mapping, version 1
 *     The virtual disk backing uses a raw device mapping to back the virtual disk. Supported for ESX Server 2.5 and 3.x.
 *
 */
class VirtualDisk extends VirtualDevice {
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $capacityInBytes = 0;
	/**
	 * var privee
	 * deprecated mais obligatoire :|
	 * @access private
	 * @var integer
	 */
	private $capacityInKB = 0;
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $diskObjectId = "";
	/**
	 * var privee
	 * @access private
	 * @var SharesInfo
	 */
	private $shares = NULL;
	/**
	 * var privee
	 * @access private
	 * @var StorageIOAllocationInfo
	 */
	private $storageIOAllocation = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualDiskVFlashCacheConfigInfo
	 */
	private $vFlashCacheConfigInfo = NULL;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDisk.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDisk
	 */
	static function &creer_VirtualDisk(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualDisk ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDisk
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
		if ( $this->getCapacityInBytes () ) {
			$liste_proprietes ["capacityInBytes"] = $this->getCapacityInBytes ();
			$liste_proprietes ["capacityInKB"] = round($this->getCapacityInBytes ()/1024);
		}elseif (! $this->getCapacityInKB () ) {
			return $this->onError("Il faut definir une capacite au disque");
		} else {
			$liste_proprietes ["capacityInKB"] = $this->getCapacityInKB ();
		}
		if ( $this->getDiskObjectId () ) {
			$liste_proprietes ["diskObjectId"] = $this->getDiskObjectId ();
		}
		if ( $this->getShares () ) {
			$liste_proprietes ["shares"] = $this->getShares ()->renvoi_donnees_soap(false);
		}
		if ( $this->getStorageIOAllocation () ) {
			$liste_proprietes ["storageIOAllocation"] = $this->getStorageIOAllocation ()->renvoi_donnees_soap(false);
		}
		if ( $this->getVFlashCacheConfigInfo () ) {
			$liste_proprietes ["vFlashCacheConfigInfo"] = $this->getVFlashCacheConfigInfo ()->renvoi_donnees_soap(false);
		}
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, 'VirtualDisk' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getCapacityInBytes() {
		return $this->capacityInBytes;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCapacityInBytes($capacityInBytes) {
		$this->capacityInBytes = $capacityInBytes;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getCapacityInKB() {
		return $this->capacityInKB;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCapacityInKB($capacityInKB) {
		$this->capacityInKB = $capacityInKB;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getDiskObjectId() {
		return $this->diskObjectId;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setDiskObjectId($diskObjectId) {
		$this->diskObjectId = $diskObjectId;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return SharesInfo
	 */
	public function &getShares() {
		return $this->shares;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setShares(&$shares) {
		$this->shares = $shares;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return StorageIOAllocationInfo
	 */
	public function &getStorageIOAllocation() {
		return $this->storageIOAllocation;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setStorageIOAllocation(&$storageIOAllocation) {
		$this->storageIOAllocation = $storageIOAllocation;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return VirtualDiskVFlashCacheConfigInfo
	 */
	public function &getVFlashCacheConfigInfo() {
		return $this->vFlashCacheConfigInfo;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVFlashCacheConfigInfo(&$vFlashCacheConfigInfo) {
		$this->vFlashCacheConfigInfo = $vFlashCacheConfigInfo;
		return $this;
	}

/************************* Accesseurs ***********************/
}

?>
