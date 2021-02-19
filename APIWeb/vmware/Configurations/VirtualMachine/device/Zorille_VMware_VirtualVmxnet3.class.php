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
 * class VirtualVmxnet3<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualVmxnet3 extends VirtualEthernetCard {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualVmxnet3.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualVmxnet3
	 */
	static function &creer_VirtualVmxnet3(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualVmxnet3 ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualVmxnet3
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualVmxnet3' );
		return $soap_var;
	}
/************************* Methodes VMWare ***********************/

/************************* Accesseurs ***********************/
/************************* Accesseurs ***********************/
}

?>
