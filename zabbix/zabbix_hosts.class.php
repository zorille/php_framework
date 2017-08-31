<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_hosts
 *
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_hosts extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_host = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_host_cli = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_host
	 */
	private $zabbix_host_reference = NULL;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type zabbix_hosts. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_hosts
	 */
	static function &creer_zabbix_hosts(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_hosts ( $sort_en_erreur, $entete );
		return $objet ->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws ) );
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->setObjetHostRef ( zabbix_host::creer_zabbix_host ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @param boolean $necessaire Indique si le parametre zabbix_hosts est necessaire ou non
	 * @return false zabbix_hosts
	 * @throws Exception
	 */
	public function retrouve_zabbix_param($necessaire = true) {
		$this ->onDebug ( __METHOD__, 1 );
		
		//Gestion des hosts
		$liste_hosts = $this ->retrouve_liste_hosts ( $necessaire );
		
		$liste = array ();
		foreach ( $liste_hosts as $host ) {
			$objet_host = $this ->creer_host ( array (
					"host" => $host ) );
			$liste [$host] = $objet_host;
			$this ->setAjoutHost ( $objet_host );
		}
		
		$this ->setListeHostCli ( $liste );
		
		return $this;
	}

	/**
	 * Retrouve le parametre zabbix_hosts dans la ligne de commande/fichier de conf
	 * @param boolean $necessaire Indique si le parametre zabbix_hosts est necessaire ou non
	 * @return array
	 * @throws Exception
	 */
	public function retrouve_liste_hosts($necessaire) {
		if ($necessaire) {
			$liste_hosts = $this ->_valideOption ( array (
					"zabbix",
					"hosts" ) );
		} else {
			$liste_hosts = $this ->_valideOption ( array (
					"zabbix",
					"hosts" ), array () );
		}
		if (! is_array ( $liste_hosts )) {
			if ($liste_hosts == "") {
				$liste_hosts = array ();
			} else {
				$liste_hosts = array (
						$liste_hosts );
			}
		}
		
		return $liste_hosts;
	}

	/**
	 * Recupere la liste des hosts defini dans zabbix
	 * @return zabbix_hosts
	 */
	public function &recherche_liste_hosts() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_hosts_zabbix = $this ->getObjetZabbixWsclient () 
			->hostGet ( array (
				"output" => "extend" ) );
		foreach ( $liste_hosts_zabbix as $host_zabbix ) {
			if ($host_zabbix ['host'] != "") {
				$objet_host = $this ->creer_host ( $host_zabbix );
				$this ->setAjoutHost ( $objet_host );
			}
		}
		
		return $this;
	}

	/**
	 * Recupere la liste des hosts passe en argument par rapport a la liste defini dans zabbix
	 * @return zabbix_hosts
	 */
	public function &valide_liste_hosts() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_hosts_zabbix = $this ->getObjetZabbixWsclient () 
			->hostGet ( array (
				"output" => "extend" ) );
		$liste_finale = array ();
		foreach ( $liste_hosts_zabbix as $host_zabbix ) {
			if ($host_zabbix ['host'] != "") {
				$objet_host = $this ->creer_host ( $host_zabbix );
				foreach ( $this ->getListeHostCli () as $objhost_cli ) {
					if ($objet_host ->compare_host ( $objhost_cli )) {
						$liste_finale [$objet_host ->getHost ()] = $objet_host;
						continue 2;
					}
				}
			}
		}
		$this ->setListeHost ( $liste_finale );
		
		return $this;
	}

	/**
	 * Creer un objet zabbix_host a partir d'un tableau.
	 * @param array $host_zabbix
	 * @return zabbix_host
	 */
	public function &creer_host($host_zabbix) {
		$this ->onDebug ( __METHOD__, 1 );
		$objet_host = clone $this ->getObjetHostRef ();
		if (isset ( $host_zabbix ["hostid"] )) {
			$objet_host ->setHostId ( $host_zabbix ["hostid"] );
		}
		$objet_host ->setHost ( $host_zabbix ["host"] );
		
		return $objet_host;
	}

	/**
	 * Ajoute a l'objet en cours tous les hosts de $liste_hosts non existant
	 * @param array $liste_hosts
	 * @return zabbix_hosts
	 */
	public function ajoute_hosts($liste_hosts) {
		$this ->onDebug ( __METHOD__, 1 );
		foreach ( $liste_hosts as $host ) {
			$objet_host = $this ->creer_host ( $host );
			foreach ( $this ->getListeHost () as $obj_host ) {
				if ($obj_host ->compare_host ( $objet_host )) {
					continue 2;
				}
			}
			//on ajoute le host
			$this ->setAjoutHost ( $objet_host );
		}
		
		return $this;
	}

	/**
	 * Supprime de l'objet zabbix_hosts les hosts existant dans la liste $liste_hosts
	 * @param array $liste_hosts
	 * @return zabbix_hosts
	 */
	public function supprime_hosts($liste_hosts) {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_host_finale = array ();
		foreach ( $liste_hosts as $host_name => $host ) {
			$objet_host = $this ->creer_host ( $host );
			foreach ( $this ->getListeHost () as $obj_host ) {
				if ($objet_host ->compare_host ( $obj_host )) {
					//si on trouve une correspondance, on ne l'ajoute pas a la liste finale
					continue 2;
				}
			}
			$liste_host_finale [$objet_host ->getHost ()] = $objet_host;
		}
		$this ->setListeHost ( $liste_host_finale );
		
		return $this;
	}

	/**
	 * Creer un definition de toutes les hosts listees dans la class
	 * @return array;
	 */
	public function creer_definition_hosts_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$donnees_hosts = array ();
		
		foreach ( $this ->getListeHost () as $host ) {
			$donnees_hosts [count ( $donnees_hosts )] = $host ->creer_definition_host_ws ();
		}
		
		return $donnees_hosts;
	}

	/**
	 * Creer un tableau de hostids
	 * @return array
	 */
	public function creer_definition_hostids_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this ->getListeHost () as $host ) {
			$liste_id [count ( $liste_id )] ["hostid"] = $host ->getHostId ();
		}
		
		return $liste_id;
	}

	/**
	 * Creer un tableau de hostids
	 * @return array
	 */
	public function creer_definition_hostids_sans_champ_hostid_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this ->getListeHost () as $host ) {
			$liste_id [count ( $liste_id )] = $host ->getHostId ();
		}
		
		return $liste_id;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeHost() {
		return $this->liste_host;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeHost($liste_host) {
		$this->liste_host = $liste_host;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutHost(&$host) {
		$this->liste_host [$host ->getHost ()] = $host;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeHostCli() {
		return $this->liste_host_cli;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeHostCli($liste_host_cli) {
		$this->liste_host_cli = $liste_host_cli;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_host
	 */
	public function &getObjetHostRef() {
		return $this->zabbix_host_reference;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetHostRef(&$zabbix_host_reference) {
		$this->zabbix_host_reference = $zabbix_host_reference;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Hosts :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_hosts 'host 1' 'host 2' ... liste des hosts";
		$help = array_merge ( $help, zabbix_host::help () );
		
		return $help;
	}
}
?>
