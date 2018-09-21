<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_item
 * itemid 	string 	(readonly) ID of the item.
 * delay (required) 	integer 	Update interval of the item in seconds.
 * hostid (required) 	string 	ID of the host that the item belongs to.
 * interfaceid (required) 	string 	ID of the item's host interface. Used only for host items.
 * 		Optional for Zabbix agent (active), Zabbix internal, Zabbix trapper, Zabbix aggregate, database monitor and calculated items.
 * key_ (required) 	string 	Item key.
 * name (required) 	string 	Name of the item.
 * type (required) 	integer 	Type of the item.
 * 		Possible values:
 * 		0 - Zabbix agent;
 * 		1 - SNMPv1 agent;
 * 		2 - Zabbix trapper;
 * 		3 - simple check;
 * 		4 - SNMPv2 agent;
 * 		5 - Zabbix internal;
 * 		6 - SNMPv3 agent;
 * 		7 - Zabbix agent (active);
 * 		8 - Zabbix aggregate;
 * 		9 - web item;
 * 		10 - external check;
 * 		11 - database monitor;
 * 		12 - IPMI agent;
 * 		13 - SSH agent;
 * 		14 - TELNET agent;
 * 		15 - calculated;
 * 		16 - JMX agent;
 * 		17 - SNMP trap.
 * value_type (required) 	integer 	Type of information of the item.
 * 		Possible values:
 * 		0 - numeric float;
 * 		1 - character;
 * 		2 - log;
 * 		3 - numeric unsigned;
 * 		4 - text.
 * authtype 	integer 	SSH authentication method. Used only by SSH agent items.
 * 		Possible values:
 * 		0 - (default) password;
 * 		1 - public key.
 * data_type 	integer 	Data type of the item.
 * 		Possible values:
 * 		0 - (default) decimal;
 * 		1 - octal;
 * 		2 - hexadecimal;
 * 		3 - boolean.
 * delay_flex 	string 	Flexible intervals as a serialized string.
 * 		Each serialized flexible interval consists of an update interval and a time period separated by a forward slash. Multiple intervals are separated by a colon.
 * delta 	integer 	Value that will be stored.
 * 		Possible values:
 * 		0 - (default) as is;
 * 		1 - Delta, speed per second;
 * 		2 - Delta, simple change.
 * description 	string 	Description of the item.
 * error 	string 	(readonly) Error text if there are problems updating the item.
 * flags 	integer 	(readonly) Origin of the item.
 * 		Possible values:
 * 		0 - a plain item;
 * 		4 - a discovered item.
 * formula 	integer/float 	Custom multiplier.
 * 		Default: 1.
 * history 	integer 	Number of days to keep item's history data.
 * 		Default: 90.
 * inventory_link 	integer 	ID of the host inventory field that is populated by the item.
 * 		Refer to the host inventory page for a list of supported host inventory fields and their IDs.
 * 		Default: 0.
 * ipmi_sensor 	string 	IPMI sensor. Used only by IPMI items.
 * lastclock 	timestamp 	(readonly) Time when the item was last updated.
 * 		This property will only return a value for the period configured in ZBX_HISTORY_PERIOD.
 * lastns 	integer 	(readonly) Nanoseconds when the item was last updated.
 * 		This property will only return a value for the period configured in ZBX_HISTORY_PERIOD.
 * lastvalue 	string 	(readonly) Last value of the item.
 * 		This property will only return a value for the period configured in ZBX_HISTORY_PERIOD.
 * logtimefmt 	string 	Format of the time in log entries. Used only by log items.
 * mtime 	timestamp 	Time when the monitored log file was last updated. Used only by log items.
 * multiplier 	integer 	Whether to use a custom multiplier.
 * params 	string 	Additional parameters depending on the type of the item:
 * 		- executed script for SSH and Telnet items;
 * 		- SQL query for database monitor items;
 * 		- formula for calculated items.
 * password 	string 	Password for authentication. Used by simple check, SSH, Telnet, database monitor and JMX items.
 * port 	string 	Port monitored by the item. Used only by SNMP items.
 * prevvalue 	string 	(readonly) Previous value of the item.
 * 		This property will only return a value for the period configured in ZBX_HISTORY_PERIOD.
 * privatekey 	string 	Name of the private key file.
 * publickey 	string 	Name of the public key file.
 * snmp_community 	string 	SNMP community. Used only by SNMPv1 and SNMPv2 items.
 * snmp_oid 	string 	SNMP OID.
 * snmpv3_authpassphrase 	string 	SNMPv3 auth passphrase. Used only by SNMPv3 items.
 * snmpv3_authprotocol 	integer 	SNMPv3 authentication protocol. Used only by SNMPv3 items.
 * 		Possible values:
 * 		0 - (default) MD5;
 * 		1 - SHA.
 * snmpv3_contextname 	string 	SNMPv3 context name. Used only by SNMPv3 items.
 * snmpv3_privpassphrase 	string 	SNMPv3 priv passphrase. Used only by SNMPv3 items.
 * snmpv3_privprotocol 	integer 	SNMPv3 privacy protocol. Used only by SNMPv3 items.
 * 		Possible values:
 * 		0 - (default) DES;
 * 		1 - AES.
 * snmpv3_securitylevel 	integer 	SNMPv3 security level. Used only by SNMPv3 items.
 * 		Possible values:
 * 		0 - noAuthNoPriv;
 * 		1 - authNoPriv;
 * 		2 - authPriv.
 * snmpv3_securityname 	string 	SNMPv3 security name. Used only by SNMPv3 items.
 * state 	integer 	(readonly) State of the item.
 * 		Possible values:
 * 		0 - (default) normal;
 * 		1 - not supported.
 * status 	integer 	Status of the item.
 * 		Possible values:
 * 		0 - (default) enabled item;
 * 		1 - disabled item.
 * templateid 	string 	(readonly) ID of the parent template item.
 * trapper_hosts 	string 	Allowed hosts. Used only by trapper items.
 * trends 	integer 	Number of days to keep item's trends data.
 * 		Default: 365.
 * units 	string 	Value units.
 * username 	string 	Username for authentication. Used by simple check, SSH, Telnet, database monitor and JMX items.
 * 		Required by SSH and Telnet items.
 * valuemapid 	string 	ID of the associated value map. 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_item extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $itemid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $delay = "";
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
	 * @var int
	 */
	private $interfaceid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $key_ = "";
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
	 * @var int
	 */
	private $type = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $value_type = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $authtype = "0";
	/**
	* var privee
	*
	* @access private
	* @var int
	*/
	private $data_type = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $delay_flex = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $delta = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $description = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $error = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $flags = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int|float
	 */
	private $formula = "1";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $history = "90";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $inventory_link = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $ipmi_sensor = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $lastclock = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $lastns = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $lastvalue = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $logtimefmt = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $mtime = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $multiplier = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $params = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $password = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $port = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $prevvalue = "";
	/**
	* var privee
	*
	* @access private
	* @var string
	*/
	private $privatekey = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $publickey = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmp_community = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmp_oid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmpv3_authpassphrase = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmpv3_authprotocol = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmpv3_contextname = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmpv3_privpassphrase = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmpv3_privprotocol = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmpv3_securitylevel = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmpv3_securityname = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $state = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $status = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $templateid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $trapper_hosts = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $trends = "365";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $units = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $username = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $valuemapid = "";
	/**
	 * var privee
	 * uniquement dans le cas item.create
	 * @access private
	 * @var array
	 */
	private $applications = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_item.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_item
	 */
	static function &creer_zabbix_item(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_item ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
		) );
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
	public function retrouve_zabbix_param($nom_seulement = false) {
		$this->onDebug ( __METHOD__, 1 );
		//Gestion d'un $host
		$this->setName ( $this->_valideOption ( array (
				"zabbix",
				"item",
				"name" 
		) ) );
		if ($nom_seulement === false) {
			$this->setDelay ( $this->_valideOption ( array (
					"zabbix",
					"item",
					"delay" 
			) ) );
			$this->setHostId ( $this->_valideOption ( array (
					"zabbix",
					"item",
					"hostid" 
			) ) );
			$this->setKey_ ( $this->_valideOption ( array (
					"zabbix",
					"item",
					"key" 
			) ) );
			$this->setType ( $this->_valideOption ( array (
					"zabbix",
					"item",
					"type" 
			) ) );
			$this->setValueType ( $this->_valideOption ( array (
					"zabbix",
					"item",
					"valuetype" 
			) ) );
		}
		
		return $this;
	}

	/**
	 * insere les donnees d'un item a partir du retour d'un WS zabbix item.get
	 * @return zabbix_item
	 */
	public function inserer_ws_item($donnees_item) {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $donnees_item as $type => $valeur ) {
			$method = "set" . $type;
			if (method_exists ( "zabbix_item", $method )) {
				$this->$method ( $valeur );
			}
		}
		
		return $this;
	}

	/**
	 * Creer la definition JSON a transmettre au serveur Zabbix
	 * @return array
	 */
	public function creer_definition_item_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$item = array (
				"delay" => $this->getDelay (),
				"hostId" => $this->getHostId (),
				"interfaceid" => $this->getInterfaceId (),
				"key_" => $this->getKey_ (),
				"name" => $this->getName (),
				"type" => $this->getType (),
				"value_type" => $this->getValueType (),
				"authtype" => $this->getAuthtype (),
				"data_type" => $this->getDataType (),
				"delay_flex" => $this->getDelayFlex (),
				"delta" => $this->getDelta (),
				"description" => $this->getDescription (),
				"formula" => $this->getFormula (),
				"history" => $this->getHistory (),
				"inventory_link" => $this->getInventoryLink (),
				"ipmi_sensor" => $this->getIpmiSensor (),
				"logtimefmt" => $this->getLogtimefmt (),
				"mtime" => $this->getMtime (),
				"multiplier" => $this->getMultiplier (),
				"params" => $this->getParams (),
				"password" => $this->getPassword (),
				"port" => $this->getPort (),
				"privatekey" => $this->getPrivatekey (),
				"publickey" => $this->getPublickey (),
				"snmp_community" => $this->getSnmp_Community (),
				"snmp_oid" => $this->getSnmp_Oid (),
				"snmpv3_authpassphrase" => $this->getSnmpv3_Authpassphrase (),
				"snmpv3_authprotocol" => $this->getSnmpv3_Authprotocol (),
				"snmpv3_contextname" => $this->getSnmpv3_Contextname (),
				"snmpv3_privpassphrase" => $this->getSnmpv3_Privpassphrase (),
				"snmpv3_privprotocol" => $this->getSnmpv3_Privprotocol (),
				"snmpv3_securitylevel" => $this->getSnmpv3_Securitylevel (),
				"snmpv3_securityname" => $this->getSnmpv3_Securityname (),
				"state" => $this->getState (),
				"status" => $this->getStatus (),
				"trapper_hosts" => $this->getTrapperHosts (),
				"trends" => $this->getTrends (),
				"units" => $this->getUnits (),
				"username" => $this->getUsername (),
				"valuemapid" => $this->getValuemapId (),
				"applications" => $this->getApplications () 
		);
		
		return $item;
	}

	/**
	 * Creer un item dans zabbix
	 * 
	 * @return array
	 */
	public function creer_item() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_item_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->itemCreate ( $datas );
	}

	/**
	 * Creer un definition d'un item sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_item_delete_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = array ();
		
		if ($this->getItemId () != "") {
			$datas [] .= $this->getItemId ();
		}
		
		return $datas;
	}

	/**
	 * supprime un item dans zabbix
	 * @return array
	 */
	public function supprime_item() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_item_delete_ws ();
		$this->onDebug ( $datas, 1 );
		if (count ( $datas ) > 0) {
			return $this->getObjetZabbixWsclient ()
				->itemDelete ( $datas );
		}
		
		return array ();
	}

	/**
	 * Creer un definition d'un item sous forme de tableau
	 * @param string $output Type d'output pour la variable zabbix du meme nom
	 * @return array;
	 */
	public function creer_definition_itemByName_get_ws($output = "extend") {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"output" => $output,
				"filter" => array (
						"name" => $this->getName () 
				) 
		);
	}

	/**
	 * recherche un item dans zabbix a partir de son sendto
	 * @return zabbix_item
	 */
	public function recherche_itemid_by_Name() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_itemByName_get_ws ( "itemid" );
		$this->onDebug ( $datas, 1 );
		$liste_resultat = $this->getObjetZabbixWsclient ()
			->itemGet ( $datas );
		if (isset ( $liste_resultat [0] ) && isset ( $liste_resultat [0] ["itemid"] )) {
			$this->setItemId ( $liste_resultat [0] ["itemid"] );
		}
		
		return $this;
	}

	/**
	 * recherche un item dans zabbix a partir de son sendto
	 * @return zabbix_item
	 */
	public function recherche_donnees_by_Name() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_itemByName_get_ws ( "extend" );
		$this->onDebug ( $datas, 1 );
		$liste_resultat = $this->getObjetZabbixWsclient ()
			->itemGet ( $datas );
		
		return $liste_resultat;
	}

	/**
	 * Compare un objet de type zabbix_item avec l'objet en cours sur le champ name et,
	 * s'il est rempli, sur le champ itemid
	 * @param zabbix_item $zabbix_item_compare
	 * @return boolean True si les items correspondent, false sinon
	 */
	public function compare_item($zabbix_item_compare) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getItemId () !== "" && $zabbix_item_compare->getItemId () != "" && $zabbix_item_compare->getItemId () != $this->getItemId ()) {
			return false;
		}
		if ($zabbix_item_compare->getName () != $this->getName ()) {
			return false;
		}
		
		return true;
	}

	/**
	 * 0 - Zabbix agent;
	 * 1 - SNMPv1 agent;
	 * 2 - Zabbix trapper;
	 * 3 - simple check;
	 * 4 - SNMPv2 agent;
	 * 5 - Zabbix internal;
	 * 6 - SNMPv3 agent;
	 * 7 - Zabbix agent (active);
	 * 8 - Zabbix aggregate;
	 * 9 - web item;
	 * 10 - external check;
	 * 11 - database monitor;
	 * 12 - IPMI agent;
	 * 13 - SSH agent;
	 * 14 - TELNET agent;
	 * 15 - calculated;
	 * 16 - JMX agent;
	 * 17 - SNMP trap. 
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Type($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "snmpv1 agent" :
				return 1;
				break;
			case "zabbix trapper" :
				return 2;
				break;
			case "simple check" :
				return 3;
				break;
			case "snmpv2 agent" :
				return 4;
				break;
			case "zabbix internal" :
				return 5;
				break;
			case "snmpv3 agent" :
				return 6;
				break;
			case "zabbix agent (active)" :
				return 7;
				break;
			case "zabbix aggregate" :
				return 8;
				break;
			case "web item" :
				return 9;
				break;
			case "external check" :
				return 10;
				break;
			case "database monitor" :
				return 11;
				break;
			case "ipmi agent" :
				return 12;
				break;
			case "ssh agent" :
				return 13;
				break;
			case "telnet agent" :
				return 14;
				break;
			case "calculated" :
				return 15;
				break;
			case "jmx agent" :
				return 16;
				break;
			case "snmp trap" :
				return 17;
				break;
			case "zabbix agent" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - numeric float;
	 * 1 - character;
	 * 2 - log;
	 * 3 - numeric unsigned;
	 * 4 - text. 
	 * @param string $valueType
	 * @return number
	 */
	public function retrouve_ValueType($valueType) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $valueType )) {
			return $valueType;
		}
		switch (strtolower ( $valueType )) {
			case "character" :
				return 1;
				break;
			case "log" :
				return 2;
				break;
			case "numeric unsigned" :
				return 3;
				break;
			case "text" :
				return 4;
				break;
			case "numeric float" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - enabled item;
	 * 1 - disabled item;
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Authtype($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "public key" :
				return 1;
				break;
			case "password" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - (default) decimal;
	 * 1 - octal;
	 * 2 - hexadecimal;
	 * 3 - boolean. 
	 * @param string $dataType
	 * @return number
	 */
	public function retrouve_DataType($dataType) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $dataType )) {
			return $dataType;
		}
		switch (strtolower ( $dataType )) {
			case "octal" :
				return 1;
				break;
			case "hexadecimal" :
				return 2;
				break;
			case "boolean" :
				return 3;
				break;
			case "decimal" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - (default) as is;
	 * 1 - Delta, speed per second;
	 * 2 - Delta, simple change. 
	 * @param string $delta
	 * @return number
	 */
	public function retrouve_Delta($delta) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $delta )) {
			return $delta;
		}
		switch (strtolower ( $delta )) {
			case "speed per second" :
				return 1;
				break;
			case "simple change" :
				return 2;
				break;
			case "as is" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - MD5;
	 * 1 - SHA;
	 * @param string $data
	 * @return number
	 */
	public function retrouve_Snmpv3Authprotocol($data) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $data )) {
			return $data;
		}
		switch (strtoupper ( $data )) {
			case "SHA" :
				return 1;
			case "MD5" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - DES;
	 * 1 - AES;
	 * @param string $data
	 * @return number
	 */
	public function retrouve_Snmpv3Privprotocol($data) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $data )) {
			return $data;
		}
		switch (strtoupper ( $data )) {
			case "AES" :
				return 1;
			case "DES" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - noAuthNoPriv;
	 * 1 - authNoPriv;
	 * 2 - authPriv. 
	 * @param string $data
	 * @return number
	 */
	public function retrouve_Snmpv3Securitylevel($data) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $data )) {
			return $data;
		}
		switch (strtolower ( $data )) {
			case "authnopriv" :
				return 1;
				break;
			case "authpriv" :
				return 2;
				break;
			case "noauthnopriv" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - (default) normal;
	 * 1 - not supported. 
	 * @param string $type
	 * @return number
	 */
	public function retrouve_State($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "not supported" :
				return 1;
				break;
			case "normal" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - enabled item;
	 * 1 - disabled item;
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Status($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "disabled item" :
				return 1;
				break;
			case "enabled item" :
			default :
		}
		
		return 0;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getItemId() {
		return $this->itemid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setItemId($itemid) {
		$this->itemid = $itemid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDelay() {
		return $this->delay;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDelay($delay) {
		$this->delay = $delay;
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
	 */
	public function getInterfaceId() {
		return $this->interfaceid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInterfaceId($interfaceid) {
		$this->interfaceid = $interfaceid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getKey_() {
		return $this->key_;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setKey_($key_) {
		$this->key_ = $key_;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTypeStringValue() {
		switch ($this->type) {
			case 1 :
				return "SNMPv1 agent";
			case 2 :
				return "Zabbix trapper";
			case 3 :
				return "simple check";
			case 4 :
				return "SNMPv2 agent";
			case 5 :
				return "Zabbix internal";
			case 6 :
				return "SNMPv3 agent";
			case 7 :
				return "Zabbix agent (active)";
			case 8 :
				return "Zabbix aggregate";
			case 9 :
				return "web item";
			case 10 :
				return "external check";
			case 11 :
				return "database monitor";
			case 12 :
				return "IPMI agent";
			case 13 :
				return "SSH agent";
			case 14 :
				return "TELNET agent";
			case 15 :
				return "calculated";
			case 16 :
				return "JMX agent";
			case 17 :
				return "SNMP trap";
			case 0 :
			default :
		}
		return "Zabbix agent";
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setType($type) {
		$this->type = $this->retrouve_Type ( $type );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValueType() {
		return $this->value_type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValueType($value_type) {
		$this->value_type = $this->retrouve_ValueType ( $value_type );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAuthtype() {
		return $this->authtype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuthtype($authtype) {
		$this->authtype = $this->retrouve_Authtype ( $authtype );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDataType() {
		return $this->data_type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDataType($data_type) {
		$this->data_type = $this->retrouve_DataType ( $data_type );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDelayFlex() {
		return $this->delay_flex;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDelayFlex($delay_flex) {
		$this->delay_flex = $delay_flex;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDelta() {
		return $this->delta;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDelta($delta) {
		$this->delta = $this->retrouve_Delta ( $delta );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setError($error) {
		$this->error = $error;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFlags() {
		return $this->flags;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFlags($flags) {
		$this->flags = $flags;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFormula() {
		return $this->formula;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFormula($formula) {
		$this->formula = $formula;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHistory() {
		return $this->history;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHistory($history) {
		$this->history = $history;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getInventoryLink() {
		return $this->inventory_link;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInventoryLink($inventory_link) {
		$this->inventory_link = $inventory_link;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIpmiSensor() {
		return $this->ipmi_sensor;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIpmiSensor($ipmi_sensor) {
		$this->ipmi_sensor = $ipmi_sensor;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLastclock() {
		return $this->lastclock;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLastclock($lastclock) {
		$this->lastclock = $lastclock;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLastns() {
		return $this->lastns;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLastns($lastns) {
		$this->lastns = $lastns;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLastvalue() {
		return $this->lastvalue;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLastvalue($lastvalue) {
		$this->lastvalue = $lastvalue;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLogtimefmt() {
		return $this->logtimefmt;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLogtimefmt($logtimefmt) {
		$this->logtimefmt = $logtimefmt;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMtime() {
		return $this->mtime;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMtime($mtime) {
		$this->mtime = $mtime;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMultiplier() {
		return $this->multiplier;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMultiplier($multiplier) {
		$this->multiplier = $multiplier;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setParams($params) {
		$this->params = $params;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword($password) {
		$this->password = $password;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPort($port) {
		$this->port = $port;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrevvalue() {
		return $this->prevvalue;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPrevvalue($prevvalue) {
		$this->prevvalue = $prevvalue;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrivatekey() {
		return $this->privatekey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPrivatekey($privatekey) {
		$this->privatekey = $privatekey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPublickey() {
		return $this->publickey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPublickey($publickey) {
		$this->publickey = $publickey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmp_Community() {
		return $this->snmp_community;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmp_Community($snmp_community) {
		$this->snmp_community = $snmp_community;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmp_Oid() {
		return $this->snmp_oid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmp_Oid($snmp_oid) {
		$this->snmp_oid = $snmp_oid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Authpassphrase() {
		return $this->snmpv3_authpassphrase;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Authpassphrase($snmp_authpassphrase) {
		$this->snmp_authpassphrase = $snmp_authpassphrase;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Authprotocol() {
		return $this->snmpv3_authprotocol;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Authprotocol($snmp_authprotocol) {
		$this->snmpv3_authprotocol = $this->retrouve_Snmpv3Authprotocol ( $snmp_authprotocol );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Contextname() {
		return $this->snmpv3_contextname;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Contextname($snmp_contextname) {
		$this->snmpv3_contextname = $snmp_contextname;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Privpassphrase() {
		return $this->snmpv3_privpassphrase;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Privpassphrase($snmp_privpassphrase) {
		$this->snmpv3_privpassphrase = $snmp_privpassphrase;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Privprotocol() {
		return $this->snmpv3_privprotocol;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Privprotocol($snmp_privprotocol) {
		$this->snmpv3_privprotocol = $this->retrouve_Snmpv3Privprotocol ( $snmp_privprotocol );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Securitylevel() {
		return $this->snmpv3_securitylevel;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Securitylevel($snmp_securitylevel) {
		$this->snmpv3_securitylevel = $this->retrouve_Snmpv3Securitylevel ( $snmp_securitylevel );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Securityname() {
		return $this->snmpv3_securityname;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Securityname($snmp_securityname) {
		$this->snmpv3_securityname = $snmp_securityname;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setState($state) {
		$this->state = $this->retrouve_State ( $state );
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
		$this->status = $this->retrouve_Status ( $status );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplateId() {
		return $this->templateid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTemplateId($templateid) {
		$this->templateid = $templateid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTrapperHosts() {
		return $this->trapper_hosts;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTrapperHosts($trapper_hosts) {
		$this->trapper_hosts = $trapper_hosts;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTrends() {
		return $this->trends;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTrends($trends) {
		$this->trends = $trends;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUnits() {
		return $this->units;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUnits($units) {
		$this->units = $units;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValuemapId() {
		return $this->valuemapid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValuemapId($valuemapid) {
		$this->valuemapid = $valuemapid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getApplications() {
		return $this->applications;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setApplications($applications) {
		$this->applications = $applications;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Item :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_item_host ci.client.fr.ghc.local Nom du Host";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_item_name ci.client.fr.ghc.local Nom visuel du Host";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_item_status monitored Status possible : monitored/unmonitored";
		
		return $help;
	}
}
?>
