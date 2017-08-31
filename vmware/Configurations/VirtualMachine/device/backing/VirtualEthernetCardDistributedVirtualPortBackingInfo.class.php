<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualEthernetCardDistributedVirtualPortBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualEthernetCardDistributedVirtualPortBackingInfo extends VirtualDeviceBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var DistributedVirtualSwitchPortConnection
	 */
	private $port = NULL;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualEthernetCardDistributedVirtualPortBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualEthernetCardDistributedVirtualPortBackingInfo
	 */
	static function &creer_VirtualEthernetCardDistributedVirtualPortBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualEthernetCardDistributedVirtualPortBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualEthernetCardDistributedVirtualPortBackingInfo
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
		if (empty ( $this->getPort () )) {
			return $this->onError ( "Il faut un port" );
		}
		$liste_proprietes ["port"] = $this->getPort ()
			->renvoi_donnees_soap ( false );
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualEthernetCardDistributedVirtualPortBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return DistributedVirtualSwitchPortConnection
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPort($port) {
		$this->port = $port;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
