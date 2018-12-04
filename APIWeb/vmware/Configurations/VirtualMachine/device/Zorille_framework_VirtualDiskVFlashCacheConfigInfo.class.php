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
 * class VirtualDiskVFlashCacheConfigInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDiskVFlashCacheConfigInfo extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $blockSizeInKB = 0;
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $cacheConsistencyType = "";
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $cacheMode = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $reservationInMB = 0;
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $vFlashModule = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDiskVFlashCacheConfigInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDiskVFlashCacheConfigInfo
	 */
	static function &creer_VirtualDiskVFlashCacheConfigInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualDiskVFlashCacheConfigInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDiskVFlashCacheConfigInfo
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
		$liste_proprietes = new ArrayObject();
		if ( $this->getBlockSizeInKB () ) {
			$liste_proprietes ["blockSizeInKB"] = $this->getBlockSizeInKB ();
		}
		if ( $this->getCacheConsistencyType () ) {
			$liste_proprietes ["cacheConsistencyType"] = $this->getCacheConsistencyType ();
		}
		if ( $this->getCacheMode () ) {
			$liste_proprietes ["cacheMode"] = $this->getCacheMode ();
		}
		if ( $this->getReservationInMB () ) {
			$liste_proprietes ["reservationInMB"] = $this->getReservationInMB ();
		}
		if ( $this->getVFlashModule () ) {
			$liste_proprietes ["vFlashModule"] = $this->getVFlashModule ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualDiskVFlashCacheConfigInfo' );
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
	public function getBlockSizeInKB() {
		return $this->blockSizeInKB;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setBlockSizeInKB($blockSizeInKB) {
		$this->blockSizeInKB = $blockSizeInKB;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCacheConsistencyType() {
		return $this->cacheConsistencyType;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCacheConsistencyType($cacheConsistencyType) {
		$this->cacheConsistencyType = $cacheConsistencyType;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getCacheMode() {
		return $this->cacheMode;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCacheMode($cacheMode) {
		$this->cacheMode = $cacheMode;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getReservationInMB() {
		return $this->reservationInMB;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setReservationInMB($reservationInMB) {
		$this->reservationInMB = $reservationInMB;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getVFlashModule() {
		return $this->vFlashModule;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVFlashModule($vFlashModule) {
		$this->vFlashModule = $vFlashModule;
		return $this;
	}

/************************* Accesseurs ***********************/
}

?>
