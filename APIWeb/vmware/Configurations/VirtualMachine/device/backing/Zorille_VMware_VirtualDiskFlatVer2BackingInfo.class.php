<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualDiskFlatVer2BackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDiskFlatVer2BackingInfo extends VirtualDeviceFileBackingInfo {
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
	private $contentId = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $deltaDiskFormat = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $deltaGrainSize = 0;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $digestEnabled = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $diskMode = "";
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $eagerlyScrub = false;
	/**
	 * var privee
	 * @access private
	 * @var VirtualDiskFlatVer2BackingInfo
	 */
	private $parent = NULL;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $split = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $thinProvisioned = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $uuid = "";
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $writeThrough = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDiskFlatVer2BackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDiskFlatVer2BackingInfo
	 */
	static function &creer_VirtualDiskFlatVer2BackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualDiskFlatVer2BackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDiskFlatVer2BackingInfo
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
		if ( $this->getContentId () ) {
			$liste_proprietes ["contentId"] = $this->getContentId ();
		}
		if ( $this->getDeltaDiskFormat () ) {
			$liste_proprietes ["deltaDiskFormat"] = $this->getDeltaDiskFormat ();
		}
		if ( $this->getDeltaGrainSize () ) {
			$liste_proprietes ["deltaGrainSize"] = $this->getDeltaGrainSize ();
		}
		if ( $this->getDigestEnabled () ) {
			$liste_proprietes ["digestEnabled"] = $this->getDigestEnabled ();
		}
		if (! $this->getDiskMode () ) {
			return $this->onError ( "Il faut un diskMode" );
		}
		$liste_proprietes ["diskMode"] = $this->getDiskMode ();
		if ( $this->getEagerlyScrub () ) {
			$liste_proprietes ["eagerlyScrub"] = $this->getEagerlyScrub ();
		}
		if ( $this->getParent () ) {
			$liste_proprietes ["parent"] = $this->getParent ()
				->renvoi_donnees_soap ( true );
		}
		if ( $this->getSplit () ) {
			$liste_proprietes ["split"] = $this->getSplit ();
		}
		if ( $this->getThinProvisioned () ) {
			$liste_proprietes ["thinProvisioned"] = $this->getThinProvisioned ();
		}
		if ( $this->getUuid () ) {
			$liste_proprietes ["uuid"] = $this->getUuid ();
		}
		if ( $this->getWriteThrough () ) {
			$liste_proprietes ["writeThrough"] = $this->getWriteThrough ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualDiskFlatVer2BackingInfo" );
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
	public function getDeltaDiskFormat() {
		return $this->deltaDiskFormat;
	}
	
	/**
	 * nativeFormat/redoLogFormat/seSparseFormat
	 * @codeCoverageIgnore
	 */
	public function &setDeltaDiskFormat($deltaDiskFormat) {
		switch ($deltaDiskFormat) {
			case 'nativeFormat' :
			case 'redoLogFormat' :
			case 'seSparseFormat' :
				$this->deltaDiskFormat = $deltaDiskFormat;
				break;
			default :
				$this->deltaDiskFormat = "";
		}
	
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getDeltaGrainSize() {
		return $this->deltaGrainSize;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeltaGrainSize($deltaGrainSize) {
		$this->deltaGrainSize = $deltaGrainSize;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getDigestEnabled() {
		return $this->digestEnabled;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setDigestEnabled($digestEnabled) {
		$this->digestEnabled = $digestEnabled;
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
	public function getEagerlyScrub() {
		return $this->eagerlyScrub;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setEagerlyScrub($eagerlyScrub) {
		$this->eagerlyScrub = $eagerlyScrub;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualDiskFlatVer2BackingInfo
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
	public function getSplit() {
		return $this->split;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSplit($split) {
		$this->split = $split;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getThinProvisioned() {
		return $this->thinProvisioned;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setThinProvisioned($thinProvisioned) {
		$this->thinProvisioned = $thinProvisioned;
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

	/**
	 * @codeCoverageIgnore
	 */
	public function getWriteThrough() {
		return $this->writeThrough;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWriteThrough($writeThrough) {
		$this->writeThrough = $writeThrough;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
