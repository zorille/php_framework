<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class cacti_addDevice<br>
 *
 * Prepare une ligne de commande de generation.
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_addDevice extends cacti_hosts {
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $host_id = - 1;
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
	private $ip = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $template_id = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmp_vers = 1;
	
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $community = "public";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmp_username = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmp_password = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $authproto = "MD5";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $privproto = "DES";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $privpass = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $snmp_context = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $snmp_port = 161;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $snmp_timeout = 500;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $snmp_retries = 3;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $availability = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $disabled = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $note = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $ping_method = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $ping_port = 23;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $ping_timeout = 400;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $ping_retries = 1;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $max_oids = "10";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $device_threads = 1;
	
	/**
	 * var privee
	 *
	 * @access private
	 * @var cacti_hosts
	 */
	private $host_data = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var cacti_hostsTemplates
	 */
	private $templates_data = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_addDevice.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_addDevice
	 */
	static function &creer_cacti_addDevice(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_addDevice ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_addDevice
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
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
		
		
		$this->prepareVariablescacti_addDevice ();
	}

	/**
	 * Prepare la liste des variables specifique au merge.
	 * @throws Exception
	 */
	public function prepareVariablescacti_addDevice() {
		$this->onDebug ( "prepareVariablescacti_addDevice", 1 );
		
		// $this->setCommunity ( read_config_option ( "snmp_community" ) );
		// $this->setSnmpVersion ( read_config_option ( "snmp_ver" ) );
		$this->setDisabled ( 0 );
		// $this->setSnmpUsername ( read_config_option ( "snmp_username" ) );
		// $this->setSnmpPassword ( read_config_option ( "snmp_password" ) );
		// $this->setAuthproto(read_config_option ( "snmp_auth_protocol" ));
		// $this->setPrivpass(read_config_option ( "snmp_priv_passphrase" ));
		// $this->setPrivproto(read_config_option ( "snmp_priv_protocol" ));
		// $this->setSNMPPort(read_config_option ( "snmp_port" ));
		// $this->setSNMPTimeout(read_config_option ( "snmp_timeout" ));
		

		$this->setAvailability ( "snmp" );
		$this->setPingMethod ( "udp" );
		// $this->setPingMethod(read_config_option ( "ping_method" ));
		// $this->setPingPort(read_config_option ( "ping_port" ));
		// $this->setPingTimeout(read_config_option ( "ping_timeout" ));
		// $this->setPingRetries(read_config_option ( "ping_retries" ));
		// $this->setMaxOids(read_config_option ( "max_get_size" ));
		

		return true;
	}

	/**
	 * Valide qu'un host description existe et set l'id dans HostId
	 *
	 * @return boolean True le host existe, false le host n'existe pas.
	 */
	public function valide_host_description() {
		if ($this->valide_host_by_description ( $this->getDescription () )) {
			$this->onDebug ( "Host existe, on renvoi l'id de la machine.", 1 );
			$this->setHostId ( $this->renvoi_hostid_by_description ( $this->getDescription () ) );
			return true;
		}
		
		return false;
	}

	/**
	 * Valide qu'un host ip existe et set l'id dans HostId
	 *
	 * @return boolean True le host existe, false le host n'existe pas.
	 */
	public function valide_host_ip() {
		$id = $this->renvoi_hostid_by_ip ( $this->getIp () );
		if ($id !== false) {
			$this->onDebug ( "IP existe, on renvoi l'id de la machine : " . $id, 1 );
			$this->setHostId ( $id );
			return true;
		}
		
		return false;
	}

	/**
	 * Valide que le snmp est configure correctement
	 *
	 * @return boolean true tout est OK, false sinon
	 * @throws Exception
	 */
	public function valide_SNMP() {
		if ($this->getSnmpVersion () < 1 || $this->getSnmpVersion () > 3) {
			return $this->onError ( "Mauvaise version de SNMP : " . $this->getSnmpVersion (), "", 5006 );
		} elseif ($this->getSnmpVersion () > 0) {
			if ($this->getSnmpPort () <= 1 || $this->getSnmpPort () > 65534) {
				return $this->onError ( "Mauvais port SNMP : " . $this->getSnmpPort (), "", 5007 );
			}
			if ($this->getSnmpTimeout () <= 0 || $this->getSnmpTimeout () > 20000) {
				return $this->onError ( "Le timeout SNMP doit etre compris entre 0 et 20000 : " . $this->getSnmpTimeout (), "", 5008 );
			}
		}
		
		/* community/user/password verification */
		if ($this->getSnmpVersion () == 3) {
			if ($this->getSnmpUsername () == "" || $this->getSnmpPassword () == "") {
				return $this->onError ( "En snmp V3 if faut un username et un password.", "", 5009 );
			}
		} /* snmp community can be blank */
		
		return true;
	}

	/**
	 * Ajoute un device
	 *
	 * @return Integer/false Renvoi l'id du device, false en cas d'erreur.
	 * @throws Exception
	 */
	public function executeCacti_AddDevice($update = false, $update_ref = "none") {
		// La description est oligatoire
		if ($this->getDescription () == "") {
			return $this->onError ( "Il faut une description.", "", 5003 );
		}
		// L'IP est obligatoire
		if ($this->getIp () == "") {
			return $this->onError ( "Il faut une Ip.", "", 5004 );
		}
		
		// Si la config SNMP est invalide, on stoppe l'ajout
		$this->valide_SNMP ();
		
		if ($update) {
			// Si le CI n'existe pas, on stoppe l'update
			// La validation ajoute le numero du CI dans le HOSTId
			switch ($update_ref) {
				case "description" :
					$valide = $this->valide_host_description ();
					break;
				case "ip" :
				default :
					//Par defaut on test l'ip
					$valide = $this->valide_host_ip ();
					break;
			}
			if (! $valide) {
				return $this->onError ( "Ce CI n'existe pas en base. Donc pas d'update possible", "", 5000 );
			}
		} else {
			// En cas d'ajout, on valide qu'il n'y a pas de doublon
			// Si un doublon existe, on stoppe l'ajout
			if ($this->valide_host_description () || $this->valide_host_ip ()) {
				return $this->onError ( "Doublon en base.", "", 5002 );
			}
			$this->setHostId ( 0 );
		}
		
		if (! $this->getHostTemplatesData ()
			->valide_template_by_id ( $this->getTemplate_id () )) {
			return $this->onError ( "Le template n'existe pas", "", 5005 );
		}
		
		$this->onInfo ( "On ajoute CI : " . $this->getDescription () . " IP : " . $this->getIp () );
		
		$host_id = api_device_save ( $this->getHostId (), $this->getTemplate_id (), $this->getDescription (), $this->getIp (), $this->getCommunity (), $this->getSnmpVersion (), $this->getSnmpUsername (), $this->getSnmpPassword (), $this->getSnmpPort (), $this->getSnmpTimeout (), $this->getDisabled (), $this->getAvailability (), $this->getPingMethod (), $this->getPingPort (), $this->getPingTimeout (), $this->getPingRetries (), $this->getNote (), $this->getAuthproto (), $this->getPrivpass (), $this->getPrivproto (), $this->getSnmpContext (), $this->getMaxOids (), $this->getDeviceThreads () );
		
		if (is_error_message ()) {
			return $this->onError ( "Erreur d'ajout/modification de CI", "", 5020 );
		} else {
			$this->setHostId ( $host_id );
			$this->ajouteHosts ( $this->getDescription (), $host_id );
			$this->ajouteHostsByIPs ( $this->getIp (), $host_id );
			$this->onInfo ( "Success - new device-id: ($host_id)" );
			return $host_id;
		}
	}

	/**
	 * Reset les valeurs pour un objet vide.
	 *
	 * @return boolean true
	 * @throws Exception
	 */
	public function reset_host() {
		$this->setHostId ( - 1 );
		$this->setDescription ( "ND" );
		$this->setIp ( "ND" );
		$this->setTemplate_id ( 0 );
		$this->setSnmpVersion ( 1 );
		$this->setCommunity ( "public" );
		$this->setSnmpUsername ( "ND" );
		$this->setSnmpPassword ( "ND" );
		$this->setAuthproto ( "MD5" );
		$this->setPrivpass ( "ND" );
		$this->setPrivproto ( "DES" );
		$this->setSNMPContext ( "" );
		$this->setSNMPPort ( 161 );
		$this->setSNMPTimeout ( 500 );
		$this->setSnmpRetries ( 3 );
		$this->setAvailability ( "snmp" );
		$this->setDisabled ( 0 );
		$this->setNote ( "" );
		$this->setPingMethod ( "udp" );
		$this->setPingPort ( 23 );
		$this->setPingTimeout ( 400 );
		$this->setPingRetries ( 1 );
		$this->setMaxOids ( 10 );
		$this->setDeviceThreads ( 1 );
		
		return true;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getHostId() {
		return $this->host_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostId($host_id) {
		if (is_numeric ( $host_id )) {
			$this->host_id = $host_id;
		}
		
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
	 * @throws Exception
	 */
	public function &setDescription($description) {
		if ($description != "") {
			if ($description == "ND") {
				$this->description = "";
			} else {
				$this->description = $description;
			}
		} else {
			return $this->onError ( "le CI est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIp() {
		return $this->ip;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setIp($ip) {
		if ($ip != "") {
			if ($ip == "ND") {
				$this->ip = "";
			} else {
				$this->ip = $ip;
			}
		} else {
			return $this->onError ( "l'IP est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplate_id() {
		return $this->template_id;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setTemplate_id($Template) {
		if ($Template !== "") {
			$this->template_id = $Template;
		} else {
			return $this->onError ( "le template_id est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpVersion() {
		return $this->snmp_vers;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSnmpVersion($version) {
		if ($version !== "") {
			if ($version == "ND") {
				$this->snmp_vers = "";
			} else {
				$this->snmp_vers = $version;
			}
		} else {
			return $this->onError ( "la version SNMP est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCommunity() {
		return $this->community;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setCommunity($community) {
		if ($community !== "") {
			if ($community == "ND") {
				$this->community = "";
			} else {
				$this->community = $community;
			}
		} else {
			return $this->onError ( "la community est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpUsername() {
		return $this->snmp_username;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSnmpUsername($username) {
		if ($username != "") {
			if ($username == "ND") {
				$this->snmp_username = "";
			} else {
				$this->snmp_username = $username;
			}
		} else {
			return $this->onError ( "la username est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpPassword() {
		return $this->snmp_password;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSnmpPassword($password) {
		if ($password != "") {
			if ($password == "ND") {
				$this->snmp_password = "";
			} else {
				$this->snmp_password = $password;
			}
		} else {
			return $this->onError ( "la password est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAuthproto() {
		return $this->authproto;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setAuthproto($authproto) {
		if ($authproto != "") {
			if ($authproto == "ND") {
				$this->authproto = "";
			} else {
				$this->authproto = $authproto;
			}
		} else {
			return $this->onError ( "la authproto est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrivproto() {
		return $this->privproto;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setPrivproto($privproto) {
		if ($privproto !== "") {
			if ($privproto == "ND") {
				$this->privproto = "";
			} else {
				$this->privproto = $privproto;
			}
		} else {
			return $this->onError ( "la privproto est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrivpass() {
		return $this->privpass;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setPrivpass($privpass) {
		if ($privpass !== "") {
			if ($privpass == "ND") {
				$this->privpass = "";
			} else {
				$this->privpass = $privpass;
			}
		} else {
			return $this->onError ( "la privpass est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpContext() {
		return $this->snmp_context;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSNMPContext($context) {
		$this->snmp_context = $context;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpPort() {
		return $this->snmp_port;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSNMPPort($port) {
		if (is_numeric ( $port )) {
			$this->snmp_port = $port;
		} else {
			return $this->onError ( "Le port est de type integer" );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpRetries() {
		return $this->snmp_retries;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSnmpRetries($snmp_retries) {
		if (is_numeric ( $snmp_retries )) {
			$this->snmp_retries = $snmp_retries;
		} else {
			return $this->onError ( "SNMP retries doit etre de type integer." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpTimeout() {
		return $this->snmp_timeout;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSNMPTimeout($snmp_timeout) {
		if (is_numeric ( $snmp_timeout )) {
			$this->snmp_timeout = $snmp_timeout;
		} else {
			return $this->onError ( "Le timeout est de type integer" );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAvailability() {
		return $this->availability;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setAvailability($availability) {
		switch ($availability) {
			case "none" :
			case "AVAIL_NONE" :
				$this->availability = '0'; /* tried to use AVAIL_NONE, but then ereg failes on validation, sigh */
				break;
			case "ping" :
			case "AVAIL_PING" :
				$this->availability = AVAIL_PING;
				break;
			case "snmp" :
			case "AVAIL_SNMP" :
				$this->availability = AVAIL_SNMP;
				break;
			case "pingsnmp" :
			case "AVAIL_SNMP_AND_PING" :
				$this->availability = AVAIL_SNMP_AND_PING;
				break;
			case "AVAIL_SNMP_GET_SYSDESC" :
				$this->availability = AVAIL_SNMP_GET_SYSDESC;
				break;
			case "AVAIL_SNMP_GET_NEXT" :
				$this->availability = AVAIL_SNMP_GET_NEXT;
				break;
			default :
				return $this->onError ( "l'availability est obligatoire." );
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDisabled() {
		return $this->disabled;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setDisabled($disable) {
		/* validate the disable state */
		if ($disable != 1 && $disable != 0) {
			return $this->onError ( "Le flag est 0 ou 1 ($disable)" );
		}
		
		if ($disable == 0) {
			$this->disabled = "";
		} else {
			$this->disabled = "on";
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNote($note) {
		$this->note = $note;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxOids() {
		return $this->max_oids;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxOids($max_oids) {
		$this->max_oids = $max_oids;
		
		return $this;
	}

	/**
	 * ********************* Ping *******************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getPingMethod() {
		return $this->ping_method;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setPingMethod($ping_method) {
		switch (strtolower ( $ping_method )) {
			case "icmp" :
				$this->ping_method = PING_ICMP;
				break;
			case "tcp" :
				$this->ping_method = PING_TCP;
				break;
			case "udp" :
				$this->ping_method = PING_UDP;
				break;
			default :
				return $this->onError ( "Ping method inconnue : " . $ping_method );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPingPort() {
		return $this->ping_port;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setPingPort($ping_port) {
		if (is_numeric ( $ping_port )) {
			$this->ping_port = $ping_port;
		} else {
			return $this->onError ( "Ping port doit etre de type integer." );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPingTimeout() {
		return $this->ping_timeout;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setPingTimeout($ping_timeout) {
		if (is_numeric ( $ping_timeout )) {
			$this->ping_timeout = $ping_timeout;
		} else {
			return $this->onError ( "Ping timeout doit etre de type integer." );
		}
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPingRetries() {
		return $this->ping_retries;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setPingRetries($ping_retries) {
		if (is_numeric ( $ping_retries )) {
			$this->ping_retries = $ping_retries;
		} else {
			return $this->onError ( "Ping retries doit etre de type integer." );
		}
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDeviceThreads() {
		return $this->device_threads;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setDeviceThreads($device_threads) {
		if (is_numeric ( $device_threads )) {
			$this->device_threads = $device_threads;
		} else {
			return $this->onError ( "device_threads doit etre de type integer." );
		}
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getHostTemplatesData() {
		if (is_null ( $this->templates_data )) {
			$this->setHostTemplatesData ( cacti_hostsTemplates::creer_cacti_hostsTemplates ( $this->getListeOptions (), $this->getSortEnErreur () ) );
		}
		return $this->templates_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostTemplatesData($hostTemplates_data) {
		if ($hostTemplates_data instanceof cacti_hostsTemplates) {
			$this->templates_data = $hostTemplates_data;
		}
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Creer et execute le programme cacti_addDevice";
		$help [__CLASS__] ["text"] [] .= "NECESSITE au moins un fichier de conf machines/cacti.xml";
		$help [__CLASS__] ["text"] [] .= "\t--cacti_env mut/tlt/dev/perso permet de recuperer l'env dans la conf cacti";
		
		return $help;
	}
}
?>
