<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class cacti_graphs<br>
 *
 * Prepare une ligne de commande de generation.
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_graphs extends parametresStandard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $graphids = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_graphs.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_graphs
	 */
	static function &creer_cacti_graphs(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_graphs ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_graphs
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
		
		
		$this->charge_graphs ();
	}

	/**
	 * Charge la liste des hosts via l'API Cacti
	 * @throws Exception
	 */
	public function charge_graphs() {
		$this->onDebug ( "On charge la liste des graph_local.", 1 );
		$dbgraphids = db_fetch_assoc ( "SELECT id,graph_template_id,host_id FROM graph_local " );
		if ($dbgraphids) {
			$graphids = array ();
			foreach ( $dbgraphids as $row ) {
				$graphids [$row ['id']] = $row;
			}
		}
		$this->setGraphIds ( $graphids );
		
		return $this;
	}

	/**
	 * Valide qu'un tree existe par son id.
	 *
	 * @return boolean True le tree existe, false le tree n'existe pas.
	 */
	public function valide_graph_by_id($graph_id) {
		$graphIds = $this->getGraphIds ();
		if (isset ( $graphIds [$graph_id] )) {
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
	public function getGraphIds($graphid = "all") {
		if (isset ( $this->graphids [$graphid] )) {
			return $this->graphids [$graphid];
		}
		return $this->graphids;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setGraphIds($graphid) {
		if (is_array ( $graphid )) {
			$this->graphids = $graphid;
		} else {
			return $this->onError ( "Il faut un tableau de graphid." );
		}
		return $this;
	}

/**
	 * ***************************** ACCESSEURS *******************************
	 */
}
?>
