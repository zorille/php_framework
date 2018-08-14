<?php
/**
 * Gestion de dolibarr.
 * @author dvargas
 */
/**
 * class dolibarr_ci
 *
 * @package Lib
 * @subpackage dolibarr
 */
abstract class dolibarr_ci extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var dolibarr_wsclient
	 */
	private $dolibarr_wsclient_rest = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $ressource = array ( 
			0 => 'services' );
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $title = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $content = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_entry = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return dolibarr_ci
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setObjetdolibarrWsclient ( $liste_class ['dolibarr_wsclient'] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Remet l'url par defaut
	 * @return dolibarr_ci
	 */
	public function &reset_resource() {
		return $this ->setResource (array() );
	}

	/**
	 * Construit l'url REST
	 * @return string
	 */
	public function prepare_url() {
		return implode ( '/', $this ->getResource () );
	}

	/**
	 * 
	 * @param object|array $retour
	 * @return dolibarr_ci
	 * @throws Exception
	 */
	public function verifie_erreur($retour) {
		if($retour==NULL){
			return $this ->onError ( "Erreur durant la requete : le retour est NULL","" );
		}
		if (is_array( $retour ) && isset ( $retour['error'] )) {
			if(isset($retour['debug'])){
				$this->onDebug($retour['debug'], 1);
			}
			return $this ->onError ( "Erreur durant la requete : " . $retour['error']['message'], "",$retour['error']['code'] );
		}
		
		return $this;
	}

	/**
	 * 
	 * @param array $params
	 * @return SimpleXMLElement
	 */
	public function get($params) {
		$results = $this ->getObjetdolibarrWsclient () 
			->getMethod ( $this ->prepare_url (), $params );
		$this ->verifie_erreur ( $results );
		$this ->setListEntry ( $this ->recupereListEntry ( $results ) );
		return $results;
	}

	/**
	 * 
	 * @param array $params
	 * @return SimpleXMLElement
	 */
	public function post($params) {
		$results = $this ->getObjetdolibarrWsclient () 
			->postMethod ( $this ->prepare_url (), $params );
		$this ->verifie_erreur ( $results );
		$this ->setListEntry ( $this ->recupereListEntry ( $results ) );
		return $results;
	}

	/**
	 * 
	 * @param array $params
	 * @return SimpleXMLElement
	 */
	public function delete($params) {
		$results = $this ->getObjetdolibarrWsclient () 
			->deleteMethod ( $this ->prepare_url (), $params );
		$this ->verifie_erreur ( $results );
		$this ->setListEntry ( $this ->recupereListEntry ( $results ) );
		return $results;
	}
	
	/** Renvoie la liste des elements
	 * @param array $ListEntryArray
	 * @return array
	 * @throws Exception
	 */
	public function recupereListEntry($ListEntryArray) {
		if(isset($ListEntryArray['success'])){
		
			return $ListEntryArray['success'];
		}
		return array();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return dolibarr_wsclient
	 */
	public function &getObjetdolibarrWsclient() {
		return $this->dolibarr_wsclient_rest;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetdolibarrWsclient(&$dolibarr_wsclient) {
		$this->dolibarr_wsclient_rest = $dolibarr_wsclient;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getResource() {
		return $this->ressource;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setResource($ressource) {
		$this->ressource = $ressource;
		return $this;
	}

	/**
	 * @return dolibarr_ci
	 * @codeCoverageIgnore
	 */
	public function &addResource($ressource) {
		array_push ( $this->ressource, $ressource );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setContent($content) {
		$this->content = $content;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListEntry() {
		return $this->liste_entry;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListEntry($liste_entry) {
		$this->liste_entry = $liste_entry;
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
		$help [__CLASS__] ["text"] [] .= "dolibarr_ci :";
		
		return $help;
	}
}
?>
