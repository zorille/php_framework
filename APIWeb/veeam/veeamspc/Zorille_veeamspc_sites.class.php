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
 * class sites
 *
 * @package Lib
 * @subpackage veeamspc
 */
class sites extends companies {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $site_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_sites = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instansitese un objet de type sites. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return sites
	 */
	static function &creer_veeamspc_sites(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new sites ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return sites
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
		// Gestion de sites
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du site, l'ajoute à l'objet et renvoi l'Id
	 * @return string
	 * @throws Exception
	 */
	public function recupere_id_de_site(
			$site) {
		$this->setSiteId ( $site->siteUid );
		return ( string ) $site->siteUid;
	}

	/**
	 * Recupere le nom du site
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_de_site(
			$site) {
		return ( string ) $site->name;
	}

	/**
	 * Permet de trouver la liste des sites dans veeamspc et enregistre les donnees des sites dans l'objet
	 * @return sites
	 * @throws Exception
	 */
	public function retrouve_sites(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$sites = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_tenants = $this->listSites ( $params );
			$this->onDebug ( $liste_res_tenants, 2 );
			foreach ( $liste_res_tenants->data as $tenant ) {
				$sites [$this->recupere_id_de_site ( $tenant )] = $tenant;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setSiteId ( "" )
			->setListeSites ( $sites );
	}

	/**
	 * Liste les organisations
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listSites(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetVeeamWsclientRest ()
			->getMethod ( $this->sites_list_uri (), $params );
		return $resultat;
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un site id est rempli/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_siteid(
			$error = true) {
		if (empty ( $this->getSiteId () )) {
			$this->onDebug ( $this->getSiteId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un site id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	public function sites_list_uri() {
		if (! empty ( $this->getCompanieId () )) {
			return $this->companie_id_uri () . '/sites';
		}
		return $this->companies_list_uri () . '/sites';
	}

	public function site_id_uri() {
		if ($this->valide_siteid () == false) {
			return $this->onError ( "Il n'y pas d'id de site selectionne" );
		}
		return $this->companie_id_uri () . "/site/" . $this->getSiteId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getSiteId() {
		return $this->site_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSiteId(
			$site_id) {
		$this->site_id = $site_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeSites() {
		return $this->liste_sites;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeSites(
			$liste_sites) {
		$this->liste_sites = $liste_sites;
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
		$help [__CLASS__] ["text"] [] .= "sites :";
		return $help;
	}
}
?>
