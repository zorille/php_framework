<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_items
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_items extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_item = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_item_cli = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_item
	 */
	private $zabbix_item_reference = NULL;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_items.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_items
	 */
	static function &creer_zabbix_items(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_items ( $sort_en_erreur, $entete );
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
		
		$this->setObjetItemRef ( zabbix_item::creer_zabbix_item ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
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
	 * @return false|zabbix_items
	 * @throws Exception
	 */
	public function retrouve_zabbix_param() {
		$this->onDebug ( __METHOD__, 1 );
		//Gestion des items
		$liste_items = $this->_valideOption ( array (
				"zabbix",
				"items" 
		) );
		if (! is_array ( $liste_items )) {
			$liste_items = array (
					$liste_items 
			);
		}
		
		$liste = array ();
		foreach ( $liste_items as $item ) {
			$objet_item = $this->creer_item ( array (
					"name" => $item 
			) );
			$liste [$item] = $objet_item;
			$this->setAjoutItem ( $objet_item );
		}
		
		$this->setListeItemCli ( $liste );
		
		return $this;
	}

	/**
	 * Recupere la liste des items defini dans zabbix
	 * @return zabbix_items
	 */
	public function &recherche_liste_items() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_items_zabbix = $this->getObjetZabbixWsclient ()
			->itemGet ( array (
				"output" => "extend" 
		) );
		foreach ( $liste_items_zabbix as $item_zabbix ) {
			if ($item_zabbix ['name'] != "") {
				$objet_item = $this->creer_item ( $item_zabbix );
				$this->setAjoutItem ( $objet_item );
			}
		}
		
		return $this;
	}

	/**
	 * Recupere la liste des items defini dans zabbix
	 * @return zabbix_items
	 */
	public function &recherche_liste_items_par_filtre($name = "", $key = "", $hostid = "", $type = "") {
		$this->onDebug ( __METHOD__, 1 );
		$filter = array (
				"output" => "extend",
				"selectApplications" => "extend",
				"filter" => array () 
		);
		
		if ($name !== "") {
			$filter ["filter"] ["name"] = $name;
		}
		if ($key !== "") {
			$filter ["filter"] ["key_"] = $key;
		}
		if ($hostid !== "") {
			//$filter ["hostids"] = $hostid;
			$filter ["filter"] ["hostid"] = $hostid;
		}
		if ($type !== "") {
			$filter ["filter"] ["type"] = $type;
		}
		$liste_items_zabbix = $this->getObjetZabbixWsclient ()
			->itemGet ( $filter );
		foreach ( $liste_items_zabbix as $item_zabbix ) {
			if ($item_zabbix ['name'] != "") {
				$objet_item = $this->creer_item ( $item_zabbix );
				$this->setAjoutItem ( $objet_item );
			}
		}
		
		return $this;
	}

	/**
	 * Recupere la liste des items passe en argument par rapport a la liste defini dans zabbix
	 * @return zabbix_items
	 */
	public function &valide_liste_items() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_items_zabbix = $this->getObjetZabbixWsclient ()
			->itemGet ( array (
				"output" => "extend" 
		) );
		$liste_finale = array ();
		foreach ( $liste_items_zabbix as $item_zabbix ) {
			if ($item_zabbix ['name'] != "") {
				$objet_item = $this->creer_item ( $item_zabbix );
				foreach ( $this->getListeItemCli () as $objitem_cli ) {
					if ($objet_item->compare_item ( $objitem_cli )) {
						$liste_finale [$objet_item->getName ()] = $objet_item;
						continue 2;
					}
				}
			}
		}
		$this->setListeItem ( $liste_finale );
		
		return $this;
	}

	/**
	 * Creer un objet zabbix_item a partir d'un tableau.
	 * @param array $item_zabbix
	 * @return zabbix_item
	 */
	public function &creer_item($item_zabbix) {
		$this->onDebug ( __METHOD__, 1 );
		$objet_item = clone $this->getObjetItemRef ();
		$objet_item->inserer_ws_item ( $item_zabbix );
		
		return $objet_item;
	}

	/**
	 * Ajoute a l'objet en cours tous les items de $liste_items non existant
	 * @param array $liste_items
	 * @return zabbix_items
	 */
	public function ajoute_items($liste_items) {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $liste_items as $item ) {
			if ($item ['name'] != "") {
				$objet_item = $this->creer_item ( $item );
				foreach ( $this->getListeItem () as $obj_item ) {
					if ($obj_item->compare_item ( $objet_item )) {
						continue 2;
					}
				}
				//on ajoute le item
				$this->setAjoutItem ( $objet_item );
			}
		}
		
		return $this;
	}

	/**
	 * Supprime de l'objet zabbix_items les items existant dans la liste $liste_items
	 * @param array $liste_items
	 * @return zabbix_items
	 */
	public function supprime_items($liste_items) {
		$this->onDebug ( __METHOD__, 1 );
		$liste_item_finale = array ();
		foreach ( $liste_items as $item_name => $item ) {
			$objet_item = $this->creer_item ( $item );
			foreach ( $this->getListeItem () as $obj_item ) {
				if ($objet_item->compare_item ( $obj_item )) {
					//si on trouve une correspondance, on ne l'ajoute pas a la liste finale
					continue 2;
				}
			}
			$liste_item_finale [$objet_item->getName ()] = $objet_item;
		}
		$this->setListeItem ( $liste_item_finale );
		
		return $this;
	}

	/**
	 * Creer une definition de toutes les items listees dans la class
	 * @return array;
	 */
	public function creer_definition_items_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$donnees_items = array ();
		
		foreach ( $this->getListeItem () as $item ) {
			$donnees_items [count ( $donnees_items )] = $item->creer_definition_item_ws ();
		}
		
		return $donnees_items;
	}

	/**
	 * Creer un tableau de itemids
	 * @return array
	 */
	public function creer_definition_itemids_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this->getListeItem () as $item ) {
			$liste_id [count ( $liste_id )] ["itemid"] = $item->getItemId ();
		}
		
		return $liste_id;
	}

	/**
	 * Creer un tableau de itemids
	 * @return array
	 */
	public function creer_definition_itemids_sans_champ_itemid_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this->getListeItem () as $item ) {
			$liste_id [count ( $liste_id )] = $item->getItemId ();
		}
		
		return $liste_id;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeItem() {
		return $this->liste_item;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeItem($liste_item) {
		$this->liste_item = $liste_item;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @param zabbix_item $item
	 */
	public function &setAjoutItem(&$item) {
		$this->liste_item [$item->getName ()] = $item;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeItemCli() {
		return $this->liste_item_cli;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeItemCli($liste_item_cli) {
		$this->liste_item_cli = $liste_item_cli;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_item
	 */
	public function &getObjetItemRef() {
		return $this->zabbix_item_reference;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItemRef(&$zabbix_item_reference) {
		$this->zabbix_item_reference = $zabbix_item_reference;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Items :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_items 'item 1' 'item 2' ... liste des items";
		$help = array_merge ( $help, zabbix_item::help () );
		
		return $help;
	}
}
?>
