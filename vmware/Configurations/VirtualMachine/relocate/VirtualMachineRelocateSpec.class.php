<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualMachineRelocateSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineRelocateSpec extends VirtualMachineCommun {
	/**
	 * var privee
	 * ManagedObjectReference to a Datastore
	 * @access private
	 * @var array
	 */
	private $datastore = array ();
	/**
	 * var privee
	 * tableau de VirtualDeviceConfigSpec
	 * @access private
	 * @var array
	 */
	private $deviceChange = array ();
	/**
	 * var privee
	 * tableau de VirtualMachineRelocateSpecDiskLocator
	 * @access private
	 * @var array
	 */
	private $disk = array ();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $diskMoveType = "";
	/**
	 * var privee
	 * ManagedObjectReference to a Host
	 * @access private
	 * @var array
	 */
	private $host = array ();
	/**
	 * var privee
	 * ManagedObjectReference to a ResourcePool
	 * @access private
	 * @var array
	 */
	private $pool = array ();
	/**
	 * var privee
	 * tableau de VirtualMachineProfileSpec
	 * @access private
	 * @var array
	 */
	private $profile = array ();
	/**
	 * var privee
	 * @deprecated
	 * @access private
	 * @var string
	 */
	private $transform = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineRelocateSpec.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineRelocateSpec
	 */
	static function &creer_VirtualMachineRelocateSpec(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachineRelocateSpec ( $entete, $sort_en_erreur );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineRelocateSpec
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
		if ( $this->getDatastore () ) {
			$liste_proprietes ["datastore"] = $this->retrouve_valeur_MOR ( $this->getDatastore () );
		}
		if ( $this->getDeviceChange () ) {
			$liste_proprietes ["deviceChange"] = $this->getDeviceChange ();
		}
		if ( $this->getDisk () ) {
			$liste_proprietes ["disk"] = $this->getDisk ();
		}
		if ( $this->getDiskMoveType () ) {
			$liste_proprietes ["diskMoveType"] = $this->getDiskMoveType ();
		}
		if ( $this->getHost () ) {
			$liste_proprietes ["host"] = $this->retrouve_valeur_MOR ( $this->getHost () );
		}
		if ( $this->getPool () ) {
			$liste_proprietes ["pool"] = $this->retrouve_valeur_MOR ( $this->getPool () );
		}
		if ( $this->getProfile () ) {
			$liste_proprietes ["profile"] = $this->getProfile ();
		}
		if ( $this->getTransform () ) {
			$liste_proprietes ["transform"] = $this->getTransform ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualMachineRelocateSpec" );
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
	 */
	public function getDeviceChange() {
		return $this->deviceChange;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeviceChange($deviceChange) {
		$this->deviceChange = $deviceChange;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDisk() {
		return $this->disk;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDisk($disk) {
		$this->disk = $disk;
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
	public function getHost() {
		return $this->host;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHost($host) {
		$this->host = $host;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPool() {
		return $this->pool;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPool($pool) {
		$this->pool = $pool;
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

	/**
	 * @codeCoverageIgnore
	 */
	public function getTransform() {
		return $this->transform;
	}

	/**
	 * flat/sparse
	 * @codeCoverageIgnore
	 */
	public function &setTransform($transform) {
		switch ($transform) {
			case 'flat' :
			case 'sparse' :
				$this->transform = $transform;
				break;
			default :
				$this->transform = "";
		}
		
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
