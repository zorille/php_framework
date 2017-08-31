<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class cacti_addTree<br>
 *
 * Prepare une ligne de commande de generation.
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_hosts extends parametresStandard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $hosts = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $hosts_by_ip = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_hosts.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_hosts
	 */
	static function &creer_cacti_hosts(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_hosts ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_hosts
	 */
	public function &_initialise($liste_class) {
		parent::_initialise($liste_class);
		
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de cacti_globals
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		$this->charge_hosts();
	}
	
	/**
	 * Charge la liste des hosts via l'API Cacti
	 */
	public function charge_hosts(){
		$this->onDebug ( "On charge la liste des hosts.", 1 );
		//fonction de l'API cacti : lib/api_automation_tools.php
		$this->setHosts ( getHosts () );
		
		$this->onDebug ( "getAddresses", 1 );
		//fonction de l'API cacti : lib/api_automation_tools.php
		$this->setHostsByIPs ( getAddresses () );
		
		return $this;
	}
	
	/**
	 * Valide qu'un host existe.
	 *
	 * @return boolean True le tree existe, false le tree n'existe pas.
	 */
	public function valide_host_by_id($host_id) {
		$Hosts = $this->gethosts ();
		if (isset ( $Hosts [$host_id] )) {
			return true;
		}
	
		return false;
	}
	
	/**
	 * Valide qu'un host existe.
	 *
	 * @return boolean True le host existe, false le host n'existe pas.
	 */
	public function valide_host_by_description($description) {
		$this->onDebug ( "Valide host par description.", 2);
		foreach($this->getHosts() as $host_data){
			if(in_array($description, $host_data)){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Renvoi l'id d'un host si il existe.
	 *
	 * @return Integer/false L'id du host, false le host n'existe pas.
	 */
	public function renvoi_hostid_by_description($description) {
		$this->onDebug ( "On renvoi le hostid par description.", 2);
		foreach($this->getHosts() as $ID=>$host_data){
			if(in_array($description, $host_data)){
				return $ID;
			}
		}
		return false;
	}
	
	/**
	 * Valide qu'un host existe.
	 *
	 * @return boolean True le tree existe, false le tree n'existe pas.
	 */
	public function valide_host_by_ip($ip) {
		$this->onDebug ( "Valide host par IP.", 2);
		$Hosts = $this->getHostsByIPs ();
		if (isset ( $Hosts [$ip] )) {
			return true;
		}
	
		return false;
	}
	
	/**
	 * Renvoi l'id d'un host si l'ip existe.
	 *
	 * @return Integer/false L'id du host, false le host n'existe pas.
	 */
	public function renvoi_hostid_by_ip($ip) {
		$this->onDebug ( "On renvoi le host par IP.", 2);
		if($this->valide_host_by_ip($ip)){
			$hosts = $this->getHostsByIPs();
			return $hosts[$ip];
		}
		return false;
	}
	

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getHosts() {
		return $this->hosts;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function getOneHosts($host) {
		// Puis on renvoi le resultat de la demande
		foreach($this->hosts as $host_data){
			if(isset($host_data["description"]) && $host_data["description"]==$host){
				return $host_data;
			}
		}
		return false;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setHosts($hosts) {
		if (is_array ( $hosts )) {
			$this->hosts = $hosts;
		} else {
			return $this->onError ( "Il faut un tableau de hosts." );
		}
		return true;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function ajouteHosts($description, $device_id) {
		//puis on ajoute le nouveau
		if ($description != "" && $device_id != "") {
			$this->hosts [$device_id] = array("id"=>$device_id,"description"=>$description);
		} else {
			return $this->onError ( "Il faut une description et/ou un device_id." );
		}
		return true;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function getHostsByIPs($ip = "all") {
		if (isset ( $this->hosts_by_ip [$ip] )) {
			return $this->hosts_by_ip [$ip];
		}
		return $this->hosts_by_ip;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setHostsByIPs($liste_ips) {
		if (is_array ( $liste_ips )) {
			$this->hosts_by_ip = $liste_ips;
		} else {
			return $this->onError ( "Il faut un tableau d'ip." );
		}
		return true;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function ajouteHostsByIPs($ip, $device_id) {
		if ($ip != "" && $device_id != "") {
			$this->hosts_by_ip [$ip] = $device_id;
		} else {
			return $this->onError ( "Il faut une ip et/ou un device_id." );
		}
		return true;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
}
?>
