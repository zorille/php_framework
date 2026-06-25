<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use Exception;

/**
 * class zabbix_usergroup
 *
 * usrgrpid 	string 	(readonly) ID of the user group.
 * name (required) 	string 	Name of the user group.
 * debug_mode 	integer 	Whether debug mode is enabled or disabled.
 * 		Possible values are:
 * 		0 - (default) disabled;
 * 		1 - enabled.
 * gui_access 	integer 	Frontend authentication method of the users in the group.
 * 		Possible values:
 * 		0 - (default) use the system default authentication method;
 * 		1 - use internal authentication;
 * 		2 - disable access to the frontend.
 * users_status 	integer 	Whether the user group is enabled or disabled.
 * 		Possible values are:
 * 		0 - (default) enabled;
 * 		1 - disabled. 
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_usergroup extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $usrgrpid = "";
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
	private $debug_mode = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $gui_access = "0";
	/**
	 * var privee
	 * 
	 * @access private
	 * @var string
	 */
	private $users_status = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_permissions
	 */
	private $liste_permission = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_usergroup.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_usergroup|abstract_log
	 */
	static function &creer_zabbix_usergroup(options &$liste_option, zabbix_wsclient &$zabbix_ws, bool|string $sort_en_erreur = false, string $entete = __CLASS__): zabbix_usergroup|abstract_log
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_usergroup ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_usergroup
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		$this->setObjetZabbixWsclient ( $liste_class ["zabbix_wsclient"] )
			->setObjetListePermissions ( zabbix_permissions::creer_zabbix_permissions ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
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
	 * @return bool|zabbix_usergroup True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param(): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setName ( $this->_valideOption ( array (
				"zabbix",
				"usergroup",
				"nom" 
		) ) );
		$this->setDebugMode ( $this->_valideOption ( array (
				"zabbix",
				"usergroup",
				"debug_mode" 
		), "disabled" ) );
		$this->setGuiAccess ( $this->_valideOption ( array (
				"zabbix",
				"usergroup",
				"gui_access" 
		), "system" ) );
		$this->setUsersStatus ( $this->_valideOption ( array (
				"zabbix",
				"usergroup",
				"users_status" 
		), "enabled" ) );
		$this->getObjetListePermissions ()
			->retrouve_zabbix_param ();
		
		return $this;
	}

	/**
	 * Compare un objet de type zabbix_usergroup avec l'objet en cours
	 * @param zabbix_usergroup $zabbix_usergroup_compare
	 * @return boolean True si les usergroups correspondent, false sinon
	 */
	public function compare_usergroup(zabbix_usergroup $zabbix_usergroup_compare): bool
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getUsrgrpId () !== "" && $zabbix_usergroup_compare->getUsrgrpId () != "" && $zabbix_usergroup_compare->getUsrgrpId () != $this->getUsrgrpId ()) {
			return false;
		}
		if ($zabbix_usergroup_compare->getName () != $this->getName ()) {
			return false;
		}
		if ($zabbix_usergroup_compare->getDebugMode () != $this->getDebugMode ()) {
			return false;
		}
		if ($zabbix_usergroup_compare->getGuiAccess () != $this->getGuiAccess ()) {
			return false;
		}
		if ($zabbix_usergroup_compare->getUsersStatus () != $this->getUsersStatus ()) {
			return false;
		}
		
		return true;
	}

	/**
	 * Creer un definition d'un userGroup sous forme de tableau
	 *  
	 * @return array;
	 */
	public function creer_definition_userGroup_create_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"name" => $this->getName (),
				"debug_mode" => $this->getDebugMode (),
				"gui_access" => $this->getGuiAccess (),
				"users_status" => $this->getUsersStatus (),
				"rights" => $this->getObjetListePermissions ()
					->getPermissions (),
				"userids" => array ()
		);
	}

	/**
	 * Creer un userGroup dans zabbix
	 *
	 * @return array
	 * @throws Exception
	 */
	public function creer_userGroup(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_userGroup_create_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->usergroupCreate ( $datas );
	}

	/**
	 * Creer un definition d'un userGroup sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_userGroup_delete_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$usrgrpid = array ();
		
		if ($this->getUsrgrpId () != "") {
			$usrgrpid [] .= $this->getUsrgrpId ();
		}
		
		return $usrgrpid;
	}

	/**
	 * supprime un userGroup dans zabbix
	 * @return array
	 * @throws Exception
	 */
	public function supprime_userGroup(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_userGroup_delete_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->usergroupDelete ( $datas );
	}

	/**
	 * Creer un definition d'un userGroup sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_userGroup_get_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"output" => "usrgrpid",
				"filter" => array (
						"name" => $this->getName () 
				) 
		);
	}

	/**
	 * recherche un userGroup dans zabbix a partir de son sendto
	 * @return array
	 * @throws Exception
	 */
	public function recherche_userGroup(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_userGroup_get_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->usergroupGet ( $datas );
	}

	/**
	 * Creer un definition d'une recherche d'un userGroupid sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_usrgrpidByName_get_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"output" => "usrgrpid",
				"filter" => array (
						"name" => $this->getName () 
				) 
		);
	}

	/**
	 * Creer un definition d'un userid sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_usrgrpid_get_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"usrgrpid" => $this->getUsrgrpId () 
		);
	}

	/**
	 * recherche un userGroupId dans zabbix a partir de son nom
	 * @return array|zabbix_usergroup
	 * @throws Exception
	 */
	public function recherche_userGroupid_by_Name(): array|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_usrgrpidByName_get_ws ();
		$this->onDebug ( $datas, 1 );
		$liste_resultat = $this->getObjetZabbixWsclient ()
			->usergroupGet ( $datas );
		if (isset ( $liste_resultat [0] ) && isset ( $liste_resultat [0] ["usrgrpid"] )) {
			$this->setUsrGrpId ( $liste_resultat [0] ["usrgrpid"] );
		}
		
		return $this;
	}

	/**
	 * 0 - disabled;
	 * 1 - enabled;
	 * @param $debug_mode
	 * @return float|int|string
	 */
	public function retrouve_DebugMode($debug_mode): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $debug_mode )) {
			return $debug_mode;
		}
		return match (strtolower($debug_mode)) {
			"enabled" => 1,
			default => 0,
		};

	}

	/**
	 * 0 - system : (default) use the system default authentication method;
	 * 1 - internal : use internal authentication;
	 * 2 - frontend : disable access to the frontend.
	 * @param $gui_access
	 * @return float|int|string
	 */
	public function retrouve_GuiAccess($gui_access): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $gui_access )) {
			return $gui_access;
		}
		return match (strtolower($gui_access)) {
			"internal" => 1,
			"frontend" => 2,
			default => 0,
		};

	}

	/**
	 * 0 - enabled;
	 * 1 - disabled;
	 * @param $users_status
	 * @return float|int|string
	 */
	public function retrouve_UsersStatus($users_status): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $users_status )) {
			return $users_status;
		}
		return match (strtolower($users_status)) {
			"disabled" => 1,
			default => 0,
		};

	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getUsrgrpId(): string
	{
		return $this->usrgrpid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsrgrpId($usrgrpid): static
	{
		$this->usrgrpid = $usrgrpid;
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
	public function getDebugMode(): string
	{
		return $this->debug_mode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDebugMode($debug_mode): static
	{
		$this->debug_mode = $this->retrouve_DebugMode ( $debug_mode );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGuiAccess(): string
	{
		return $this->gui_access;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGuiAccess($gui_access): static
	{
		$this->gui_access = $this->retrouve_GuiAccess ( $gui_access );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsersStatus(): string
	{
		return $this->users_status;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsersStatus($users_status): static
	{
		$this->users_status = $this->retrouve_UsersStatus ( $users_status );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetListePermissions(): ?zabbix_permissions
	{
		return $this->liste_permission;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetListePermissions(&$liste_permission): static
	{
		$this->liste_permission = $liste_permission;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix UserGroup :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usergroup_nom Nom du userGroup";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usergroup_debug_mode enabled/disabled";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usergroup_gui_access system/internal/frontend";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usergroup_users_status enabled/disabled";
		
		return $help;
	}
}
