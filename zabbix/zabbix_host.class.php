<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_host
 * hostid 	string 	(readonly) ID of the host.
 * host (required) 	string 	Technical name of the host.
 * available 	integer 	(readonly) Availability of Zabbix agent.
 * 		Possible values are:
 * 		0 - (default) unknown;
 * 		1 - available;
 * 		2 - unavailable.
 * disable_until 	timestamp 	(readonly) The next polling time of an unavailable Zabbix agent.
 * error 	string 	(readonly) Error text if Zabbix agent is unavailable.
 * errors_from 	timestamp 	(readonly) Time when Zabbix agent became unavailable.
 * flags 	integer 	(readonly) Origin of the host.
 * 		Possible values:
 * 		0 - a plain host;
 * 		4 - a discovered host.
 * ipmi_authtype 	integer 	IPMI authentication algorithm.
 * 		Possible values are:
 * 		-1 - (default) default;
 * 		0 - none;
 * 		1 - MD2;
 * 		2 - MD5
 * 		4 - straight;
 * 		5 - OEM;
 * 		6 - RMCP+.
 * ipmi_available 	integer 	(readonly) Availability of IPMI agent.
 * 		Possible values are:
 * 		0 - (default) unknown;
 * 		1 - available;
 * 		2 - unavailable.
 * ipmi_disable_until 	timestamp 	(readonly) The next polling time of an unavailable IPMI agent.
 * ipmi_error 	string 	(readonly) Error text if IPMI agent is unavailable.
 * ipmi_errors_from 	timestamp 	(readonly) Time when IPMI agent became unavailable.
 * ipmi_password 	string 	IPMI password.
 * ipmi_privilege 	integer 	IPMI privilege level.
 * 		Possible values are:
 * 		1 - callback;
 * 		2 - (default) user;
 * 		3 - operator;
 * 		4 - admin;
 * 		5 - OEM.
 * ipmi_username 	string 	IPMI username.
 * jmx_available 	integer 	(readonly) Availability of JMX agent.
 * 		Possible values are:
 * 		0 - (default) unknown;
 * 		1 - available;
 * 		2 - unavailable.
 * jmx_disable_until 	timestamp 	(readonly) The next polling time of an unavailable JMX agent.
 * jmx_error 	string 	(readonly) Error text if JMX agent is unavailable.
 * jmx_errors_from 	timestamp 	(readonly) Time when JMX agent became unavailable.
 * maintenance_from 	timestamp 	(readonly) Starting time of the effective maintenance.
 * maintenance_status 	integer 	(readonly) Effective maintenance status.
 * 		Possible values are:
 * 		0 - (default) no maintenance;
 * 		1 - maintenance in effect.
 * maintenance_type 	integer 	(readonly) Effective maintenance type.
 * 		Possible values are:
 * 		0 - (default) maintenance with data collection;
 * 		1 - maintenance without data collection.
 * maintenanceid 	string 	(readonly) ID of the maintenance that is currently in effect on the host.
 * name 	string 	Visible name of the host.
 * 		Default: host property value.
 * proxy_hostid 	string 	ID of the proxy that is used to monitor the host.
 * snmp_available 	integer 	(readonly) Availability of SNMP agent.
 * 		Possible values are:
 * 		0 - (default) unknown;
 * 		1 - available;
 * 		2 - unavailable.
 * snmp_disable_until 	timestamp 	(readonly) The next polling time of an unavailable SNMP agent.
 * snmp_error 	string 	(readonly) Error text if SNMP agent is unavailable.
 * snmp_errors_from 	timestamp 	(readonly) Time when SNMP agent became unavailable.
 * status 	integer 	Status and function of the host.
 * 		Possible values are:
 * 		0 - (default) monitored host;
 * 		1 - unmonitored host. 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_host extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $hostId = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $host = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $name = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $proxy_hostid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_host_interfaces
	 */
	private $host_interfaces = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_host_hostgroups
	 */
	private $host_groups = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_host_templates
	 */
	private $host_templates = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $status = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_host.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_host
	 */
	static function &creer_zabbix_host(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_host ( $sort_en_erreur, $entete );
		return $objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"zabbix_wsclient" => $zabbix_ws ) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
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
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return boolean True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param() {
		$this ->onDebug ( __METHOD__, 1 );
		//Gestion d'un $host
		$this ->setHost ( $this ->_valideOption ( array ( 
				"zabbix", 
				"host", 
				"host" ) ) );
		$this ->setVisibleName ( $this ->_valideOption ( array ( 
				"zabbix", 
				"host", 
				"name" ) ) );
		$this ->setStatus ( $this ->_valideOption ( array ( 
				"zabbix", 
				"host", 
				"status" ), "monitored" ) );
		
		return $this;
	}

	/**
	 * Creer la definition JSON a transmettre au serveur Zabbix
	 * @return array
	 */
	public function creer_definition_host_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$host = array ( 
				"host" => $this ->getHost (), 
				"status" => $this ->getStatus () );
		if ($this ->getProxyId () != "") {
			$host ["proxy_hostid"] = $this ->getProxyId ();
		}
		$host ["interfaces"] = $this ->getObjetInterfaces () 
			->creer_definition_host_interfaces_ws ( true );
		$host ["groups"] = $this ->getObjetGroups () 
			->creer_definition_groupsids_ws ();
		$host ["templates"] = $this ->getObjetTemplates () 
			->creer_definition_templatesids_ws ();
		$host ["inventory"] = $this ->creer_definition_inventory_ws ();
		
		return $host;
	}

	/**
	 * Creer un host dans zabbix
	 * 
	 * @return array
	 */
	public function creer_host() {
		$this ->onDebug ( __METHOD__, 1 );
		$datas = $this ->creer_definition_host_ws ();
		$this ->onDebug ( $datas, 1 );
		return $this ->getObjetZabbixWsclient () 
			->hostCreate ( $datas );
	}

	/**
	 * Compare un objet de type zabbix_host avec l'objet en cours sur le champ host et,
	 * s'il est rempli, sur le champ hostid
	 * @param zabbix_host $zabbix_host_compare
	 * @return boolean True si les hosts correspondent, false sinon
	 */
	public function compare_host($zabbix_host_compare) {
		$this ->onDebug ( __METHOD__, 1 );
		if ($this ->getHostId () !== "" && $zabbix_host_compare ->getHostId () != "" && $zabbix_host_compare ->getHostId () != $this ->getHostId ()) {
			return false;
		}
		if ($zabbix_host_compare ->getHost () != $this ->getHost ()) {
			return false;
		}
		
		return true;
	}

	/**
	 * Creer un definition d'un host sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_host_delete_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$datas = array ();
		
		if ($this ->getHostId () != "") {
			$datas [] .= $this ->getHostId ();
		}
		
		return $datas;
	}

	/**
	 * supprime un host dans zabbix
	 * @return array
	 */
	public function supprime_host() {
		$this ->onDebug ( __METHOD__, 1 );
		$datas = $this ->creer_definition_host_delete_ws ();
		$this ->onDebug ( $datas, 1 );
		if (count ( $datas ) > 0) {
			return $this ->getObjetZabbixWsclient () 
				->hostDelete ( $datas );
		}
		
		return array ();
	}

	/**
	 * Creer un definition d'un host sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_hostByName_get_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		return array ( 
				"output" => "hostid", 
				"filter" => array ( 
						"host" => $this ->getHost () ) );
	}

	/**
	 * recherche un host dans zabbix a partir de son sendto
	 * @return zabbix_host
	 */
	public function recherche_hostid_by_Name() {
		$this ->onDebug ( __METHOD__, 1 );
		$datas = $this ->creer_definition_hostByName_get_ws ();
		$this ->onDebug ( $datas, 2 );
		$liste_resultat = $this ->getObjetZabbixWsclient () 
			->hostGet ( $datas );
		if (isset ( $liste_resultat [0] ) && isset ( $liste_resultat [0] ["hostid"] )) {
			$this ->setHostId ( $liste_resultat [0] ["hostid"] );
		}
		
		return $this;
	}

	/**
	 * Creer un definition d'un host sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_hostNameByHostId_get_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		return array ( 
				"output" => array ( 
						"hostid", 
						"host" ), 
				"filter" => array ( 
						"hostid" => $this ->getHostId () ) );
	}

	/**
	 * recherche un host dans zabbix a partir de son sendto
	 * @return zabbix_host
	 */
	public function recherche_Name_by_hostid() {
		$this ->onDebug ( __METHOD__, 1 );
		$datas = $this ->creer_definition_hostNameByHostId_get_ws ();
		$this ->onDebug ( $datas, 2 );
		$liste_resultat = $this ->getObjetZabbixWsclient () 
			->hostGet ( $datas );
		if (isset ( $liste_resultat [0] ) && isset ( $liste_resultat [0] ["host"] )) {
			$this ->setHost ( $liste_resultat [0] ["host"] );
		}
		
		return $this;
	}
	
	/**
	 * Valide qu'un hostid est present
	 * @return Ambigous <false, boolean>|zabbix_host
	 * @throws Exception 
	 */
	public function valide_hostid_present(){
		if (empty ( $this ->getHostId () )) {
			return $this ->onError ( "Il faut un host valide : " . $this ->getHost () );
		}
		
		return $this;
	}

	/********************** GESTION DES HOSTGROUPS ***********************/
	/**
	 * Creer un tableau de recuperation de liste des hostgroups d'un host
	 * @return array
	 */
	public function creer_definition_hostListeGroups_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$datas = $this ->creer_definition_hostByName_get_ws ();
		$datas ["selectGroups"] = array ( 
				"groupid", 
				"name" );
		
		$this ->onDebug ( $datas, 2 );
		return $datas;
	}

	/**
	 * Retrouve la liste des hostgroup d'un host
	 * @return false|array
	 */
	public function retrouve_liste_groups_par_host() {
		$this ->onDebug ( __METHOD__, 1 );
		//On recupere la liste des hostgroups du host
		$liste_hostgroups_host = $this ->getObjetZabbixWsclient () 
			->hostGet ( $this ->creer_definition_hostListeGroups_ws () );
		if (isset ( $liste_hostgroups_host [0] ["groups"] )) {
			$this ->onDebug ( $liste_hostgroups_host [0] ["groups"], 2 );
			return $liste_hostgroups_host [0] ["groups"];
		}
		
		$this ->onDebug ( false, 2 );
		return false;
	}

	/**
	 * Ajoute un groupe existant dans zabbix au host
	 * Pré-requis : Le groupe existe dans Zabbix
	 * @return array
	 */
	public function ajouter_groups_au_host() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_hostgroups_host = $this ->retrouve_liste_groups_par_host ();
		if ($liste_hostgroups_host !== false) {
			//On retire les hostgroups deja defini dans le host
			$tempo_ids = array ();
			foreach ( $liste_hostgroups_host as $hostgroup ) {
				$tempo_ids [] .= $hostgroup ["groupid"];
			}
			$this ->getObjetGroups () 
				->valide_liste_groupes_a_partir_de_tableau ( $tempo_ids )  //On valide tous les groupes utilises par le host
				->ajoute_groupe_a_partir_cli (); //On ajoute celui de la ligne de commande (pre-requis : Il doit exister dans zabbix)
		}
		//On update le host avec les hostgroups restant
		return $this ->getObjetZabbixWsclient () 
			->hostUpdate ( $this ->creer_definition_hostUpdate_manageGroups_ws () );
	}

	/**
	 * Supprime un groupe du host
	 * @return array
	 */
	public function supprimer_groups_au_host() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_hostgroups_host = $this ->retrouve_liste_groups_par_host ();
		if ($liste_hostgroups_host !== false) {
			//On retire les hostgroups deja defini dans le host
			$tempo_ids = array ();
			foreach ( $liste_hostgroups_host as $hostgroup ) {
				$tempo_ids [] .= $hostgroup ["groupid"];
			}
			$this ->getObjetGroups () 
				->valide_liste_groupes_a_partir_de_tableau ( $tempo_ids )  //On valide tous les groupes utilises par le host
				->retire_groupe_a_partir_cli (); //On retire celui de la ligne de commande
		}
		//On update le host avec les hostgroups restant
		return $this ->getObjetZabbixWsclient () 
			->hostUpdate ( $this ->creer_definition_hostUpdate_manageGroups_ws () );
	}

	/**
	 * Creer un tableau d'ajout de hostgroup a un host
	 * @return array
	 * @throws Exception
	 */
	public function creer_definition_hostUpdate_manageGroups_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$this->valide_hostid_present();
		$host = array ( 
				"hostid" => $this ->getHostId () );
		$host ["groups"] = $this ->getObjetGroups () 
			->creer_definition_groupsids_ws ();
		
		return $host;
	}

	/********************** GESTION DES HOSTGROUPS ***********************/
	
	/********************** GESTION DES TEMPLATES ***********************/
	/**
	 * Creer un tableau de recuperation de liste des templates d'un host
	 * @return array
	 */
	public function creer_definition_hostListeTemplates_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$datas = array ( 
				"output" => "hostid", 
				"selectParentTemplates" => array ( 
						"templateid", 
						"name" ), 
				"hostids" => $this ->getHostId () );
		
		$this ->onDebug ( $datas, 2 );
		return $datas;
	}

	/**
	 * Retrouve la liste des template d'un host
	 * @return false|array
	 */
	public function retrouve_liste_template_par_host() {
		$this ->onDebug ( __METHOD__, 1 );
		//On recupere la liste des templates du host
		$liste_templates_host = $this ->getObjetZabbixWsclient () 
			->hostGet ( $this ->creer_definition_hostListeTemplates_ws () );
		if (isset ( $liste_templates_host [0] ["parentTemplates"] )) {
			$this ->onDebug ( $liste_templates_host [0] ["parentTemplates"], 2 );
			return $liste_templates_host [0] ["parentTemplates"];
		}
		
		$this ->onDebug ( false, 2 );
		return false;
	}

	/**
	 * Creer un tableau de recuperation de liste des templates d'un host
	 * @return array
	 */
	public function ajouter_templates_au_host() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_templates_host = $this ->retrouve_liste_template_par_host ();
		if ($liste_templates_host !== false) {
			//On retire les templates deja defini dans le host
			$tempo_ids = array ();
			foreach ( $liste_templates_host as $template ) {
				$tempo_ids [] .= $template ["templateid"];
			}
			$this ->getObjetTemplates () 
				->valide_liste_templates_a_partir_de_tableau ( $tempo_ids ) 
				->ajoute_template_a_partir_cli ();
		}
		//On update le host avec la liste entiere des templates
		return $this ->getObjetZabbixWsclient () 
			->hostUpdate ( $this ->creer_definition_hostUpdate_addTemplate_ws () );
	}

	/**
	 * Creer un tableau de recuperation de liste des templates d'un host
	 * @return array
	 */
	public function supprimer_templates_au_host() {
		$this ->onDebug ( __METHOD__, 1 );
		$this ->getObjetTemplates () 
			->valide_liste_templates ();
		//On update le host avec les templates restant
		return $this ->getObjetZabbixWsclient () 
			->hostUpdate ( $this ->creer_definition_hostUpdate_clearTemplate_ws () );
	}

	/**
	 * Creer un tableau d'ajout de template a un host
	 * @return array
	 * @throws Exception
	 */
	public function creer_definition_hostUpdate_addTemplate_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$this->valide_hostid_present();
		$host = array ( 
				"hostid" => $this ->getHostId () );
		$host ["templates"] = $this ->getObjetTemplates () 
			->creer_definition_templatesids_ws ();
		
		return $host;
	}

	/**
	 * Creer un tableau de suppression de template d'un host
	 * @return array
	 * @throws Exception
	 */
	public function creer_definition_hostUpdate_clearTemplate_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$this->valide_hostid_present();
		$host = array ( 
				"hostid" => $this ->getHostId () );
		$host ["templates_clear"] = array ();
		$liste_templateid = $this ->getObjetTemplates () 
			->creer_definition_templatesids_ws ();
		foreach ( $liste_templateid as $templateid ) {
			$host ["templates_clear"] [count ( $host ["templates_clear"] )] = $templateid;
		}
		
		return $host;
	}

	/********************** GESTION DES TEMPLATES ***********************/
	
	/********************** GESTION DES INTERFACES **********************/
	
	/**
	 * Creer un tableau de recuperation de liste des interfaces d'un host
	 * @return array
	 */
	public function creer_definition_hostListeInterfaces_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$datas = array ( 
				"output" => "hostid", 
				"selectInterfaces" => array ( 
						'interfaceid', 
						'hostid', 
						'dns', 
						'port', 
						'type', 
						'main', 
						'ip', 
						'useip' ), 
				"hostids" => $this ->getHostId () );
		
		$this ->onDebug ( $datas, 2 );
		return $datas;
	}

	/**
	 * Retrouve la liste des interfaces d'un host
	 * @return false|array
	 */
	public function retrouve_liste_interfaces_par_host() {
		$this ->onDebug ( __METHOD__, 1 );
		//On recupere la liste des templates du host
		$liste_interfaces_host = $this ->getObjetZabbixWsclient () 
			->hostGet ( $this ->creer_definition_hostListeInterfaces_ws () );
		if (isset ( $liste_interfaces_host [0] ["interfaces"] )) {
			$this ->onDebug ( $liste_interfaces_host [0] ["interfaces"], 2 );
			return $liste_interfaces_host [0] ["interfaces"];
		}
		
		$this ->onDebug ( false, 2 );
		return false;
	}

	/**
	 * Ajoute une ou plusieurs interfaces au host
	 * @return array
	 */
	public function ajouter_interfaces_au_host() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_interfaces_host = $this ->retrouve_liste_interfaces_par_host ();
		if ($liste_interfaces_host !== false) {
			$this ->getObjetInterfaces () 
				->ajoute_interfaces ( $liste_interfaces_host );
		}
		//On update le host avec les interfaces restant
		$this ->getObjetZabbixWsclient () 
			->hostUpdate ( $this ->creer_definition_hostUpdate_addInterface_ws () );
		
		return $this;
	}

	/**
	 * Supprime une ou plusieurs interfaces au host
	 * @return array
	 */
	public function supprimer_interfaces_au_host() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_interfaces_host = $this ->retrouve_liste_interfaces_par_host ();
		if ($liste_interfaces_host !== false) {
			$this ->getObjetInterfaces () 
				->supprime_interfaces ( $liste_interfaces_host );
		}
		//On update le host avec les interfaces restant
		$this ->getObjetZabbixWsclient () 
			->hostUpdate ( $this ->creer_definition_hostUpdate_addInterface_ws () );
		
		return $this;
	}

	/**
	 * Creer un tableau d'ajout d'interface a un host
	 * @return array
	 */
	public function creer_definition_hostUpdate_addInterface_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$host = array ( 
				"hostid" => $this ->getHostId () );
		$host ["interfaces"] = $this ->getObjetInterfaces () 
			->creer_definition_host_interfaces_ws ();
		
		return $host;
	}

	/********************** GESTION DES INTERFACES **********************/
	
	/********************** GESTION DES INVENTORY ***********************/
	/**
	 * Creer un tableau de definition d'inventory pour un host
	 * @return array
	 */
	public function creer_definition_inventory_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		return array ();
	}

	/********************** GESTION DES INVENTORY ***********************/
	
	/**
	 * 0 - monitored;
	 * 1 - unmonitored;
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Status($type) {
		$this ->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "unmonitored" :
				return 1;
				break;
			case "monitored" :
			default :
		}
		
		return 0;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHost($host) {
		$this->host = $host;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getVisibleName() {
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVisibleName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getProxyId() {
		return $this->proxy_hostid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProxyId($proxy_hostid) {
		$this->proxy_hostid = $proxy_hostid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHostId() {
		return $this->hostId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostId($hostId) {
		$this->hostId = $hostId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_host_interfaces
	 */
	public function &getObjetInterfaces() {
		return $this->host_interfaces;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetInterfaces(&$HostInterfaces) {
		$this->host_interfaces = $HostInterfaces;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_hostgroups
	 */
	public function &getObjetGroups() {
		return $this->host_groups;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetGroups(&$HostGroups) {
		$this->host_groups = $HostGroups;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_templates
	 */
	public function &getObjetTemplates() {
		return $this->host_templates;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetTemplates(&$HostTemplates) {
		$this->host_templates = $HostTemplates;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setStatus($status) {
		$this->status = $this ->retrouve_Status ( $status );
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Host :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_host_host ci.client.fr.ghc.local Nom du Host";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_host_name ci.client.fr.ghc.local Nom visuel du Host";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_host_status monitored Status possible : monitored/unmonitored";
		$help = array_merge ( $help, zabbix_host_interfaces::help () );
		$help = array_merge ( $help, zabbix_hostgroups::help () );
		$help = array_merge ( $help, zabbix_templates::help () );
		
		return $help;
	}
}
?>
