<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class CustomizationIPSettings<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationIPSettings extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $dnsDomain = "";
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $dnsServerList = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $gateway = array ();
	/**
	 * var privee
	 * @access private
	 * @var CustomizationIpGenerator
	 */
	private $ip = NULL;
	/**
	 * var privee
	 * @access private
	 * @var CustomizationIPSettingsIpV6AddressSpec
	 */
	private $ipV6Spec = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $netBIOS = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $primaryWINS = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $secondaryWINS = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $subnetMask = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationIPSettings.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationIPSettings
	 */
	static function &creer_CustomizationIPSettings(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new CustomizationIPSettings ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationIPSettings
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/*********************** Creation de l'objet *********************/
	
	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if ( $this->getDnsDomain () ) {
			$liste_proprietes ["dnsDomain"] = $this->getDnsDomain ();
		}
		if ( $this->getDnsServerList () ) {
			$liste_proprietes ["dnsServerList"] = $this->getDnsServerList ();
		}
		if ( $this->getGateway () ) {
			$liste_proprietes ["gateway"] = $this->getGateway ();
		}
		if (! $this->getIp () ) {
			return $this->onError ( "Il faut une ip" );
		}
		$liste_proprietes ["ip"] = $this->getIp ()
			->renvoi_objet_soap ( false );
		if ( $this->getIpV6Spec () ) {
			$liste_proprietes ["ipV6Spec"] = $this->getIpV6Spec ()
				->renvoi_objet_soap ( false );
		}
		if ( $this->getNetBIOS () ) {
			$liste_proprietes ["netBIOS"] = $this->getNetBIOS ();
		}
		if ( $this->getPrimaryWINS () ) {
			$liste_proprietes ["primaryWINS"] = $this->getPrimaryWINS ();
		}
		if ( $this->getSecondaryWINS () ) {
			$liste_proprietes ["secondaryWINS"] = $this->getSecondaryWINS ();
		}
		if ( $this->getSubnetMask () ) {
			$liste_proprietes ["subnetMask"] = $this->getSubnetMask ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject = false) {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationIPSettings" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDnsDomain() {
		return $this->dnsDomain;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDnsDomain($dnsDomain) {
		$this->dnsDomain = $dnsDomain;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDnsServerList() {
		return $this->dnsServerList;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDnsServerList($dnsServerList) {
		$this->dnsServerList = $dnsServerList;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGateway() {
		return $this->gateway;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGateway($gateway) {
		$this->gateway = $gateway;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationIpGenerator
	 */
	public function &getIp() {
		return $this->ip;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIp(&$ip) {
		$this->ip = $ip;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationIPSettingsIpV6AddressSpec
	 */
	public function &getIpV6Spec() {
		return $this->ipV6Spec;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIpV6Spec(&$ipV6Spec) {
		$this->ipV6Spec = $ipV6Spec;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNetBIOS() {
		return $this->netBIOS;
	}

	/**
	 * disableNetBIOS/enableNetBIOS/enableNetBIOSViaDhcp
	 * @codeCoverageIgnore
	 */
	public function &setNetBIOS($netBIOS) {
		switch ($netBIOS) {
			case 'disableNetBIOS' :
			case 'enableNetBIOS' :
			case 'enableNetBIOSViaDhcp' :
				$this->netBIOS = $netBIOS;
				break;
			default :
				$this->netBIOS = "";
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrimaryWINS() {
		return $this->primaryWINS;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPrimaryWINS($primaryWINS) {
		$this->primaryWINS = $primaryWINS;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSecondaryWINS() {
		return $this->secondaryWINS;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSecondaryWINS($secondaryWINS) {
		$this->secondaryWINS = $secondaryWINS;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getSubnetMask() {
		return $this->subnetMask;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSubnetMask($subnetMask) {
		$this->subnetMask = $subnetMask;
		return $this;
	}
	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

?>
