<?php

/**
 * Gestion de pipedrive.
 * @author dvargas
 */
namespace Zorille\pipedrive;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class leads
 *
 * @package Lib
 * @subpackage pipedrive
 */
class leads extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type leads. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $pipedrive_webservice_rest Reference sur un objet wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return leads
	 */
	static function &creer_leads(
			&$liste_option,
			&$pipedrive_webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new leads ( $sort_en_erreur, $entete );
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
	 * @return leads
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
	 * @return leads
	 */
	public function &reset_resource() {
		return parent::reset_resource ()->addResource ( 'leads' );
	}

	/**
	 * Resource: leads Method: Get Get details of all current searches. params : sortfield,sortorder,limit,page,thirdparty_ids,sqlfilters
	 * 
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getAllLeads(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->getLeads( $params );
		while( isset($this->getAdditionalData()['pagination']) && 
				isset($this->getAdditionalData()['pagination']['more_items_in_collection']) && 
				$this->getAdditionalData()['pagination']['more_items_in_collection']=== true ){
			$params['start']=$this->getAdditionalData()['pagination']['next_start'];
			$this->getLeads($params, true);
		}
		return $this;
	}
	
	/**
	 * Resource: leads Method: Get Get details of all current searches. params : sortfield,sortorder,limit,page,thirdparty_ids,sqlfilters
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getLeads(
			$params = array(), 
			$add_data = false ) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->setMessage404Error ( "Not Found: Leads not found" )
			->get ( $params, false, $add_data );
		return $this;
	}

	/**
	 * Resource: Leads Method: Get Get search for a Leads params : sortfield,sortorder,limit,page
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getLeadsSearch(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->addResource ( 'search' )
			->get ( $params );
		return $this;
	}
	
	/**
	 * Resource: leads Method: Get Get one lead by ID
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getLeadById(
			$Leads_id,
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->addResource ( $Leads_id )
			->get ( $params, true );
		return $this;
	}
		
	/**
	 * Resource: Leads Method: Get Get search for a Leads params : sortfield,sortorder,limit,page
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getLeadsParticipants(
			$Leads_id,
			$params = array()) {
				$this->onDebug ( __METHOD__, 1 );
				$this->reset_resource ()
				->addResource ( $Leads_id )
				->addResource ( 'participants' )
				->get ( $params, true );
			return $this;
	}

	/**
	 * Resource: leads Method: Post Start a new search and return the search ID (<sid>)
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function insertSingleLeads(
			$liste_donnees) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $liste_donnees;
		$results = $this->reset_resource ()
			->post ( $params );
		return $results;
	}

	/**
	 * Resource: leads Method: Post Start a new search and return the search ID (<sid>)
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function updateSingleLeads(
			$Leads_id,
			$liste_donnees) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $liste_donnees;
		$results = $this->reset_resource ()
			->addResource ( $Leads_id )
			->patch ( $params );
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
		$help [__CLASS__] ["text"] [] .= "leads :";
		return $help;
	}
}
?>
