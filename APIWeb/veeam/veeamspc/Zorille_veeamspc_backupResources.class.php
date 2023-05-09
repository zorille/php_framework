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
 * class backupResources
 *
 * @package Lib
 * @subpackage veeamspc
 */
class backupResources extends sites {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $backupResource_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_backupResources = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * backupResourcese un objet de type backupResources. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return backupResources
	 */
	static function &creer_veeamspc_backupResources(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new backupResources ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return backupResources
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
		// Gestion de backupResources
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du backupResource, l'ajoute à l'objet et renvoi l'Id
	 * @return string
	 * @throws Exception
	 */
	public function recupere_id_de_backupResource(
			$backupResource) {
		$this->setBackupResourceId ( $backupResource->backupResourceUid );
		return $backupResource->backupResourceUid;
	}

	/**
	 * Recupere le nom du backupResource
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_de_backupResource(
			$backupResource) {
		return ( string ) $backupResource->name;
	}

	/**
	 * Permet de trouver la liste des backupResources dans veeamspc et enregistre les donnees des backupResources dans l'objet
	 * @return backupResources
	 * @throws Exception
	 */
	public function retrouve_backupResources(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$backupResources = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_tenants = $this->listBackupResources ( $params );
			$this->onDebug ( $liste_res_tenants, 2 );
			foreach ( $liste_res_tenants->data as $tenant ) {
				$backupResources [$this->recupere_id_de_backupResource ( $tenant )] = $tenant;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setBackupResourceId ( "" )
			->setListeBackupResources ( $backupResources );
	}

	/**
	 * Liste les organisations
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupResources(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetVeeamWsclientRest ()
			->getMethod ( $this->backupResourceUsages_site_id_uri (), $params );
		return $resultat;
	}

	/**
	 * Liste les toutes les ressources utilisees
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listAllBackupResourcesUsage(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$backupResourcesUsage = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_tenants = $this->getObjetVeeamWsclientRest ()
				->getMethod ( $this->backupResourcesUsages_all_list_uri (), $params );
			$this->onDebug ( $liste_res_tenants, 2 );
			foreach ( $liste_res_tenants->data as $tenant ) {
				$backupResourcesUsage [$this->recupere_id_de_backupResource ( $tenant )] = $tenant;
			}
		}
		$this->onDebug ( $backupResourcesUsage, 2 );
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setBackupResourceId ( "" )
			->setListeBackupResources ( $backupResourcesUsage );
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un backupResource id est rempli/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_backupResourceid(
			$error = true) {
		if (empty ( $this->getBackupResourceId () )) {
			$this->onDebug ( $this->getBackupResourceId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un backupResource id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	public function backupResources_list_uri() {
		return $this->sites_list_uri () . '/backupResources';
	}

	public function backupResource_id_uri() {
		if ($this->valide_backupResourceid () == false) {
			return $this->onError ( "Il n'y pas d'id de backupResource selectionne" );
		}
		return $this->site_id_uri () . "/backupResource/" . $this->getBackupResourceId ();
	}

	public function backupResourcesUsages_all_list_uri() {
		return $this->backupResources_list_uri () . '/usage';
	}

	public function backupResourceUsages_site_id_uri() {
		if($this->valide_siteid(false)){
			return $this->site_id_uri () . "/backupResource/usage";
		}
		return $this->backupResourcesUsages_all_list_uri ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getBackupResourceId() {
		return $this->backupResource_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBackupResourceId(
			$backupResource_id) {
		$this->backupResource_id = $backupResource_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeBackupResources() {
		return $this->liste_backupResources;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeBackupResources(
			$liste_backupResources) {
		$this->liste_backupResources = $liste_backupResources;
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
		$help [__CLASS__] ["text"] [] .= "backupResources :";
		return $help;
	}
}
?>
