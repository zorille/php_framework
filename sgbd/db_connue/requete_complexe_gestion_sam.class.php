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

class requete_complexe_gestion_sam extends desc_bd_gestion_sam {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requete_complexe_gestion_sam.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_complexe_gestion_sam
	 */
	static function &creer_requete_complexe_gestion_sam(&$liste_option, $sort_en_erreur = false, $entete = "requete_complexe_gestion_sam") {
		$objet = new requete_complexe_gestion_sam ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_complexe_gestion_sam
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/***************** ci *********************/
	public function retrouve_ci_avec_apps($nom_serveur)
	{
		$order="";
		$table=array('sv'=>'serveur', 'ci'=>'ci','tr'=>'tree');
	
		$select=array();
		$this->fabrique_select($select,$table['ci'],"id","id");
		$this->fabrique_select($select,$table['ci'],"name","name");
		$this->fabrique_select($select,$table['ci'],"status","status");
		$from=array();
		$this->fabrique_from($from,$table['ci']);
		$this->fabrique_from_jointure($from,$table['tr'],'parent_id',$table['ci'],'id');
		$this->fabrique_from_jointure($from,$table['sv'],'id',$table['tr'],'ci_id');
		$where=array();
		$this->fabrique_where($where,$table['sv'],"name",$nom_serveur);
	
		$order_liste[0]=$this->fabrique_order_by("name",$table['ci']);
		$order=sql::prepare_order_by($order_liste);
	
		$resultat=$this->selectionner_avec_jointure($select,$from,$where,$order,"DISTINCT");
	
		return $resultat;
	}
	
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
	
	public function retrouve_ci_par_nom($serveur_id,$ciname)
	{
		$order="";
		$table=array('ci'=>'ci','sv'=>'serveur');
	
		$select=array();
		$this->fabrique_select($select,$table['ci'],"id","tree_id");
		$this->fabrique_select($select,$table['ci'],"name","name");
		$this->fabrique_select($select,$table['ci'],"serveur_id","serveur_id");
		$this->fabrique_select($select,$table['sv'],"name","serveurname");
		$this->fabrique_select($select,$table['sv'],"name","name");
		$from=array();
		$this->fabrique_from($from,$table['ci']);
		$this->fabrique_from_jointure($from,$table['sv'],'id',$table['ci'],'serveur_id');
		$where=array();
		$this->fabrique_where($where,$table['ci'],"serveur_id",$serveur_id);
		$this->fabrique_where($where,$table['ci'],"name","%".$ciname."%");
	
		$order_liste[0]=$this->fabrique_order_by("id",$table['ci']);
		$order=sql::prepare_order_by($order_liste);
	
		$resultat=$this->selectionner_avec_jointure($select,$from,$where,$order,"DISTINCT");
	
		return $resultat;
	}
	
	public function retrouve_ci_props($ciname="")
	{
		$order="";
		$table=array('ps'=> 'props', 'ci'=>'ci','sv'=>'serveur');
	
		$select=array();
		$this->fabrique_select($select,$table['ci'],"name","name");
		$this->fabrique_select($select,$table['ci'],"status","ci_status");
		$this->fabrique_select($select,$table['ps'],"key","key");
		$this->fabrique_select($select,$table['ps'],"value","value");
		$from=array();
		$this->fabrique_from($from,$table['ps']);
		$this->fabrique_from_jointure($from,$table['ci'],'id',$table['ps'],'parent_id');
		$this->fabrique_from_jointure($from,$table['sv'],'id',$table['ci'],'serveur_id');
		$where=array();
		$this->fabrique_where($where,$table['ps'],"table_parent",'ci');
		$this->fabrique_where($where,$table['ci'],"name",$ciname);
	
		$order_liste[0]=$this->fabrique_order_by("id",$table['ci']);
		$order=sql::prepare_order_by($order_liste);
	
		$resultat=$this->selectionner_avec_jointure($select,$from,$where,$order,"DISTINCT");
	
		return $resultat;
	}
	
	public function retrouve_ci_runtime($ciname="")
	{
		$order="";
		$table=array('rt'=> 'runtime', 'ci'=>'ci','sv'=>'serveur');
	
		$select=array();
		$this->fabrique_select($select,$table['ci'],"name","name");
		$this->fabrique_select($select,$table['ci'],"status","ci_status");
		$this->fabrique_select($select,$table['rt'],"key","key");
		$this->fabrique_select($select,$table['rt'],"value","value");
		$from=array();
		$this->fabrique_from($from,$table['rt']);
		$this->fabrique_from_jointure($from,$table['ci'],'id',$table['rt'],'parent_id');
		$this->fabrique_from_jointure($from,$table['sv'],'id',$table['ci'],'serveur_id');
		$where=array();
		$this->fabrique_where($where,$table['rt'],"table_parent",'ci');
		$this->fabrique_where($where,$table['ci'],"name",$ciname);
	
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
		$this->fabrique_select ( $select, $table, "name" );
		$this->fabrique_select ( $select, $table, "actif" );
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$where = array ();
		$this->fabrique_where ( $where, $table , "actif", $actif );
	
		$order_liste [0] = $this->fabrique_order_by ( "name", $table );
		$order = sql::prepare_order_by ( $order_liste );
	
		return $this->selectionner($select, $from, $where, $order,'DISTINCT');
	}
	/***************** serveur *********************/
	
	/***************** tree *********************/
	public function retrouve_tree_par_ci_et_application($ci,$application,$parent_id){
		$order="";
		$table=array('sv'=>'serveur', 'tr'=>'tree');
		
		//Sub request for application id
		$select=array();
		$this->fabrique_select ( $select, $table ['sv'], "id" );
		$where=array();
		$this->fabrique_where($where,$table['sv'],"name",$application);
		$this->creer_select ( $select, $table ['sv'], $where, "" );
		$sous_requete = $this->getSql ();
	
		$select=array();
		$this->fabrique_select($select,$table['tr'],"id","id");
		$this->fabrique_select($select,$table['tr'],"name","name");
		$this->fabrique_select($select,$table['tr'],"fullpathname","fullpathname");
		$from=array();
		$this->fabrique_from($from,$table['tr']);
		$where=array();
		$this->creer_select ( $select, $table ['tr'], $where, "", "DISTINCT" );
		$requete_depart = $this->getSql ();
		
		$where=array();
		$this->fabrique_where($where,$table['tr'],"parent_id",$parent_id);
	
		$order_liste[0]=$this->fabrique_order_by("name",$table['sv']);
		$order=sql::prepare_order_by($order_liste);
	
		$requete = $requete_depart . " WHERE " . $where[0]." AND ". $this->renvoi_champ ( $table ['tr'], "ci_id" ) . " IN (" . $sous_requete . ")";
			
		return $this->faire_requete($requete);
	}
	/***************** tree *********************/
}
?>
