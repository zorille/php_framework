<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
/**
 * class splunk_search_jobs_id
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_search_jobs_id extends splunk_search_jobs {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $search_id = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_search_jobs_id. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string $search_id Id de l'instance a traiter
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_search_jobs_id
	 */
	static function &creer_splunk_search_jobs_id(&$liste_option, &$splunk_webservice_rest, $search_id, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_search_jobs_id ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest, 
				'search_id' => $search_id ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_search_jobs_id
	 */
	public function &_initialise($liste_class) {
		$this ->setSearchId ( $liste_class ['search_id'] );
		parent::_initialise ( $liste_class );
		
		return $this ->reset_resource ();
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
	 * @return splunk_search_jobs_id
	 * @throws  Exception
	 */
	public function &reset_resource() {
		$this ->valid_search_id ();
		return parent::reset_resource () ->addResource ( $this ->getSearchId () );
	}

	/**
	 * Valid if search_id is not empty. Send Exception if false.
	 * @return splunk_search_jobs_id
	 * @throws  Exception
	 */
	public function valid_search_id() {
		if (empty ( $this ->getSearchId () )) {
			$this ->onError ( "Il faut un search_id pour travailler" );
		}
		
		return $this;
	}

	/**
	 * Resource: search/jobs/{search_id}
	 *   Method: Get 
	 * Get information about the {search_id} search job. 
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getJobInformations($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$results = $this ->reset_resource () 
			->get ( $params );
		
		return $results;
	}

	/**
	 * Resource: search/jobs/{search_id}/results
	 *   Method: Get 
	 * Get {search_id} search results.
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getJobResults($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource () 
			->addResource ( 'results' ) 
			->get ( $params );
		
		return $results;
	}
	
	/**
	 * Resource: search/jobs/{search_id}/results_preview
	 *   Method: Get
	 * Preview {search_id} search results.
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getJobResultsPreview($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource ()
		->addResource ( 'results_preview' )
		->get ( $params );
	
		return $results;
	}

	/**
	 * Resource: search/jobs/{search_id}/control
	 *   Method: Post
	 * Run a job control command for the {search_id} search.
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function runJob($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource () 
			->addResource ( 'control' ) 
			->post ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getSearchId() {
		return $this->search_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSearchId($search_id) {
		$this->search_id = $search_id;
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "splunk_search_jobs_id :";
		
		return $help;
	}
}
?>
