<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_host_administration
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_host_administration extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_host
	 */
	private $zabbix_host = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_hostgroups
	 */
	private $zabbix_hostgroups = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_templates
	 */
	private $zabbix_templates = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_host_interfaces
	 */
	private $zabbix_host_interfaces = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_proxy
	 */
	private $zabbix_proxy = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_host_administration.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_host_administration
	 */
	static function &creer_zabbix_host_administration(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_host_administration ( $sort_en_erreur, $entete );
		return $objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"zabbix_wsclient" => $zabbix_ws ) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->setObjetZabbixWsclient ( $liste_class ["zabbix_wsclient"] ) 
			->setObjetZabbixHost ( zabbix_host::creer_zabbix_host ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) ) 
			->setObjetZabbixHostgroups ( zabbix_hostgroups::creer_zabbix_hostgroups ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) ) 
			->setObjetZabbixTemplates ( zabbix_templates::creer_zabbix_templates ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) ) 
			->setObjetZabbixHostInterfaces ( zabbix_host_interfaces::creer_zabbix_host_interfaces ( $liste_class ["options"] ) ) 
			->setObjetZabbixProxy ( zabbix_proxy::creer_zabbix_proxy ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
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
	 * Ajoute un group au host du zabbix connecte en webservice
	 * @codeCoverageIgnore
	 * @return zabbix_host_administration
	 * @throws Exception
	 */
	public function ajoute_group() {
		$this ->getObjetZabbixHostgroups () 
			->retrouve_zabbix_param () 
			->valide_liste_groups () 
			->creer_liste_groups ();
		
		$this ->getObjetZabbixHost () 
			->retrouve_zabbix_param () 
			->setObjetGroups ( $this ->getObjetZabbixHostgroups () ) 
			->recherche_hostid_by_Name () 
			->getObjetGroups () 
			->recherche_liste_groups ();
		
		$this ->onInfo ( "Ajout au groupe de " . $this ->getObjetZabbixHost () 
			->getHost () );
		$this ->getObjetZabbixHost () 
			->ajouter_groups_au_host ();
		
		return $this;
	}

	/**
	 * Supprime un group au host du zabbix connecte en webservice
	 * @codeCoverageIgnore
	 * @return zabbix_host_administration
	 * @throws Exception
	 */
	public function supprime_group() {
		$this ->getObjetZabbixHostgroups () 
			->retrouve_zabbix_param () 
			->recherche_liste_groups ();
		
		$this ->getObjetZabbixHost () 
			->retrouve_zabbix_param () 
			->setObjetGroups ( $this ->getObjetZabbixHostgroups () ) 
			->recherche_hostid_by_Name ();
		
		$this ->onInfo ( "Supp du groupe de " . $this ->getObjetZabbixHost () 
			->getHost () );
		$this ->getObjetZabbixHost () 
			->supprimer_groups_au_host ();
		
		return $this;
	}

	/**
	 * Ajoute un hostInterface au host du zabbix connecte en webservice
	 * @codeCoverageIgnore
	 * @return zabbix_host_administration
	 * @throws Exception
	 */
	public function ajoute_hostInterface() {
		$this ->getObjetZabbixHostInterfaces () 
			->retrouve_zabbix_param ();
		
		$this ->getObjetZabbixHost () 
			->retrouve_zabbix_param () 
			->setObjetInterfaces ( $this ->getObjetZabbixHostInterfaces () ) 
			->recherche_hostid_by_Name ();
		
		$this ->onInfo ( "Ajout de l'interface a " . $this ->getObjetZabbixHost () 
			->getHost () );
		$this ->getObjetZabbixHost () 
			->ajouter_interfaces_au_host ();
		
		return $this;
	}

	/**
	 * Supprime un hostInterface au host du zabbix connecte en webservice
	 * @codeCoverageIgnore
	 * @return zabbix_host_administration
	 * @throws Exception
	 */
	public function supprime_hostInterface() {
		$this ->getObjetZabbixHostInterfaces () 
			->retrouve_zabbix_param ();
		
		$this ->getObjetZabbixHost () 
			->retrouve_zabbix_param () 
			->setObjetInterfaces ( $this ->getObjetZabbixHostInterfaces () ) 
			->recherche_hostid_by_Name ();
		
		$this ->onInfo ( "Supp de l'interface de " . $this ->getObjetZabbixHost () 
			->getHost () );
		$this ->getObjetZabbixHost () 
			->supprimer_interfaces_au_host ();
		
		return $this;
	}

	/**
	 * Valide la presence d'un host dans Zabbix
	 * @codeCoverageIgnore
	 * @return boolean
	 */
	public function valide_host_existe() {
		$this ->getObjetZabbixHost () 
			->setHostId ( "" ) 
			->recherche_hostid_by_Name ();
		if (empty ( $this ->getObjetZabbixHost () 
			->getHostId () )) {
			return false;
		}
		
		return true;
	}

	/**
	 * Ajoute un host au zabbix connecte en webservice
	 * @codeCoverageIgnore
	 * @return zabbix_host_administration
	 * @throws Exception
	 */
	public function ajoute_host() {
		$this ->getObjetZabbixHost () 
			->retrouve_zabbix_param ();
		
		if ($this ->valide_host_existe ()) {
			$this ->onWarning ( "Le host " . $this ->getObjetZabbixHost () 
				->getHost () . " existe." );
			return $this;
		}
		
		//Retrouve la liste des Templates
		$this ->getObjetZabbixTemplates () 
			->setListeTemplates ( array () ) 
			->setListeTemplatesCli ( array () ) 
			->retrouve_zabbix_param () 
			->valide_liste_templates ();
		
		//Retrouve la liste des HostGroup et on creer ceux qui n'existent pas
		$this ->getObjetZabbixHostgroups () 
			->setListeGroups ( array () ) 
			->setListeGroupsCli ( array () ) 
			->retrouve_zabbix_param () 
			->valide_liste_groups () 
			->creer_liste_groups ();
		
		//On retrouve la liste des interfaces
		$this ->getObjetZabbixHostInterfaces () 
			->setListeInterface ( array () ) 
			->setListeInterfaceCli ( array () ) 
			->retrouve_zabbix_param ();
		
		//On retrouve le proxy s'il existe
		if ($this ->getListeOptions () 
			->verifie_variable_standard ( array ( 
				"zabbix", 
				"proxy", 
				"name" ) ) !== false) {
			$proxyids = $this ->getObjetZabbixProxy () 
				->retrouve_zabbix_param ( true ) 
				->recherche_proxy ();
			if (isset ( $proxyids [0] ) && isset ( $proxyids [0] ['proxyid'] )) {
				$this ->getObjetZabbixHost () 
					->setProxyId ( $proxyids [0] ['proxyid'] );
			} else {
				return $this ->onError ( "Le proxy " . $this ->getObjetZabbixProxy () 
					->getProxy () . " n'existe pas" );
			}
		}
		
		$this ->onInfo ( "ajout de " . $this ->getObjetZabbixHost () 
			->getHost () );
		
		$liste_retour = $this ->getObjetZabbixHost () 
			->setObjetInterfaces ( $this ->getObjetZabbixHostInterfaces () ) 
			->setObjetTemplates ( $this ->getObjetZabbixTemplates () ) 
			->setObjetGroups ( $this ->getObjetZabbixHostgroups () ) 
			->creer_host ();
		
		$this ->onDebug ( $liste_retour, 1 );
		return $this;
	}

	/**
	 * Supprime un host au zabbix connecte en webservice
	 * @codeCoverageIgnore
	 * @return zabbix_host_administration
	 * @throws Exception
	 */
	public function supprime_host() {
		$this ->getObjetZabbixHost () 
			->retrouve_zabbix_param ();
		
		$this ->onInfo ( "Supp de " . $this ->getObjetZabbixHost () 
			->getHost () );
		$this ->getObjetZabbixHost () 
			->recherche_hostid_by_Name () 
			->supprime_host ();
		
		return $this;
	}

	/**
	 * Ajoute un template au host du zabbix connecte en webservice
	 * @codeCoverageIgnore
	 * @return zabbix_host_administration
	 * @throws Exception
	 */
	public function ajoute_template() {
		$this ->getObjetZabbixTemplates () 
			->retrouve_zabbix_param ();
		
		$this ->getObjetZabbixHost () 
			->retrouve_zabbix_param () 
			->setObjetTemplates ( $this ->getObjetZabbixTemplates () ) 
			->recherche_hostid_by_Name () 
			->getObjetTemplates () 
			->recherche_liste_templates ();
		
		$this ->onInfo ( "Ajout du template a " . $this ->getObjetZabbixHost () 
			->getHost () );
		$this ->getObjetZabbixHost () 
			->ajouter_templates_au_host ();
		
		return $this;
	}

	/**
	 * Supprime un template au host du zabbix connecte en webservice
	 * @codeCoverageIgnore
	 * @return zabbix_host_administration
	 * @throws Exception
	 */
	public function supprime_template() {
		$this ->getObjetZabbixTemplates () 
			->retrouve_zabbix_param ();
		
		$this ->getObjetZabbixHost () 
			->retrouve_zabbix_param () 
			->setObjetTemplates ( $this ->getObjetZabbixTemplates () ) 
			->recherche_hostid_by_Name ();
		
		$this ->onInfo ( "Supp du template de " . $this ->getObjetZabbixHost () 
			->getHost () );
		$this ->getObjetZabbixHost () 
			->supprimer_templates_au_host ();
		
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 * @return zabbix_host
	 */
	public function &getObjetZabbixHost() {
		return $this->zabbix_host;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetZabbixHost(&$zabbix_host) {
		$this->zabbix_host = $zabbix_host;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_hostgroups
	 */
	public function &getObjetZabbixHostgroups() {
		return $this->zabbix_hostgroups;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetZabbixHostgroups(&$zabbix_hostgroups) {
		$this->zabbix_hostgroups = $zabbix_hostgroups;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_templates
	 */
	public function &getObjetZabbixTemplates() {
		return $this->zabbix_templates;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetZabbixTemplates(&$zabbix_templates) {
		$this->zabbix_templates = $zabbix_templates;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_host_interfaces
	 */
	public function &getObjetZabbixHostInterfaces() {
		return $this->zabbix_host_interfaces;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetZabbixHostInterfaces(&$zabbix_host_interfaces) {
		$this->zabbix_host_interfaces = $zabbix_host_interfaces;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_templates
	 */
	public function &getObjetZabbixProxy() {
		return $this->zabbix_proxy;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetZabbixProxy(&$zabbix_proxy) {
		$this->zabbix_proxy = $zabbix_proxy;
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
		$help [__CLASS__] ["text"] [] .= __CLASS__ . " :";
		$help = array_merge ( $help, zabbix_host_interface::help () );
		$help = array_merge ( $help, zabbix_host_interfaces::help () );
		$help = array_merge ( $help, zabbix_host::help () );
		$help = array_merge ( $help, zabbix_hostgroup::help () );
		$help = array_merge ( $help, zabbix_hostgroups::help () );
		$help = array_merge ( $help, zabbix_template::help () );
		$help = array_merge ( $help, zabbix_templates::help () );
		$help = array_merge ( $help, zabbix_proxy::help () );
		
		return $help;
	}
}
?>
