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
 * class CustomizationSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationSpec extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $encryptionKey = "";
	/**
	 * var privee
	 * @access private
	 * @var CustomizationGlobalIPSettings
	 */
	private $globalIPSettings = NULL;
	/**
	 * var privee
	 * @access private
	 * @var CustomizationIdentitySettings
	 */
	private $identity = NULL;
	/**
	 * var privee
	 * Liste de CustomizationAdapterMapping
	 * @access private
	 * @var array
	 */
	private $nicSettingMap = array ();
	/**
	 * var privee
	 * @access private
	 * @var CustomizationOptions
	 */
	private $options = NULL;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationSpec.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationSpec
	 */
	static function &creer_CustomizationSpec(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomizationSpec ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationSpec
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
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if ( $this->getEncryptionKey () ) {
			$liste_proprietes ["encryptionKey"] = $this->getEncryptionKey ();
		}
		if (! $this->getGlobalIPSettings () ) {
			return $this->onError ( "Il faut un globalIPSettings" );
		}
		$liste_proprietes ["globalIPSettings"] = $this->getGlobalIPSettings ()
			->renvoi_donnees_soap ( false );
		if (empty ( $this->getIdentity () )) {
			return $this->onError ( "Il faut une identity" );
		}
		$liste_proprietes ["identity"] = $this->getIdentity ()
			->renvoi_objet_soap ( false );
		if ( $this->getNicSettingMap () ) {
			$liste_proprietes ["nicSettingMap"] = $this->getNicSettingMap ();
		}
		if ( $this->getOptions () ) {
			$liste_proprietes ["options"] = $this->getOptions ()
				->renvoi_objet_soap ( false );
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationSpec" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getEncryptionKey() {
		return $this->encryptionKey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEncryptionKey($encryptionKey) {
		$this->encryptionKey = $encryptionKey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationGlobalIPSettings
	 */
	public function &getGlobalIPSettings() {
		return $this->globalIPSettings;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGlobalIPSettings(&$globalIPSettings) {
		$this->globalIPSettings = $globalIPSettings;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationIdentitySettings
	 */
	public function &getIdentity() {
		return $this->identity;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIdentity(&$identity) {
		$this->identity = $identity;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNicSettingMap() {
		return $this->nicSettingMap;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNicSettingMap($nicSettingMap) {
		$this->nicSettingMap = $nicSettingMap;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationOptions
	 */
	public function &getOptions() {
		return $this->options;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOptions(&$options) {
		$this->options = $options;
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
