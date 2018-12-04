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
 * class CustomizationSysprep<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationSysprep extends CustomizationIdentitySettings {
	/**
	 * var privee
	 * @access private
	 * @var CustomizationGuiRunOnce
	 */
	private $guiRunOnce = NULL;
	/**
	 * var privee
	 * @access private
	 * @var CustomizationGuiUnattended
	 */
	private $guiUnattended = NULL;
	/**
	 * var privee
	 * @access private
	 * @var CustomizationIdentification
	 */
	private $identification = NULL;
	/**
	 * var privee
	 * @access private
	 * @var CustomizationLicenseFilePrintData
	 */
	private $licenseFilePrintData = NULL;
	/**
	 * var privee
	 * @access private
	 * @var CustomizationUserData
	 */
	private $userData = NULL;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationSysprep.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationSysprep
	 */
	static function &creer_CustomizationSysprep(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomizationSysprep ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationSysprep
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

	/*********************** Creation de l'objet *********************/
	
	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if (empty ( $this->getGuiUnattended () )) {
			return $this->onError ( "Il faut un guiUnattended" );
		}
		$liste_proprietes ["guiUnattended"] = $this->getGuiUnattended ()
			->renvoi_objet_soap ( false );
		if (! $this->getUserData () ) {
			return $this->onError ( "Il faut un userData" );
		}
		$liste_proprietes ["userData"] = $this->getUserData ()
			->renvoi_objet_soap ( false );
		if ( $this->getGuiRunOnce () ) {
			$liste_proprietes ["guiRunOnce"] = $this->getGuiRunOnce ()
				->renvoi_donnees_soap ( true );
		}
		if (! $this->getIdentification () ) {
			return $this->onError ( "Il faut une identification" );
		}
		$liste_proprietes ["identification"] = $this->getIdentification ()
			->renvoi_objet_soap ( false );
		if ( $this->getLicenseFilePrintData () ) {
			$liste_proprietes ["licenseFilePrintData"] = $this->getLicenseFilePrintData ()
				->renvoi_donnees_soap ( false );
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationSysprep" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return CustomizationGuiRunOnce
	 */
	public function &getGuiRunOnce() {
		return $this->guiRunOnce;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGuiRunOnce(&$guiRunOnce) {
		$this->guiRunOnce = $guiRunOnce;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationGuiUnattended
	 */
	public function &getGuiUnattended() {
		return $this->guiUnattended;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGuiUnattended(&$guiUnattended) {
		$this->guiUnattended = $guiUnattended;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationIdentification
	 */
	public function &getIdentification() {
		return $this->identification;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIdentification(&$identification) {
		$this->identification = $identification;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationLicenseFilePrintData
	 */
	public function &getLicenseFilePrintData() {
		return $this->licenseFilePrintData;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLicenseFilePrintData(&$licenseFilePrintData) {
		$this->licenseFilePrintData = $licenseFilePrintData;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationUserData
	 */
	public function &getUserData() {
		return $this->userData;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUserData(&$userData) {
		$this->userData = $userData;
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
