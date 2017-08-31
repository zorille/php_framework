<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualDiskRawDiskMappingVer1BackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDiskRawDiskMappingVer1BackingInfo extends VirtualDeviceFileBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $changeId = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $compatibilityMode = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $contentId = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $deviceName = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $diskMode = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $lunUuid = "";
	/**
	 * var privee
	 * @access private
	 * @var VirtualDiskRawDiskMappingVer1BackingInfo
	 */
	private $parent = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $uuid = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDiskRawDiskMappingVer1BackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDiskRawDiskMappingVer1BackingInfo
	 */
	static function &creer_VirtualDiskRawDiskMappingVer1BackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualDiskRawDiskMappingVer1BackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDiskRawDiskMappingVer1BackingInfo
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
		if ( $this->getChangeId () ) {
			$liste_proprietes ["changeId"] = $this->getChangeId ();
		}
		if ( $this->getCompatibilityMode () ) {
			$liste_proprietes ["compatibilityMode"] = $this->getCompatibilityMode ();
		}
		if ( $this->getContentId () ) {
			$liste_proprietes ["contentId"] = $this->getContentId ();
		}
		if ( $this->getDeviceName () ) {
			$liste_proprietes ["deviceName"] = $this->getDeviceName ();
		}
		if ( $this->getDiskMode () ) {
			$liste_proprietes ["diskMode"] = $this->getDiskMode ();
		}
		if ( $this->getLunUuid () ) {
			$liste_proprietes ["lunUuid"] = $this->getLunUuid ();
		}
		if ( $this->getParent () ) {
			$liste_proprietes ["parent"] = $this->getParent ()
				->renvoi_donnees_soap ( true );
		}
		if ( $this->getUuid () ) {
			$liste_proprietes ["uuid"] = $this->getUuid ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualDiskRawDiskMappingVer1BackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getChangeId() {
		return $this->changeId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setChangeId($changeId) {
		$this->changeId = $changeId;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getCompatibilityMode() {
		return $this->compatibilityMode;
	}
	
	/**
	 * physicalMode/virtualMode
	 * @codeCoverageIgnore
	 */
	public function &setCompatibilityMode($compatibilityMode) {
		switch ($compatibilityMode) {
			case 'physicalMode' :
			case 'virtualMode' :
				$this->compatibilityMode = $compatibilityMode;
				break;
			default :
				$this->compatibilityMode = "";
		}
	
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getContentId() {
		return $this->contentId;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setContentId($contentId) {
		$this->contentId = $contentId;
		return $this;
	}
	
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
	public function getDiskMode() {
		return $this->diskMode;
	}

	/**
	 * persistent/independent_persistent/independent_nonpersistent/nonpersistent/
	 * undoable/append
	 * @codeCoverageIgnore
	 */
	public function &setDiskMode($diskMode) {
		switch ($diskMode) {
			case 'persistent' :
			case 'independent_persistent' :
			case 'independent_nonpersistent' :
			case 'nonpersistent' :
			case 'undoable' :
			case 'append' :
				$this->diskMode = $diskMode;
				break;
			default :
				$this->diskMode = "";
		}
		
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getLunUuid() {
		return $this->lunUuid;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setLunUuid($lunUuid) {
		$this->lunUuid = $lunUuid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualDiskRawDiskMappingVer1BackingInfo
	 */
	public function &getParent() {
		return $this->parent;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setParent($parent) {
		$this->parent = $parent;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getUuid() {
		return $this->uuid;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setUuid($uuid) {
		$this->uuid = $uuid;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
