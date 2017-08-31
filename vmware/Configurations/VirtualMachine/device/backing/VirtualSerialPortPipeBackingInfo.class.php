<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualSerialPortPipeBackingInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualSerialPortPipeBackingInfo extends VirtualDevicePipeBackingInfo {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $endpoint = "";
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $noRxLoss = false;
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualSerialPortPipeBackingInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualSerialPortPipeBackingInfo
	 */
	static function &creer_VirtualSerialPortPipeBackingInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualSerialPortPipeBackingInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualSerialPortPipeBackingInfo
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
		if (! $this->getEndpoint () ) {
			return $this->onError("Il faut un endpoint");
		}
		$liste_proprietes ["endpoint"] = $this->getEndpoint ();
		if ( $this->getNoRxLoss () ) {
			$liste_proprietes ["noRxLoss"] = $this->getNoRxLoss ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "VirtualSerialPortPipeBackingInfo" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getEndpoint() {
		return $this->endpoint;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setEndpoint($endpoint) {
		$this->endpoint = $endpoint;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getNoRxLoss() {
		return $this->noRxLoss;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setNoRxLoss($noRxLoss) {
		$this->noRxLoss = $noRxLoss;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
