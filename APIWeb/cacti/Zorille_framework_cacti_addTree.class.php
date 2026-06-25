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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_addTree
	 */
	static function &creer_cacti_addTree(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): cacti_addTree {
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
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
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
	 * @throws Exception
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
	public function valide_ParentNode(): bool {
		return in_array ( $this->getTreeId (), $this->getGraphTreesItemData ()
			->getGraphTreeItems ( $this->getParentNode () ) );
	}

	/**
	 * Ajoute un tree dans cacti
	 *
	 * @return boolean unknown
	 * @throws Exception
	 */
	public function executeAdd_tree(): bool {
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
	public function executeAdd_node(): bool {
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
	 * @return bool|int Renvoi l'id du device, false en cas d'erreur.
	 * @throws Exception
	 */
	public function executeCacti_addTree(): bool|int {
		return match ($this->getType()) {
			"tree" => $this->executeAdd_tree(),
			"node" => $this->executeAdd_node(),
			default => $this->onError("Ce type n'existe pas : " . $this->getType()),
		};
	}

	/**
	 * Reset les valeurs pour un objet vide.
	 *
	 * @return boolean true
	 * @throws Exception
	 */
	public function reset_host(): bool {
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
	public function getHostId(): int {
		return $this->host_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostId($host_id): static {
		if (is_numeric ( $host_id )) {
			$this->host_id = $host_id;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHostGroupStyle(): int {
		return $this->hostGroupStyle;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setHostGroupStyle($hostGroupStyle): bool|static {
		if ($hostGroupStyle === 1 || $hostGroupStyle === 2) {
			$this->hostGroupStyle = $hostGroupStyle;
			return $this;
		}

		$r = $this->onError ( "Ce group Style n'existe pas." );
		return $r;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNodeId(): int {
		return $this->node_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNodeId($node_id): static {
		if (is_numeric ( $node_id )) {
			$this->node_id = $node_id;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setType($type): bool|static {
		switch ($type) {
			case "ND" :
				$this->type = "";
				return $this;
			case "node" :
			case "tree" :
				$this->type = $type;
				return $this;
			default :
				$r = $this->onError ( "Type inconnu : " . $type );
				return $r;
		}
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setName($name): bool|static {
		if ($name != "") {
			if ($name == "ND") {
				$this->name = "";
			} else {
				$this->name = $name;
			}
		} else {
			$r = $this->onError ( "le nom est obligatoire." );
			return $r;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSortMethod(): string {
		return $this->sortMethod;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setSortMethod($sortMethod): bool|static {
		if (array_key_exists ( $sortMethod, $this->getSortMethods () )) {
			$this->sortMethod = $sortMethod;
		} else {
			$this->sortMethod = "";
			$r = $this->onError ( "Cette methode n'existe pas : " . $sortMethod );
			return $r;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getParentNode(): int {
		return $this->parentNode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setParentNode($parentNode): static {
		if (is_numeric ( $parentNode )) {
			$this->parentNode = $parentNode;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTreeId(): int {
		return $this->treeId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTreeId($treeId): static {
		if (is_numeric ( $treeId )) {
			$this->treeId = $treeId;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNodeType(): string {
		return $this->nodeType;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setNodeType($nodeType): bool|static {
		if (array_key_exists ( $nodeType, $this->getNodeTypes () )) {
			$this->nodeType = $nodeType;
		} else {
			$this->nodeType = "";
			$r = $this->onError ( "Ce typde de node n'existe pas : " . $nodeType );
			return $r;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGraphId(): int {
		return $this->graphId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGraphId($graphId): static {
		if (is_numeric ( $graphId )) {
			$this->graphId = $graphId;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRraId(): int {
		return $this->rra_id;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setRraId($rra_periodicity): bool|static {
		$rraList = $this->getRraTypes ();
		if (array_key_exists ( $rra_periodicity, $rraList )) {
			$this->rra_id = $rraList [$rra_periodicity];
		} else {
			$this->rra_id = 1;
			$r = $this->onError ( "Ce rra_id n'existe pas : " . $rra_periodicity );
			return $r;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSortMethods(): array {
		return $this->sortMethods;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNodeTypes(): array {
		return $this->nodeTypes;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRraTypes(): array {
		return $this->rraTypes;
	}

	/**
	 * @codeCoverageIgnore
	 * @return cacti_hosts
	 */
	public function &getHostData(): cacti_hosts {
		return $this->host_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostData($host_data): static {
		if ($host_data instanceof cacti_hosts) {
			$this->host_data = $host_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return cacti_graphTreeItems|null
	 */
	public function &getGraphTreesItemData(): ?cacti_graphTreeItems {
		return $this->GraphTreesItem_Data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGraphTreesItemData($graphTreeItems_data): static {
		if ($graphTreeItems_data instanceof cacti_graphTreeItems) {
			$this->GraphTreesItem_Data = $graphTreeItems_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return cacti_graphs|null
	 */
	public function &getGraphsData(): ?cacti_graphs {
		return $this->Graphs_Data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGraphsData($graph_data): static {
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
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Creer et execute le programme cacti_addTree";
		$help [__CLASS__] ["text"] [] .= "NECESSITE au moins un fichier de conf machines/cacti.xml";
		$help [__CLASS__] ["text"] [] .= "\t--cacti_env mut/tlt/dev/perso permet de recuperer l'env dans la conf cacti";
		
		return $help;
	}
}
