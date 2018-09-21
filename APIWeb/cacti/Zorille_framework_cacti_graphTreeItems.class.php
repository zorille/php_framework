<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class cacti_graphTreeItems<br>
 *
 * Prepare une ligne de commande de generation.
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_graphTreeItems extends parametresStandard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $graph_tree_items = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_graphTreeItems.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_graphTreeItems
	 */
	static function &creer_cacti_graphTreeItems(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_graphTreeItems ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_graphTreeItems
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
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
		
		
		$this->charge_graphTreeItems ();
	}

	/**
	 * Charge la liste des hosts via l'API Cacti
	 * @throws Exception
	 */
	public function charge_graphTreeItems() {
		$dbparentNodes = db_fetch_assoc ( "SELECT id,graph_tree_id
					FROM graph_tree_items" );
		if ($dbparentNodes) {
			$parentNodeListe = array ();
			foreach ( $dbparentNodes as $row ) {
				$parentNodeListe [$row ['id']] = $row;
			}
		}
		return $this->setgraphTreeItems ( $parentNodeListe );
	}

	/**
	 * Valide qu'un tree existe par son nom.
	 *
	 * @return boolean True le tree existe, false le tree n'existe pas.
	 */
	public function valide_graphTreeItem_by_tree_id($tree_id) {
		foreach($this->getGraphTreeItems () as $GraphTreeItem){
			if(in_array ( $tree_id, $GraphTreeItem )){
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
	public function valide_graphTreeItem_by_id($getGraphTreeItems_id) {
		$trees = $this->getGraphTreeItems ();
		if (isset ( $trees [$getGraphTreeItems_id] )) {
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
	public function getGraphTreeItems($parentNodes = "all") {
		if (isset ( $this->graphTreeItems [$parentNodes] )) {
			return $this->graphTreeItems [$parentNodes];
		}
		return $this->graphTreeItems;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setGraphTreeItems($graphTreeItems) {
		if (is_array ( $graphTreeItems )) {
			$this->graphTreeItems = $graphTreeItems;
		} else {
			return $this->onError ( "Il faut un tableau de graph Tree Items." );
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &ajouteGraphTreeItems($id, $tree_id) {
		if ($id != "" && $tree_id != "") {
			$this->graphTreeItems [$id] = $tree_id;
		} else {
			return $this->onError ( "Il faut un id et/ou un tree_id." );
		}
		return $this;
	}

/**
	 * ***************************** ACCESSEURS *******************************
	 */
}
?>
