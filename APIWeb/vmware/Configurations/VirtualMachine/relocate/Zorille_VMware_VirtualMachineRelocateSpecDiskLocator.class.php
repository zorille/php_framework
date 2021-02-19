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
 * class VirtualMachineRelocateSpecDiskLocator<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineRelocateSpecDiskLocator extends VirtualMachineCommun {
	/**
	 * var privee
	 * ManagedObjectReference to a Datastore
	 * @access private
	 * @var array
	 */
	private $datastore = array ();
	/**
	 * var privee
	 * @access private
	 * @var VirtualDeviceBackingInfo
	 */
	private $diskBackingInfo = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $diskId = 0;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $diskMoveType = "";
	/**
	 * var privee
	 * tableau de VirtualMachineProfileSpec
	 * @access private
	 * @var array
	 */
	private $profile = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineRelocateSpecDiskLocator.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineRelocateSpecDiskLocator
	 */
	static function &creer_VirtualMachineRelocateSpecDiskLocator(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualMachineRelocateSpecDiskLocator ( $entete, $sort_en_erreur );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineRelocateSpecDiskLocator
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de VirtualMachineCommun
		parent::__construct ( $sort_en_erreur, $entete );
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
		$liste_proprietes = new ArrayObject ();
		if (empty ( $this->getDatastore () )) {
			return $this->onError("Il faut un MOR datastore");
		}
		$liste_proprietes ["datastore"] = $this->retrouve_valeur_MOR ( $this->getDatastore () );
		if ( $this->getDiskBackingInfo () ) {
			$liste_proprietes ["diskBackingInfo"] = $this->getDiskBackingInfo ()
				->renvoi_objet_soap ( false );
		}
		if (empty ( $this->getDiskId () )) {
			return $this->onError("Il faut un diskId");
		}
		$liste_proprietes ["diskId"] = $this->getDiskId ();
		if ( $this->getDiskMoveType () ) {
			$liste_proprietes ["diskMoveType"] = $this->getDiskMoveType ();
		}
		if ( $this->getProfile () ) {
			$liste_proprietes ["profile"] = $this->getProfile ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualMachineRelocateSpecDiskLocator" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
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
	 * @return VirtualDeviceBackingInfo
	 */
	public function &getDiskBackingInfo() {
		return $this->diskBackingInfo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDiskBackingInfo(&$diskBackingInfo) {
		$this->diskBackingInfo = $diskBackingInfo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDiskId() {
		return $this->diskId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDiskId($diskId) {
		$this->diskId = $diskId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDiskMoveType() {
		return $this->diskMoveType;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDiskMoveType($diskMoveType) {
		$this->diskMoveType = $diskMoveType;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getProfile() {
		return $this->profile;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProfile($profile) {
		$this->profile = $profile;
		return $this;
	}

/************************* Accesseurs ***********************/
}

?>
