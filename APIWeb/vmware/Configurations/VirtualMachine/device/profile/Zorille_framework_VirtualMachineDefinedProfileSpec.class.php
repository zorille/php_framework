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
 * class VirtualMachineDefinedProfileSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineDefinedProfileSpec extends VirtualMachineProfileSpec {
	/**
	 * var privee
	 * @access private
	 * @var VirtualMachineProfileRawData
	 */
	private $profileData = NULL;
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $profileId = "";
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineDefinedProfileSpec.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineDefinedProfileSpec
	 */
	static function &creer_VirtualMachineDefinedProfileSpec(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachineDefinedProfileSpec ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineDefinedProfileSpec
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
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if ( $this->getProfileData () ) {
			$liste_proprietes ["profileData"] = $this->getProfileData ()->renvoi_donnees_soap(true);
		}
		if ( $this->getProfileId () ) {
			$liste_proprietes ["profileId"] = $this->getProfileId ();
		}
	
		if($arrayObject){
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy();
	}
	
	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject){
		$soap_var= new soapvar ( $this->renvoi_donnees_soap($arrayObject), SOAP_ENC_OBJECT, 'VirtualMachineDefinedProfileSpec' );
		return $soap_var;
	}
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachineProfileRawData
	 */
	public function &getProfileData() {
		return $this->profileData;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setProfileData(&$profileData) {
		$this->profileData = $profileData;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getProfileId() {
		return $this->profileId;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setProfileId($profileId) {
		$this->profileId = $profileId;
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
