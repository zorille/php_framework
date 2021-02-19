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
 * class VirtualMachineCloneSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineCloneSpec extends VirtualMachineCommun {
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineConfigSpec
	 */
	private $config = NULL;
	/**
	 * var privee
	 * @access private
	 * @var CustomizationSpec
	 */
	private $customization = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineRelocateSpec
	 */
	private $location = NULL;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $memory = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $powerOn = false;
	/**
	 * var privee
	 * ManagedObjectReference to a VirtualMachineSnapshot
	 * @access private
	 * @var array
	 */
	private $snapshot = array ();
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $template = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineCloneSpec.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineCloneSpec
	 */
	static function &creer_VirtualMachineCloneSpec(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new VirtualMachineCloneSpec ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineCloneSpec
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
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if ( $this->getConfig () ) {
			$liste_proprietes ["config"] = $this->getConfig ()
				->renvoi_donnees_soap ( false );
		}
		if ( $this->getCustomization () ) {
			$liste_proprietes ["customization"] = $this->getCustomization ()
				->renvoi_donnees_soap ( false );
		}
		if (empty ( $this->getLocation () )) {
			return $this->onError ( "Il faut une location" );
		}
		$liste_proprietes ["location"] = $this->getLocation ()
			->renvoi_donnees_soap ( false );
		if ( $this->getMemory () ) {
			$liste_proprietes ["memory"] = $this->getMemory ();
		}
		$liste_proprietes ["powerOn"] = $this->getPowerOn ();
		if ( $this->getSnapshot () ) {
			$liste_proprietes ["snapshot"] = $this->retrouve_valeur_MOR ( $this->getSnapshot () );
		}
		$liste_proprietes ["template"] = $this->getTemplate ();
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualMachineCloneSpec" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineConfigSpec
	 */
	public function &getConfig() {
		return $this->config;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConfig(&$config) {
		$this->config = $config;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationSpec
	 */
	public function &getCustomization() {
		return $this->customization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCustomization(&$customization) {
		$this->customization = $customization;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineRelocateSpec
	 */
	public function &getLocation() {
		return $this->location;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLocation(&$location) {
		$this->location = $location;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMemory() {
		return $this->memory;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMemory($memory) {
		$this->memory = $memory;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPowerOn() {
		return $this->powerOn;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPowerOn($powerOn) {
		$this->powerOn = $powerOn;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnapshot() {
		return $this->snapshot;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnapshot($snapshot) {
		$this->snapshot = $snapshot;
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
