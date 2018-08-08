<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
/**
 * class splunk_saved_searches_name
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_saved_searches_name extends splunk_saved_searches {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $saved_search_name = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_saved_searches_name. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string $saved_searches_name Nom de l'instance a traiter
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_saved_searches_name
	 */
	static function &creer_splunk_saved_searches_name(&$liste_option, &$splunk_webservice_rest, $saved_searches_name, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_saved_searches_name ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest, 
				"saved_searches_name" => $saved_searches_name ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_saved_searches_name
	 */
	public function &_initialise($liste_class) {
		$this ->setSavedSearchesName ( $liste_class ['saved_searches_name'] );
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
	 * @return splunk_saved_searches_name
	 * @throws  Exception
	 */
	public function &reset_resource() {
		$this ->valid_search_saved_name ();
		return parent::reset_resource () ->addResource ( $this ->getSavedSearchesNamed () );
	}

	/**
	 * Valid if search_id is not empty. Send Exception if false.
	 * @return splunk_saved_searches_name
	 * @throws  Exception
	 */
	public function valid_search_saved_name() {
		if (empty ( $this ->getSavedSearchesNamed () )) {
			$this ->onError ( "Il faut un saved_searches_name pour travailler" );
		}
		
		return $this;
	}

	/**
	 * Resource: saved/searches/{name}
	 *   Method: Get 
	 * Access the named saved search.
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getSavedSearchInformations($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$results = $this ->reset_resource () 
			->get ( $params );
		
		return $results;
	}

	/**
	 * Resource: saved/searches/{name}/history
	 *   Method: Get 
	 * List available search jobs created from the {name} saved search.
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getSavedSearchHistory($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$results = $this ->reset_resource () 
			->addResource ( 'history' ) 
			->get ( $params );
		
		return $results;
	}

	/**
	 * Resource: saved/searches/{name}/dispatch
	 *   Method: Post
	 * Dispatch the {name} saved search.
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getSavedSearchDispatch($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$results = $this ->reset_resource () 
			->addResource ( 'dispatch' ) 
			->post ( $params );
		
		return $results;
	}

	/**
	 * Resource: saved/searches/{name}/scheduled_times
	 *   Method: Get
	 * Access {name} saved search scheduled time. 
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getSavedSearchScheduledTime($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$results = $this ->reset_resource () 
			->addResource ( 'scheduled_times' ) 
			->get ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getSavedSearchesNamed() {
		return $this->saved_search_name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSavedSearchesName($saved_search_name) {
		$this->saved_search_name = $saved_search_name;
		
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
		$help [__CLASS__] ["text"] [] .= "splunk_saved_searches_name :";
		
		return $help;
	}
}
?>
