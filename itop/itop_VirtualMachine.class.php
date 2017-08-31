<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_VirtualMachine
 *
 * @package Lib
 * @subpackage itop
 */
class itop_VirtualMachine extends itop_FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_Hypervisor
	 */
	private $itop_Hypervisor = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_OSFamily
	 */
	private $itop_OSFamily = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_OSVersion
	 */
	private $itop_OSVersion = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_VirtualMachine. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_webservice_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_VirtualMachine
	 */
	static function &creer_itop_VirtualMachine(&$liste_option, &$itop_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_VirtualMachine ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"itop_wsclient_rest" => $itop_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_VirtualMachine
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'VirtualMachine' ) 
			->setObjetItopOrganization ( itop_Organization::creer_itop_Organization ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) ) 
			->setObjetItophypervisor ( itop_Hypervisor::creer_itop_Hypervisor ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) ) 
			->setObjetItopOSFamily ( itop_OSFamily::creer_itop_OSFamily ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) ) 
			->setObjetItopOSVersion ( itop_OSVersion::creer_itop_OSVersion ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_VirtualMachine($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

/*	public function creer_oql($name) {
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . " WHERE name='" . $name . "'" );
	}*/

	public function gestion_VirtualMachine($server_name, $org_name, $hyp_name, $os_type, $os_version, $status, $business_criticity, $managementip, $cpu, $mem, $move2production, $fqdn) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'org_id' => $this ->getObjetItopOrganization () 
					->creer_oql ( $org_name ) 
					->getOqlCi (), 
				'virtualhost_id' => $this ->getObjetItopHypervisor () 
					->creer_oql ( $hyp_name ) 
					->getOqlCi (), 
				'osfamily_id' => $this ->getObjetItopOSFamily () 
					->creer_oql ( $os_type ) 
					->getOqlCi (), 
				'osversion_id' => $this ->getObjetItopOSVersion () 
					->creer_oql ( $os_version ) 
					->getOqlCi (), 
				'name' => $server_name, 
				'status' => $status, 
				'business_criticity' => $business_criticity, 
				'managementip' => $managementip, 
				'cpu' => $cpu, 
				'ram' => $mem, 
				'move2production' => $move2production, 
				'description' => 'FQDN:' . $fqdn );
		return $this ->creer_oql ( $server_name ) 
			->creer_ci ( $server_name, $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * @codeCoverageIgnore
	 * @return itop_hypervisor
	 */
	public function &getObjetItophypervisor() {
		return $this->itop_hypervisor;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItophypervisor(&$itop_hypervisor) {
		$this->itop_hypervisor = $itop_hypervisor;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return itop_OSFamily
	 */
	public function &getObjetItopOSFamily() {
		return $this->itop_OSFamily;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOSFamily(&$itop_OSFamily) {
		$this->itop_OSFamily = $itop_OSFamily;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return itop_OSVersion
	 */
	public function &getObjetItopOSVersion() {
		return $this->itop_OSVersion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOSVersion(&$itop_OSVersion) {
		$this->itop_OSVersion = $itop_OSVersion;
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "itop_VirtualMachine :";
		
		return $help;
	}
}
?>
