<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualDeviceConfigSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDeviceConfigSpec extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var VirtualDevice
	 */
	private $device = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $fileOperation = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $operation = "";
	/**
	 * var privee
	 * Liste de VirtualMachineProfileSpec
	 * @access private
	 * @var array
	 */
	private $profile = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDeviceConfigSpec.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDeviceConfigSpec
	 */
	static function &creer_VirtualDeviceConfigSpec(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualDeviceConfigSpec ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDeviceConfigSpec
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
		if ( $this->getOperation () ) {
			$liste_proprietes ["operation"] = $this->getOperation ();
		}
		if ( $this->getFileOperation () ) {
			$liste_proprietes ["fileOperation"] = $this->getFileOperation ();
		}
		if ( $this->getDevice () ) {
			$liste_proprietes ["device"] = $this->getDevice ()
				->renvoi_objet_soap ();
		}
		if ( $this->getProfile () ) {
			$liste_proprietes ["profile"] = array ();
			foreach ( $this->getProfile () as $VirtualMachineProfileSpec ) {
				$liste_proprietes ["profile"] [] = $VirtualMachineProfileSpec->renvoi_objet_soap ( false );
			}
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, 'VirtualDeviceConfigSpec' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return VirtualDevice
	 */
	public function &getDevice() {
		return $this->device;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDevice(&$device) {
		$this->device = $device;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFileOperation() {
		return $this->fileOperation;
	}

	/**
	 * create/destroy/replace
	 * @codeCoverageIgnore
	 */
	public function &setFileOperation($fileOperation) {
		switch ($fileOperation) {
			case 'create' :
			case 'destroy' :
			case 'replace' :
				$this->fileOperation = $fileOperation;
				break;
			default :
				$this->fileOperation = "";
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOperation() {
		return $this->operation;
	}

	/**
	 * add/edit/remove
	 * @codeCoverageIgnore
	 */
	public function &setOperation($operation) {
		switch ($operation) {
			case 'add' :
			case 'edit' :
			case 'remove' :
				$this->operation = $operation;
				break;
			default :
				$this->operation = "";
		}
		
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
