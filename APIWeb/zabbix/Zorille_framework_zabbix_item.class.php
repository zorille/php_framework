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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_item
	 */
	static function &creer_zabbix_item(options &$liste_option, zabbix_wsclient &$zabbix_ws, bool|string $sort_en_erreur = false, string $entete = __CLASS__): zabbix_item
	{
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
	 * @return zabbix_item
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return zabbix_item True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param($nom_seulement = false): static
	{
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
	 * @param $donnees_item
	 * @return zabbix_item
	 */
	public function inserer_ws_item($donnees_item): static
	{
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $donnees_item as $type => $valeur ) {
			$method = "set" . $type;
			if (method_exists ( 'Zorille\framework\zabbix_item', $method )) {
				$this->$method ( $valeur );
			}
		}
		
		return $this;
	}

	/**
	 * Creer la definition JSON a transmettre au serveur Zabbix
	 * @return array
	 */
	public function creer_definition_item_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		return array (
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
	}

	/**
	 * Creer un item dans zabbix
	 *
	 * @return array
	 * @throws Exception
	 */
	public function creer_item(): array
	{
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
	public function creer_definition_item_delete_ws(): array
	{
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
	 * @throws Exception
	 */
	public function supprime_item(): array
	{
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
	public function creer_definition_itemByName_get_ws(string $output = "extend"): array
	{
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
	 * @throws Exception
	 */
	public function recherche_itemid_by_Name(): static
	{
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
	 * @return array|\stdClass|string
	 * @throws Exception
	 */
	public function recherche_donnees_by_Name(): array|string|\stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_itemByName_get_ws ( "extend" );
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->itemGet ( $datas );
	}

	/**
	 * Compare un objet de type zabbix_item avec l'objet en cours sur le champ name et,
	 * s'il est rempli, sur le champ itemid
	 * @param zabbix_item $zabbix_item_compare
	 * @return boolean True si les items correspondent, false sinon
	 */
	public function compare_item(zabbix_item $zabbix_item_compare): bool
	{
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
	 * @return float|int|string
	 */
	public function retrouve_Type(string $type): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		return match (strtolower($type)) {
			"snmpv1 agent" => 1,
			"zabbix trapper" => 2,
			"simple check" => 3,
			"snmpv2 agent" => 4,
			"zabbix internal" => 5,
			"snmpv3 agent" => 6,
			"zabbix agent (active)" => 7,
			"zabbix aggregate" => 8,
			"web item" => 9,
			"external check" => 10,
			"database monitor" => 11,
			"ipmi agent" => 12,
			"ssh agent" => 13,
			"telnet agent" => 14,
			"calculated" => 15,
			"jmx agent" => 16,
			"snmp trap" => 17,
			default => 0,
		};

	}

	/**
	 * 0 - numeric float;
	 * 1 - character;
	 * 2 - log;
	 * 3 - numeric unsigned;
	 * 4 - text. 
	 * @param string $valueType
	 * @return float|int|string
	 */
	public function retrouve_ValueType($valueType): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $valueType )) {
			return $valueType;
		}
		return match (strtolower($valueType)) {
			"character" => 1,
			"log" => 2,
			"numeric unsigned" => 3,
			"text" => 4,
			default => 0,
		};

	}

	/**
	 * 0 - enabled item;
	 * 1 - disabled item;
	 * @param string $type
	 * @return float|int|string
	 */
	public function retrouve_Authtype($type): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		return match (strtolower($type)) {
			"public key" => 1,
			default => 0,
		};

	}

	/**
	 * 0 - (default) decimal;
	 * 1 - octal;
	 * 2 - hexadecimal;
	 * 3 - boolean. 
	 * @param string $dataType
	 * @return float|int|string
	 */
	public function retrouve_DataType(string $dataType): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $dataType )) {
			return $dataType;
		}
		return match (strtolower($dataType)) {
			"octal" => 1,
			"hexadecimal" => 2,
			"boolean" => 3,
			default => 0,
		};
	}

	/**
	 * 0 - (default) as is;
	 * 1 - Delta, speed per second;
	 * 2 - Delta, simple change. 
	 * @param string $delta
	 * @return float|int|string
	 */
	public function retrouve_Delta($delta): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $delta )) {
			return $delta;
		}
		return match (strtolower($delta)) {
			"speed per second" => 1,
			"simple change" => 2,
			default => 0,
		};

	}

	/**
	 * 0 - MD5;
	 * 1 - SHA;
	 * @param string $data
	 * @return float|int|string
	 */
	public function retrouve_Snmpv3Authprotocol($data): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $data )) {
			return $data;
		}
		return match (strtoupper($data)) {
			"SHA" => 1,
			default => 0,
		};

	}

	/**
	 * 0 - DES;
	 * 1 - AES;
	 * @param string $data
	 * @return float|int|string
	 */
	public function retrouve_Snmpv3Privprotocol(string $data): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $data )) {
			return $data;
		}
		return match (strtoupper($data)) {
			"AES" => 1,
			default => 0,
		};

	}

	/**
	 * 0 - noAuthNoPriv;
	 * 1 - authNoPriv;
	 * 2 - authPriv. 
	 * @param string $data
	 * @return float|int|string
	 */
	public function retrouve_Snmpv3Securitylevel(string $data): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $data )) {
			return $data;
		}
		return match (strtolower($data)) {
			"authnopriv" => 1,
			"authpriv" => 2,
			default => 0,
		};
	}

	/**
	 * 0 - (default) normal;
	 * 1 - not supported. 
	 * @param string $type
	 * @return float|int|string
	 */
	public function retrouve_State(string $type): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		return match (strtolower($type)) {
			"not supported" => 1,
			default => 0,
		};
	}

	/**
	 * 0 - enabled item;
	 * 1 - disabled item;
	 * @param string $type
	 * @return float|int|string
	 */
	public function retrouve_Status(string $type): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		return match (strtolower($type)) {
			"disabled item" => 1,
			default => 0,
		};

	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getItemId(): int|string
	{
		return $this->itemid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setItemId($itemid): static
	{
		$this->itemid = $itemid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDelay(): int|string
	{
		return $this->delay;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDelay($delay): static
	{
		$this->delay = $delay;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHostId(): int|string
	{
		return $this->hostId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostId($hostId): static
	{
		$this->hostId = $hostId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getInterfaceId(): int|string
	{
		return $this->interfaceid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInterfaceId($interfaceid): static
	{
		$this->interfaceid = $interfaceid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getKey_(): int|string
	{
		return $this->key_;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setKey_($key_): static
	{
		$this->key_ = $key_;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setName($name): static
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getType(): int|string
	{
		return $this->type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTypeStringValue(): string
	{
		return match ($this->type) {
			1 => "SNMPv1 agent",
			2 => "Zabbix trapper",
			3 => "simple check",
			4 => "SNMPv2 agent",
			5 => "Zabbix internal",
			6 => "SNMPv3 agent",
			7 => "Zabbix agent (active)",
			8 => "Zabbix aggregate",
			9 => "web item",
			10 => "external check",
			11 => "database monitor",
			12 => "IPMI agent",
			13 => "SSH agent",
			14 => "TELNET agent",
			15 => "calculated",
			16 => "JMX agent",
			17 => "SNMP trap",
			default => 'Zabbix agent',
		};
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setType($type): static
	{
		$this->type = $this->retrouve_Type ( $type );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValueType(): int|string
	{
		return $this->value_type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValueType($value_type): static
	{
		$this->value_type = $this->retrouve_ValueType ( $value_type );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAuthtype(): int|string
	{
		return $this->authtype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuthtype($authtype): static
	{
		$this->authtype = $this->retrouve_Authtype ( $authtype );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDataType(): int|string
	{
		return $this->data_type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDataType($data_type): static
	{
		$this->data_type = $this->retrouve_DataType ( $data_type );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDelayFlex(): int|string
	{
		return $this->delay_flex;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDelayFlex($delay_flex): static
	{
		$this->delay_flex = $delay_flex;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDelta(): int|string
	{
		return $this->delta;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDelta($delta): static
	{
		$this->delta = $this->retrouve_Delta ( $delta );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDescription($description): static
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getError(): string
	{
		return $this->error;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setError($error): static
	{
		$this->error = $error;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFlags(): int|string
	{
		return $this->flags;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFlags($flags): static
	{
		$this->flags = $flags;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFormula(): float|int|string
	{
		return $this->formula;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFormula($formula): static
	{
		$this->formula = $formula;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHistory(): int|string
	{
		return $this->history;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHistory($history): static
	{
		$this->history = $history;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getInventoryLink(): int|string
	{
		return $this->inventory_link;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInventoryLink($inventory_link): static
	{
		$this->inventory_link = $inventory_link;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIpmiSensor(): string
	{
		return $this->ipmi_sensor;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIpmiSensor($ipmi_sensor): static
	{
		$this->ipmi_sensor = $ipmi_sensor;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLastclock(): int|string
	{
		return $this->lastclock;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLastclock($lastclock): static
	{
		$this->lastclock = $lastclock;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLastns(): int|string
	{
		return $this->lastns;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLastns($lastns): static
	{
		$this->lastns = $lastns;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLastvalue(): string
	{
		return $this->lastvalue;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLastvalue($lastvalue): static
	{
		$this->lastvalue = $lastvalue;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLogtimefmt(): string
	{
		return $this->logtimefmt;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLogtimefmt($logtimefmt): static
	{
		$this->logtimefmt = $logtimefmt;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMtime(): int|string
	{
		return $this->mtime;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMtime($mtime): static
	{
		$this->mtime = $mtime;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMultiplier(): int|string
	{
		return $this->multiplier;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMultiplier($multiplier): static
	{
		$this->multiplier = $multiplier;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getParams(): string
	{
		return $this->params;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setParams($params): static
	{
		$this->params = $params;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword($password): static
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPort(): string
	{
		return $this->port;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPort($port): static
	{
		$this->port = $port;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrevvalue(): string
	{
		return $this->prevvalue;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPrevvalue($prevvalue): static
	{
		$this->prevvalue = $prevvalue;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrivatekey(): string
	{
		return $this->privatekey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPrivatekey($privatekey): static
	{
		$this->privatekey = $privatekey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPublickey(): string
	{
		return $this->publickey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPublickey($publickey): static
	{
		$this->publickey = $publickey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmp_Community(): string
	{
		return $this->snmp_community;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmp_Community($snmp_community): static
	{
		$this->snmp_community = $snmp_community;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmp_Oid(): string
	{
		return $this->snmp_oid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmp_Oid($snmp_oid): static
	{
		$this->snmp_oid = $snmp_oid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Authpassphrase(): string
	{
		return $this->snmpv3_authpassphrase;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Authpassphrase($snmp_authpassphrase): static
	{
		$this->snmp_authpassphrase = $snmp_authpassphrase;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Authprotocol(): string
	{
		return $this->snmpv3_authprotocol;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Authprotocol($snmp_authprotocol): static
	{
		$this->snmpv3_authprotocol = $this->retrouve_Snmpv3Authprotocol ( $snmp_authprotocol );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Contextname(): string
	{
		return $this->snmpv3_contextname;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Contextname($snmp_contextname): static
	{
		$this->snmpv3_contextname = $snmp_contextname;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Privpassphrase(): string
	{
		return $this->snmpv3_privpassphrase;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Privpassphrase($snmp_privpassphrase): static
	{
		$this->snmpv3_privpassphrase = $snmp_privpassphrase;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Privprotocol(): string
	{
		return $this->snmpv3_privprotocol;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Privprotocol($snmp_privprotocol): static
	{
		$this->snmpv3_privprotocol = $this->retrouve_Snmpv3Privprotocol ( $snmp_privprotocol );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Securitylevel(): string
	{
		return $this->snmpv3_securitylevel;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Securitylevel($snmp_securitylevel): static
	{
		$this->snmpv3_securitylevel = $this->retrouve_Snmpv3Securitylevel ( $snmp_securitylevel );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpv3_Securityname(): string
	{
		return $this->snmpv3_securityname;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnmpv3_Securityname($snmp_securityname): static
	{
		$this->snmpv3_securityname = $snmp_securityname;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getState(): int|string
	{
		return $this->state;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setState($state): static
	{
		$this->state = $this->retrouve_State ( $state );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getStatus(): int|string
	{
		return $this->status;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setStatus($status): static
	{
		$this->status = $this->retrouve_Status ( $status );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplateId(): string
	{
		return $this->templateid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTemplateId($templateid): static
	{
		$this->templateid = $templateid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTrapperHosts(): string
	{
		return $this->trapper_hosts;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTrapperHosts($trapper_hosts): static
	{
		$this->trapper_hosts = $trapper_hosts;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTrends(): int|string
	{
		return $this->trends;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTrends($trends): static
	{
		$this->trends = $trends;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUnits(): string
	{
		return $this->units;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUnits($units): static
	{
		$this->units = $units;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsername($username): static
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValuemapId(): string
	{
		return $this->valuemapid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValuemapId($valuemapid): static
	{
		$this->valuemapid = $valuemapid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getApplications(): array
	{
		return $this->applications;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setApplications($applications): static
	{
		$this->applications = $applications;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Item :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_item_host ci.client.fr.ghc.local Nom du Host";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_item_name ci.client.fr.ghc.local Nom visuel du Host";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_item_status monitored Status possible : monitored/unmonitored";
		
		return $help;
	}
}

