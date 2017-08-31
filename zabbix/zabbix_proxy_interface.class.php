<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_proxy_interface
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
class zabbix_proxy_interface extends zabbix_common_interface {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_proxy_interface.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_proxy_interface
	 */
	static function &creer_zabbix_proxy_interface(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_proxy_interface ( $sort_en_erreur, $entete );
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
	public function retrouve_zabbix_param() {
		$this->onDebug ( __METHOD__, 1 );
		parent::retrouve_zabbix_common_param ();
		//Gestion de l'interface
		$this->setPort ( $this->_valideOption ( array (
				"zabbix",
				"interface",
				"port" 
		), 10050 ) );
		
		return $this;
	}

	/**
	 * Creer un definition de l'interface sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_proxy_interface_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$interface = array (
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

	/******************************* ACCESSEURS ********************************/
	
	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Proxy Interface :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_interface_port 10050 Port du Proxy";
		$help = array_merge ( $help, zabbix_common_interface::help () );
		
		return $help;
	}
}
?>
