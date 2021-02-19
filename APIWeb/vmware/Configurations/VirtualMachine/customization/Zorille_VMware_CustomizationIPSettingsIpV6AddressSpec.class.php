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
 * class CustomizationIPSettingsIpV6AddressSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationIPSettingsIpV6AddressSpec extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $gateway = array ();
	/**
	 * var privee
	 * Liste de CustomizationIpV6Generator
	 * @access private
	 * @var array
	 */
	private $ip = array();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationIPSettingsIpV6AddressSpec.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationIPSettingsIpV6AddressSpec
	 */
	static function &creer_CustomizationIPSettingsIpV6AddressSpec(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new CustomizationIPSettingsIpV6AddressSpec ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationIPSettingsIpV6AddressSpec
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
		if ( $this->getGateway () ) {
			$liste_proprietes ["gateway"] = $this->getGateway ();
		}
		if (empty ( $this->getIp () )) {
			return $this->onError ( "Il faut une ip" );
		}
		$liste_proprietes ["ip"] = $this->getIp ();
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationIPSettingsIpV6AddressSpec" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
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
	 */
	public function getIp() {
		return $this->ip;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIp($ip) {
		$this->ip = $ip;
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
