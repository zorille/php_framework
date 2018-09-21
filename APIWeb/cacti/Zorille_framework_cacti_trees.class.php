<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class cacti_addTree<br>
 *
 * Prepare une ligne de commande de generation.
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_trees extends parametresStandard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $trees = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $trees_structure = array ();
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_trees.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_trees
	 */
	static function &creer_cacti_trees(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_trees ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_trees
	 */
	public function &_initialise($liste_class) {
		parent::_initialise($liste_class);
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de cacti_globals
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		$this->charge_trees ();
	}
	
	/**
	 * Charge la liste des hosts via l'API Cacti
	 * @throws Exception
	 */
	public function charge_trees() {
		$this->onDebug ( "On charge la liste des trees.", 1 );
		// fonction de l'API cacti : lib/database.php via global.php
		$dbtrees = db_fetch_assoc ( "select id,name from graph_tree" );
		if ($dbtrees) {
			$trees = array ();
			foreach ( $dbtrees as $row ) {
				$trees [$row ['id']] = $row ['name'];
			}
		}
		return $this->setTrees ( $trees );
	}
	
	/**
	 * Charge la liste des hosts via l'API Cacti
	 */
	public function charge_trees_list() {
		$this->onDebug ( "On charge la liste des sous-trees.", 1 );
		$array_trees = array ();
		foreach ( $this->getTrees () as $tree_id => $tree_name ) {
			$array_trees[$tree_name]=array();
			$list_trees = db_fetch_assoc ( "select
		graph_tree_items.id,
		graph_tree_items.title,
		graph_tree_items.graph_tree_id,
		graph_tree_items.local_graph_id,
		graph_tree_items.host_id,
		graph_tree_items.order_key,
		graph_tree_items.sort_children_type,
		graph_templates_graph.title_cache as graph_title,
		CONCAT_WS('',description,' (',hostname,')') as hostname
		from graph_tree_items
		left join graph_templates_graph on (graph_tree_items.local_graph_id=graph_templates_graph.local_graph_id and graph_tree_items.local_graph_id>0)
		left join host on (host.id=graph_tree_items.host_id)
		where graph_tree_items.graph_tree_id=" . $tree_id . "
		order by graph_tree_id, graph_tree_items.order_key" );
			
			foreach ( $list_trees as $tree ) {
				$tier = tree_tier ( $tree ["order_key"] );
				
				if ($tree ["local_graph_id"] > 0) {
					$this->_creerTableau($array_trees[$tree_name],$tier,"Graph",$tree ["graph_title"]);
				} elseif ($tree ["title"] != "") {
					$this->_creerTableau($array_trees[$tree_name],$tier,"Heading",$tree ["title"]);
				} elseif ($tree ["host_id"] > 0) {
					$this->_creerTableau($array_trees[$tree_name],$tier,"Host",$tree ["hostname"]);
				}
			}
		}
		$this->setTreesStruct($array_trees);
		
		return $this;
	}
	
	/**
	 * Creer un tableau de structure des arbres cacti
	 * @codeCoverageIgnore
	 * @param array $tableau
	 * @param int $niveau
	 * @param string $type
	 * @param string $nom_menu
	 */
	private function _creerTableau(&$tableau,$niveau,$type,$nom_menu){
		//Le niveau commence a 1
		$i=1;
		while ($i<$niveau){
			$last_entry="";
			foreach($tableau as $nom=>$inutile){
				$last_entry=$nom;
			}
			$tableau=&$tableau[$last_entry];
			
			$i++;
		}
		
		switch($type){
			case "Heading":
				$tableau[$nom_menu]=array();
				break;
			default:
				$pos=count($tableau);
				$tableau[$pos]["nom"]=$nom_menu;
				$tableau[$pos]["type"]=$type;
		}
		
		return $this;
	}
	
	/**
	 * Valide qu'un tree existe par son nom.
	 *
	 * @return boolean True le tree existe, false le tree n'existe pas.
	 */
	public function valide_tree_by_name($tree_name) {
		foreach($this->getTrees () as $Tree){
			if(in_array ( $tree_name, $Tree )){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Valide qu'un tree existe par son id.
	 *
	 * @return boolean True le tree existe, false le tree n'existe pas.
	 */
	public function valide_tree_by_id($tree_id) {
		$trees = $this->getTrees ();
		if (isset ( $trees [$tree_id] )) {
			return true;
		}
		return false;
	}
	
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getTrees($tree = "all") {
		if (isset ( $this->trees [$tree] )) {
			return $this->trees [$tree];
		}
		return $this->trees;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setTrees($trees) {
		if (is_array ( $trees )) {
			$this->trees = $trees;
		} else {
			return $this->onError ( "Il faut un tableau de trees." );
		}
		return $this;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &ajoutetrees($nom, $tree_id) {
		if ($nom != "" && $tree_id != "") {
			$this->trees [$nom] = $tree_id;
		} else {
			return $this->onError ( "Il faut un nom et/ou un tree_id." );
		}
		return $this;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function getTreesStruct() {
		return $this->trees_structure;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setTreesStruct($trees_structure) {
		if (is_array ( $trees_structure )) {
			$this->trees_structure = $trees_structure;
		} else {
			return $this->onError ( "Il faut un tableau de trees structure." );
		}
		return $this;
	}

/**
 * ***************************** ACCESSEURS *******************************
 */
}
?>
