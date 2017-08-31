<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_valuemapping
 *
 * valuemapid 	string 	(readonly) ID of the value mapping.
 * name (required) 	string 	Name of the value mapping.
 *  
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_valuemapping extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $valuemapid = "";
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
	 * @var zabbix_mappings
	 */
	private $mappings = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_valuemapping.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_valuemapping
	 */
	static function &creer_zabbix_valuemapping(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_valuemapping ( $sort_en_erreur, $entete );
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
		
		$this->setObjetMappings ( zabbix_mappings::creer_zabbix_mappings ( $liste_class ['options'] ) );
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
	 * @param boolean $nom_seulement valide uniquement le nom (description) du valuemapping
	 * @return zabbix_valuemapping
	 */
	public function retrouve_zabbix_param($nom_seulement = false) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setName ( $this->_valideOption ( array (
				"zabbix",
				"valuemapping",
				"nom" 
		) ) );
		if ($nom_seulement === false) {
			$this->getObjetMappings ()
				->retrouve_zabbix_param ();
		}
		
		return $this;
	}

	/**
	 * Creer un definition d'un valuemapping sous forme de tableau
	 * @return array;
	 * @throws Exception
	 */
	public function creer_definition_valuemapping_create_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$valuemappingid = array (
				"name" => $this->getName (),
				"mappings" => $this->getObjetMappings ()
					->creer_definition_mappings_create_ws () 
		);
		
		return $valuemappingid;
	}

	/**
	 * Creer un valuemapping dans zabbix
	 * @return array
	 */
	public function creer_valuemapping() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_valuemapping_create_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->valuemappingCreate ( $datas );
	}

	/**
	 * Creer un definition d'un valuemapping sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_valuemapping_delete_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$valuemappingid = array ();
		
		if ($this->getValueMapId () != "") {
			$valuemappingid [] .= $this->getValueMapId ();
		}
		
		return $valuemappingid;
	}

	/**
	 * supprime un valuemapping dans zabbix
	 * @return array
	 */
	public function supprime_valuemapping() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_valuemapping = $this->recherche_valuemapping ( false );
		foreach ( $liste_valuemapping as $valuemappingids ) {
			if (isset ( $valuemappingids ["valuemapid"] ) && $valuemappingids ["valuemapid"] != "") {
				$this->setValueMapId ( $valuemappingids ["valuemapid"] );
				$datas = $this->creer_definition_valuemapping_delete_ws ();
				$this->onDebug ( $datas, 1 );
				return $this->getObjetZabbixWsclient ()
					->valuemappingDelete ( $datas );
			}
		}
		
		return array ();
	}

	/**
	 * Creer un definition d'un valuemapping sous forme de tableau
	 * @param boolean $with_mappings demande le mapping des valeurs pour le valuemapping demande
	 * @return array;
	 */
	public function creer_definition_valuemapping_get_ws($with_mappings = true, $output = "valuemapid") {
		$this->onDebug ( __METHOD__, 1 );
		$get = array (
				"output" => $output,
				"filter" => array (
						"name" => $this->getName () 
				) 
		);
		if ($with_mappings) {
			$get ["with_mappings"] = true;
		}
		
		return $get;
	}

	/**
	 * recherche un valuemapping dans zabbix a partir de sa description
	 * @param boolean $with_mappings demande le mapping des valeurs pour le valuemapping demande
	 * @return array
	 */
	public function recherche_valuemapping($with_mappings = true, $output = "valuemapid") {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_valuemapping_get_ws ( $with_mappings, $output );
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->valuemappingGet ( $datas );
	}

	/**
	 * recherche un valuemapping dans zabbix a partir de sa description et ajoute le valuemappingId dans l'objet
	 * Le mot All renvoi l'id 0
	 * @return array
	 */
	public function recherche_valuemappingid_by_Name() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_valuemapping_get_ws ();
		$this->onDebug ( $datas, 1 );
		$liste_valuemapping = $this->getObjetZabbixWsclient ()
			->valuemappingGet ( $datas );
		foreach ( $liste_valuemapping as $valuemappingids ) {
			if (isset ( $valuemappingids ["valuemapid"] ) && $valuemappingids ["valuemapid"] != "") {
				$this->setValueMapId ( $valuemappingids ["valuemapid"] );
			}
		}
		
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getValueMapId() {
		return $this->valuemapid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValueMapId($valuemapid) {
		$this->valuemapid = $valuemapid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_mappings
	 */
	public function &getObjetMappings() {
		return $this->mappings;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetMappings(&$mappings) {
		$this->mappings = $mappings;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix ValueMapping :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_valuemapping_nom Nom du valueMapping";
		$help = array_merge ( $help, zabbix_mappings::help () );
		
		return $help;
	}
}
?>
