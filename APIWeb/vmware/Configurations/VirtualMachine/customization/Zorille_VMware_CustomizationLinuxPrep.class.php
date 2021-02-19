<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class CustomizationLinuxPrep<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationLinuxPrep extends CustomizationIdentitySettings {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $domain = "";
	/**
	 * var privee
	 * @access private
	 * @var CustomizationName
	 */
	private $hostName = NULL;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $hwClockUTC = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $timeZone = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationLinuxPrep.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationLinuxPrep
	 */
	static function &creer_CustomizationLinuxPrep(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new CustomizationLinuxPrep ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationLinuxPrep
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
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if (empty ( $this->getHostName () )) {
			return $this->onError ( "Il faut un hostName" );
		}
		$liste_proprietes ["hostName"] = $this->getHostName ()
			->renvoi_objet_soap ( false );
		if (empty ( $this->getDomain () )) {
			return $this->onError ( "Il faut un domain" );
		}
		$liste_proprietes ["domain"] = $this->getDomain ();
		if ( $this->etHwClockUTC () ) {
			$liste_proprietes ["hwClockUTC"] = $this->etHwClockUTC ();
		}
		if ( $this->getTimeZone () ) {
			//Le webservice renvoie une erreur sur le champ timezone
			//$liste_proprietes ["timeZone"] = $this->getTimeZone ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationLinuxPrep" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDomain($domain) {
		$this->domain = $domain;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationName
	 */
	public function &getHostName() {
		return $this->hostName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostName(&$hostName) {
		$this->hostName = $hostName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function etHwClockUTC() {
		return $this->hwClockUTC;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHwClockUTC($hwClockUTC) {
		$this->hwClockUTC = $hwClockUTC;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTimeZone() {
		return $this->timeZone;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimeZone($timeZone) {
		$this->timeZone = $timeZone;
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
