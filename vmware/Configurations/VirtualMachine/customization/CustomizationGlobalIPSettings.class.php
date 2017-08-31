<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class CustomizationGlobalIPSettings<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationGlobalIPSettings extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $dnsServerList = array();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $dnsSuffixList = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationGlobalIPSettings.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationGlobalIPSettings
	 */
	static function &creer_CustomizationGlobalIPSettings(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomizationGlobalIPSettings ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationGlobalIPSettings
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
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if ( $this->getDnsServerList () ) {
			$liste_proprietes ["dnsServerList"] = $this->getDnsServerList ();
		}
		if ( $this->getDnsSuffixList () ) {
			$liste_proprietes ["dnsSuffixList"] = $this->getDnsSuffixList ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationGlobalIPSettings" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
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
	public function getDnsSuffixList() {
		return $this->dnsSuffixList;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDnsSuffixList($dnsSuffixList) {
		$this->dnsSuffixList = $dnsSuffixList;
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
