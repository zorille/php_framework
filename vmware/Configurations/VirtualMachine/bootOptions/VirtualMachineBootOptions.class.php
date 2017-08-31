<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualMachineBootOptions<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineBootOptions extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $bootDelay = "";
	/**
	 * var privee
	 * tableau de VirtualMachineBootOptionsBootableDevice
	 * @access private
	 * @var array
	 */
	private $bootOrder = array ();
	/**
	 * var privee
	 * @access private
	 * @var long
	 */
	private $bootRetryDelay = 0;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $bootRetryEnabled = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $enterBIOSSetup = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineBootOptions.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineBootOptions
	 */
	static function &creer_VirtualMachineBootOptions(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachineBootOptions ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineBootOptions
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
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if ( $this->getBootDelay () ) {
			$liste_proprietes ["bootDelay"] = $this->getBootDelay ();
		}
		if ( $this->getBootOrder () ) {
			$liste_proprietes ["bootOrder"] = $this->getBootOrder ();
		}
		if ( $this->getBootRetryDelay () ) {
			$liste_proprietes ["bootRetryDelay"] = $this->getBootRetryDelay ();
		}
		if ( $this->getBootRetryEnabled () ) {
			$liste_proprietes ["bootRetryEnabled"] = $this->getBootRetryEnabled ();
		}
		if ( $this->getEnterBIOSSetup () ) {
			$liste_proprietes ["enterBIOSSetup"] = $this->getEnterBIOSSetup ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualMachineBootOptions" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getBootDelay() {
		return $this->bootDelay;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBootDelay($alternateGuestName) {
		$this->bootDelay = $alternateGuestName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getBootOrder() {
		return $this->bootOrder;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBootOrder($bootOrder) {
		$this->bootOrder = $bootOrder;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getBootRetryDelay() {
		return $this->bootRetryDelay;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBootRetryDelay($bootRetryDelay) {
		$this->bootRetryDelay = $bootRetryDelay;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &getBootRetryEnabled() {
		return $this->bootRetryEnabled;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setBootRetryEnabled($bootRetryEnabled) {
		$this->bootRetryEnabled = $bootRetryEnabled;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &getEnterBIOSSetup() {
		return $this->enterBIOSSetup;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setEnterBIOSSetup($enterBIOSSetup) {
		$this->enterBIOSSetup = $enterBIOSSetup;
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
