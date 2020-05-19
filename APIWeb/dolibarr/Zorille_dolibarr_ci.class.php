<?php

/**
 * Gestion de dolibarr.
 * @author dvargas
 */
namespace Zorille\dolibarr;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class ci
 *
 * @package Lib
 * @subpackage dolibarr
 */
abstract class ci extends Core\abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var wsclient
	 */
	private $wsclient_rest = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $ressource = array ();
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
	 * @var string
	 */
	private $Message404Error = "";
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
	 * @return ci
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetdolibarrWsclient ( $liste_class ['wsclient'] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Remet l'url par defaut
	 * @return ci
	 */
	public function &reset_resource() {
		return $this->setResource ( array () );
	}

	/**
	 * Construit l'url REST
	 * @return string
	 */
	public function prepare_url() {
		return implode ( '/', $this->getResource () );
	}

	/**
	 * @return ci
	 * @throws Exception
	 */
	public function verifie_erreur($null_accepted=false) {
		$retour = $this->getContent ();
		if ($retour == NULL) {
			if($null_accepted){
				return $this->setListEntry ( array () );
			}
			return $this->onError ( "Erreur durant la requete : le retour est NULL", "" );
		}
		if (is_array ( $retour ) && isset ( $retour ['error'] )) {
			if (isset ( $retour ['debug'] )) {
				$this->onDebug ( $retour ['debug'], 1 );
			}
			// Dolibarr renvoi un 404 lorsqu'il n'y a pas de resultat a la requete emise
			if (strpos ( $retour ['error'] ['message'], $this->getMessage404Error () ) !== false) {
				$this->onWarning ( $retour ['error'] ['message'] );
				$this->setListEntry ( array () );
			} else {
				return $this->onError ( "Erreur durant la requete : " . $retour ['error'] ['message'], "", $retour ['error'] ['code'] );
			}
		}
		return $this;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	public function get(
			$params,$null_accepted=false) {
		$this->setContent ( $this->getObjetdolibarrWsclient ()
			->getMethod ( $this->prepare_url (), $params ) )
			->recupereListEntry ()
			->verifie_erreur ($null_accepted);
		return $this;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	public function post(
			$params) {
		$this->setContent ( $this->getObjetdolibarrWsclient ()
			->postMethod ( $this->prepare_url (), $params ) )
			->recupereListEntry ()
			->verifie_erreur ();
		return $this;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	public function put(
			$params) {
		$this->setContent ( $this->getObjetdolibarrWsclient ()
			->putMethod ( $this->prepare_url (), $params ) )
			->recupereListEntry ()
			->verifie_erreur ();
		return $this;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	public function delete(
			$params) {
		$this->setContent ( $this->getObjetdolibarrWsclient ()
			->deleteMethod ( $this->prepare_url (), $params ) )
			->recupereListEntry ()
			->verifie_erreur ();
		return $this;
	}

	/**
	 * Renvoie la liste des elements
	 * @param array $ListEntryArray
	 * @return array
	 * @throws Exception
	 */
	public function recupereListEntry() {
		$ListEntryArray = $this->getContent ();
		if (isset ( $ListEntryArray ['success'] )) {
			return $this->setListEntry ( $ListEntryArray ['success'] );
		}
		return $this->setListEntry ( $ListEntryArray );
	}

	/**
	 * Insert Dolibarr Single Entry
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function insertSingleEntry(
			$liste_donnees) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $liste_donnees;
		$results = $this->reset_resource ()
			->post ( $params );
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return wsclient
	 */
	public function &getObjetdolibarrWsclient() {
		return $this->wsclient_rest;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetdolibarrWsclient(
			&$wsclient) {
		$this->wsclient_rest = $wsclient;
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
	public function &setResource(
			$ressource) {
		$this->ressource = $ressource;
		return $this;
	}

	/**
	 * @return ci
	 * @codeCoverageIgnore
	 */
	public function &addResource(
			$ressource) {
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
	public function &setTitle(
			$title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMessage404Error() {
		return $this->Message404Error;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMessage404Error(
			$Message404Error) {
		$this->Message404Error = $Message404Error;
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
	public function &setContent(
			$content) {
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
	public function &setListEntry(
			$liste_entry) {
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
		$help [__CLASS__] ["text"] [] .= "ci :";
		return $help;
	}
}
?>
