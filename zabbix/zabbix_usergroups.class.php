<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_usergroups
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_usergroups extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_group = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_group_cli = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_user_group
	 */
	private $zabbix_group_reference = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_usergroups.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_usergroups
	 */
	static function &creer_zabbix_usergroups(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_usergroups ( $sort_en_erreur, $entete );
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
		
		$this->setObjetUserGroupRef ( zabbix_usergroup::creer_zabbix_usergroup ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
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
	 * @return false|zabbix_usergroups
	 * @throws Exception
	 */
	public function retrouve_zabbix_param() {
		$this->onDebug ( __METHOD__, 1 );
		//Gestion des groups
		$liste_groups = $this->_valideOption ( array (
				"zabbix",
				"usergroups" 
		) );
		if (! is_array ( $liste_groups )) {
			$liste_groups = array (
					$liste_groups 
			);
		}
		
		$liste = array ();
		foreach ( $liste_groups as $usergroup ) {
			$objet_group = clone $this->getObjetUserGroupRef ();
			$objet_group->setName ( $usergroup );
			$liste [$usergroup] = $objet_group;
			$this->setAjoutUserGroup ( $objet_group );
		}
		
		$this->setListeGroupCli ( $liste );
		
		return $this;
	}

	/**
	 * Recupere la liste des hostGroups defini dans zabbix
	 * @return zabbix_hostgroups
	 */
	public function &recherche_liste_groups() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_groupes_zabbix = $this->getObjetZabbixWsclient ()
			->usergroupGet ( array (
				"output" => "extend" 
		) );
		foreach ( $liste_groupes_zabbix as $groupe_zabbix ) {
			if ($groupe_zabbix ['name'] != "") {
				$objet_group = $this->creer_usergroup ( $groupe_zabbix );
				$this->setAjoutUserGroup ( $objet_group );
			}
		}
		
		return $this;
	}

	/**
	 * Recupere la liste des hostGroups passe en argument par rapport a la liste defini dans zabbix
	 * @return zabbix_hostgroups
	 */
	public function &valide_liste_groups() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_groupes_zabbix = $this->getObjetZabbixWsclient ()
			->usergroupGet ( array (
				"output" => "extend" 
		) );
		$liste_finale = array ();
		foreach ( $liste_groupes_zabbix as $groupe_zabbix ) {
			if ($groupe_zabbix ['name'] != "") {
				$objet_group = $this->creer_usergroup ( $groupe_zabbix );
				foreach ( $this->getListeGroupCli () as $objgroup_cli ) {
					if ($objet_group->compare_usergroup ( $objgroup_cli )) {
						$liste_finale [$objet_group->getName ()] = $objet_group;
						continue 2;
					}
				}
			}
		}
		$this->setListeGroup ( $liste_finale );
		
		return $this;
	}

	/**
	 * Creer un objet zabbix_usergroup a partir d'un tableau.
	 * @param array $groupe_zabbix
	 * @return zabbix_usergroup
	 */
	public function &creer_usergroup($groupe_zabbix) {
		$this->onDebug ( __METHOD__, 1 );
		$objet_group = clone $this->getObjetUserGroupRef ();
		if (isset ( $groupe_zabbix ["usrgrpid"] )) {
			$objet_group->setUsrgrpId ( $groupe_zabbix ["usrgrpid"] );
		}
		$objet_group->setName ( $groupe_zabbix ["name"] )
			->setGuiAccess ( $groupe_zabbix ["gui_access"] )
			->setUsersStatus ( $groupe_zabbix ["users_status"] )
			->setDebugMode ( $groupe_zabbix ["debug_mode"] );
		
		return $objet_group;
	}

	/**
	 * Ajoute a l'objet en cours tous les groups de $liste_groups non existant
	 * @param array $liste_groups
	 * @return zabbix_usergroups
	 */
	public function ajoute_groups($liste_groups) {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $liste_groups as $usergroup ) {
			$objet_group = $this->creer_usergroup ( $usergroup );
			foreach ( $this->getListeGroup () as $obj_group ) {
				if ($obj_group->compare_usergroup ( $objet_group )) {
					continue 2;
				}
			}
			//on ajoute le usergroup
			$this->setAjoutUserGroup ( $objet_group );
		}
		
		return $this;
	}

	/**
	 * Supprime de l'objet zabbix_usergroups les usergroups existant dans la liste $liste_groups
	 * @param array $liste_groups
	 * @return zabbix_usergroups
	 */
	public function supprime_groups($liste_groups) {
		$this->onDebug ( __METHOD__, 1 );
		$liste_group_finale = array ();
		foreach ( $liste_groups as $usergroup_name => $usergroup ) {
			$objet_group = $this->creer_usergroup ( $usergroup );
			foreach ( $this->getListeGroup () as $obj_group ) {
				if ($objet_group->compare_usergroup ( $obj_group )) {
					//si on trouve une correspondance, on ne l'ajoute pas a la liste finale
					continue 2;
				}
			}
			$liste_group_finale [$objet_group->getName ()] = $objet_group;
		}
		$this->setListeGroup ( $liste_group_finale );
		
		return $this;
	}

	/**
	 * Creer un definition de toutes les groups listees dans la class
	 * @return array;
	 */
	public function creer_definition_usergroups_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$donnees_groups = array ();
		
		foreach ( $this->getListeGroup () as $usergroup ) {
			$donnees_groups [count ( $donnees_groups )] = $usergroup->creer_definition_userGroup_get_ws ();
		}
		
		return $donnees_groups;
	}

	/**
	 * Creer un tableau de usrgrpids
	 * @return array
	 */
	public function creer_definition_usergroupsids_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this->getListeGroup () as $usergroup ) {
			$liste_id [count ( $liste_id )] ["usrgrpid"] = $usergroup->getUsrgrpId ();
		}
		
		return $liste_id;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeGroup() {
		return $this->liste_group;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeGroup($liste_group) {
		$this->liste_group = $liste_group;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutUserGroup(&$usergroup) {
		$this->liste_group [$usergroup->getName ()] = $usergroup;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeGroupCli() {
		return $this->liste_group_cli;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeGroupCli($liste_group_cli) {
		$this->liste_group_cli = $liste_group_cli;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_usergroup
	 */
	public function &getObjetUserGroupRef() {
		return $this->zabbix_group_reference;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetUserGroupRef(&$zabbix_group_reference) {
		$this->zabbix_group_reference = $zabbix_group_reference;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix UserGroups :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usergroups 'usergroupe 1' 'usergroupe 2' ... liste des usergroups";
		$help = array_merge ( $help, zabbix_usergroup::help () );
		
		return $help;
	}
}
?>
