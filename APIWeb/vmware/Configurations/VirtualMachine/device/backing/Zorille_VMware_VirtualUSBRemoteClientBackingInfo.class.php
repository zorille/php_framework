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
 * class VirtualUSBRemoteClientBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualUSBRemoteClientBackingInfo extends VirtualDeviceRemoteDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $hostname = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualUSBRemoteClientBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualUSBRemoteClientBackingInfo
	 */
	static function &creer_VirtualUSBRemoteClientBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualUSBRemoteClientBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualUSBRemoteClientBackingInfo
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
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if (empty ( $this->getHostname () )) {
			return $this->onError("Il faut un hostname");
		}
		$liste_proprietes ["hostname"] = $this->getHostname ();
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject = false) {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualUSBRemoteClientBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getHostname() {
		return $this->hostname;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostname($hostname) {
		$this->hostname = $hostname;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
