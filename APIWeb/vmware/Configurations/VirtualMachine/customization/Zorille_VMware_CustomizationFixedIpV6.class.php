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
 * class CustomizationFixedIpV6<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationFixedIpV6 extends CustomizationIpV6Generator {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $ipAddress = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $subnetMask = 0;
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationFixedIpV6.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationFixedIpV6
	 */
	static function &creer_CustomizationFixedIpV6(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new CustomizationFixedIpV6 ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationFixedIpV6
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
		$liste_proprietes = parent::renvoi_donnees_soap(true);
		if (empty ( $this->getAddress () )) {
			return $this->onError ( "Il faut une ipAddress" );
		}
		$liste_proprietes ["ipAddress"] = $this->getAddress ();
		if (empty ( $this->getSubnetMask () )) {
			return $this->onError ( "Il faut un subnetMask" );
		}
		$liste_proprietes ["subnetMask"] = $this->getSubnetMask ();
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationFixedIpV6" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getAddress() {
		return $this->ipAddress;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setAddress($ipAddress) {
		$this->ipAddress = $ipAddress;
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
