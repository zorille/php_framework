<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\veeamman;

use stdClass;
use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;
use Zorille\framework\gestion_connexion_url;
use Zorille\framework\options;

/**
 * class wsclient<br> Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage veeamman
 */
class wsclient extends Core\wsclient {
	/**
	 * var privee
	 * @access private
	 * @var datas
	 */
	private $datas = null;
	/**
	 * var privee
	 * @access private
	 * @var array.
	 */
	private $defaultParams = array ();
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $auth = '';
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $session = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param object|null &$datas Reference sur un objet datas
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return wsclient
	 * @throws Exception
	 */
	static function &creer_wsclient(
		options     &$liste_option,
		object      &$datas = NULL,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): Core\wsclient
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new wsclient ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"datas" => $datas
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return wsclient|bool
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		if (! isset ( $liste_class ["datas"] )) {
			$r = $this->onError ( "il faut un objet de type datas" );
			return $r;
		}
		$this->setObjetveeamDatas ( $liste_class ["datas"] )
			->setContentType ( 'application/xml' )
			->setAccept ( 'application/xml' );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		// Gestion de wsclient
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au veeamman nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): bool|wsclient|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_veeamman = $this->getObjetveeamDatas ()
			->valide_presence_data ( $nom );
		if ($liste_data_veeamman === false) {
			return $this->onError ( "Aucune definition de veeamman pour " . $nom );
		}
		if (! isset ( $liste_data_veeamman ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres veeamman" );
		}
		if (! isset ( $liste_data_veeamman ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres veeamman" );
		}
		if (! isset ( $liste_data_veeamman ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres veeamman" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_veeamman )
			->prepare_prepend_url ( $liste_data_veeamman ["url"] );
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->userLogin ( array (
				'username' => $liste_data_veeamman ["username"],
				'password' => $liste_data_veeamman ["password"]
		) );
		return $this;
	}

	/**
	 * Http Veeam header creator
	 *
	 * @param array $params
	 * @return wsclient Http Header
	 */
	public function prepare_html_entete(
		array $params = array ()): static
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getAuth ()) {
			return $this->setHttpHeader ( array (
					"Content-Type: " . $this->getContentType (),
					"X-RestSvcSessionId: " . $this->getAuth (),
					"Accept: " . $this->getAccept ()
			) );
		} else if (! empty ( $params )) {
			return $this->setHttpHeader ( array (
					"Content-Type: application/xml",
					"Authorization: Basic " . base64_encode ( $params ['username'] . ":" . $params ['password'] ),
					"Accept: application/xml"
			) );
		}
		return $this->setHttpHeader ( array (
				"Content-Type: " . $this->getContentType (),
				"Accept: " . $this->getAccept ()
		) );
	}

	/**
	 * Http Veeam params creator
	 *
	 * @return wsclient
	 */
	public function prepare_params(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * Convert return data to array
	 *
	 * @param $retour_wsclient
	 * @return false|SimpleXMLElement
	 */
	public function prepare_retour(
			$retour_wsclient): false|SimpleXMLElement
	{
		$this->onDebug ( __METHOD__, 1 );
		return simplexml_load_string ( $retour_wsclient );
	}

	/**
	 * Nettoie le retour JSon contenant {"message":"","success":true,"ressource":0}
	 * @param string $retour_json
	 * @param boolean $return_array
	 * @return mixed
	 */
	public function traite_retour_json(
		string $retour_json,
		bool   $return_array = true): mixed
	{
		$this->onDebug ( __METHOD__, 1 );
		return json_decode ( $retour_json, $return_array );
	}

	/**
	 * Valide le code retour dans une page HTML
	 * @param string $retour_wsclient
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_retour(
		mixed $retour_wsclient): bool
	{
		$this->onDebug ( __METHOD__, 1 );
		if (preg_match ( '/HTTP Error (.*)</', $retour_wsclient, $retour ) === 1) {
			return $this->onError ( "Requete en erreur : " . $retour [1], $retour_wsclient, 1 );
		}
		return true;
	}

	/**
	 * Valide le code retour dans une page HTML
	 * @param SimpleXMLElement $simplexmlobject
	 * @return boolean|self
	 * @throws Exception
	 */
	public function gere_erreur_simplexml(
		SimpleXMLElement &$simplexmlobject): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($simplexmlobject instanceof SimpleXMLElement) {
			$attributes = $simplexmlobject->attributes ();
			if (isset ( $attributes ['StatusCode'] ) && ( int ) $attributes ['StatusCode'] != 200) {
				return $this->onError ( "Requete en erreur : " . ( string ) $attributes ['Message'], "", ( int ) $attributes ['StatusCode'] );
			}
		}
		return $this;
	}

	/**
	 * Sends are prepare_requete_json to the veeamman API and returns the response as object.
	 *
	 * @return string API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete(
			$params_login = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) && ($this->getHttpMethod () == 'POST' || $this->getHttpMethod () == 'DELETE')) {
			$this->onInfo ( "DRY RUN :" . $this->getUrl () );
			$this->onInfo ( "DRY RUN :" . print_r ( $this->getParams (), true ) );
		} else {
			$retour_wsclient = $this->prepare_html_entete ( $params_login )
				->envoi_requete ();
			$this->valide_retour ( $retour_wsclient );
			$retour = $this->prepare_retour ( $retour_wsclient );
			$this->onDebug ( $retour, 2 );
			$this->gere_erreur_simplexml ( $retour );
			return $retour;
		}
		return "";
	}

	/**
	 * *********************** API veeamman **********************
	 */
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params_login
	 * @return bool|wsclient
	 * @throws Exception
	 */
	final public function userLogin(
		array $params_login = array ()): bool|wsclient
	{
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->setUrl ( 'sessionMngr/?v=latest' )
			->setHttpMethod ( "POST" )
			->setCollectHeader ( true )
			->prepare_requete ( $params_login );
		if (preg_match ( '/X-RestSvcSessionId: (.*)/', $this->getHeaderData (), $retour ) === 1) {
			if (! empty ( $resultat->SessionId )) {
				return $this->setAuth ( trim ( $retour [1] ) )
					->setSession ( ( string ) $resultat->SessionId );
			}
		} elseif (preg_match ( '/x-restsvcsessionid: (.*)/', $this->getHeaderData (), $retour ) === 1) {
			if (! empty ( $resultat->SessionId )) {
				return $this->setAuth ( trim ( $retour [1] ) )
					->setSession ( ( string ) $resultat->SessionId );
			}
		}
		return $this->onError ( "Erreur durant l'autentification", $resultat );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	final public function userLogout(
		array $params = array ()): static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->deleteMethod ( 'logonSessions/' . $this->getSession (), array () );
		return $this;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listJobs(
		array $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'jobs', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function Job(
		$jobid,
		array $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'jobs/' . $jobid, $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listeJobIncludes(
		$jobid,
		array $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'jobs/' . $jobid . '/includes', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function JobInclude(
		$jobid,
		$ObjectInJobId,
		array $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'jobs/' . $jobid . '/includes/' . $ObjectInJobId, $params );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param string $jobid numero du job
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupSessionsParJob(
		string $jobid,
		array  $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'jobs/' . $jobid . '/backupSessions', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupSessions(
		array $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupSessions', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupSessions(
		$BackupSessionsId,
		array $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupSessions/' . $BackupSessionsId, $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param $backupSessionsid
	 * @param array $params Request Parameters
	 * @return bool|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function listBackupTaskSessionParBackup(
		$backupSessionsid,
		array $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupSessions/' . $backupSessionsid . '/taskSessions', $params );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupTasksSessions(
		array $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupTaskSessions', $params );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param string $backupTaskSessionsid numero de la task
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupTaskSession(
		string $backupTaskSessionsid,
		array  $params = array ()): SimpleXMLElement|bool|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupTaskSessions/' . $backupTaskSessionsid, $params );
	}

	/**
	 * List of Backups servers connected
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listbackups(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backups', $params );
	}

	/**
	 * Data of each backups servers connected
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupData(
		$backupId,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backups/' . $backupId, $params );
	}

	/**
	 * Data of each backups servers connected
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupFilesbybackup(
		$backupId,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backups/' . $backupId . "/backupFiles", $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listbackupServers(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupServers', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupServerData(
		$backupServerId,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupServers/' . $backupServerId, $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupServerListJobs(
		$backupServerId,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupServers/' . $backupServerId . "/jobs", $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupFiles(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupFiles', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupFileData(
		$backupfileuid,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupFiles/' . $backupfileuid, $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reports(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'reports/summary', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsOverview(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'reports/summary/overview', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsVmsOverview(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'reports/summary/vms_overview', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsJobStatistics(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'reports/summary/job_statistics', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsProcessedVms(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'reports/summary/processed_vms', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsRepository(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'reports/summary/repository', $params );
	}

	/**
	 * List of Backups servers connected
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listReplicaSessions(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'replicaSessions', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function ReplicaSessions(
		$ReplicaSessionsId,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'replicaSessions/' . $ReplicaSessionsId, $params );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listReplicaTasksSessions(
		$replicaSessionId,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'replicaSessions/' . $replicaSessionId . '/replicaTaskSessions', $params );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param $replicaTaskSessionsid
	 * @param array $params Request Parameters
	 * @return bool|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function ReplicaTaskSession(
		$replicaTaskSessionsid,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'replicaTaskSessions/' . $replicaTaskSessionsid, $params );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listCatalogVms(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'catalog/vms', $params );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param $vmname
	 * @param array $params Request Parameters
	 * @return bool|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function CatalogVm(
		$vmname,
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'catalog/vms/' . $vmname, $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function querySvc(): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'querySvc', array () );
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function query(
		array $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (! isset ( $params ['type'] )) {
			return $this->onError ( "La query doit contenir un type au minimum" );
		}
		return $this->getMethod ( 'query', $params );
	}

	/**
	 * ************************************* Standard Request ************************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement|bool|string
	 * @throws Exception
	 */
	public function getMethod(
		string $resource,
		array  $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "GET" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement|bool|string
	 * @throws Exception
	 */
	public function postMethod(
		string $resource,
		array  $params = array ()): SimpleXMLElement|bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "POST" )
			->setPostDatas ( http_build_query ( $full_params ) );
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement|bool|string
	 * @throws Exception
	 */
	public function deleteMethod(
		string $resource,
		array  $params = array ()): SimpleXMLElement|bool|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "DELETE" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * *********************** API veeamman **********************
	 */
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas|null
	 */
	public function &getObjetveeamDatas(): ?datas
	{
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetveeamDatas(
			&$datas): static
	{
		$this->datas = $datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getAuth(): string
	{
		return $this->auth;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuth(
			$auth): static
	{
		$this->auth = $auth;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getSession(): string
	{
		return $this->session;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSession(
			$session): static
	{
		$this->session = $session;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Returns the default params.
	 *
	 * @retval  array   Array with default params.
	 */
	public function getDefaultParams(): array
	{
		return $this->defaultParams;
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Sets the default params.
	 *
	 * @param $defaultParams array with default params.
	 * @retrun wsclient
	 *
	 * @throws Exception
	 */
	public function setDefaultParams(
		array $defaultParams): bool|static
	{
		if (is_array ( $defaultParams ))
			$this->defaultParams = $defaultParams;
		else
			return $this->onError ( 'The argument defaultParams on setDefaultParams() has to be an array.' );
		return $this;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "veeamman Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, datas::help () );
		return $help;
	}
}
