<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework\options as options;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualUSBController<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualUSBController extends VirtualController {
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $autoConnectDevices = false;
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $ehciEnabled = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualUSBController.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualUSBController
	 */
	static function &creer_VirtualUSBController(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualUSBController ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualUSBController
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
	 * @throws Exception
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap(true);
		if ( $this->getAutoConnectDevices () ) {
			$liste_proprietes ["autoConnectDevices"] = $this->getAutoConnectDevices ();
		}
		if ( $this->getEhciEnabled () ) {
			$liste_proprietes ["ehciEnabled"] = $this->getEhciEnabled ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualUSBController' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getAutoConnectDevices() {
		return $this->autoConnectDevices;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAutoConnectDevices($autoConnectDevices) {
		$this->autoConnectDevices = $autoConnectDevices;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getEhciEnabled() {
		return $this->ehciEnabled;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setEhciEnabled($ehciEnabled) {
		$this->ehciEnabled = $ehciEnabled;
		return $this;
	}
	
/************************* Accesseurs ***********************/
}

?>
