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
 * class VirtualEthernetCardOpaqueNetworkBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualEthernetCardOpaqueNetworkBackingInfo extends VirtualDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $opaqueNetworkId = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $opaqueNetworkType = "";
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualEthernetCardOpaqueNetworkBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualEthernetCardOpaqueNetworkBackingInfo
	 */
	static function &creer_VirtualEthernetCardOpaqueNetworkBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualEthernetCardOpaqueNetworkBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualEthernetCardOpaqueNetworkBackingInfo
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
		$liste_proprietes = new ArrayObject ();
		if (empty ( $this->getOpaqueNetworkId () )) {
			return $this->onError("Il faut un opaqueNetworkId");
		}
		$liste_proprietes ["opaqueNetworkId"] = $this->getOpaqueNetworkId ();
		if (empty ( $this->getOpaqueNetworkType () )) {
			return $this->onError("Il faut un opaqueNetworkType");
		}
		$liste_proprietes ["opaqueNetworkType"] = $this->getOpaqueNetworkType ();
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualEthernetCardOpaqueNetworkBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getOpaqueNetworkId() {
		return $this->opaqueNetworkId;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpaqueNetworkId($opaqueNetworkId) {
		$this->opaqueNetworkId = $opaqueNetworkId;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getOpaqueNetworkType() {
		return $this->opaqueNetworkType;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpaqueNetworkType($opaqueNetworkType) {
		$this->opaqueNetworkType = $opaqueNetworkType;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
