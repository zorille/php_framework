<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_addDevice
	 * @throws Exception
	 */
	static function &creer_cacti_addDevice(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): cacti_addDevice {
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
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/

	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @throws Exception
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
	public function prepareVariablescacti_addDevice(): bool {
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
	public function valide_host_description(): bool {
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
	public function valide_host_ip(): bool {
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
	public function valide_SNMP(): bool {
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
	public function executeCacti_AddDevice($update = false, $update_ref = "none"): int {
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
			$valide = match ($update_ref) {
				"description" => $this->valide_host_description(),
				default => $this->valide_host_ip(),
			};
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
	public function reset_host(): bool {
		$this->setHostId ( - 1 )
			->setDescription ( "ND" )
			->setIp ( "ND" )
			->setTemplate_id ( 0 )
			->setSnmpVersion ( 1 )
			->setCommunity ( "public" )
			->setSnmpUsername ( "ND" )
			->setSnmpPassword ( "ND" )
			->setAuthproto ( "MD5" )
			->setPrivpass ( "ND" )
			->setPrivproto ( "DES" )
			->setSNMPContext ( "" )
			->setSNMPPort ( 161 )
			->setSNMPTimeout ( 500 )
			->setSnmpRetries ( 3 )
			->setAvailability ( "snmp" )
			->setDisabled ( 0 )
			->setNote ( "" )
			->setPingMethod ( "udp" )
			->setPingPort ( 23 )
			->setPingTimeout ( 400 );
		$this->setPingRetries ( 1 );
		$this->setMaxOids ( 10 )
			 ->setDeviceThreads ( 1 );
		
		return true;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getHostId(): int {
		return $this->host_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostId($host_id): static {
		if (is_numeric ( $host_id )) {
			$this->host_id = $host_id;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDescription(): string {
		return $this->description;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setDescription($description): bool|static {
		if ($description != "") {
			if ($description == "ND") {
				$this->description = "";
			} else {
				$this->description = $description;
			}
		} else {
			$r = $this->onError ( "le CI est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIp(): string {
		return $this->ip;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setIp($ip): bool|static {
		if ($ip != "") {
			if ($ip == "ND") {
				$this->ip = "";
			} else {
				$this->ip = $ip;
			}
		} else {
			$r = $this->onError ( "l'IP est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplate_id(): int|string {
		return $this->template_id;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setTemplate_id($Template): bool|static {
		if ($Template !== "") {
			$this->template_id = $Template;
		} else {
			$r = $this->onError ( "le template_id est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpVersion(): int|string {
		return $this->snmp_vers;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSnmpVersion($version): bool|static {
		if ($version !== "") {
			if ($version == "ND") {
				$this->snmp_vers = "";
			} else {
				$this->snmp_vers = $version;
			}
		} else {
			$r = $this->onError ( "la version SNMP est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCommunity(): string {
		return $this->community;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setCommunity($community): bool|static {
		if ($community !== "") {
			if ($community == "ND") {
				$this->community = "";
			} else {
				$this->community = $community;
			}
		} else {
			$r = $this->onError ( "la community est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpUsername(): string {
		return $this->snmp_username;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSnmpUsername($username): bool|static {
		if ($username != "") {
			if ($username == "ND") {
				$this->snmp_username = "";
			} else {
				$this->snmp_username = $username;
			}
		} else {
			$r = $this->onError ( "la username est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpPassword(): string {
		return $this->snmp_password;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSnmpPassword($password): bool|static {
		if ($password != "") {
			if ($password == "ND") {
				$this->snmp_password = "";
			} else {
				$this->snmp_password = $password;
			}
		} else {
			$r = $this->onError ( "la password est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAuthproto(): string {
		return $this->authproto;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setAuthproto($authproto): bool|static {
		if ($authproto != "") {
			if ($authproto == "ND") {
				$this->authproto = "";
			} else {
				$this->authproto = $authproto;
			}
		} else {
			$r = $this->onError ( "la authproto est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrivproto(): string {
		return $this->privproto;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setPrivproto($privproto): bool|static {
		if ($privproto !== "") {
			if ($privproto == "ND") {
				$this->privproto = "";
			} else {
				$this->privproto = $privproto;
			}
		} else {
			$r = $this->onError ( "la privproto est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrivpass(): string {
		return $this->privpass;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setPrivpass($privpass): bool|static {
		if ($privpass !== "") {
			if ($privpass == "ND") {
				$this->privpass = "";
			} else {
				$this->privpass = $privpass;
			}
		} else {
			$r = $this->onError ( "la privpass est obligatoire." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpContext(): string {
		return $this->snmp_context;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSNMPContext($context): static {
		$this->snmp_context = $context;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpPort(): int {
		return $this->snmp_port;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSNMPPort($port): bool|static {
		if (is_numeric ( $port )) {
			$this->snmp_port = $port;
		} else {
			$r = $this->onError ( "Le port est de type integer" );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpRetries(): int {
		return $this->snmp_retries;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSnmpRetries($snmp_retries): bool|static {
		if (is_numeric ( $snmp_retries )) {
			$this->snmp_retries = $snmp_retries;
		} else {
			$r = $this->onError ( "SNMP retries doit etre de type integer." );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnmpTimeout(): int {
		return $this->snmp_timeout;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSNMPTimeout($snmp_timeout): bool|static {
		if (is_numeric ( $snmp_timeout )) {
			$this->snmp_timeout = $snmp_timeout;
		} else {
			$r = $this->onError ( "Le timeout est de type integer" );
			return $r;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAvailability(): string {
		return $this->availability;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setAvailability($availability): bool|static {
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
	public function getDisabled(): string {
		return $this->disabled;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setDisabled($disable): bool|static {
		/* validate the disable state */
		if ($disable != 1 && $disable != 0) {
			$r = $this->onError ( "Le flag est 0 ou 1 ($disable)" );
			return $r;
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
	public function getNote(): string {
		return $this->note;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNote($note): static {
		$this->note = $note;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxOids(): string {
		return $this->max_oids;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxOids($max_oids): static {
		$this->max_oids = $max_oids;
		
		return $this;
	}

	/**
	 * ********************* Ping *******************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getPingMethod(): string {
		return $this->ping_method;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setPingMethod($ping_method): bool|static {
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
				$r = $this->onError ( "Ping method inconnue : " . $ping_method );
				return $r;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPingPort(): int {
		return $this->ping_port;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setPingPort($ping_port): bool|static {
		if (is_numeric ( $ping_port )) {
			$this->ping_port = $ping_port;
		} else {
			$r = $this->onError ( "Ping port doit etre de type integer." );
			return $r;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPingTimeout(): int {
		return $this->ping_timeout;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setPingTimeout($ping_timeout): bool {
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
	public function getPingRetries(): int {
		return $this->ping_retries;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setPingRetries($ping_retries): bool {
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
	public function getDeviceThreads(): int {
		return $this->device_threads;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setDeviceThreads($device_threads): bool {
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
	public function &getHostTemplatesData(): ?cacti_hostsTemplates {
		if (is_null ( $this->templates_data )) {
			$this->setHostTemplatesData ( cacti_hostsTemplates::creer_cacti_hostsTemplates ( $this->getListeOptions (), $this->getSortEnErreur () ) );
		}
		return $this->templates_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostTemplatesData($hostTemplates_data): static {
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
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Creer et execute le programme cacti_addDevice";
		$help [__CLASS__] ["text"] [] .= "NECESSITE au moins un fichier de conf machines/cacti.xml";
		$help [__CLASS__] ["text"] [] .= "\t--cacti_env mut/tlt/dev/perso permet de recuperer l'env dans la conf cacti";
		
		return $help;
	}
}
