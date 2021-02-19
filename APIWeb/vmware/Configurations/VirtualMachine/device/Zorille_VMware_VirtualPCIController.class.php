<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \Exception as Exception;
use \soapvar as soapvar;
/**
 * class VirtualPCIController<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualPCIController extends VirtualController {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualPCIController.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualPCIController
	 */
	static function &creer_VirtualPCIController(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualPCIController ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualPCIController
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualPCIController' );
		return $soap_var;
	}
/************************* Methodes VMWare ***********************/

/************************* Accesseurs ***********************/
/************************* Accesseurs ***********************/
}

?>
