<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\veeamman;

use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;

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
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param datas &$datas Reference sur un objet datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return wsclient
	 */
	static function &creer_wsclient(
			&$liste_option,
			&$datas = NULL,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
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
	 * @return wsclient
	 * @throws Exception
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		if (! isset ( $liste_class ["datas"] )) {
			$this->onError ( "il faut un objet de type datas" );
			return false;
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
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
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
			$nom) {
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
	 * @return string Http Header
	 */
	public function prepare_html_entete(
			$params = array ()) {
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
	public function prepare_params() {
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * Convert return data to array
	 *
	 * @return array
	 * @throws Exception
	 */
	public function prepare_retour(
			$retour_wsclient) {
		$this->onDebug ( __METHOD__, 1 );
		return simplexml_load_string ( $retour_wsclient );
	}

	/**
	 * Nettoie le retour JSon contenant {"message":"","success":true,"ressource":0}
	 * @param string $retour_json
	 * @param boolean $return_array
	 * @return array
	 */
	public function traite_retour_json(
			$retour_json,
			$return_array = true) {
		$this->onDebug ( __METHOD__, 1 );
		$tableau_resultat = json_decode ( $retour_json, $return_array );
		return $tableau_resultat;
	}

	/**
	 * Valide le code retour dans une page HTML
	 * @param string $retour_wsclient
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_retour(
			$retour_wsclient) {
		$this->onDebug ( __METHOD__, 1 );
		if (preg_match ( '/HTTP Error (.*)</', $retour_wsclient, $retour ) === 1) {
			return $this->onError ( "Requete en erreur : " . $retour [1], $retour_wsclient, 1 );
		}
		return true;
	}

	/**
	 * Valide le code retour dans une page HTML
	 * @param \SimpleXMLElement $simplexmlobject
	 * @return boolean
	 * @throws Exception
	 */
	public function gere_erreur_simplexml(
			&$simplexmlobject) {
		$this->onDebug ( __METHOD__, 1 );
		if ($simplexmlobject instanceof \SimpleXMLElement) {
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
			$params_login = array ()) {
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
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	final public function userLogin(
			$params_login = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->setUrl ( 'sessionMngr/?v=latest' )
			->setHttpMethod ( "POST" )
			->setCollectHeader ( true )
			->prepare_requete ( $params_login );
		if (preg_match ( '/X-RestSvcSessionId: (.*)/', $this->getHeaderData (), $retour ) === 1) {
			if (isset ( $resultat->SessionId ) && ! empty ( $resultat->SessionId )) {
				return $this->setAuth ( trim ( $retour [1] ) )
					->setSession ( ( string ) $resultat->SessionId );
			}
		} elseif (preg_match ( '/x-restsvcsessionid: (.*)/', $this->getHeaderData (), $retour ) === 1) {
			if (isset ( $resultat->SessionId ) && ! empty ( $resultat->SessionId )) {
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
			$params = array ()) {
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
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'jobs', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function Job(
			$jobid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'jobs/' . $jobid, $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listeJobIncludes(
			$jobid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'jobs/' . $jobid . '/includes', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function JobInclude(
			$jobid,
			$ObjectInJobId,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'jobs/' . $jobid . '/includes/' . $ObjectInJobId, $params );
		return $resultat;
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
			$jobid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'jobs/' . $jobid . '/backupSessions', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupSessions(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupSessions', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupSessions(
			$BackupSessionsId,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupSessions/' . $BackupSessionsId, $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $jobid numero du job
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupTaskSessionParBackup(
			$backupSessionsid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupSessions/' . $backupSessionsid . '/taskSessions', $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupTasksSessions(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupTaskSessions', $params );
		return $resultat;
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
			$backupTaskSessionsid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupTaskSessions/' . $backupTaskSessionsid, $params );
		return $resultat;
	}

	/**
	 * List of Backups servers connected
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listbackups(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backups', $params );
		return $resultat;
	}

	/**
	 * Data of each backups servers connected
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupData(
			$backupId,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backups/' . $backupId, $params );
		return $resultat;
	}

	/**
	 * Data of each backups servers connected
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupFilesbybackup(
			$backupId,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backups/' . $backupId . "/backupFiles", $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listbackupServers(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupServers', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupServerData(
			$backupServerId,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupServers/' . $backupServerId, $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupServerListJobs(
			$backupServerId,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupServers/' . $backupServerId . "/jobs", $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupFiles(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupFiles', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function BackupFileData(
			$backupfileuid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'backupFiles/' . $backupfileuid, $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reports(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'reports/summary', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsOverview(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'reports/summary/overview', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsVmsOverview(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'reports/summary/vms_overview', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsJobStatistics(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'reports/summary/job_statistics', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsProcessedVms(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'reports/summary/processed_vms', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function reportsRepository(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'reports/summary/repository', $params );
		return $resultat;
	}

	/**
	 * List of Backups servers connected
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listReplicaSessions(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'replicaSessions', $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function ReplicaSessions(
			$ReplicaSessionsId,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'replicaSessions/' . $ReplicaSessionsId, $params );
		return $resultat;
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
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'replicaSessions/' . $replicaSessionId . '/replicaTaskSessions', $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param string $backupTaskSessionsid numero de la task
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function ReplicaTaskSession(
			$replicaTaskSessionsid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'replicaTaskSessions/' . $replicaTaskSessionsid, $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listCatalogVms(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'catalog/vms', $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param string $backupTaskSessionsid numero de la task
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function CatalogVm(
			$vmname,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'catalog/vms/' . $vmname, $params );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function querySvc() {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'querySvc', array () );
		return $resultat;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function query(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if (! isset ( $params ['type'] )) {
			return $this->onError ( "La query doit contenir un type au minimum" );
		}
		$resultat = $this->getMethod ( 'query', $params );
		return $resultat;
	}

	/**
	 * ************************************* Standard Request ************************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function getMethod(
			$resource,
			$params = array ()) {
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
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function postMethod(
			$resource,
			$params = array ()) {
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
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function deleteMethod(
			$resource,
			$params = array ()) {
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
	 * @return datas
	 */
	public function &getObjetveeamDatas() {
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetveeamDatas(
			&$datas) {
		$this->datas = $datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getAuth() {
		return $this->auth;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuth(
			$auth) {
		$this->auth = $auth;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getSession() {
		return $this->session;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSession(
			$session) {
		$this->session = $session;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Returns the default params.
	 *
	 * @retval  array   Array with default params.
	 */
	public function getDefaultParams() {
		return $this->defaultParams;
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Sets the default params.
	 *
	 * @param $defaultParams Array with default params.
	 * @retrun wsclient
	 *
	 * @throws Exception
	 */
	public function setDefaultParams(
			$defaultParams) {
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
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "veeamman Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, datas::help () );
		return $help;
	}
}
?>
