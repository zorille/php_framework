<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework\options as options;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualDiskSparseVer1BackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDiskSparseVer1BackingInfo extends VirtualDeviceFileBackingInfo {
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
	private $diskMode = "";
	/**
	 * var privee
	 * @access private
	 * @var VirtualDiskSparseVer1BackingInfo
	 */
	private $parent = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $spaceUsedInKB = 0;
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
	private $writeThrough = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDiskSparseVer1BackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDiskSparseVer1BackingInfo
	 */
	static function &creer_VirtualDiskSparseVer1BackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualDiskSparseVer1BackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDiskSparseVer1BackingInfo
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
		if ( $this->getContentId () ) {
			$liste_proprietes ["contentId"] = $this->getContentId ();
		}
		if (! $this->getDiskMode () ) {
			return $this->onError ( "Il faut un diskMode" );
		}
		$liste_proprietes ["diskMode"] = $this->getDiskMode ();
		if ( $this->getParent () ) {
			$liste_proprietes ["parent"] = $this->getParent ()
				->renvoi_donnees_soap ( true );
		}
		if ( $this->getSpaceUsedInKB () ) {
			$liste_proprietes ["spaceUsedInKB"] = $this->getSpaceUsedInKB ();
		}
		if ( $this->getSplit () ) {
			$liste_proprietes ["split"] = $this->getSplit ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualDiskSparseVer1BackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
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
	 * @return VirtualDiskSparseVer1BackingInfo
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
	public function getSpaceUsedInKB() {
		return $this->spaceUsedInKB;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSpaceUsedInKB($spaceUsedInKB) {
		$this->spaceUsedInKB = $spaceUsedInKB;
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
