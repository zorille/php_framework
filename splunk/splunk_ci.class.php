<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
/**
 * class splunk_ci
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_ci extends splunk_AtomFeed {
	/**
	 * var privee
	 *
	 * @access private
	 * @var splunk_wsclient
	 */
	private $splunk_wsclient_rest = null;
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
	 * @return splunk_ci
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setObjetSplunkWsclient ( $liste_class ['splunk_wsclient'] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Remet l'url par defaut
	 * @return splunk_ci
	 */
	public function &reset_resource() {
		return $this ->setResource ( array ( 
				0 => 'services' ) );
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
	 * @return splunk_ci
	 * @throws Exception
	 */
	public function verifie_erreur($retour) {
		if (is_object ( $retour ) && isset ( $retour->messages ) && isset ( $retour->messages->msg ) && $retour->messages->msg != '') {
			return $this ->onError ( "Erreur durant la requete : " . $retour->messages->msg, $retour );
		} elseif (is_array ( $retour ) && isset ( $retour ['messages'] ) && isset ( $retour ['messages'] ['msg'] ) && $retour ['messages'] ['msg'] != '') {
			return $this ->onError ( "Erreur durant la requete : " . $retour ['messages'] ['msg'], $retour );
		}
		
		return $this;
	}

	/**
	 * 
	 * @param array $params
	 * @return SimpleXMLElement
	 */
	public function get($params) {
		$results = $this ->getObjetSplunkWsclient () 
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
		$results = $this ->getObjetSplunkWsclient () 
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
		$results = $this ->getObjetSplunkWsclient () 
			->deleteMethod ( $this ->prepare_url (), $params );
		$this ->verifie_erreur ( $results );
		$this ->setListEntry ( $this ->recupereListEntry ( $results ) );
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return splunk_wsclient
	 */
	public function &getObjetSplunkWsclient() {
		return $this->splunk_wsclient_rest;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSplunkWsclient(&$splunk_wsclient) {
		$this->splunk_wsclient_rest = $splunk_wsclient;
		
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
	 * @return splunk_ci
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
		$help [__CLASS__] ["text"] [] .= "splunk_ci :";
		
		return $help;
	}
}
?>
