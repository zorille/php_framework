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
class cacti_addTree extends cacti_trees {
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $host_id = - 1;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $hostGroupStyle = 1; // 1 = Graph Template, 2 = Data Query Index
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $node_id = - 1;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $type = ''; // tree or node
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $name = ''; // Name of a tree or node
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $sortMethod = 'alpha'; // manual, alpha, natural, numeric
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $parentNode = - 1; // When creating a node, the parent node of this node (or zero for root-node)
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $treeId = 0; // When creating a node, it has to go in a tree
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $nodeType = ''; // Should be 'header', 'graph' or 'host' when creating a node
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $graphId = 0; // The ID of the graph to add (gets added to parentNode)
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $rra_id = 1; // The rra_id for the graph to display: 1 = daily, 2 = weekly, 3 = monthly, 4 = yearly
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $sortMethods = array (
			'manual' => 1,
			'alpha' => 2,
			'natural' => 4,
			'numeric' => 3 
	);
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $nodeTypes = array (
			'header' => 1,
			'graph' => 2,
			'host' => 3 
	);
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $rraTypes = array (
			'none' => 0,
			'daily' => 1,
			'weekly' => 2,
			'monthly' => 3,
			'yearly' => 4,
			'hourly' => 5 
	);
	
	/**
	 * var privee
	 *
	 * @access private
	 * @var cacti_hosts
	 */
	private $hosts_data = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var cacti_trees
	 */
	private $trees_data = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var cacti_graphTreeItems
	 */
	private $GraphTreesItem_Data = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var cacti_graphs
	 */
	private $Graphs_Data = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_addTree.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_addTree
	 */
	static function &creer_cacti_addTree(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_addTree ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_addTree
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setGraphTreesItemData ( cacti_graphTreeItems::creer_cacti_graphTreeItems ( $liste_class ["options"], $this->getSortEnErreur () ) )
			->setHostData ( cacti_hosts::creer_cacti_hosts ( $liste_class ["options"], $this->getSortEnErreur () ) )
			->setGraphsData ( cacti_graphs::creer_cacti_graphs ( $liste_class ["options"], $this->getSortEnErreur () ) );
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
		
	}

	/**
	 * Valide qu'un parentNode existe.
	 *
	 * @return boolean True le tree existe, false le tree n'existe pas.
	 */
	public function valide_ParentNode() {
		return in_array ( $this->getTreeId (), $this->getGraphTreesItemData ()
			->getGraphTreeItems ( $this->getParentNode () ) );
	}

	/**
	 * Ajoute un tree dans cacti
	 *
	 * @return boolean unknown
	 */
	public function executeAdd_tree() {
		if ($this->getName () == "") {
			return $this->onError ( "Il faut un nom." );
		}
		
		if ($this->valide_tree_by_name ( $this->getName () )) {
			return $this->onError ( "Doublon de tree en base." );
		}
		
		// On ajoute le tree en base
		$treeOpts = array ();
		$treeOpts ["id"] = 0; // Zero means create a new one rather than save over an existing one
		$treeOpts ["name"] = $this->getName ();
		$treeOpts ["sort_type"] = SORT_TYPE_TREE;
		$treeId = sql_save ( $treeOpts, "graph_tree" );
		sort_tree ( SORT_TYPE_TREE, $treeId, $treeOpts ["sort_type"] );
		$this->setTreeId ( $treeId );
		$this->onDebug ( "Tree-id : " . $treeId, 1 );
		
		return $treeId;
	}

	/**
	 * Ajoute un node dans cacti
	 *
	 * @return boolean unknown
	 * @throws Exception
	 */
	public function executeAdd_node() {
		if ($this->getNodeType () == "") {
			return $this->onError ( "Il faut un NodeType." );
		}
		if (! $this->valide_ParentNode ()) {
			return $this->onError ( "Il faut un Parent Node valide." );
		}
		
		switch ($this->getNodeType ()) {
			case 'header' :
				// Blank out the graphId, rra_id, hostID and host_grouping_style fields
				$this->setGraphId ( 0 );
				$this->setRraId ( 'none' );
				$this->setHostId ( 0 );
				$this->setHostGroupStyle ( 1 );
				// Header --name must be given
				if ($this->getName () == "") {
					return $this->onError ( "Il faut un Nom." );
				}
				break;
			case 'graph' :
				// Blank out name, hostID, host_grouping_style
				$this->setName ( 'ND' );
				$this->setHostId ( 0 );
				$this->setHostGroupStyle ( 1 );
				// verify rra-id
				if ($this->getRraId () === 0) {
					return $this->onError ( "Il faut un rra-id > 0." );
				}
				if (! $this->getGraphsData ()
					->valide_graph_by_id ( $this->getGraphId () )) {
					return $this->onError ( "Le graph Id n'est pas valide." );
				}
				break;
			case 'host' :
				// Blank out graphId, rra_id, name fields
				$this->setGraphId ( 0 );
				$this->setRraId ( 'none' );
				$this->setName ( 'ND' );
				if (! $this->getHostData ()
					->valide_host_by_id ( $this->getHostId () )) {
					return $this->onError ( "Il faut un host Id valide." );
				}
				break;
		}
		
		// $nodeId could be a Header Node, a Graph Node, or a Host node.
		$nodeId = api_tree_item_save ( 0, $this->getTreeId (), $this->getNodeType (), $this->getParentNode (), $this->getName (), $this->getGraphId (), $this->getRraId (), $this->getHostId (), $this->getHostGroupStyle (), $this->getSortMethod (), false );
		$this->setNodeId ( $nodeId );
		$this->onDebug ( "Node-id : " . $nodeId, 1 );
		
		return $nodeId;
	}

	/**
	 * Ajoute un device
	 *
	 * @return Integer/false Renvoi l'id du device, false en cas d'erreur.
	 * @throws Exception
	 */
	public function executeCacti_addTree() {
		switch ($this->getType ()) {
			case "tree" :
				return $this->executeAdd_tree ();
			case "node" :
				return $this->executeAdd_node ();
			default :
				return $this->onError ( "Ce type n'existe pas : " . $this->getType () );
		}
	}

	/**
	 * Reset les valeurs pour un objet vide.
	 *
	 * @return boolean true
	 * @throws Exception
	 */
	public function reset_host() {
		$this->setHostId ( - 1 );
		$this->setHostGroupStyle ( 1 );
		$this->setNodeId ( - 1 );
		$this->setName ( "ND" );
		$this->setType ( "ND" );
		$this->setParentNode ( - 1 );
		$this->setTreeId ( - 1 );
		
		$this->setGraphId ( - 1 );
		$this->setRraId ( 'none' );
		$this->setHostId ( 0 );
		
		return true;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getHostId() {
		return $this->host_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostId($host_id) {
		if (is_numeric ( $host_id )) {
			$this->host_id = $host_id;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHostGroupStyle() {
		return $this->hostGroupStyle;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setHostGroupStyle($hostGroupStyle) {
		if (is_numeric ( $hostGroupStyle ) && ($hostGroupStyle === 1 || $hostGroupStyle === 2)) {
			$this->hostGroupStyle = $hostGroupStyle;
		} else {
			return $this->onError ( "Ce group Style n'existe pas." );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNodeId() {
		return $this->node_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNodeId($node_id) {
		if (is_numeric ( $node_id )) {
			$this->node_id = $node_id;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setType($type) {
		switch ($type) {
			case "ND" :
				$this->type = "";
				return $this;
				break;
			case "node" :
			case "tree" :
				$this->type = $type;
				return $this;
				break;
			default :
				return $this->onError ( "Type inconnu : " . $type );
		}
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setName($name) {
		if ($name != "") {
			if ($name == "ND") {
				$this->name = "";
			} else {
				$this->name = $name;
			}
		} else {
			return $this->onError ( "le nom est obligatoire." );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSortMethod() {
		return $this->sortMethod;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSortMethod($sortMethod) {
		if (array_key_exists ( $sortMethod, $this->getSortMethods () )) {
			$this->sortMethod = $sortMethod;
		} else {
			$this->sortMethod = "";
			return $this->onError ( "Cette methode n'existe pas : " . $sortMethod );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getParentNode() {
		return $this->parentNode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setParentNode($parentNode) {
		if (is_numeric ( $parentNode )) {
			$this->parentNode = $parentNode;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTreeId() {
		return $this->treeId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTreeId($treeId) {
		if (is_numeric ( $treeId )) {
			$this->treeId = $treeId;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNodeType() {
		return $this->nodeType;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setNodeType($nodeType) {
		if (array_key_exists ( $nodeType, $this->getNodeTypes () )) {
			$this->nodeType = $nodeType;
		} else {
			$this->nodeType = "";
			return $this->onError ( "Ce typde de node n'existe pas : " . $nodeType );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGraphId() {
		return $this->graphId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGraphId($graphId) {
		if (is_numeric ( $graphId )) {
			$this->graphId = $graphId;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRraId() {
		return $this->rra_id;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setRraId($rra_periodicity) {
		$rraList = $this->getRraTypes ();
		if (array_key_exists ( $rra_periodicity, $rraList )) {
			$this->rra_id = $rraList [$rra_periodicity];
		} else {
			$this->rra_id = 1;
			return $this->onError ( "Ce rra_id n'existe pas : " . $rra_periodicity );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSortMethods() {
		return $this->sortMethods;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNodeTypes() {
		return $this->nodeTypes;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRraTypes() {
		return $this->rraTypes;
	}

	/**
	 * @codeCoverageIgnore
	 * @return cacti_hosts
	 */
	public function &getHostData() {
		return $this->host_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostData($host_data) {
		if ($host_data instanceof cacti_hosts) {
			$this->host_data = $host_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return cacti_graphTreeItems
	 */
	public function &getGraphTreesItemData() {
		return $this->GraphTreesItem_Data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGraphTreesItemData($graphTreeItems_data) {
		if ($graphTreeItems_data instanceof cacti_graphTreeItems) {
			$this->GraphTreesItem_Data = $graphTreeItems_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return cacti_graphs
	 */
	public function &getGraphsData() {
		return $this->Graphs_Data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGraphsData($graph_data) {
		if ($graph_data instanceof cacti_graphs) {
			$this->Graphs_Data = $graph_data;
		}
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 *
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Creer et execute le programme cacti_addTree";
		$help [__CLASS__] ["text"] [] .= "NECESSITE au moins un fichier de conf machines/cacti.xml";
		$help [__CLASS__] ["text"] [] .= "\t--cacti_env mut/tlt/dev/perso permet de recuperer l'env dans la conf cacti";
		
		return $help;
	}
}
?>
