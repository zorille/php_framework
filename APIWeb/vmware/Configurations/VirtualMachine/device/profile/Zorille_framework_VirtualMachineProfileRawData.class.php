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
 * class VirtualMachineProfileRawData<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineProfileRawData extends VirtualMachineProfileSpec {
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $extensionKey = "";
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $objectData = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineProfileRawData.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineProfileRawData
	 */
	static function &creer_VirtualMachineProfileRawData(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachineProfileRawData ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineProfileRawData
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
		if ( $this->getExtensionKey () ) {
			$liste_proprietes ["extensionKey"] = $this->getExtensionKey ();
		}
		if ( $this->getObjectData () ) {
			$liste_proprietes ["objectData"] = $this->getObjectData ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject){
		$soap_var= new soapvar ( $this->renvoi_donnees_soap($arrayObject), SOAP_ENC_OBJECT, 'VirtualMachineProfileRawData' );
		return $soap_var;
	}

	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getExtensionKey() {
		return $this->extensionKey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setExtensionKey($extensionKey) {
		$this->extensionKey = $extensionKey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getObjectData() {
		return $this->objectData;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectData($objectData) {
		$this->objectData = $objectData;
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
