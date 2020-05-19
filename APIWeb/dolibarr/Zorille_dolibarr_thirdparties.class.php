<?php

/**
 * Gestion de dolibarr.
 * @author dvargas
 */
namespace Zorille\dolibarr;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class thirdparties
 *
 * @package Lib
 * @subpackage dolibarr
 */
class thirdparties extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type thirdparties. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $dolibarr_webservice_rest Reference sur un objet wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return thirdparties
	 */
	static function &creer_thirdparties(
			&$liste_option,
			&$dolibarr_webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new thirdparties ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $dolibarr_webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return thirdparties
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
	 * @return thirdparties
	 */
	public function &reset_resource() {
		return parent::reset_resource ()->addResource ( 'thirdparties' );
	}

	/**
	 * Resource: thirdparties Method: Get Get details of all current searches. params : sortfield,sortorder,limit,page,thirdparty_ids,sqlfilters
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getAllThirdparties(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->setMessage404Error ( "Not Found: Thirdparties not found" )
			->get ( $params );
		return $this;
	}

	/**
	 * Resource: Thirdpartie Method: Get Get categories for a Thirdpartie params : sortfield,sortorder,limit,page
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getThirdpartieCategories(
			$Thirdpartie_id,
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->addResource ( $Thirdpartie_id )
			->addResource ( 'categories' )
			->get ( $params );
		return $this;
	}
	
	/**
	 * Resource: Thirdpartie Method: Get Get categories for a Thirdpartie params : sortfield,sortorder,limit,page
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getThirdpartieRepresentatives(
			$Thirdpartie_id,
			$params = array()) {
				$this->onDebug ( __METHOD__, 1 );
				$this->reset_resource ()
				->addResource ( $Thirdpartie_id )
				->addResource ( 'representatives' )
				->get ( $params, true );
				return $this;
	}

	/**
	 * Resource: thirdparties Method: Post Start a new search and return the search ID (<sid>)
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function insertSingleThirdpartie(
			$liste_donnees) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $liste_donnees;
		$results = $this->reset_resource ()
			->post ( $params );
		return $results;
	}

	/**
	 * Resource: thirdparties Method: Post Start a new search and return the search ID (<sid>)
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function updateSingleThirdpartie(
			$Thirdpartie_id,
			$liste_donnees) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $liste_donnees;
		$results = $this->reset_resource ()
			->addResource ( $Thirdpartie_id )
			->put ( $params );
		return $results;
	}

	/**
	 * Resource: Thirdpartie Method: Get Get categories for a Thirdpartie params : sortfield,sortorder,limit,page
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function addThirdpartieCategories(
			$Thirdpartie_id,
			$Category_id,
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->addResource ( $Thirdpartie_id )
			->addResource ( 'categories' )
			->addResource ( $Category_id )
			->post ( $params );
		return $this;
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
		$help [__CLASS__] ["text"] [] .= "thirdparties :";
		return $help;
	}
}
?>
