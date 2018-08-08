<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_host_interface
 *
 * interfaceid 	string 	(readonly) ID of the interface.
 * dns (required) 	string 	DNS name used by the interface.Can be empty if the connection is made via IP.
 * hostid (required) 	string 	ID of the host the interface belongs to.
 * ip (required) 	string 	IP address used by the interface.Can be empty if the connection is made via DNS.
 * main (required) 	integer 	Whether the interface is used as default on the host. Only one interface of some type can be set as default on a host.
 * 		Possible values are:
 * 		0 - not default;
 * 		1 - default.
 * 
 * port (required) 	string 	Port number used by the interface. Can contain user macros.
 * type (required) 	integer 	Interface type.
 * 		Possible values are:
 * 		1 - agent;
 * 		2 - SNMP;
 * 		3 - IPMI;
 * 		4 - JMX.
 * 
 * useip (required) 	integer 	Whether the connection should be made via IP.
 * 		Possible values are:
 * 		0 - connect using host DNS name;
 * 		1 - connect using host IP address.
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_host_interface extends zabbix_common_interface {
	/**
	 * var privee
	 * oui=default,non=not default
	 * @access private
	 * @var integer
	 */
	private $main = 1;
	/**
	 * var privee
	 * agent/SNMP/IPMI/JMX
	 * @access private
	 * @var integer
	 */
	private $type = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_host_interface.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_host_interface
	 */
	static function &creer_zabbix_host_interface(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_host_interface ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
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
		// Gestion de zabbix_common_interface
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @param string $interface donnees au format 'agent/snmp|main: oui/non|port'
	 * @return false|zabbix_host_interface
	 * @throws Exception
	 */
	public function retrouve_zabbix_param($interface) {
		$this->onDebug ( __METHOD__, 1 );
		parent::retrouve_zabbix_common_param ();
		
		$liste_interface = explode ( "|", trim ( $interface ) );
		if ($liste_interface === false || count ( $liste_interface ) != 3) {
			return $this->onError ( "Parametre inutilisable : " . $interface );
		}
		$this->setType ( $liste_interface [0] );
		$this->setMain ( $liste_interface [1] );
		$this->setPort ( $liste_interface [2] );
		
		return $this;
	}

	/**
	 * Compare un objet de type zabbix_interface avec l'objet en cours
	 * @param zabbix_common_interface $zabbix_interface_compare
	 * @return boolean True si les interfaces correspondent, false sinon
	 */
	public function compare_interface($zabbix_interface_compare) {
		$this->onDebug ( __METHOD__, 1 );
		if ($zabbix_interface_compare->getType () != $this->getType ()) {
			return false;
		}
		if ($zabbix_interface_compare->getIP () != $this->getIP ()) {
			return false;
		}
		if ($zabbix_interface_compare->getFQDN () != $this->getFQDN ()) {
			return false;
		}
		if ($zabbix_interface_compare->getMain () != $this->getMain ()) {
			return false;
		}
		if ($zabbix_interface_compare->getPort () != $this->getPort ()) {
			return false;
		}
		return true;
	}

	/**
	 * Creer un definition de l'interface sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_host_interface_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$interface = array (
				"type" => $this->getType (),
				"main" => $this->getMain (),
				"useip" => $this->getUseIp (),
				"ip" => $this->getIP (),
				"dns" => $this->getFQDN (),
				"port" => $this->getPort () 
		);
		if ($this->getInterfaceId () != "") {
			$interface ["interfaceid"] = $this->getInterfaceId ();
			$interface ["hostid"] = $this->getHostId ();
		}
		
		return $interface;
	}

	/**
	 * 1 - agent;
 	 * 2 - SNMP;
 	 * 3 - IPMI;
 	 * 4 - JMX.
	 * @param string $type
	 * @return number
	 */
	public function retrouve_code_interface($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "snmp" :
				return 2;
				break;
			case "ipmi" :
				return 3;
				break;
			case "jmx" :
				return 4;
				break;
			case "agent" :
			default :
				return 1;
		}
		
		return 1;
	}

	/**
	 * 0 - not default;
 	 * 1 - default.
	 * @param string $main
	 * @return number
	 */
	public function retrouve_main($main) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $main )) {
			return $main;
		}
		switch ($main) {
			case "oui" :
				return 1;
				break;
			case "non" :
				return 0;
				break;
		}
		
		return 1;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getMain() {
		return $this->main;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMain($main) {
		$this->main = $this->retrouve_main ( $main );
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
	public function &setType($type) {
		$this->type = $this->retrouve_code_interface ( $type );
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Host Interface :";
		$help = array_merge ( $help, zabbix_common_interface::help () );
		
		return $help;
	}
}
?>
