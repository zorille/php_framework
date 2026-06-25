<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use SimpleXMLElement;
use stdClass;
use Zorille\framework as Core;
use Exception as Exception;
use Zorille\itop\Organization;

/**
 * class backupServers
 *
 * @package Lib
 * @subpackage veeamspc
 */
class backupServers extends infrastructures {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $backupServer_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var SimpleXMLElement
	 */
	private $liste_backupServers = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * InstanbackupServerse un objet de type backupServers. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return backupServers
	 */
	static function &creer_veeamspc_backupServers(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): backupServers
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new backupServers ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return backupServers
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		// Gestion de backupServers
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du backupServer, l'ajoute à l'objet et renvoi l'Id
	 * @param $backupServer
	 * @return string
	 */
	public function recupere_id_de_backupServer(
			$backupServer): string
	{
		$this->setBackupServerId ( $backupServer->backupServerUid );
		return ( string ) $backupServer->backupServerUid;
	}

	/**
	 * Recupere le nom du backupServer
	 * @param $backupServer
	 * @return string
	 */
	public function recupere_nom_de_backupServer(
			$backupServer): string
	{
		return ( string ) $backupServer->name;
	}

	/**
	 * Permet de trouver la liste des backupServers dans veeamspc et enregistre les donnees des backupServers dans l'objet
	 * @param array $params
	 * @return backupServers
	 * @throws Exception
	 */
	public function retrouve_backupServers(
		array $params = array ()): backupServers
	{
		$this->onDebug ( __METHOD__, 1 );
		$backupServers = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_tenants = $this->listBackupServers ( $params );
			$this->onDebug ( $liste_res_tenants, 2 );
			foreach ( $liste_res_tenants->data as $tenant ) {
				$backupServers [$this->recupere_instanceUid ( $tenant )] = $tenant;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setBackupServerId ( "" )
			->setListeBackupServers ( $backupServers );
	}

	/**
	 * Liste les organisations
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupServers(
		array $params = array ()): SimpleXMLElement|bool|array|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjetVeeamWsclientRest ()
			->getMethod ( $this->backupServers_list_uri (), $params );
	}

	/**
	 * Liste les toutes les ressources utilisees
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function retrouveAllBackupServersRepositories(
		array $params = array ()): backupServers
	{
		$this->onDebug ( __METHOD__, 1 );
		$backupResourcesUsage = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_tenants = $this->getObjetVeeamWsclientRest ()
				->getMethod ( $this->backupServers_repositories_list_uri (), array_merge($params, ['expand' => 'BackupRepositoryInfo']) );
			$this->onDebug ( $liste_res_tenants, 2 );
			foreach ( $liste_res_tenants->data as $tenant ) {
				$backupResourcesUsage [$this->recupere_instanceUid ( $tenant )] = $tenant;
			}
		}
		$this->onDebug ( $backupResourcesUsage, 2 );
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setBackupServerId ( "" )
			->setListeBackupServers ( $backupResourcesUsage );
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un backupServer id est rempli/existe
	 * @param bool $error
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_backupServerid(
		bool $error = true): bool
	{
		if (empty ( $this->getBackupServerId () )) {
			$this->onDebug ( $this->getBackupServerId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un backupServer id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	/**
	 * @throws Exception
	 */
	public function backupServers_list_uri(): string
	{
		if ($this->valide_infrastructureid ( false )) {
			return $this->infrastructure_id_uri () . '/backupServers';
		}
		return $this->infrastructures_list_uri () . '/backupServers';
	}

	/**
	 * @throws Exception
	 */
	public function backupServers_repositories_list_uri(): string
	{
		return $this->backupServers_list_uri () . "/repositories";
	}

	/**
	 * @throws Exception
	 */
	public function backupServer_id_uri(): bool|string
	{
		if (!$this->valide_backupServerid()) {
			return $this->onError ( "Il n'y pas d'id de backupServer selectionne" );
		}
		return $this->infrastructure_id_uri () . "/backupServer/" . $this->getBackupServerId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getBackupServerId(): ?string
	{
		return $this->backupServer_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBackupServerId(
			$backupServer_id): static
	{
		$this->backupServer_id = $backupServer_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeBackupServers(): SimpleXMLElement|null|array
	{
		return $this->liste_backupServers;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeBackupServers(
			$liste_backupServers): static
	{
		$this->liste_backupServers = $liste_backupServers;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "backupServers :";
		return $help;
	}
}
