<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_hostgroups
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_hostgroups extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_hostgroup
	 */
	private $zabbix_hostgroup_reference = "";
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

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_hostgroups.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return abstract_log|zabbix_hostgroups
	 */
	static function &creer_zabbix_hostgroups(options &$liste_option, zabbix_wsclient &$zabbix_ws, bool|string $sort_en_erreur = false, string $entete = __CLASS__): abstract_log|zabbix_hostgroups
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_hostgroups ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_hostgroups
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		$this->setObjetHostGroupRef ( zabbix_hostgroup::creer_zabbix_hostgroup ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
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
	 * @return zabbix_hostgroups.
	 * @throws Exception
	 */
	public function &retrouve_zabbix_param(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		//Gestion des hostgroup
		$liste_hostgroup = $this->_valideOption ( array (
				"zabbix",
				"host",
				"groups" 
		) );
		if (! is_array ( $liste_hostgroup )) {
			$liste_hostgroup = array (
					$liste_hostgroup 
			);
		}
		
		foreach ( $liste_hostgroup as $hostgroupe ) {
			$this->setAjoutListeGroups ( $hostgroupe, "", false );
		}
		
		$this->setListeGroupsCli($liste_hostgroup);
		
		return $this;
	}

	/**
	 * Met le champ exist a TRUE pour tous les groupes issuent de Zabbix dont le nom apparait dans la liste d'arguments
	 * @throws Exception
	 */
	public function &ajoute_groupe_a_partir_cli(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getListeGroupsCli() as $groupe ) {
			$this->RemplaceValeurListeGroups ( $groupe, "exist", true );
		}
	
		return $this;
	}
	
	/**
	 * Met le champ exist a FALSE pour tous les groupes issuent de Zabbix dont le nom apparait dans la liste d'arguments
	 * @throws Exception
	 */
	public function &retire_groupe_a_partir_cli(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getListeGroupsCli() as $groupe ) {
			$this->RemplaceValeurListeGroups ( $groupe, "exist", false );
		}
	
		return $this;
	}
	
	/**
	 * Ajoute et/ou remplace une valeur pour un groupe
	 * @return false|zabbix_hostgroups
	 * @throws Exception
	 */
	public function &RemplaceValeurListeGroups($group_name, $champ, $valeur, $erreur = true): bool|zabbix_hostgroups|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_groupe = $this->getListeGroups ();
		if (isset ( $liste_groupe [$group_name] )) {
			$liste_groupe [$group_name] [$champ] = $valeur;
		} elseif ($erreur) {
			$r = $this->onError ( "le groupe " . $group_name . " n'existe pas." );
			return $r;
		}
		$this->setListeGroups ( $liste_groupe );
		
		return $this;
	}

	/**
	 * Creer tous les groupes non existant dans zabbix contenu dans la liste ($this).
	 * @return zabbix_hostgroups
	 * @throws Exception
	 */
	public function &creer_liste_groups(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getListeGroups () as $donnees_groupe ) {
			if ($donnees_groupe ["exist"] === false) {
				$groupe = clone $this->getObjetHostGroupRef ();
				$groupe->setName ( $donnees_groupe ["name"] );
				$retour = $groupe->creer_hostGroup ();
				if (! isset ( $retour ["groupids"] )) {
					$this->onWarning ( "Pas de groupids dans la liste" );
				} else {
					foreach ( $retour ["groupids"] as $groupid ) {
						$this->RemplaceValeurListeGroups ( $donnees_groupe ["name"], "groupid", $groupid )
							->RemplaceValeurListeGroups ( $donnees_groupe ["name"], "exist", true );
					}
				}
				unset ( $groupe );
			}
		}
		
		return $this;
	}

	/**
	 * Recupere la liste des hostGroups defini dans zabbix
	 * @return zabbix_hostgroups
	 * @throws Exception
	 */
	public function &recherche_liste_groups(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_groupes_zabbix = $this->getObjetZabbixWsclient ()
			->hostgroupGet ( array (
				"output" => "extend" 
		) );
		foreach ( $liste_groupes_zabbix as $groupe_zabbix ) {
			if ($groupe_zabbix ['name'] != "") {
				$this->setAjoutListeGroups ( $groupe_zabbix ['name'], $groupe_zabbix ["groupid"], true );
			}
		}
		
		return $this;
	}

	/**
	 * Valide que chaque groupe de la liste ($this) existe dans zabbix
	 * @return zabbix_hostgroups
	 * @throws Exception
	 */
	public function &valide_liste_groups(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_groupes_zabbix = $this->getObjetZabbixWsclient ()
			->hostgroupGet ( array (
				"output" => "extend" 
		) );
		
		foreach ( $liste_groupes_zabbix as $groupe_zabbix ) {
			if ($groupe_zabbix ['name'] != "") {
				$this->onDebug ( "Remplacement de " . $groupe_zabbix ['name'], 2 );
				$this->RemplaceValeurListeGroups ( $groupe_zabbix ['name'], "groupid", $groupe_zabbix ["groupid"], false );
				$this->RemplaceValeurListeGroups ( $groupe_zabbix ['name'], "exist", true, false );
			}
		}
		
		return $this;
	}

	/**
	 * Valide les groupes zabbix qui correspondent au groupid dans la liste en argument
	 * @param $liste_groupids
	 * @return zabbix_hostgroups
	 * @throws Exception
	 */
	public function &ajoute_liste_groupes_a_partir_de_tableau($liste_groupids): static
	{
		$this->onDebug ( __METHOD__, 1 );
		
		foreach ( $this->getListeGroups () as $groupe ) {
			if (isset($groupe ["groupid"]) && in_array ( $groupe ["groupid"], $liste_groupids )) {
				$this->RemplaceValeurListeGroups ( $groupe ['name'], "exist", true, true );
			}
		}
		$this->onDebug ( $this->getListeGroups (), 1 );
		return $this;
	}

	/**
	 * Valide les groupes zabbix qui correspondent au groupid dans la liste en argument et invalide tous les autres
	 * @param $liste_groupids
	 * @return zabbix_hostgroups
	 * @throws Exception
	 */
	public function &valide_liste_groupes_a_partir_de_tableau($liste_groupids): static
	{
		$this->onDebug ( __METHOD__, 1 );
		
		foreach ( $this->getListeGroups() as $groupe ) {
			if (isset($groupe ["groupid"]) && in_array ( $groupe ["groupid"], $liste_groupids )) {
				$this->RemplaceValeurListeGroups ( $groupe ['name'], "exist", true, true );
			} else {
				$this->RemplaceValeurListeGroups ( $groupe ['name'], "exist", false, true );
			}
		}
		$this->onDebug ( $this->getListeGroups (), 2 );
		return $this;
	}

	/**
	 * Invalide les groupes zabbix qui correspondent au groupid dans la liste en argument
	 * @param $liste_groupids
	 * @return zabbix_hostgroups
	 * @throws Exception
	 */
	public function &invalide_liste_groupes_a_partir_de_tableau($liste_groupids): static
	{
		$this->onDebug ( __METHOD__, 1 );
		
		foreach ( $this->getListeGroups () as $groupe ) {
			if ($groupe ["exist"] === true && isset($groupe ["groupid"]) && in_array ( $groupe ["groupid"], $liste_groupids )) {
				$this->RemplaceValeurListeGroups ( $groupe ['name'], "exist", false, true );
			}
		}
		$this->onDebug ( $this->getListeGroups (), 2 );
		return $this;
	}

	/**
	 * Creer un tableau de groupids
	 * @return array
	 */
	public function creer_definition_groupsids_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this->getListeGroups () as $group ) {
			if ($group ["exist"] === true) {
				$liste_id [count ( $liste_id )] ["groupid"] = $group ["groupid"];
			}
		}
		
		return $liste_id;
	}

	/**
	 * Creer un tableau de groupids
	 * @return array
	 */
	public function creer_definition_groupsids_sans_champ_groupid_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this->getListeGroups () as $group ) {
			if ($group ["exist"] === true) {
				$liste_id[] = $group ["groupid"];
			}
		}
		
		return $liste_id;
	}

	/**
	 * Retrouve l'id d'un hostgroup
	 * @param string $nom_hostgroup
	 * @return string|boolean hostgoupid ou False en cas d'erreur
	 */
	public function retrouve_hostgroupId(string $nom_hostgroup): bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getListeGroups () as $group ) {
			if ($group ["exist"] === true && $group ["name"] == $nom_hostgroup) {
				return $group ["groupid"];
			}
		}
		
		return false;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetHostGroupRef(): string|zabbix_hostgroup
	{
		return $this->zabbix_hostgroup_reference;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetHostGroupRef(&$zabbix_hostgroup_reference): static
	{
		$this->zabbix_hostgroup_reference = $zabbix_hostgroup_reference;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutListeGroups($group_name, $group_id, $exist = true): static
	{
		$this->liste_group [$group_name] = array (
				"groupid" => $group_id,
				"name" => $group_name,
				"exist" => $exist 
		);
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeGroups(): array
	{
		return $this->liste_group;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeGroups($liste_group): static
	{
		$this->liste_group = $liste_group;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeGroupsCli(): array
	{
		return $this->liste_group_cli;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeGroupsCli($liste_groupes_cli): static
	{
		$this->liste_group_cli = $liste_groupes_cli;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix HostGroups :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_host_groups 'groupe 1' 'groupe 2' ... liste des groupes du CI";
		$help = array_merge ( $help, zabbix_hostgroup::help () );
		
		return $help;
	}
}
