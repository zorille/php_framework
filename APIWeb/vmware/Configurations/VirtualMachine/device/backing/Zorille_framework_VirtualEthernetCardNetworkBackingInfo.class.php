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
 * class VirtualEthernetCardNetworkBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualEthernetCardNetworkBackingInfo extends VirtualDeviceDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @deprecated
	 * @var boolean
	 */
	private $inPassthroughMode = false;
	/**
	 * var privee
	 * ManagedObjectReference to a Network
	 * @access private
	 * @var array
	 */
	private $network=array();
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualEthernetCardNetworkBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualEthernetCardNetworkBackingInfo
	 */
	static function &creer_VirtualEthernetCardNetworkBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualEthernetCardNetworkBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualEthernetCardNetworkBackingInfo
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
		if ( $this->getInPassthroughMode () ) {
			$liste_proprietes ["inPassthroughMode"] = $this->getInPassthroughMode ();
		}
		if (! $this->getNetwork () ) {
			return $this->onError("Il faut un ManagedObjectReference de type network");
		}
		$liste_proprietes ["network"]=VirtualMachineCommun::retrouve_valeur_MOR($this->getNetwork ());
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualEthernetCardNetworkBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getInPassthroughMode() {
		return $this->inPassthroughMode;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setInPassthroughMode($inPassthroughMode) {
		$this->inPassthroughMode = $inPassthroughMode;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getNetwork() {
		return $this->network;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setNetwork($network) {
		$this->network = $network;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
