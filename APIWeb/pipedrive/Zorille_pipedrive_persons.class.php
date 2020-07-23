<?php

/**
 * Gestion de pipedrive.
 * @author dvargas
 */
namespace Zorille\pipedrive;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class persons
 *
 * @package Lib
 * @subpackage pipedrive
 */
class persons extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type persons. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $pipedrive_webservice_rest Reference sur un objet wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return persons
	 */
	static function &creer_persons(
			&$liste_option,
			&$pipedrive_webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new persons ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $pipedrive_webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return persons
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->reset_resource ();
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
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return persons
	 */
	public function &reset_resource() {
		return parent::reset_resource ()->addResource ( 'persons' );
	}

	/**
	 * Resource: persons Method: Get Get details of all current searches. params : sortfield,sortorder,limit,page,thirdparty_ids,sqlfilters
	 * 
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getAllPersons(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->getPersons( $params );
		while( isset($this->getAdditionalData()['pagination']) && 
				isset($this->getAdditionalData()['pagination']['more_items_in_collection']) && 
				$this->getAdditionalData()['pagination']['more_items_in_collection']=== true ){
			$params['start']=$this->getAdditionalData()['pagination']['next_start'];
			$this->getPersons($params, true);
		}
		return $this;
	}
	
	/**
	 * Resource: persons Method: Get Get details of all current searches. params : sortfield,sortorder,limit,page,thirdparty_ids,sqlfilters
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getPersons(
			$params = array(), 
			$add_data = false ) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->setMessage404Error ( "Not Found: Persons not found" )
			->get ( $params, false, $add_data );
		return $this;
	}

	/**
	 * Resource: Persons Method: Get Get search for a Persons params : sortfield,sortorder,limit,page
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getPersonsSearch(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->addResource ( 'search' )
			->get ( $params );
		return $this;
	}
	
	/**
	 * Resource: persons Method: Get Get one deal by ID
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getPersonById(
			$Persons_id,
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->addResource ( $Persons_id )
			->get ( $params, true );
		return $this;
	}
		
	/**
	 * Resource: Persons Method: Get 
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getPersonsDeals(
			$Persons_id,
			$params = array()) {
				$this->onDebug ( __METHOD__, 1 );
				$this->reset_resource ()
				->addResource ( $Persons_id )
				->addResource ( 'deals' )
				->get ( $params, true );
			return $this;
	}

	/**
	 * Resource: persons Method: Post Start a new search and return the search ID (<sid>)
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function insertSinglePersons(
			$liste_donnees) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $liste_donnees;
		$results = $this->reset_resource ()
			->post ( $params );
		return $results;
	}

	/**
	 * Resource: persons Method: Post Start a new search and return the search ID (<sid>)
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function updateSinglePersons(
			$Persons_id,
			$liste_donnees) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $liste_donnees;
		$results = $this->reset_resource ()
			->addResource ( $Persons_id )
			->put ( $params );
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
		$help [__CLASS__] ["text"] [] .= "persons :";
		return $help;
	}
}
?>
