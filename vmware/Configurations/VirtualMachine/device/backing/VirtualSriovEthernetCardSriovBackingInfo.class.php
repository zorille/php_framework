<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualSriovEthernetCardSriovBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualSriovEthernetCardSriovBackingInfo extends VirtualDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var VirtualPCIPassthroughDeviceBackingInfo
	 */
	private $physicalFunctionBacking = NULL;
	/**
	 * var privee
	 * @access private
	 * @var VirtualPCIPassthroughDeviceBackingInfo
	 */
	private $virtualFunctionBacking = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $virtualFunctionIndex = 0;
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualSriovEthernetCardSriovBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualSriovEthernetCardSriovBackingInfo
	 */
	static function &creer_VirtualSriovEthernetCardSriovBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualSriovEthernetCardSriovBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualSriovEthernetCardSriovBackingInfo
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
		if ( $this->getPhysicalFunctionBacking () ) {
			$liste_proprietes ["physicalFunctionBacking"] = $this->getPhysicalFunctionBacking ()->renvoi_donnees_soap(true);
		}
		if (! $this->getVirtualFunctionBacking () ) {
			return $this->onError("Il faut un virtualFunctionBacking");
		}
		$liste_proprietes ["virtualFunctionBacking"] = $this->getVirtualFunctionBacking ()->renvoi_donnees_soap(true);
		if ( $this->getVirtualFunctionIndex () ) {
			$liste_proprietes ["virtualFunctionIndex"] = $this->getVirtualFunctionIndex ();
		}
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualSriovEthernetCardSriovBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return VirtualPCIPassthroughDeviceBackingInfo
	 */
	public function &getPhysicalFunctionBacking() {
		return $this->physicalFunctionBacking;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setPhysicalFunctionBacking($physicalFunctionBacking) {
		$this->physicalFunctionBacking = $physicalFunctionBacking;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &getVirtualFunctionBacking() {
		return $this->virtualFunctionBacking;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVirtualFunctionBacking($virtualFunctionBacking) {
		$this->virtualFunctionBacking = $virtualFunctionBacking;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getVirtualFunctionIndex() {
		return $this->virtualFunctionIndex;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVirtualFunctionIndex($virtualFunctionIndex) {
		$this->virtualFunctionIndex = $virtualFunctionIndex;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
