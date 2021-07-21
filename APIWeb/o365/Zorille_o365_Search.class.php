<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Search
 *
 * @package Lib
 * @subpackage o365
 */
class Search extends Core\abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var wsclient
	 */
	private $wsclient = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_search = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Search. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Search
	 */
	static function &creer_Search(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Search ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Search
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetO365Wsclient ( $liste_class ['wsclient'] );
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
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * ******************************* SEARCH *********************************
	 */
	public function creer_search_query(
			$entityTypes = array(),
			$query = "*") {
		$request = array (
				"requests" => array (
						array (
								"entityTypes" => $entityTypes,
								"query" => array (
										"queryString" => $query
								)
						)
				)
		);
		$this->onDebug ( json_encode ( $request ), 0 );
		return $request;
	}

	/**
	 * ******************************* O365 SEARCH *********************************
	 */
	/**
	 * Recuperer la liste d'utilisateurs O365
	 * @param array $entityTypes
	 * @param string $query
	 * @return \Zorille\o365\Search|false
	 */
	public function search(
			$entityTypes = array(),
			$query = "*") {
		$this->onDebug ( __METHOD__, 1 );
		$liste_search_o365 = $this->getObjetO365Wsclient ()
			->jsonPostMethod ( '/search/query', $this->creer_search_query ( $entityTypes, $query ) );
		return $this->setListeSearch ( $liste_search_o365->value );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return wsclient
	 */
	public function &getObjetO365Wsclient() {
		return $this->wsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetO365Wsclient(
			&$wsclient) {
		$this->wsclient = $wsclient;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeSearch() {
		return $this->liste_search;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeSearch(
			&$liste_search) {
		$this->liste_search = $liste_search;
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
		$help [__CLASS__] ["text"] [] .= "Search :";
		return $help;
	}
}
?>
