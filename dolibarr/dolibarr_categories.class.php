<?php
/**
 * Gestion de dolibarr.
 * @author dvargas
 */
/**
 * class dolibarr_categories
 *
 * @package Lib
 * @subpackage dolibarr
 */
class dolibarr_categories extends dolibarr_ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type dolibarr_categories. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param dolibarr_wsclient $dolibarr_webservice_rest Reference sur un objet dolibarr_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return dolibarr_categories
	 */
	static function &creer_dolibarr_categories(&$liste_option, &$dolibarr_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new dolibarr_categories ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"dolibarr_wsclient" => $dolibarr_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet 
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return dolibarr_categories
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		 return $this ->reset_resource();
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return dolibarr_categories
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'categories' );
	}

	/**
	 * Resource: categories
	 *   Method: Get 
	 * Get details of all current searches.  
	 * params : sortfield,sortorder,limit,page,type,sqlfilters
	 * type : Type of category ('member', 'customer', 'supplier', 'product', 'contact')
	 * sqlfilters : Other criteria to filter answers separated by a comma. Syntax example "(t.ref:like:'SO-%') and (t.date_creation:<:'20160101')"
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getAllCategories($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource () 
			->get ( $params );
		
		return $results;
	}

	/**
	 * Resource: categories
	 *   Method: Post 
	 * Start a new search and return the search ID (<sid>) 
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function runSingleCategory($search, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params ['search'] = $search;
		$results = $this ->reset_resource () 
			->post ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "dolibarr_categories :";
		
		return $help;
	}
}
?>
