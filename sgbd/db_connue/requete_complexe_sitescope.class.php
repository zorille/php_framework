<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class requete_complexe_sitescope<br>
 *
 * Gere la connexion a une base sitescope.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class requete_complexe_sitescope extends desc_bd_sitescope {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requete_complexe_sitescope.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_complexe_sitescope
	 */
	static function &creer_requete_complexe_sitescope(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new requete_complexe_sitescope ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_complexe_sitescope
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/**
     * Retrouve le last report id.
     *
     * @return int|false Renvoi le last report id, FALSE sinon.
     */
	public function retrouve_lastid($nomid, $table) {
		$select = array ();
		$from = array ();
		$where = "";
		$this->fabrique_from ( $from, $table );
		$this->fabrique_select ( $select, $table, $nomid );
		
		if (isset ( $from [0] ) && $from [0] != "" && isset ( $select [0] ) && $select [0] != "") {
			$select [0] = "max(" . $select [0] . ") as lastinserted";
			$resultat = $this->selectionner ( $select, $from, $where, "limit 1" );
			if ($resultat) {
				foreach ( $resultat as $row ) {
					$Lastreportid = $row ["lastinserted"];
				}
			} else {
				$Lastreportid = false;
			}
		} else {
			$Lastreportid = false;
		}
		
		return $Lastreportid;
	}

	/***************** alert *********************/
	/**
     * Retrouve le last report id de alert.
     *
     * @return int|false Renvoi le last report id, FALSE sinon.
     */
	public function retrouve_lastalertsid() {
		return $this->retrouve_lastid ( "id", 'alert' );
	}

	/**
     * Cree et applique cette requete de type select.
     *
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_alert_sans_id($serveur_id) {
		$table = 'alert';
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$select = array ();
		$this->fabrique_select ( $select, $table, "serveur_id", "serveur_id" );
		$this->fabrique_select ( $select, $table, "parent_id", "parent_id" );
		$this->fabrique_select ( $select, $table, "name", "_name" );
		$this->fabrique_select ( $select, $table, "fullpathname", "_fullpathname" );
		$this->fabrique_select ( $select, $table, "deleted", "deleted" );
		
		$where = array ();
		$this->fabrique_where ( $where, $table, "serveur_id", $serveur_id );
		
		$order_liste [0] = $this->fabrique_order_by ( "id ASC", $table );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** alert *********************/
	
	/***************** ci *********************/
	function requete_select_ci_sis($serveur_id) {
		$table = 'ci';
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$select = array ();
		$this->fabrique_select ( $select, $table, "id", "id" );
		$this->fabrique_select ( $select, $table, "serveur_id", "serveur_id" );
		$this->fabrique_select ( $select, $table, "name", "_name" );
		
		$where = array ();
		$this->fabrique_where ( $where, $table, "serveur_id", $serveur_id );
		
		$order_liste [0] = $this->fabrique_order_by ( "id ASC", $table );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	public function recherche_ci($serveur_id, $name) {
		$table = array (
				'ci' => 'ci',
				'sv' => 'serveur' 
		);
		$from = array ();
		$this->fabrique_from ( $from, $table ['ci'] );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['ci'], 'serveur_id' );
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['ci'], "id", "tree_id" );
		$this->fabrique_select ( $select, $table ['ci'], "serveur_id", "serveur_id" );
		$this->fabrique_select ( $select, $table ['ci'], "name", "_name" );
		$this->fabrique_select ( $select, $table ['sv'], "customer", "customer" );
		$this->fabrique_select ( $select, $table ['sv'], "name", "serveur_name" );
		
		$where = array ();
		$this->fabrique_where ( $where, $table ['ci'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['ci'], "name", $name );
		
		$order_liste [0] = $this->fabrique_order_by ( "id ASC", $table ['ci'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	public function retrouve_ci_props($customer = "", $ci_name = "") {
		$order = "";
		$table = array (
				'ps' => 'props',
				'ci' => 'ci',
				'sv' => 'serveur' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['ci'], "name", "_name" );
		//$this->fabrique_select ( $select, $table ['ci'], "status", "ci_status" );
		$this->fabrique_select ( $select, $table ['ps'], "key", "_key" );
		$this->fabrique_select ( $select, $table ['ps'], "value", "_value" );
		$this->fabrique_select ( $select, $table ['sv'], "id", "serveurid" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['ps'] );
		$this->fabrique_from_jointure ( $from, $table ['ci'], 'id', $table ['ps'], 'parent_id' );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['ci'], 'serveur_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['ps'], "table_parent", 'ci' );
		$this->fabrique_where ( $where, $table ['ci'], "name", $ci_name );
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['ci'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	public function retrouve_ci_runtime($customer = "", $ci_name = "") {
		$order = "";
		$table = array (
				'rt' => 'runtime',
				'ci' => 'ci',
				'sv' => 'serveur' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['ci'], "name", "_name" );
		//$this->fabrique_select ( $select, $table ['ci'], "status", "ci_status" );
		$this->fabrique_select ( $select, $table ['rt'], "key", "_key" );
		$this->fabrique_select ( $select, $table ['rt'], "value", "_value" );
		$this->fabrique_select ( $select, $table ['sv'], "id", "serveurid" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['rt'] );
		$this->fabrique_from_jointure ( $from, $table ['ci'], 'id', $table ['rt'], 'parent_id' );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['ci'], 'serveur_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['rt'], "table_parent", 'ci' );
		$this->fabrique_where ( $where, $table ['ci'], "name", $ci_name );
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['ci'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/**
	 * Retrouve le nombre de CI par client
	 * @param string|array $serveur_id
	 * @param string|array $name
	 * @return Ambigous <PDO, boolean, multitype:, false>
	 */
	public function compte_ci_par_client($customer, $serveur_id, $name) {
		//select count(distinct _name) from ci where serveur_id in (18,19,20,21,22) and _name like "%TPL%" ;
		$table = array (
				'ci' => 'ci',
				'sv' => 'serveur'
		);
		$from = array ();
		$this->fabrique_from ( $from, $table ['ci'] );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['ci'], 'serveur_id' );
	
		$select = array ();
		$this->fabrique_select ( $select, $table ['sv'], "customer", "customer" );
		$select[].="count(distinct ".$this->renvoi_champ ( $table ['ci'], "name" ).") AS compteur";
			
		$where = array ();
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		$this->fabrique_where ( $where, $table ['ci'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['ci'], "name", $name );
	
		$order_liste [0] = $this->fabrique_order_by ( "id ASC", $table ['ci'] );
		$order = sql::prepare_order_by ( $order_liste );
	
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
	
		return $resultat;
	}
	
	/**
	 * Retrouve le nombre de CI par type d'OS (NT ou !NT) par client (via les serveur_ids)
	 * @param string|array $serveur_id
	 * @param string|array $name
	 * @param string|array $type_os 
	 * @return Ambigous <PDO, boolean, multitype:, false>
	 */
	public function compte_ci_par_client_et_os($serveur_id, $name, $type_os) {
		//select count(distinct _name) from ci join props on ci.id=props.parent_id where serveur_id in (18,19,20,21,22,23) and _name like "LTPL%" and _key='_os' and _value='NT' ;
		$table = array (
				'ci' => 'ci',
				'ps' => 'props'
		);
		$from = array ();
		$this->fabrique_from ( $from, $table ['ci'] );
		$this->fabrique_from_jointure ( $from, $table ['ps'], 'parent_id', $table ['ci'], 'id' );
	
		$select="count(distinct ".$this->renvoi_champ ( $table ['ci'], "name" ).") AS compteur";
			
		$where = array ();
		$this->fabrique_where ( $where, $table ['ps'], "table_parent", "ci" );
		$this->fabrique_where ( $where, $table ['ps'], "key", "_os" );
		$this->fabrique_where ( $where, $table ['ps'], "value", $type_os );
		$this->fabrique_where ( $where, $table ['ci'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['ci'], "name", $name );
	
		$order_liste [0] = $this->fabrique_order_by ( "id ASC", $table ['ci'] );
		$order = sql::prepare_order_by ( $order_liste );
	
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
	
		return $resultat;
	}
	/***************** ci *********************/
	
	/***************** credentials *********************/
	/**
     * Retrouve le last report id de credentials.
     *
     * @return int|false Renvoi le last report id, FALSE sinon.
     */
	public function retrouve_lastcredentialsid() {
		return $this->retrouve_lastid ( "id", 'credentials' );
	}

	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_credentials_sans_id($serveur_id, $type = "") {
		$order = "";
		$table = array (
				'ps' => 'credentials' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['ps'], "serveur_id" );
		$this->fabrique_select ( $select, $table ['ps'], "type" );
		$this->fabrique_select ( $select, $table ['ps'], "name" );
		$this->fabrique_select ( $select, $table ['ps'], "key" );
		$this->fabrique_select ( $select, $table ['ps'], "value" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['ps'] );
		$where = array ();
		$this->fabrique_where ( $where, $table ['ps'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['ps'], "type", $type );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['ps'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** credentials *********************/
	
	/***************** leaf *********************/
	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_leaf_fullpath($serveur_id = "", $parent_id = "") {
		$order = "";
		$table = array (
				'tr' => 'tree',
				'lf' => 'leaf' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['lf'], "id", "id" );
		$this->fabrique_select ( $select, $table ['lf'], "name", "name" );
		$this->fabrique_select ( $select, $table ['tr'], "fullpathname", "fullpathname" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['lf'] );
		$this->fabrique_from_jointure ( $from, $table ['tr'], 'id', $table ['lf'], 'parent_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['lf'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['lf'], "parent_id", $parent_id );
		
		$order_liste [0] = $this->fabrique_order_by ( "name", $table ['lf'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_leaf_sis($serveur_id) {
		$table = 'leaf';
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$select = array ();
		$this->fabrique_select ( $select, $table, "id", "id" );
		$this->fabrique_select ( $select, $table, "serveur_id", "serveur_id" );
		$this->fabrique_select ( $select, $table, "parent_id", "parent_id" );
		$this->fabrique_select ( $select, $table, "name", "_name" );
		$this->fabrique_select ( $select, $table, "deleted", "deleted" );
		
		$where = array ();
		$this->fabrique_where ( $where, $table, "serveur_id", $serveur_id );
		
		$order_liste [0] = $this->fabrique_order_by ( "id ASC", $table );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}
	
	/**
	 * Retrouve les donnees d'une leaf et de son serveur associe
	 * @param string $customer
	 * @param string $leaf_name
	 * @param string $serveur_id
	 * @param string $parent_id
	 * @return Ambigous <PDO, boolean, multitype:, false>
	 */
	public function retrouve_leaf_avec_donnees_serveur($customer = "", $leaf_name = "", $serveur_id = "", $leaf_id = "", $parent_id = "") {
		$order = "";
		$table = array (
				'l' => 'leaf',
				'sv' => 'serveur'
		);
	
		$select = array ();
		$this->fabrique_select ( $select, $table ['l'] );
		$this->fabrique_select ( $select, $table ['sv'] );
		$from = array ();
		$this->fabrique_from ( $from, $table ['sv'] );
		$this->fabrique_from_jointure ( $from, $table ['l'], 'serveur_id', $table ['sv'], 'id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		$this->fabrique_where ( $where, $table ['sv'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['l'], "name", $leaf_name );
		$this->fabrique_where ( $where, $table ['l'], "id", $leaf_id );
		$this->fabrique_where ( $where, $table ['l'], "parent_id", $parent_id );

		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['l'] );
		$order = sql::prepare_order_by ( $order_liste );
	
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
	
		return $resultat;
	}

	/**
	 * Cree et applique cette requete de type select.
	 *
	 * @return array|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	function recherche_leaf($serveur_id = "", $leaf_name = "") {
		$order = "";
		$table = array (
				'tr' => 'tree',
				'lf' => 'leaf'
		);
		
		// $sql="select leaf.id as tree_id,tree.serveur_id,serveur.name as serveur_name,serveur.customer
		//,leaf._name,tree._fullpathname as tree_path,tree.parent_id from leaf ";
		//$sql.="inner join tree on leaf.parent_id=tree.id ";
		//$sql.="inner join serveur on serveur.id=tree.serveur_id ";
		//if ($customer!="") $sql.=" and serveur.customer=".$customer;
		//$sql.=" where leaf._name like '%".$text."%'";
		$select = array ();
		$this->fabrique_select ( $select, $table ['lf'], "id", "tree_id" );
		$this->fabrique_select ( $select, $table ['lf'], "name", "_name" );
		$this->fabrique_select ( $select, $table ['tr'], "serveur_id", "serveur_id" );
		$this->fabrique_select ( $select, $table ['tr'], "fullpathname", "tree_path" );
		$this->fabrique_select ( $select, $table ['lf'], "parent_id", "parent_id" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['lf'] );
		$this->fabrique_from_jointure ( $from, $table ['tr'], 'id', $table ['lf'], 'parent_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['tr'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['lf'], "name", $leaf_name );
		
		$order_liste [0] = $this->fabrique_order_by ( "name", $table ['lf'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		return $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
	}

	protected function _retrouveLeafDatas($table_data, $customer = "", $leaf_name = "", $serveur_id = "", $key = "", $value = "", $parent_id = "") {
		$order = "";
		$table = array (
				'l' => 'leaf',
				'sv' => 'serveur' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['sv'], "id", "id" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['sv'] );
		$where = array ();
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		$this->fabrique_where ( $where, $table ['sv'], "serveur_id", $serveur_id );
		$this->creer_select ( $select, $from, $where, "", "DISTINCT" );
		
		$sous_requete = $this->getSql ();
		
		//La requete principale
		//SELECT DISTINCT leaf.`id` AS 'id',leaf.`parent_id` AS 'parent_id',props.`_key` AS '_key',
		//props.`_value` AS '_value',leaf.`serveur_id` AS 'serveurid' FROM props
		//JOIN leaf ON leaf.`id`=props.`parent_id`  WHERE props.`table_parent`='leaf'
		//AND leaf.`serveur_id`  IN (SELECT DISTINCT serveur.`id` AS 'id' FROM serveur)
		//ORDER BY leaf.`id`  ASC
		$select = array ();
		$this->fabrique_select ( $select, $table ['l'], "id", "id" );
		$this->fabrique_select ( $select, $table ['l'], "parent_id", "parent_id" );
		$this->fabrique_select ( $select, $table_data, "key", "_key" );
		$this->fabrique_select ( $select, $table_data, "value", "_value" );
		$this->fabrique_select ( $select, $table ['l'], "serveur_id", "serveurid" );
		$from = array ();
		$this->fabrique_from ( $from, $table_data );
		$this->fabrique_from_jointure ( $from, $table ['l'], 'id', $table_data, 'parent_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table_data, "table_parent", 'leaf' );
		$this->fabrique_where ( $where, $table_data, "parent_id", $parent_id );
		$this->fabrique_where ( $where, $table_data, "key", $key );
		$this->fabrique_where ( $where, $table_data, "value", $value );
		$this->fabrique_where ( $where, $table ['l'], "name", $leaf_name );
		$this->fabrique_where ( $where, $table ['l'], "serveur_id", array (
				$sous_requete 
		) );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['l'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	public function retrouve_leaf_props($customer = "", $leaf_name = "", $serveur_id = "", $key = "", $value = "", $parent_id = "") {
		return $this->_retrouveLeafDatas ( 'props', $customer, $leaf_name, $serveur_id, $key, $value, $parent_id );
	}

	public function retrouve_leaf_runtime($customer = "", $leaf_name = "", $serveur_id = "", $key = "", $value = "", $parent_id = "") {
		return $this->_retrouveLeafDatas ( 'runtime', $customer, $leaf_name, $serveur_id, $key, $value, $parent_id );
	}

	public function retrouve_leaf_dependson($serveur_id = "", $value = "", $leaf_parent_id = "") {
		$order = "";
		$table = array (
				'ps' => 'props',
				'l' => 'leaf' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['l'], "id", "id" );
		$this->fabrique_select ( $select, $table ['l'], "name", "_name" );
		$this->fabrique_select ( $select, $table ['l'], "parent_id", "parent_id" );
		$this->fabrique_select ( $select, $table ['ps'], "key", "_key" );
		$this->fabrique_select ( $select, $table ['ps'], "value", "_value" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['ps'] );
		$this->fabrique_from_jointure ( $from, $table ['l'], 'id', $table ['ps'], 'parent_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['ps'], "table_parent", 'leaf' );
		$this->fabrique_where ( $where, $table ['ps'], "key", "_id" );
		$this->fabrique_where ( $where, $table ['ps'], "value", $value );
		$this->fabrique_where ( $where, $table ['l'], "parent_id", $leaf_parent_id );
		$this->fabrique_where ( $where, $table ['l'], "serveur_id", $serveur_id );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['l'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** leaf *********************/
	
	/***************** tasks_elements *********************/
	/***************** retrouve_tasks_data *********************/
	/**
     * Retrouve les donnees de tasks sitescope.
     *
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	public function retrouve_tasks_data($serveur_id, $schedule_enable = '1', $schedule_times = "") {
		//select a.*,tasks.* from tasks 
		//inner join tasks_elements a on a.task_id=tasks.id
		$select = array ();
		$from = array ();
		$where = array ();
		$table = array (
				'te' => 'tasks_elements',
				't' => 'tasks' 
		);
		
		$this->fabrique_select ( $select, $table ['t'] );
		
		$this->fabrique_from ( $from, $table ['t'] );
		$this->fabrique_from_jointure ( $from, $table ['te'], 'task_id', $table ['t'], 'id' );
		
		$this->fabrique_where ( $where, $table ['te'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['t'], "schedule_enable", $schedule_enable );
		$this->fabrique_where ( $where, $table ['t'], "schedule_times", $schedule_times );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['t'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/**
     * Retrouve les donnees de tasks sitescope.
     *
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	public function retrouve_liste_tasks_par_customer($customer = "", $task_id = "", $fullpathname = "") {
		
		// "select tasks.*,tasks_elements.*,serveur.name as serveur_name from tasks";
		// " inner join tasks_elements on tasks.id=tasks_elements.task_id";
		// " inner join serveur on tasks_elements.serveur_id=serveur.id";
		// " where serveur.customer='" . $customer . "'";
		$select = array ();
		$from = array ();
		$where = array ();
		$table = array (
				'te' => 'tasks_elements',
				't' => 'tasks',
				'sv' => 'serveur' 
		);
		
		$this->fabrique_select ( $select, $table ['t'] );
		$this->fabrique_select ( $select, $table ['te'] );
		$this->fabrique_select ( $select, $table ['sv'], "name", "serveur_name" );
		$this->fabrique_select ( $select, $table ['sv'], "customer", "customer" );
		
		$this->fabrique_from ( $from, $table ['t'] );
		$this->fabrique_from_jointure ( $from, $table ['te'], 'task_id', $table ['t'], 'id' );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['te'], 'serveur_id' );
		
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		$this->fabrique_where ( $where, $table ['te'], "task_id", $task_id );
		$this->fabrique_where ( $where, $table ['te'], "fullpathname", $fullpathname );
		
		$order_liste [0] = $this->fabrique_order_by ( "fullpathname", $table ['te'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** retrouve_tasks_data *********************/
	
	/**
     * Retrouve les donnees de tasks sitescope.
     *
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	public function retrouve_tasks_elements_sans_tasks() {
		//select distinct task_id from tasks_elements where task_id not in (select id from tasks);
		$select = array ();
		$where = array ();
		$table = array (
				'te' => 'tasks_elements',
				't' => 'tasks' 
		);
		
		$this->fabrique_select ( $select, $table ['t'], "id" );
		$this->creer_select ( $select, $table ['t'], $where, "" );
		$sous_requete = $this->getSql ();
		
		$select = array ();
		$where = array ();
		$this->fabrique_select ( $select, $table ['te'], "ele_id" );
		$this->creer_select ( $select, $table ['te'], $where, "", "DISTINCT" );
		$requete_depart = $this->getSql ();
		
		$requete = $requete_depart . " WHERE " . $this->renvoi_champ ( $table ['te'], "task_id" ) . " NOT IN (" . $sous_requete . ")";
		
		return $this->faire_requete ( $requete );
	}

	/**
     * Retrouve les donnees de tasks sitescope.
     *
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	public function retrouve_tasks_elements_sans_leaf($isgroup) {
		//select ele_id,id from tasks_elements left join tree on tasks_elements.source_id=tree.id where tasks_elements._isgroup=1;
		$select = array ();
		$where = array ();
		$table = array (
				'te' => 'tasks_elements',
				'lf' => 'leaf',
				'tr' => 'tree' 
		);
		
		if ($isgroup == "1") {
			$this->fabrique_select ( $select, $table ['tr'], "id" );
			$this->creer_select ( $select, $table ['tr'], $where, "" );
			$sous_requete = $this->getSql ();
		} else {
			$this->fabrique_select ( $select, $table ['lf'], "id" );
			$this->creer_select ( $select, $table ['lf'], $where, "" );
			$sous_requete = $this->getSql ();
		}
		
		$select = array ();
		$where = array ();
		$this->fabrique_select ( $select, $table ['te'], "ele_id" );
		$this->fabrique_select ( $select, $table ['te'], "not_exist" );
		$this->fabrique_select ( $select, $table ['te'], "not_exist_since" );
		$this->creer_select ( $select, $table ['te'], $where, "", "DISTINCT" );
		$requete_depart = $this->getSql ();
		
		$requete = $requete_depart . " WHERE " . $this->renvoi_champ ( $table ['te'], "isgroup" ) . "=" . $isgroup . " AND " . $this->renvoi_champ ( $table ['te'], "source_id" ) . " NOT IN (" . $sous_requete . ")";
		
		return $this->faire_requete ( $requete );
	}

	/***************** tasks_elements *********************/
	
	/***************** preferences *********************/
	/**
     * Retrouve le last report id de preferences.
     *
     * @return int|false Renvoi le last report id, FALSE sinon.
     */
	public function retrouve_lastpreferencesid() {
		return $this->retrouve_lastid ( "id", 'preferences' );
	}

	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_preferences_sans_id($serveur_id, $type = "") {
		$order = "";
		$table = array (
				'ps' => 'preferences' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['ps'], "serveur_id" );
		$this->fabrique_select ( $select, $table ['ps'], "type" );
		$this->fabrique_select ( $select, $table ['ps'], "key" );
		$this->fabrique_select ( $select, $table ['ps'], "value" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['ps'] );
		$where = array ();
		$this->fabrique_where ( $where, $table ['ps'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['ps'], "type", $type );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['ps'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** preferences *********************/
	
	/***************** props *********************/
	/**
     * Retrouve le last report id de props.
     *
     * @return int|false Renvoi le last report id, FALSE sinon.
     */
	public function retrouve_lastpropsid() {
		return $this->retrouve_lastid ( "id", 'props' );
	}

	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_props_sans_id($serveur_id, $table_parent = "leaf") {
		$order = "";
		$table = array (
				'ps' => 'props',
				'lf' => $table_parent 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['ps'], "parent_id" );
		$this->fabrique_select ( $select, $table ['ps'], "key" );
		$this->fabrique_select ( $select, $table ['ps'], "value" );
		$this->fabrique_select ( $select, $table ['ps'], "table_parent" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['ps'] );
		$this->fabrique_from_jointure ( $from, $table ['lf'], 'id', $table ['ps'], 'parent_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['lf'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['ps'], "table_parent", $table_parent );
		
		$order_liste [0] = $this->fabrique_order_by ( "key", $table ['ps'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** props *********************/
	
	/***************** runtime *********************/
	
	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_runtime_sans_id($serveur_id, $table_parent = "leaf") {
		$order = "";
		$table = array (
				'rt' => 'runtime',
				'lf' => $table_parent 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['rt'], "parent_id" );
		$this->fabrique_select ( $select, $table ['rt'], "key" );
		$this->fabrique_select ( $select, $table ['rt'], "value" );
		$this->fabrique_select ( $select, $table ['rt'], "table_parent" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['rt'] );
		$this->fabrique_from_jointure ( $from, $table ['lf'], 'id', $table ['rt'], 'parent_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['lf'], "serveur_id", $serveur_id );
		$this->fabrique_where ( $where, $table ['rt'], "table_parent", $table_parent );
		
		$order_liste [0] = $this->fabrique_order_by ( "parent_id", $table ['rt'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** runtime *********************/
	
	/***************** schedules *******************/
	public function retrouve_schedules_par_customer($customer = "") {
		$order = "";
		$table = array (
				's' => 'schedules',
				'sv' => 'serveur'
		);
	
		$select = array ();
		$this->fabrique_select ( $select, $table ['s'],"schedule_id","schedule_id" );
		$this->fabrique_select ( $select, $table ['s'],"name","schedule_name" );
		$this->fabrique_select ( $select, $table ['sv'], "name", "serveur_name" );
		$this->fabrique_select ( $select, $table ['sv'], "id", "serveurid" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['s'] );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['s'], 'serveur_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
	
		$order_liste [0] = $this->fabrique_order_by ( "schedule_id", $table ['s'] );
		$order = sql::prepare_order_by ( $order_liste );
	
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
	
		return $resultat;
	}
	/***************** schedules *******************/
	
	/***************** serveur *********************/
	public function select_customer($actif = 1) {
		$table = 'serveur';
		$select = array ();
		$this->fabrique_select ( $select, $table, "customer" );
		$this->fabrique_select ( $select, $table, "actif" );
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$where = array ();
		$this->fabrique_where ( $where, $table, "actif", $actif );
		
		$order_liste [0] = $this->fabrique_order_by ( "customer", $table );
		$order = sql::prepare_order_by ( $order_liste );
		
		return $this->selectionner ( $select, $from, $where, $order, 'DISTINCT' );
	}

	public function retrouve_dependances_serveur($sid, $_name = "", $_remoteID = "", $_host = "", $_id = "") {
		$table = array (
				'sv' => 'serveur',
				'lf' => 'leaf',
				'tr' => 'tree',
				'ps' => 'props' 
		);
		
		//Sous requete : select distinct props.parent_id from props
		//inner join leaf on props.parent_id=leaf.id where (_name OR _remoteID OR _host OR _id )
		//AND table_parent='leaf' AND leaf.serveur_id=" . $sid . "
		$select = array ();
		$this->fabrique_select ( $select, $table ['ps'], "parent_id", "parent_id" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['ps'] );
		$this->fabrique_from_jointure ( $from, $table ['lf'], 'id', $table ['ps'], 'parent_id' );
		$from_join = $this->creer_from_join ( $from );
		$where_local = array ();
		$this->fabrique_where ( $where_local, $table ['ps'], "table_parent", "leaf" );
		$this->fabrique_where ( $where_local, $table ['lf'], "serveur_id", $sid );
		$where_or = array ();
		$this->fabrique_where ( $where_or, $table ['ps'], "value", $_name );
		$this->fabrique_where ( $where_or, $table ['ps'], "value", $_remoteID );
		$this->fabrique_where ( $where_or, $table ['ps'], "value", $_host );
		$this->fabrique_where ( $where_or, $table ['ps'], "value", $_id );
		$where_local [] .= "(" . $this->creer_liste_or ( $where_or ) . ")";
		$this->creer_select ( $select, $from_join, $where_local, "", "DISTINCT" );
		
		$sous_requete = $this->getSql ();
		
		//La requete principale
		//select leaf.id,tree.serveur_id,serveur.name,leaf._name,tree._fullpathname
		//from leaf inner join tree on leaf.parent_id=tree.id
		//inner join serveur on tree.serveur_id=serveur.id where leaf.id in (sous requete)
		$select = array ();
		$this->fabrique_select ( $select, $table ['lf'], "id", "id" );
		$this->fabrique_select ( $select, $table ['lf'], "name", "name" );
		$this->fabrique_select ( $select, $table ['tr'], "serveur_id", "serveur_id" );
		$this->fabrique_select ( $select, $table ['tr'], "fullpathname", "fullpathname" );
		$this->fabrique_select ( $select, $table ['sv'], "name", "serveur_name" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['lf'] );
		$this->fabrique_from_jointure ( $from, $table ['tr'], 'id', $table ['lf'], 'parent_id' );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['tr'], 'serveur_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['lf'], "id", array (
				$sous_requete 
		) );
		$order = "ORDER BY " . $this->renvoi_champ ( $table ['lf'], "name" ) . " ASC";
		
		return $this->selectionner_avec_jointure ( $select, $from, $where, "", "DISTINCT" );
	}

	/***************** serveur *********************/
	
	/***************** sitescope_ci *********************/
	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_sitescope_ci_synchro($serveur_id = "") {
		$table = 'sitescope_ci';
		$order = "";
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$select = array ();
		$this->fabrique_select ( $select, $table, "customer" );
		$this->fabrique_select ( $select, $table, "ci_name" );
		$this->fabrique_select ( $select, $table, "id" );
		
		$where = array ();
		$this->fabrique_where ( $where, $table, "serveur_id", $serveur_id );
		
		$resultat = $this->selectionner ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_sitescope_ci_from_ci($serveur_id = "") {
		//select distinct serveur.customer,ci._name as tree_name,sitescope_ci.id 
		//from ci inner join serveur on serveur.id=ci.serveur_id 
		//left join sitescope_ci on sitescope_ci.customer=serveur.customer;
		$select = array ();
		$from = array ();
		$where = array ();
		$table = array (
				'sv' => 'serveur',
				'sci' => 'sitescope_ci',
				'ci' => 'ci' 
		);
		
		$this->fabrique_select ( $select, $table ['sv'], "customer" );
		$this->fabrique_select ( $select, $table ['ci'], "name" );
		$this->fabrique_select ( $select, $table ['ci'], "id" );
		
		$this->fabrique_from ( $from, $table ['sv'] );
		$this->fabrique_from_jointure ( $from, $table ['ci'], 'serveur_id', $table ['sv'], 'id' );
		$this->fabrique_from_jointure ( $from, $table ['sci'], 'customer', $table ['sv'], 'customer', "left" );
		
		$this->fabrique_where ( $where, $table ['sv'], "serveur_id", $serveur_id );
		
		$order_liste [0] = $this->fabrique_order_by ( "customer", $table ['sv'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** sitescope_ci *********************/
	
	/***************** tree *********************/
	/**
     * Cree et applique cette requete de type select.
     *
     * @param string $serial Serial a trouver
     * @return array|false Renvoi un tableau de resultat, FALSE sinon.
     */
	function requete_select_tree_sis($serveur_id) {
		$table = 'tree';
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$select = array ();
		$this->fabrique_select ( $select, $table, "id", "id" );
		$this->fabrique_select ( $select, $table, "serveur_id", "serveur_id" );
		$this->fabrique_select ( $select, $table, "parent_id", "parent_id" );
		$this->fabrique_select ( $select, $table, "name", "_name" );
		$this->fabrique_select ( $select, $table, "fullpathname", "_fullpathname" );
		
		$where = array ();
		$this->fabrique_where ( $where, $table, "serveur_id", $serveur_id );
		
		$order_liste [0] = $this->fabrique_order_by ( "id ASC", $table );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	public function retrouve_tree_par_customer($customer = "", $tree_name = "") {
		//select tree.id,tree._fullpathname from tree inner join serveur on tree.serveur_id=serveur.id where customer='" . $customer . "';";
		$order = "";
		$table = array (
				't' => 'tree',
				'sv' => 'serveur' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['t'], "id", "id" );
		$this->fabrique_select ( $select, $table ['t'], "fullpathname", "_fullpathname" );
		$this->fabrique_select ( $select, $table ['sv'], "name", "serveur_name" );
		$this->fabrique_select ( $select, $table ['sv'], "id", "serveurid" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['t'] );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['t'], 'serveur_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['t'], "name", $tree_name );
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['t'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	public function retrouve_tree_props($customer = "", $tree_name = "", $serveur_id = "", $key = "", $value = "", $parent_id = "") {
		$order = "";
		$table = array (
				'ps' => 'props',
				't' => 'tree',
				'sv' => 'serveur' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['t'], "id", "id" );
		$this->fabrique_select ( $select, $table ['t'], "parent_id", "parent_id" );
		$this->fabrique_select ( $select, $table ['t'], "fullpathname", "_fullpathname" );
		$this->fabrique_select ( $select, $table ['ps'], "key", "_key" );
		$this->fabrique_select ( $select, $table ['ps'], "value", "_value" );
		$this->fabrique_select ( $select, $table ['sv'], "name", "serveur_name" );
		$this->fabrique_select ( $select, $table ['sv'], "id", "serveurid" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['ps'] );
		$this->fabrique_from_jointure ( $from, $table ['t'], 'id', $table ['ps'], 'parent_id' );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['t'], 'serveur_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['ps'], "table_parent", 'tree' );
		$this->fabrique_where ( $where, $table ['ps'], "parent_id", $parent_id );
		$this->fabrique_where ( $where, $table ['ps'], "key", $key );
		$this->fabrique_where ( $where, $table ['ps'], "value", $value );
		$this->fabrique_where ( $where, $table ['t'], "name", $tree_name );
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		$this->fabrique_where ( $where, $table ['sv'], "id", $serveur_id );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['t'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	public function retrouve_tree_runtime($customer = "", $tree_name = "") {
		//tree.id,tree.parent_id,_key,_value,serveur.name as serveur_name,serveur.id as serveurid from props";
		$order = "";
		$table = array (
				'rt' => 'runtime',
				't' => 'tree',
				'sv' => 'serveur' 
		);
		
		$select = array ();
		$this->fabrique_select ( $select, $table ['t'], "id", "id" );
		$this->fabrique_select ( $select, $table ['t'], "parent_id", "parent_id" );
		$this->fabrique_select ( $select, $table ['rt'], "key", "_key" );
		$this->fabrique_select ( $select, $table ['rt'], "value", "_value" );
		$this->fabrique_select ( $select, $table ['sv'], "name", "serveur_name" );
		$this->fabrique_select ( $select, $table ['sv'], "id", "serveurid" );
		$from = array ();
		$this->fabrique_from ( $from, $table ['rt'] );
		$this->fabrique_from_jointure ( $from, $table ['t'], 'id', $table ['rt'], 'parent_id' );
		$this->fabrique_from_jointure ( $from, $table ['sv'], 'id', $table ['t'], 'serveur_id' );
		$where = array ();
		$this->fabrique_where ( $where, $table ['rt'], "table_parent", 'tree' );
		$this->fabrique_where ( $where, $table ['t'], "name", $tree_name );
		$this->fabrique_where ( $where, $table ['sv'], "customer", $customer );
		
		$order_liste [0] = $this->fabrique_order_by ( "id", $table ['t'] );
		$order = sql::prepare_order_by ( $order_liste );
		
		$resultat = $this->selectionner_avec_jointure ( $select, $from, $where, $order, "DISTINCT" );
		
		return $resultat;
	}

	/***************** tree *********************/
	
	/**
     * Ferme la connexion.
     */
	public function close() {
		
	}

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>