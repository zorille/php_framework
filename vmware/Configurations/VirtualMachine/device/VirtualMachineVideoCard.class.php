<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualMachineVideoCard<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineVideoCard extends VirtualDevice {
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $enable3DSupport = false;
	/**
	 * var privee
	 * @access private
	 * @var Integer
	 */
	private $numDisplays = 0;
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $use3dRenderer = "automatic";
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $useAutoDetect = false;
	/**
	 * var privee
	 * @access private
	 * @var Long
	 */
	private $videoRamSizeInKB = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineVideoCard.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineVideoCard
	 */
	static function &creer_VirtualMachineVideoCard(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachineVideoCard ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineVideoCard
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
		if ( $this->getEnable3DSupport () ) {
			$liste_proprietes ["enable3DSupport"] = $this->getEnable3DSupport ();
		}
		if ( $this->getNumDisplays () ) {
			$liste_proprietes ["numDisplays"] = $this->getNumDisplays ();
		}
		if ( $this->getUse3dRenderer () ) {
			$liste_proprietes ["use3dRenderer"] = $this->getUse3dRenderer ();
		}
		if ( $this->getUseAutoDetect () ) {
			$liste_proprietes ["useAutoDetect"] = $this->getUseAutoDetect ();
		}
		if ( $this->getVideoRamSizeInKB () ) {
			$liste_proprietes ["videoRamSizeInKB"] = $this->getVideoRamSizeInKB ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualMachineVideoCard' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getEnable3DSupport() {
		return $this->enable3DSupport;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEnable3DSupport($enable3DSupport) {
		$this->enable3DSupport = $enable3DSupport;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNumDisplays() {
		return $this->numDisplays;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNumDisplays($numDisplays) {
		$this->numDisplays = $numDisplays;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUse3dRenderer() {
		return $this->use3dRenderer;
	}

	/**
	 * automatic/software/hardware
	 * @codeCoverageIgnore
	 */
	public function &setUse3dRenderer($use3dRenderer) {
		switch ($use3dRenderer) {
			case 'software' :
			case 'hardware' :
				$this->use3dRenderer = $use3dRenderer;
				break;
			case 'automatic' :
			default :
				$this->use3dRenderer = "automatic";
		}
		
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getUseAutoDetect() {
		return $this->useAutoDetect;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setUseAutoDetect($useAutoDetect) {
		$this->useAutoDetect = $useAutoDetect;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getVideoRamSizeInKB() {
		return $this->videoRamSizeInKB;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVideoRamSizeInKB($videoRamSizeInKB) {
		$this->videoRamSizeInKB = $videoRamSizeInKB;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
