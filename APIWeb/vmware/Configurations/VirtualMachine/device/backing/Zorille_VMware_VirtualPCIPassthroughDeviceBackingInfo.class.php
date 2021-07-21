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
 * class VirtualPCIPassthroughDeviceBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualPCIPassthroughDeviceBackingInfo extends VirtualDeviceDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $deviceId = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $id = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $systemId = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $vendorId = 0;
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualPCIPassthroughDeviceBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualPCIPassthroughDeviceBackingInfo
	 */
	static function &creer_VirtualPCIPassthroughDeviceBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualPCIPassthroughDeviceBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualPCIPassthroughDeviceBackingInfo
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
		if (empty ( $this->getDeviceId () )) {
			return $this->onError("Il faut un deviceId");
		}
		$liste_proprietes ["deviceId"] = $this->getDeviceId ();
		if (empty ( $this->getId () )) {
			return $this->onError("Il faut un id");
		}
		$liste_proprietes ["id"] = $this->getId ();
		if (empty ( $this->getSystemId () )) {
			return $this->onError("Il faut un systemId");
		}
		$liste_proprietes ["systemId"] = $this->getSystemId ();
		if (empty ( $this->getVendorId () )) {
			return $this->onError("Il faut un vendorId");
		}
		$liste_proprietes ["vendorId"] = $this->getVendorId ();
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualPCIPassthroughDeviceBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDeviceId() {
		return $this->deviceId;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeviceId($deviceId) {
		$this->deviceId = $deviceId;
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
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getSystemId() {
		return $this->systemId;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSystemId($systemId) {
		$this->systemId = $systemId;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getVendorId() {
		return $this->vendorId;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVendorId($vendorId) {
		$this->vendorId = $vendorId;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
