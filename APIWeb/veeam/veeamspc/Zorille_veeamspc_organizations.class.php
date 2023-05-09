<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class organizations
 *
 * @package Lib
 * @subpackage veeamspc
 */
class organizations extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $organization_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_organizations = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_includes = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instanorganizationse un objet de type organizations. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return organizations
	 */
	static function &creer_veeamspc_organizations(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new organizations ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return organizations
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetVeeamWsclientRest ( $liste_class ["wsclient"] );
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
		// Gestion de organizations
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du organization, l'ajoute à l'objet et renvoi l'Id
	 * @return string
	 * @throws Exception
	 */
	public function recupere_id_de_organization(
			$organization) {
		$this->setOrganizationId ( $this->recupere_instanceUid ( $organization ) );
		return $this->getOrganizationId ();
	}

	/**
	 * Recupere le nom du organization
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_organization(
			$organization) {
		return ( string ) $organization->name;
	}

	/**
	 * Permet de trouver la liste des organizations dans veeamspc et enregistre les donnees des organizations dans l'objet
	 * @return organizations
	 * @throws Exception
	 */
	public function retrouve_organizations(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$organizations = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_tenants = $this->listOrganizations ( $params );
			$this->onDebug ( $liste_res_tenants, 2 );
			foreach ( $liste_res_tenants->data as $tenant ) {
				$organizations [$this->recupere_id_de_organization ( $tenant )] = $tenant;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setOrganizationId ( "" )
			->setListeOrganizations ( $organizations );
	}

	/**
	 * Liste les organisations
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listOrganizations(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetVeeamWsclientRest ()
			->getMethod ( $this->organizations_list_uri (), $params );
		return $resultat;
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un organization id est rempli/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_organizationid(
			$error = true) {
		if (empty ( $this->getOrganizationId () )) {
			$this->onDebug ( $this->getOrganizationId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un organization id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	public function organizations_list_uri() {
		return '/organizations';
	}

	public function organization_id_uri() {
		if ($this->valide_organizationid () == false) {
			return $this->onError ( "Il n'y pas d'id d'organization selectionne" );
		}
		return $this->organizations_list_uri () . '/' . $this->getOrganizationId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getOrganizationId() {
		return $this->organization_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOrganizationId(
			$organization_id) {
		$this->organization_id = $organization_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeOrganizations() {
		return $this->liste_organizations;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeOrganizations(
			$liste_organizations) {
		$this->liste_organizations = $liste_organizations;
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
		$help [__CLASS__] ["text"] [] .= "organizations :";
		return $help;
	}
}
?>
