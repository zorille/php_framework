<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use Zorille\framework as Core;
use Exception as Exception;
use Zorille\itop\Organization;

/**
 * class companies
 *
 * @package Lib
 * @subpackage veeamspc
 */
class companies extends organizations {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $companie_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_companies = null;


	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancompaniese un objet de type companies. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return companies
	 */
	static function &creer_veeamspc_companies(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new companies ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return companies
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
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
		// Gestion de companies
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du companie, l'ajoute à l'objet et renvoi l'Id
	 * @return string
	 * @throws Exception
	 */
	public function recupere_id_de_companie(
			$companie) {
		$this->setCompanieId ( $this->recupere_instanceUid ( $companie ) );
		return $this->getCompanieId();
	}

	/**
	 * Recupere le nom du companie
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_de_companie(
			$companie) {
		return ( string ) $companie->name;
	}

	/**
	 * Permet de trouver la liste des companies dans veeamspc et enregistre les donnees des companies dans l'objet
	 * @return companies
	 * @throws Exception
	 */
	public function retrouve_companies(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$companies = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_tenants = $this->listCompanies ( $params );
			$this->onDebug ( $liste_res_tenants, 2 );
			foreach ( $liste_res_tenants->data as $tenant ) {
				$companies [$this->recupere_id_de_companie ( $tenant )] = $tenant;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setCompanieId ( "" )
			->setListeCompanies ( $companies );
	}

	/**
	 * Liste les organisations
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listCompanies(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetVeeamWsclientRest ()
			->getMethod ( $this->companies_list_uri (), $params );
		return $resultat;
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un companie id est rempli/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_companieid(
			$error = true) {
		if (empty ( $this->getCompanieId () )) {
			$this->onDebug ( $this->getCompanieId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un companie id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	public function companies_list_uri() {
		return $this->organizations_list_uri().'/companies';
	}

	public function companie_id_uri() {
		if ($this->valide_companieid () == false) {
			return $this->onError ( "Il n'y pas d'id d'companie selectionne" );
		}
		return $this->companies_list_uri(). "/" . $this->getCompanieId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getCompanieId() {
		return $this->companie_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCompanieId(
			$companie_id) {
		$this->companie_id = $companie_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeCompanies() {
		return $this->liste_companies;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeCompanies(
			$liste_companies) {
		$this->liste_companies = $liste_companies;
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
		$help [__CLASS__] ["text"] [] .= "companies :";
		return $help;
	}
}
?>
