<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class VirtualMachineVMCIDevice<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineVMCIDevice extends VirtualDevice {
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $allowUnrestrictedCommunication = false;
	/**
	 * var privee
	 * @access private
	 * @var Integer
	 */
	private $id = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineVMCIDevice.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineVMCIDevice
	 */
	static function &creer_VirtualMachineVMCIDevice(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachineVMCIDevice ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineVMCIDevice
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if ( $this->getAllowUnrestrictedCommunication () ) {
			$liste_proprietes ["allowUnrestrictedCommunication"] = $this->getAllowUnrestrictedCommunication ();
		}
		if ( $this->getId () ) {
			$liste_proprietes ["id"] = $this->getId ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @return soapvar
	 */
	public function &renvoi_objet_soap() {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualMachineVMCIDevice' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getAllowUnrestrictedCommunication() {
		return $this->allowUnrestrictedCommunication;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAllowUnrestrictedCommunication($allowUnrestrictedCommunication) {
		$this->allowUnrestrictedCommunication = $allowUnrestrictedCommunication;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setId($id) {
		$this->id = $id;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
