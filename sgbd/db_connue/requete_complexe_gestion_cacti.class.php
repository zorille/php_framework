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

class requete_complexe_gestion_cacti extends desc_bd_gestion_cacti {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requete_complexe_gestion_cacti.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_complexe_gestion_cacti
	 */
	static function &creer_requete_complexe_gestion_cacti(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new requete_complexe_gestion_cacti ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_complexe_gestion_cacti
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/***************** ci *********************/
	public function retrouve_ci_avec_liste_ip($serveur_id)
	{
		$order="";
    	$table=array('ci'=>'ci','ps'=>'props');
    	 
    	$select=array();
    	$this->fabrique_select($select,$table['ci'],"id","id");
    	$this->fabrique_select($select,$table['ci'],"name","name");
    	$this->fabrique_select($select,$table['ci'],"status","status");
    	$this->fabrique_select($select,$table['ps'],"key","key");
    	$this->fabrique_select($select,$table['ps'],"value","value");
    	$from=array();
    	$this->fabrique_from($from,$table['ci']);
    	$this->fabrique_from_jointure($from,$table['ps'],'parent_id',$table['ci'],'id');
    	$where=array();
    	$this->fabrique_where($where,$table['ci'],"serveur_id",$serveur_id);
    
    	$order_liste[0]=$this->fabrique_order_by("id",$table['ci']);
    	$order=sql::prepare_order_by($order_liste);
    
    	$resultat=$this->selectionner_avec_jointure($select,$from,$where,$order,"DISTINCT");
    
    	return $resultat;
	}
	
	public function retrouve_ci_par_nom($serveur_id,$ci_name)
	{
		$order="";
		$table=array('ci'=>'ci','sv'=>'serveur');
	
		$select=array();
		$this->fabrique_select($select,$table['ci'],"id","tree_id");
		$this->fabrique_select($select,$table['ci'],"name","_name");
		$this->fabrique_select($select,$table['ci'],"serveur_id","serveur_id");
		$this->fabrique_select($select,$table['sv'],"name","serveur_name");
		$this->fabrique_select($select,$table['sv'],"customer","customer");
		$from=array();
		$this->fabrique_from($from,$table['ci']);
		$this->fabrique_from_jointure($from,$table['sv'],'id',$table['ci'],'serveur_id');
		$where=array();
		$this->fabrique_where($where,$table['ci'],"serveur_id",$serveur_id);
		$this->fabrique_where($where,$table['ci'],"name","%".$ci_name."%");
	
		$order_liste[0]=$this->fabrique_order_by("id",$table['ci']);
		$order=sql::prepare_order_by($order_liste);
	
		$resultat=$this->selectionner_avec_jointure($select,$from,$where,$order,"DISTINCT");
	
		return $resultat;
	}
	
	public function retrouve_ci_props($customer="",$ci_name="")
	{
		$order="";
		$table=array('ps'=> 'props', 'ci'=>'ci','sv'=>'serveur');
	
		$select=array();
		$this->fabrique_select($select,$table['ci'],"name","_name");
		$this->fabrique_select($select,$table['ci'],"status","ci_status");
		$this->fabrique_select($select,$table['ps'],"key","_key");
		$this->fabrique_select($select,$table['ps'],"value","_value");
		$from=array();
		$this->fabrique_from($from,$table['ps']);
		$this->fabrique_from_jointure($from,$table['ci'],'id',$table['ps'],'parent_id');
		$this->fabrique_from_jointure($from,$table['sv'],'id',$table['ci'],'serveur_id');
		$where=array();
		$this->fabrique_where($where,$table['ps'],"table_parent",'ci');
		$this->fabrique_where($where,$table['ci'],"name",$ci_name);
		$this->fabrique_where($where,$table['sv'],"customer",$customer);
	
		$order_liste[0]=$this->fabrique_order_by("id",$table['ci']);
		$order=sql::prepare_order_by($order_liste);
	
		$resultat=$this->selectionner_avec_jointure($select,$from,$where,$order,"DISTINCT");
	
		return $resultat;
	}
	
	public function retrouve_ci_runtime($customer="",$ci_name="")
	{
		$order="";
		$table=array('rt'=> 'runtime', 'ci'=>'ci','sv'=>'serveur');
	
		$select=array();
		$this->fabrique_select($select,$table['ci'],"name","_name");
		$this->fabrique_select($select,$table['ci'],"status","ci_status");
		$this->fabrique_select($select,$table['rt'],"key","_key");
		$this->fabrique_select($select,$table['rt'],"value","_value");
		$from=array();
		$this->fabrique_from($from,$table['rt']);
		$this->fabrique_from_jointure($from,$table['ci'],'id',$table['rt'],'parent_id');
		$this->fabrique_from_jointure($from,$table['sv'],'id',$table['ci'],'serveur_id');
		$where=array();
		$this->fabrique_where($where,$table['rt'],"table_parent",'ci');
		$this->fabrique_where($where,$table['ci'],"name",$ci_name);
		$this->fabrique_where($where,$table['sv'],"customer",$customer);
	
		$order_liste[0]=$this->fabrique_order_by("id",$table['ci']);
		$order=sql::prepare_order_by($order_liste);
	
		$resultat=$this->selectionner_avec_jointure($select,$from,$where,$order,"DISTINCT");
	
		return $resultat;
	}
	/***************** ci *********************/
	
	/***************** serveur *********************/
	public function select_customer($actif=1){
		$table='serveur';
		$select = array ();
		$this->fabrique_select ( $select, $table, "customer" );
		$this->fabrique_select ( $select, $table, "actif" );
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$where = array ();
		$this->fabrique_where ( $where, $table , "actif", $actif );
	
		$order_liste [0] = $this->fabrique_order_by ( "customer", $table );
		$order = sql::prepare_order_by ( $order_liste );
	
		return $this->selectionner($select, $from, $where, $order,'DISTINCT');
	}
	/***************** serveur *********************/
}
?>