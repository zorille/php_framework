<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualDeviceURIBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDeviceURIBackingInfo extends VirtualDeviceDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $direction = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $proxyURI = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $serviceURI = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDeviceURIBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDeviceURIBackingInfo
	 */
	static function &creer_VirtualDeviceURIBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualDeviceURIBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDeviceURIBackingInfo
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
		if (empty ( $this->getDirection () )) {
			return $this->onError("Il faut une direction");
		}
		$liste_proprietes ["direction"] = $this->getDirection ();
		if ( $this->getProxyURI () ) {
			$liste_proprietes ["proxyURI"] = $this->getProxyURI ();
		}
		if (empty ( $this->getServiceURI () )) {
			return $this->onError("Il faut un serviceURI");
		}
		$liste_proprietes ["serviceURI"] = $this->getServiceURI ();
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualDeviceURIBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 * client/server
	 * @codeCoverageIgnore
	 */
	public function &setDirection($direction) {
		switch ($direction) {
			case 'client' :
			case 'server' :
				$this->direction = $direction;
				break;
			default :
				$this->direction = "";
		}
		
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getProxyURI() {
		return $this->proxyURI;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setProxyURI($proxyURI) {
		$this->proxyURI = $proxyURI;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getServiceURI() {
		return $this->serviceURI;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setServiceURI($serviceURI) {
		$this->serviceURI = $serviceURI;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
