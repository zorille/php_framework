<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualDevicePciBusSlotInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDevicePciBusSlotInfo extends VirtualDeviceBusSlotInfo {
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $pciSlotNumber = 0;
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDevicePciBusSlotInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDevicePciBusSlotInfo
	 */
	static function &creer_VirtualDevicePciBusSlotInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualDevicePciBusSlotInfo ( $entete, $sort_en_erreur );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDevicePciBusSlotInfo
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
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap(true);
		if (empty ( $this->getPciSlotNumber () )) {
			return $this->onError("Il faut un pciSlotNumber");
		}
		$liste_proprietes ["pciSlotNumber"] = $this->getPciSlotNumber ();
	
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualDevicePciBusSlotInfo' );
		return $soap_var;
	}
/************************* Methodes VMWare ***********************/

/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getPciSlotNumber() {
		return $this->pciSlotNumber;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setPciSlotNumber($pciSlotNumber) {
		$this->pciSlotNumber = $pciSlotNumber;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
