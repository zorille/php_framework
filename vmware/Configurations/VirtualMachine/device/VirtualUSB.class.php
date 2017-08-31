<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualUSB<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualUSB extends VirtualDevice {
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $connected = false;
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $family = "";
	/**
	 * var privee
	 * @access private
	 * @var Integer
	 */
	private $product = 0;
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $speed = "";
	/**
	 * var privee
	 * @access private
	 * @var Integer
	 */
	private $vendor = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualUSB.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualUSB
	 */
	static function &creer_VirtualUSB(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualUSB ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualUSB
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
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		$liste_proprietes ["connected"] = $this->getConnected ();
		if ( $this->getFamily () ) {
			$liste_proprietes ["family"] = $this->getFamily ();
		}
		if ( $this->getProduct () ) {
			$liste_proprietes ["product"] = $this->getProduct ();
		}
		if ( $this->getSpeed () ) {
			$liste_proprietes ["speed"] = $this->getSpeed ();
		}
		if ( $this->getVendor () ) {
			$liste_proprietes ["vendor"] = $this->getVendor ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualUSB' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getConnected() {
		return $this->connected;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnected($connected) {
		$this->connected = $connected;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFamily() {
		return $this->family;
	}

	/**
	 * audio/bluetooth/communication/hid/hid_bootable/hub/imaging/other
	 * pda/physical/printer/security/smart_card/storage/unknownFamily/vendor_specific
	 * video/wireless/wusb
	 * @codeCoverageIgnore
	 */
	public function &setFamily($family) {
		switch ($family) {
			case 'audio' :
			case 'bluetooth' :
			case 'communication' :
			case 'hid' :
			case 'hid_bootable' :
			case 'hub' :
			case 'imaging' :
			case 'other' :
			case 'pda' :
			case 'physical' :
			case 'printer' :
			case 'security' :
			case 'smart_card' :
			case 'storage' :
			case 'unknownFamily' :
			case 'vendor_specific' :
			case 'video' :
			case 'wireless' :
			case 'wusb' :
				$this->family = $family;
				break;
			default :
				$this->family = "";
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getProduct() {
		return $this->product;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProduct($product) {
		$this->product = $product;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSpeed() {
		return $this->speed;
	}

	/**
	 * full/high/low/superSpeed/unknownSpeed
	 * @codeCoverageIgnore
	 */
	public function &setSpeed($speed) {
		switch ($speed) {
			case 'full' :
			case 'high' :
			case 'low' :
			case 'superSpeed' :
			case 'unknownSpeed' :
				$this->speed = $speed;
				break;
			default :
				$this->speed = "";
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVendor() {
		return $this->vendor;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVendor($vendor) {
		$this->vendor = $vendor;
		return $this;
	}

/************************* Accesseurs ***********************/
}

?>
