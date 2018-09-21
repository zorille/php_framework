<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_permissions
 *
 * Permissions :
 * id (required) 	string 	ID of the host group to add permission to.
 * permission (required) 	integer 	Access level to the host group.
 * 		Possible values:
 * 		0 - access denied;
 * 		2 - read-only access;
 * 		3 - read-write access. 
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_permissions extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $hostgrouppermissionid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $hostgroupname = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $permission = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_permission = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_hostgroups
	 */
	private $zabbix_hostgroups = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_permissions.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_permissions
	 */
	static function &creer_zabbix_permissions(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_permissions ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
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
		
		$this->setObjetHostGroups ( zabbix_hostgroups::creer_zabbix_hostgroups ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
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
	 * 
	 * @param string $permission
	 * @return false|zabbix_permissions false en cas d'erreur
	 * @throws Exception
	 */
	public function decoupe_permission($permission) {
		$this->onDebug ( __METHOD__, 1 );
		$donnees_permission = explode ( "|", $permission );
		if (count ( $donnees_permission ) != 2) {
			return $this->onError ( "permission non utilisable : " . $permission );
		}
		$this->setHostGroupName ( $donnees_permission [0] );
		$this->setPermission ( $donnees_permission [1] );
		
		return $this;
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return false|zabbix_permissions
	 * @throws Exception
	 */
	public function retrouve_zabbix_param() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_permission = $this->_valideOption ( array (
				"zabbix",
				"liste_permission" 
		), array () );
		if (! is_array ( $liste_permission )) {
			$liste_permission = array (
					$liste_permission 
			);
		}
		
		foreach ( $liste_permission as $permission ) {
			$this->decoupe_permission ( $permission );
			$this->setAjoutPermissions ( array (
					"name" => $this->getHostGroupName (),
					"permission" => $this->getPermission () 
			) );
		}
		
		return $this;
	}

	/**
	 * Retrouve la liste des Ids de type hostgroup
	 * @return zabbix_permissions
	 * @throws Exception
	 */
	public function retrouve_hostgroupsIds() {
		$this->onDebug ( __METHOD__, 1 );
		//On charge la liste des hostgroups de zabbix en memoire
		$this->getObjetHostGroups ()
			->recherche_liste_groups ();
		
		//On s'occupe des ids
		$liste_permissions = $this->getPermissions ();
		foreach ( $liste_permissions as $position => $permission ) {
			$hostgroupid = $this->getObjetHostGroups ()
				->retrouve_hostgroupId ( $permission ["name"] );
			if ($hostgroupid !== false) {
				$liste_permissions [$position] ["hostgroupid"] = $hostgroupid;
			}
		}
		$this->setPermissions ( $liste_permissions );
		
		return $this;
	}

	/**
	 * Creer un definition d'une liste de permissions pour un usergroup sous forme de tableau
	 *
	 * @return array;
	 */
	public function creer_definition_permissions_create_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$permissions = array ();
		foreach ( $this->getPermissions () as $permission ) {
			if (isset ( $permission ["hostgroupid"] )) {
				$permissions [count ( $permissions )] = array (
						"permission" => $permission ["permission"],
						"id" => $permission ["hostgroupid"] 
				);
			}
		}
		
		return $permissions;
	}

	/**
	 * 0 - denied : access denied;
 	 * 2 - read-only : read-only access;
 	 * 3 - read-write : read-write access.
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Permission($permission) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $permission )) {
			return $permission;
		}
		switch (strtolower ( $permission )) {
			case "read-only" :
				return 2;
				break;
			case "read-write" :
				return 3;
				break;
			case "denied" :
			default :
		}
		
		return 0;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getHostGroupPermissionId() {
		return $this->hostgrouppermissionid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostGroupPermissionId($hostgrouppermissionid) {
		$this->hostgrouppermissionid = $hostgrouppermissionid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHostGroupName() {
		return $this->hostgroupname;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostGroupName($hostgroupname) {
		$this->hostgroupname = $hostgroupname;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPermission() {
		return $this->permission;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPermission($permission) {
		$this->permission = $this->retrouve_Permission ( $permission );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPermissions() {
		return $this->liste_permission;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPermissions($permissions) {
		$this->liste_permission = $permissions;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutPermissions($permission) {
		$this->liste_permission [count ( $this->liste_permission )] = $permission;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_hostgroups
	 */
	public function &getObjetHostGroups() {
		return $this->zabbix_hostgroups;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetHostGroups(&$zabbix_hostgroups) {
		$this->zabbix_hostgroups = $zabbix_hostgroups;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Liste Permissions :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_liste_permission '2|denied' '3|read-only' '4|read-write'";
		$help = array_merge ( $help, zabbix_hostgroups::help () );
		
		return $help;
	}
}
?>
