<?php
/**
 * Gestion de SiteScope.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class sitescope_template_datas
 *
 * @package Lib
 * @subpackage SiteScope
 */
class sitescope_template_datas extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $type_os = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $type_os_global = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $CI = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $IP = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $schedule = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $disk = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $dns = "";
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
	 * @var array
	 */
	private $services = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $scripts = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type sitescope_template_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return sitescope_template_datas
	 */
	static function &creer_sitescope_template_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new sitescope_template_datas ( $entete, $sort_en_erreur );
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
	public function __construct($entete = __CLASS__, $sort_en_erreur = false) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Nettoie les vaariables de l'objet en cours
	 * @return sitescope_template_datas
	 */
	public function reset_datas() {
		$this->onDebug ( "On reset la liste des parametres", 2 );
		$this->setOS ( "" )
			->setCI ( "" )
			->setIPs ( array () )
			->setSchedule ( "" )
			->setDisks ( array () )
			->setServices ( array () )
			->setDNS ( "" )
			->setFQDN ( "" )
			->setScript ( "reset" );
		
		return $this;
	}

	/**
	 * Retrouve le type d'OS (WINDOWS,LINUX,UNIX) et fonction de l'OS defini
	 * @return boolean|sitescope_template_datas
	 * @throws Exception
	 */
	public function prepare_OS_global() {
		switch ($this->getOS ()) {
			case "WINDOWS" :
				$this->setOSGlobal ( "WINDOWS" );
				break;
			case "LINUX" :
				$this->setOSGlobal ( "LINUX" );
				break;
			case "UNIX : UNIX" :
			case "UNIX : AIX" :
			case "UNIX : HP/UX" :
			case "UNIX : HP-UX" :
			case "UNIX : HP/UX 64-bit" :
				$this->setOSGlobal ( "UNIX" );
				break;
			case "" :
				$this->setOSGlobal ( "" );
				break;
			default :
				return $this->onError ( "OS introuvable : " . $this->getOS () );
		}
		
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getOS() {
		return $this->type_os;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setOS($OS) {
		$this->type_os = $OS;
		$this->prepare_OS_global ();
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOSGlobal() {
		return $this->type_os_global;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setOSGlobal($OS) {
		$this->type_os_global = $OS;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCI() {
		return $this->CI;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setCI($CI) {
		$this->CI = $CI;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIPs() {
		return $this->IP;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setIPs($IP) {
		if (! is_array ( $IP )) {
			return $this->onError ( "Il faut un tableau d'IP" );
		}
		$this->IP = $IP;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function AjouteIP($IP) {
		if (filter_var ( $IP, FILTER_VALIDATE_IP ) === false) {
			return $this->onError ( "Adresse IP Invalide" );
		}
		if (! in_array ( $IP, $this->IP )) {
			$this->IP [] .= $IP;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSchedule() {
		return $this->schedule;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setSchedule($schedule) {
		if (stripos ( $schedule, "permanent" ) !== false) {
			$this->schedule = "PERMANENT";
		} elseif (stripos ( $schedule, "etendu" ) !== false) {
			$this->schedule = "ETENDU";
		} elseif (stripos ( $schedule, "normal" ) !== false) {
			$this->schedule = "NORMAL";
		} elseif ($schedule == "") {
			$this->schedule = "";
		} else {
			return $this->onError ( "Schedule inconnu : " . $schedule );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDisks() {
		return $this->disk;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setDisks($liste_disks) {
		$this->disk = $liste_disks;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function AjouteDisk($disk) {
		$this->disk [] .= $disk;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getServices() {
		return $this->services;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setServices($liste_services) {
		$this->services = $liste_services;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function AjouteService($service) {
		$this->services [] .= str_replace ( "(", "\(", str_replace ( ")", "\)", $service ) );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getScripts() {
		return $this->scripts;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setScript($script) {
		if (stripos ( $script, "backup" ) !== false) {
			$this->scripts ["BACKUP"] = true;
		} elseif (stripos ( $script, "fstab" ) !== false) {
			$this->scripts ["FSTAB"] = true;
		} elseif (stripos ( $script, "inode" ) !== false) {
			$this->scripts ["INODE"] = true;
		} elseif (stripos ( $script, "lvm" ) !== false) {
			$this->scripts ["LVM"] = true;
		} elseif (stripos ( $script, "fs_ro" ) !== false) {
			$this->scripts ["READONLY"] = true;
		} elseif ($script == "reset") {
			$this->scripts = array ();
		} else {
			$script = str_replace ( "check_", "", $script );
			$script = str_replace ( ".sh", "", $script );
			$this->scripts [strtoupper ( $script )] = true;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDNS() {
		return $this->dns;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setDNS($ip_dns) {
		$this->dns = $ip_dns;
		
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
	public function setFQDN($fqdn) {
		$this->fqdn = $fqdn;
		
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
		
		return $help;
	}

	/**
	 * (non-PHPdoc)
	 * @codeCoverageIgnore
	 * @see lib/fork/message#__destruct()
	 */
	public function __destruct() {
	}
}
?>
