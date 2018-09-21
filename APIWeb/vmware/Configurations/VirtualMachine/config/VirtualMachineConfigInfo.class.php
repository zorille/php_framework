<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class VirtualMachineConfigInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineConfigInfo extends VirtualMachineConfig {
	/**
	 * var privee
	 * tableau de VirtualMachineConfigInfoDatastoreUrlPair
	 * @access private
	 * @var array
	 */
	private $datastoreUrl = array ();
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineDefaultPowerOpInfo
	 */
	private $defaultPowerOps = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $guestFullName = "";
	/**
	 * var privee
	 * @access private
	 * @var VirtualHardware
	 */
	private $hardware = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $hotPlugMemoryIncrementSize = 0;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $hotPlugMemoryLimit = 0;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineConfigInfoOverheadInfo
	 */
	private $initialOverhead = NULL;
	/**
	 * var privee dateTime
	 * @access private
	 * @var string
	 */
	private $modified = "";
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $template = false;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $vFlashCacheReservation = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineConfigInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineConfigInfo
	 */
	static function &creer_VirtualMachineConfigInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachineConfigInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineConfigInfo
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
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if ( $this->getDatastoreUrl () ) {
			$liste_proprietes ["datastoreUrl"] = $this->getDatastoreUrl ();
		}
		if ( $this->getDefaultPowerOps () ) {
			//objet
			$liste_proprietes ["defaultPowerOps"] = $this->getDefaultPowerOps ();
		}
		if ( $this->getGuestFullName () ) {
			$liste_proprietes ["guestFullName"] = $this->getGuestFullName ();
		}
		if ( $this->getHardware () ) {
			//Objet
			$liste_proprietes ["hardware"] = $this->getHardware ();
		}
		if ( $this->etHotPlugMemoryIncrementSize () ) {
			$liste_proprietes ["hotPlugMemoryIncrementSize"] = $this->etHotPlugMemoryIncrementSize ();
		}
		if ( $this->etHotPlugMemoryLimit () ) {
			$liste_proprietes ["hotPlugMemoryLimit"] = $this->etHotPlugMemoryLimit ();
		}
		if ( $this->etInitialOverhead () ) {
			//Objet
			$liste_proprietes ["initialOverhead"] = $this->etInitialOverhead ();
		}
		if ( $this->getModified () ) {
			$liste_proprietes ["modified"] = $this->getModified ();
		}
		if ( $this->getTemplate () ) {
			$liste_proprietes ["template"] = $this->getTemplate ();
		}
		if ( $this->getVFlashCacheReservation () ) {
			//Objet
			$liste_proprietes ["vFlashCacheReservation"] = $this->getVFlashCacheReservation ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject = false) {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualMachineConfigInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDatastoreUrl() {
		return $this->datastoreUrl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatastoreUrl($datastoreUrl) {
		$this->datastoreUrl = $datastoreUrl;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineDefaultPowerOpInfo
	 */
	public function &getDefaultPowerOps() {
		return $this->defaultPowerOps;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDefaultPowerOps(&$defaultPowerOps) {
		$this->defaultPowerOps = $defaultPowerOps;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGuestFullName() {
		return $this->guestFullName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGuestFullName($guestFullName) {
		$this->guestFullName = $guestFullName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualHardware
	 */
	public function &getHardware() {
		return $this->hardware;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHardware(&$hardware) {
		$this->hardware = $hardware;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function etHotPlugMemoryIncrementSize() {
		return $this->hotPlugMemoryIncrementSize;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHotPlugMemoryIncrementSize($hotPlugMemoryIncrementSize) {
		$this->hotPlugMemoryIncrementSize = $hotPlugMemoryIncrementSize;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function etHotPlugMemoryLimit() {
		return $this->hotPlugMemoryLimit;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHotPlugMemoryLimit($hotPlugMemoryLimit) {
		$this->hotPlugMemoryLimit = $hotPlugMemoryLimit;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineConfigInfoOverheadInfo
	 */
	public function &etInitialOverhead() {
		return $this->initialOverhead;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInitialOverhead(&$initialOverhead) {
		$this->initialOverhead = $initialOverhead;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getModified() {
		return $this->modified;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setModified($modified) {
		$this->modified = $modified;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTemplate($template) {
		$this->template = $template;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVFlashCacheReservation() {
		return $this->vFlashCacheReservation;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVFlashCacheReservation($vFlashCacheReservation) {
		$this->vFlashCacheReservation = $vFlashCacheReservation;
		return $this;
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

?>
