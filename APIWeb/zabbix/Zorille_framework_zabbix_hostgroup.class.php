<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_hostgroup
 * groupid 	string 	(readonly) ID of the host group.
 * name (required) 	string 	Name of the host group.
 * flags 	integer 	(readonly) Origin of the host group.
 * 		Possible values:
 * 		0 - a plain host group;
 * 		4 - a discovered host group.
 * internal 	integer 	(readonly) Whether the group is used internally by the system. An internal group cannot be deleted.
 * 		Possible values:
 * 		0 - (default) not internal;
 * 		1 - internal. 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_hostgroup extends zabbix_fonctions_standard {
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
	 * @var string
	 */
	private $groupid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $flags = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $internal = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_hostgroup.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_hostgroup
	 */
	static function &creer_zabbix_hostgroup(options &$liste_option, zabbix_wsclient &$zabbix_ws, bool|string $sort_en_erreur = false, string $entete = __CLASS__): zabbix_hostgroup
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_hostgroup ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_hostgroup
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
	 * @return zabbix_hostgroup True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		//Gestion des hostgroup
		$hostgroup = $this->_valideOption ( array (
				"zabbix",
				"host",
				"group" 
		) );
		$this->setName ( $hostgroup );
		
		return $this;
	}

	/**
	 * Creer un definition d'un hostGroup sous forme de tableau
	 * 
	 * @return array;
	 */
	public function creer_definition_hostGroup_create_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"name" => $this->getName ()
		);
	}

	/**
	 * Creer un hostGroup dans zabbix
	 *
	 * @return array
	 * @throws Exception
	 */
	public function creer_hostGroup(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_hostGroup_create_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->hostgroupCreate ( $datas );
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getGroupsId(): string
	{
		return $this->groupid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGroupsId($groupid): static
	{
		$this->groupid = $groupid;
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
	public function getFlags(): int
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
	public function getInternal(): int|string
	{
		return $this->internal;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInternal($internal): static
	{
		$this->internal = $internal;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix hostGroup :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_host_group 'groupe' groupe d'un host";
		
		return $help;
	}
}
