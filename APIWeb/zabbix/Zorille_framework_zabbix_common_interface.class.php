<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_common_interface
 *
 * interfaceid 	string 	(readonly) ID of the interface.
 * dns (required) 	string 	DNS name used by the interface.Can be empty if the connection is made via IP.
 * hostid (required) 	string 	ID of the host the interface belongs to.
 * ip (required) 	string 	IP address used by the interface.Can be empty if the connection is made via DNS.
 * 
 * port (required) 	string 	Port number used by the interface. Can contain user macros.
 * 
 * useip (required) 	integer 	Whether the connection should be made via IP.
 * 		Possible values are:
 * 		0 - connect using host DNS name;
 * 		1 - connect using host IP address.
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_common_interface extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $interfaceid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $fqdn = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $hostid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $IP = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $port = 10050;
	/**
	 * var privee
	 * oui=useIP,non=useDNS
	 * @access private
	 * @var integer
	 */
	private $useip = 1;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_common_interface.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_common_interface
	 */
	static function &creer_zabbix_common_interface(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_common_interface ( $sort_en_erreur, $entete );
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
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return false|zabbix_common_interface
	 * @throws Exception
	 */
	public function retrouve_zabbix_common_param() {
		$this->onDebug ( __METHOD__, 1 );
		//Gestion des interfaces
		//gestion IP/FQDN
		$ip_CI = $this->_valideOption ( array (
				"zabbix",
				"interface",
				"ip" 
		), "" );
		$FQDN_CI = $this->_valideOption ( array (
				"zabbix",
				"interface",
				"fqdn" 
		), "" );
		$resolv_fqdn = $this->_valideOption ( array (
				"zabbix",
				"interface",
				"resolv_fqdn" 
		), "FQDN" );
		
		if ($ip_CI === "" && $FQDN_CI === "") {
			return $this->onError ( "Il faut une IP ou un FQDN pour travailler." );
		}
		if ($ip_CI !== "" && $FQDN_CI !== "") {
			//Si on a une ip et un fqdn, alors on doit avoir le type de resolution
			if ($resolv_fqdn === "") {
				return $this->onError ( "Il n'y a pas resolv_fqdn defini pour travailler" );
			}
		} elseif ($ip_CI !== "") {
			//Si on a qu'une ip
			$resolv_fqdn = "IP";
			$FQDN_CI = "";
		} else {
			//Enfin c'est un fqdn a resoudre
			$resolv_fqdn = "FQDN";
			$ip_CI = "";
		}
		
		$this->creer_une_interface ( $resolv_fqdn, $ip_CI, $FQDN_CI );
		
		return $this;
	}

	/**
	 * Creer un interface a partir de variables en arguments
	 *
	 * @param string $UseIp IP/FQDN utilise l'ip ou la resolution DNS
	 * @param string $IP IP du CI
	 * @param string $FQDN FQDN du CI
	 * @return zabbix_common_interface
	 * @throws Exception
	 */
	public function creer_une_interface($UseIp, $IP, $FQDN) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setUseIp ( $UseIp );
		if (is_array ( $IP )) {
			$count = count ( $IP );
			if ($count === 0) {
				$IP = "";
			} elseif ($count === 1) {
				$IP = $IP [0];
			} else {
				return $this->onError ( "Une interface ne peut pas avoir plusieurs IPs" );
			}
		}
		$this->setIP ( $IP );
		if (is_array ( $FQDN )) {
			$count = count ( $FQDN );
			if ($count === 0) {
				$FQDN = "";
			} elseif ($count === 1) {
				$FQDN = $FQDN [0];
			} else {
				return $this->onError ( "Une interface ne peut pas avoir plusieurs FQDNs" );
			}
		}
		$this->setFQDN ( $FQDN );
		
		return $this;
	}

	/**
	 * 0 - connect using host DNS name;
 	 * 1 - connect using host IP address.
	 * @param string $useip
	 * @return number
	 */
	public function retrouve_useip($useip) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $useip )) {
			return $useip;
		}
		switch ($useip) {
			case "IP" :
				return 1;
				break;
			case "FQDN" :
			default :
				return 0;
		}
		
		return 0;
	}

	/******************************* ACCESSEURS ********************************/
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
	public function getFQDN() {
		return $this->fqdn;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFQDN($fqdn) {
		$this->fqdn = $fqdn;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIP() {
		return $this->IP;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIP($IP) {
		$this->IP = $IP;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHostId() {
		return $this->hostid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostId($hostid) {
		$this->hostid = $hostid;
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
	public function getUseIp() {
		return $this->useip;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUseIp($useip) {
		$this->useip = $this->retrouve_useip ( $useip );
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Interface :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_interface_ip 10.10.10.10 IP du CI";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_interface_fqdn ci.client.fr.ghc.local FQDN du CI";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_interface_resolv_fqdn IP|FQDN Si l'IP et le FQDN son fourni, permet de connaitre la methode a utiliser pour contacter le CI";
		
		return $help;
	}
}
?>
