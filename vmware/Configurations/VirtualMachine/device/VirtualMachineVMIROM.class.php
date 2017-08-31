<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualMachineVMIROM<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineVMIROM extends VirtualDevice {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineVMIROM.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineVMIROM
	 */
	static function &creer_VirtualMachineVMIROM(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachineVMIROM ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineVMIROM
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/************************* Methodes VMWare ***********************/
	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @return soapvar
	 */
	public function &renvoi_objet_soap() {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualMachineVMIROM' );
		return $soap_var;
	}
/************************* Methodes VMWare ***********************/

/************************* Accesseurs ***********************/
/************************* Accesseurs ***********************/
}

?>
