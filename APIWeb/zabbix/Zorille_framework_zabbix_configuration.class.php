<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_configuration
 * format (required) 	string 	Format of the serialized string.
 * Possible values:
 * 		json - JSON;
 * 		xml - XML.
 * source (required to import) 	string 	Serialized string containing the configuration data.
 * rules 	object 	Rules on how new and existing objects should be imported.
 * 		The rules parameter is described in detail in the table below.
 * 		applications 
 * 		discoveryRules
 * 		graphs
 * 		groups
 * 		hosts
 * 		images
 * 		items 
 * 		maps
 * 		screens
 * 		templateLinkage
 * 		templates
 * 		templateScreens
 * 		triggers
 * options (required to export) 	object 	Objects to be exported.
 * 		The options object has the following parameters:
 * 		groups - (array) IDs of host groups to export;
 * 		hosts - (array) IDs of hosts to export;
 * 		images - (array) IDs of images to export;
 *		maps - (array) IDs of maps to export.
 * 		screens - (array) IDs of screens to export;
 * 		templates - (array) IDs of templates to export; 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_configuration extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $format = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $rules = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $rules_param = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $options = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $fichier = "/tmp/fichier";
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_hostgroups
	 */
	private $groups = null;
	/** 
	 * var privee
	 *
	 * @access private
	 * @var zabbix_hosts
	 */
	private $hosts = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_images
	 */
	private $images = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_maps
	 */
	private $maps = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_screens
	 */
	private $screens = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_templates
	 */
	private $templates = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_configuration.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_configuration
	 */
	static function &creer_zabbix_configuration(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_configuration ( $sort_en_erreur, $entete );
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
		
		$this->setObjetZabbixWsclient ( $liste_class ["zabbix_wsclient"] );
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
	 * @return boolean True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param($import = false, $export = false) {
		$this->onDebug ( __METHOD__, 1 );
		//Gestion d'un host
		$this->setFormat ( $this->_valideOption ( array (
				"zabbix",
				"configuration",
				"format" 
		) ) );
		$this->setFichier ( $this->_valideOption ( array (
				"zabbix",
				"configuration",
				"fichier" 
		) ) );
		if ($import) {
			$rules = $this->_valideOption ( array (
					"zabbix",
					"configuration",
					"rules" 
			) );
			if (! is_array ( $rules )) {
				$rules = array (
						$rules 
				);
			}
			$this->setRules ( $rules )
				->retrouve_rules_param ();
		}
		if ($export) {
			$options = $this->_valideOption ( array (
					"zabbix",
					"configuration",
					"options" 
			) );
			if (! is_array ( $options )) {
				$options = array (
						$options 
				);
			}
			$this->setOptions ( $options );
		}
		
		return $this;
	}

	/**
	 * Valide le format d'un parametre de type boolean
	 * @param string $param
	 * @return zabbix_configuration
	 */
	public function valide_format_param(&$param) {
		$this->onDebug ( __METHOD__, 1 );
		switch ($param) {
			case "true" :
				$param = true;
				break;
			case "false" :
				$param = false;
				break;
		}
		return $this;
	}

	/**
	 * Retrouve, dans $liste_option les parametres necessaire au rules d'import
	 * @return zabbix_configuration
	 */
	public function retrouve_rules_param() {
		$this->onDebug ( __METHOD__, 1 );
		$rules_params = array ();
		foreach ( $this->getRules () as $rule ) {
			//On creer par defaut
			$rule_param_createMissing = $this->_valideOption ( array (
					$rule,
					"createMissing" 
			), true );
			$this->valide_format_param ( $rule_param_createMissing );
			
			$rule_param_updateExisting = $this->_valideOption ( array (
					$rule,
					"updateExisting" 
			), false );
			$this->valide_format_param ( $rule_param_updateExisting );
			
			$rules_params [$rule] = $this->valide_RulesParams ( $rule, $rule_param_createMissing, $rule_param_updateExisting );
		}
		
		return $this->setRulesParams ( $rules_params );
	}

	/**
	 * Recupere les donnees du fichier declare
	 * @return string|false en cas d'erreur
	 * @throws Exception
	 */
	public function charge_fichier() {
		$this->onDebug ( __METHOD__, 1 );
		$donnees = fichier::Lit_integralite_fichier ( $this->getFichier () );
		if ($donnees === false) {
			return $this->onError ( "Le fichier " . $this->getFichier () . " n'est pas lisible." );
		}
		
		return $donnees;
	}

	/**
	 * Creer une definition d'un proxy sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_import_ws() {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"format" => $this->getFormat (),
				"rules" => $this->getRulesParams (),
				"source" => $this->charge_fichier () 
		);
	}

	/**
	 * Creer un proxy dans zabbix
	 * @return array
	 */
	public function importer() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_import_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->configurationImport ( $datas );
	}

	/**
	 * Creer un definition d'un proxy sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_export_ws() {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"format" => $this->getFormat (),
				"options" => $this->fabrique_options () 
		);
	}

	/**
	 * exporte un fichier de donnees a partir de zabbix
	 * @return array
	 * @throws Exception
	 */
	public function exporter() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_export_ws ();
		$this->onDebug ( $datas, 2 );
		$fichier_sortie = fichier::creer_fichier ( $this->getListeOptions (), $this->getFichier (), "oui" );
		$fichier_sortie->ouvrir ( "w" );
		$fichier_sortie->ecrit ( $this->getObjetZabbixWsclient ()
			->configurationExport ( $datas ) );
		$fichier_sortie->close ();
		return $this;
	}

	/**
	 * json par defaut;
	 * xml;
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Format($type) {
		$this->onDebug ( __METHOD__, 1 );
		switch (strtolower ( $type )) {
			case "xml" :
				return "xml";
				break;
			case "json" :
			default :
		}
		
		return "json";
	}

	/**
	 * Retrouve la casse exacte supportee par Zabbix
	 * applications 
	 * discoveryRules
	 * graphs
	 * groups
	 * hosts
	 * images
	 * items 
	 * maps
	 * screens
	 * templateLinkage
	 * templates
	 * templateScreens
	 * triggers
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Rules(&$rules) {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $rules as $pos => $rule ) {
			switch (strtolower ( $rule )) {
				case "applications" :
					$rules [$pos] = "applications";
					break;
				case "discoveryrules" :
					$rules [$pos] = "discoveryRules";
					break;
				case "graphs" :
					$rules [$pos] = "graphs";
					break;
				case "groups" :
					$rules [$pos] = "groups";
					break;
				case "hosts" :
					$rules [$pos] = "hosts";
					break;
				case "images" :
					$rules [$pos] = "images";
					break;
				case "items" :
					$rules [$pos] = "items";
					break;
				case "maps" :
					$rules [$pos] = "maps";
					break;
				case "screens" :
					$rules [$pos] = "screens";
					break;
				case "templatelinkage" :
					$rules [$pos] = "templateLinkage";
					break;
				case "templates" :
					$rules [$pos] = "templates";
					break;
				case "templatescreens" :
					$rules [$pos] = "templateScreens";
					break;
				case "triggers" :
					$rules [$pos] = "triggers";
					break;
				default :
					//La rule n'existe pas, on la supprime de la liste
					unset ( $rules [$pos] );
			}
		}
		
		return $this;
	}

	/**
	 * Valide la liste des parametres en fonction de la rule pour l'import
	 * @param string $rule
	 * @param boolean $createMissing
	 * @param boolean $updateExisting
	 * @return array
	 */
	public function valide_RulesParams($rule, $createMissing, $updateExisting) {
		$this->onDebug ( __METHOD__, 1 );
		switch ($rule) {
			case "groups" :
			case "templateLinkage" :
				return array (
						"createMissing" => $createMissing 
				);
		}
		
		return array (
				"createMissing" => $createMissing,
				"updateExisting" => $updateExisting 
		);
	}

	/**
	 * Fabrique la liste d'id en fonction du type demande (groups,hosts,images,maps,screens,templates)
	 * @return zabbix_configuration
	 * @throws Exception
	 */
	public function fabrique_options() {
		$this->onDebug ( __METHOD__, 1 );
		$options = array ();
		foreach ( $this->getOptions () as $option ) {
			switch (strtolower ( $option )) {
				case "groups" :
					if ($this->getObjetGroups () !== null) {
						$options ["groups"] = $this->getObjetGroups ()
							->creer_definition_groupsids_sans_champ_groupid_ws ();
					} else {
						return $this->onError ( "Il faut un objet de type zabbix_hostgroups" );
					}
					break;
				case "hosts" :
					if ($this->getObjetHosts () !== null) {
						$options ["hosts"] = $this->getObjetHosts ()
							->creer_definition_hostids_sans_champ_hostid_ws ();
					} else {
						return $this->onError ( "Il faut un objet de type zabbix_hosts" );
					}
					break;
				case "images" :
					if ($this->getObjetImages () !== null) {
						$options ["images"] = $this->getObjetImages ()
							->creer_definition_imageids_sans_champ_imageid_ws ();
					} else {
						return $this->onError ( "Il faut un objet de type zabbix_images" );
					}
					break;
				case "maps" :
					if ($this->getObjetMaps () !== null) {
						$options ["maps"] = $this->getObjetMaps ()
							->creer_definition_mapids_sans_champ_mapid_ws ();
					} else {
						return $this->onError ( "Il faut un objet de type zabbix_maps" );
					}
					break;
				case "screens" :
					if ($this->getObjetScreens () !== null) {
						$options ["screens"] = $this->getObjetScreens ()
							->creer_definition_screenids_sans_champ_screenid_ws ();
					} else {
						return $this->onError ( "Il faut un objet de type zabbix_screens" );
					}
					break;
				case "templates" :
					if ($this->getObjetTemplates () !== null) {
						$options ["templates"] = $this->getObjetTemplates ()
							->creer_definition_templatesids_sans_champ_templateid_ws ();
					} else {
						return $this->onError ( "Il faut un objet de type zabbix_templates" );
					}
					break;
				default :
					return $this->onError ( "Cette option " . $option . " n'existe pas." );
			}
		}
		
		return $options;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFormat($format) {
		$this->format = $this->retrouve_Format ( $format );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRules() {
		return $this->rules;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRules($rules) {
		$this->retrouve_Rules ( $rules );
		$this->rules = $rules;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRulesParams() {
		return $this->rules_param;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneRuleParams($rule) {
		if (isset ( $this->rules_param [$rule] )) {
			return $this->rules_param [$rule];
		}
		
		return array ();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRulesParams($rules_param) {
		$this->rules_param = $rules_param;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOptions($options) {
		$this->options = $options;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFichier() {
		return $this->fichier;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFichier($fichier) {
		$this->fichier = $fichier;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_hostgroups
	 */
	public function &getObjetGroups() {
		return $this->groups;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetGroups(&$HostGroup) {
		$this->groups = $HostGroup;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_hosts
	 */
	public function &getObjetHosts() {
		return $this->hosts;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetHosts(&$Hosts) {
		$this->hosts = $Hosts;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_images
	 */
	public function &getObjetImages() {
		return $this->images;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetImages(&$images) {
		$this->images = $images;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_maps
	 */
	public function &getObjetMaps() {
		return $this->maps;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetMaps(&$maps) {
		$this->maps = $maps;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_screens
	 */
	public function &getObjetScreens() {
		return $this->screens;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetScreens(&$screens) {
		$this->screens = $screens;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_templates
	 */
	public function &getObjetTemplates() {
		return $this->templates;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetTemplates(&$templates) {
		$this->templates = $templates;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Configuration :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_configuration_format json|xml (xml par defaut) format du fichier d'entree ou de sortie";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_configuration_fichier /tmp/fichier Chemin et nom du fichier d'import ou de sortie pour l'export";
		$help [__CLASS__] ["text"] [] .= "En cas d'IMPORT :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_configuration_rules {rule1} ... {ruleX} liste des rules d'import applications|discoveryrules|graphs|groups|hosts|images|items|maps|screens|templateLinkage|templates|templatescreens|triggers";
		$help [__CLASS__] ["text"] [] .= "\t--{rule1}_createMissing true/false ";
		$help [__CLASS__] ["text"] [] .= "\t--{rule1}_updateExisting true/false ";
		$help [__CLASS__] ["text"] [] .= "\t   ... ";
		$help [__CLASS__] ["text"] [] .= "\t--{rulex}_createMissing true/false ";
		$help [__CLASS__] ["text"] [] .= "\t--{ruleX}_updateExisting true/false ";
		$help [__CLASS__] ["text"] [] .= "En cas d'EXPORT :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_configuration_options groups|hosts|images|maps|screens|templates Une ou plusieurs options sont possible parmis cette liste";
		$help = array_merge ( $help, zabbix_hostgroups::help () );
		$help = array_merge ( $help, zabbix_hosts::help () );
		$help = array_merge ( $help, zabbix_images::help () );
		$help = array_merge ( $help, zabbix_maps::help () );
		$help = array_merge ( $help, zabbix_screens::help () );
		$help = array_merge ( $help, zabbix_templates::help () );
		
		return $help;
	}
}
?>
