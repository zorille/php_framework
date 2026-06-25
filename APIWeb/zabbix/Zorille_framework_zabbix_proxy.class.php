<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_proxy 
 * proxyid string (readonly) ID of the proxy. 
 * host (required) string Name of the proxy. 
 * status (required) integer Type of proxy. 
 *  Possible values: 
 *   5 - active proxy; 
 *   6 - passive proxy. 
 * lastaccess timestamp (readonly) Time when the proxy last connected to the server.
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_proxy extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $proxyId = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $proxy = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $status = 5;
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $lastaccess = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_proxy_interface
	 */
	private $proxy_interface = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_hosts
	 */
	private $hosts = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type zabbix_proxy. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return abstract_log|zabbix_proxy
	 */
	static function &creer_zabbix_proxy(options &$liste_option, zabbix_wsclient &$zabbix_ws, bool|string $sort_en_erreur = false, string $entete = __CLASS__): abstract_log|zabbix_proxy
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_proxy ( $sort_en_erreur, $entete );
		return $objet ->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws ) );
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_proxy
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		$this ->setObjetInterface ( zabbix_proxy_interface::creer_zabbix_proxy_interface ( $liste_class ["options"] ) ) 
			->setObjetHosts ( zabbix_hosts::creer_zabbix_hosts ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return zabbix_proxy True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param($nom_seulement = false): static
	{
		$this ->onDebug ( __METHOD__, 1 );
		//Gestion d'un host
		$this ->setProxy ( $this ->_valideOption ( array (
				"zabbix",
				"proxy",
				"name" ) ) );
		if ($nom_seulement === false) {
			$this ->setStatus ( $this ->_valideOption ( array (
					"zabbix",
					"proxy",
					"status" ) ) );
			//Si le proxy est passif, on ajoute son interface
			if ($this ->getStatus () == 6) {
				$this ->getObjetInterface () 
					->retrouve_zabbix_param ();
			}
			try {
				$this ->getObjetHosts () 
					->retrouve_zabbix_param ( false );
			} catch ( Exception $e ) {}
		}
		
		return $this;
	}

	/**
	 * Creer une definition d'un proxy sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_proxy_create_ws(): array
	{
		$this ->onDebug ( __METHOD__, 1 );
		return array (
				"host" => $this ->getProxy (),
				"status" => $this ->getStatus (),
				"interface" => $this ->getObjetInterface ()
					->creer_definition_proxy_interface_ws (),
				"hosts" => $this ->getObjetHosts ()
					->creer_definition_hostids_ws () );
	}

	/**
	 * Creer un proxy dans zabbix
	 * @return array
	 * @throws Exception
	 */
	public function creer_proxy(): array
	{
		$this ->onDebug ( __METHOD__, 1 );
		$datas = $this ->creer_definition_proxy_create_ws ();
		$this ->onDebug ( $datas, 1 );
		return $this ->getObjetZabbixWsclient () 
			->proxyCreate ( $datas );
	}

	/**
	 * Creer un definition d'un proxy sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_proxy_delete_ws(): array
	{
		$this ->onDebug ( __METHOD__, 1 );
		$proxyid = array ();
		
		if ($this ->getProxyId () != "") {
			$proxyid [] .= $this ->getProxyId ();
		}
		
		return $proxyid;
	}

	/**
	 * supprime un proxy dans zabbix
	 * @return array
	 * @throws Exception
	 */
	public function supprime_proxy(): array
	{
		$this ->onDebug ( __METHOD__, 1 );
		$liste_proxyids = $this ->recherche_proxy ();
		foreach ( $liste_proxyids as $proxyid ) {
			if (isset ( $proxyid ['proxyid'] )) {
				$this ->setProxyId ( $proxyid ['proxyid'] );
				$datas = $this ->creer_definition_proxy_delete_ws ();
				$this ->onDebug ( $datas, 1 );
				return $this ->getObjetZabbixWsclient () 
					->proxyDelete ( $datas );
			}
		}
		return array ();
	}

	/**
	 * Creer un definition d'un proxy sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_proxy_get_ws(): array
	{
		$this ->onDebug ( __METHOD__, 1 );
		return array (
				"output" => "proxyid",
				"filter" => array (
						"host" => $this ->getProxy () ) );
	}

	/**
	 * recherche un proxy dans zabbix a partir de son sendto
	 * @return array
	 * @throws Exception
	 */
	public function recherche_proxy(): array
	{
		$this ->onDebug ( __METHOD__, 1 );
		$datas = $this ->creer_definition_proxy_get_ws ();
		$this ->onDebug ( $datas, 1 );
		return $this ->getObjetZabbixWsclient () 
			->proxyGet ( $datas );
	}

	/**
	 * 5 - active; 6 - passive;
	 * @param string $type
	 * @return float|int|string
	 */
	public function retrouve_Status(string $type): float|int|string
	{
		$this ->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		return match (strtolower($type)) {
			"passive" => 6,
			default => 5,
		};

	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getProxyId(): int|string
	{
		return $this->proxyId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProxyId($proxyId): static
	{
		$this->proxyId = $proxyId;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getProxy(): string
	{
		return $this->proxy;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProxy($proxy): static
	{
		$this->proxy = $proxy;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setStatus($status): static
	{
		$this->status = $this ->retrouve_Status ( $status );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLastAccess(): int
	{
		return $this->lastaccess;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLastAccess($lastaccess): static
	{
		$this->lastaccess = $lastaccess;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_proxy_interface|null
	 */
	public function &getObjetInterface(): ?zabbix_proxy_interface
	{
		return $this->proxy_interface;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetInterface(&$ProxyInterface): static
	{
		$this->proxy_interface = $ProxyInterface;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_hosts|null
	 */
	public function &getObjetHosts(): ?zabbix_hosts
	{
		return $this->hosts;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetHosts(&$Hosts): static
	{
		$this->hosts = $Hosts;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Proxy :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_proxy_name ci.client.fr.ghc.local Nom visuel du Proxy";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_proxy_status active/passive Type de Proxy : actif ou passif";
		$help = array_merge ( $help, zabbix_proxy_interface::help () );
		$help = array_merge ( $help, zabbix_hosts::help () );
		
		return $help;
	}
}
