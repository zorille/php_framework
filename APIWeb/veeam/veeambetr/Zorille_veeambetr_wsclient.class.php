<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\veeambetr;

use stdClass;
use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;
use Zorille\framework\gestion_connexion_url;
use Zorille\framework\options;

/**
 * class wsclient<br> Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage veeam
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
			->setContentType ( 'application/json' )
			->setAccept ( 'application/json' );
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
	 * Prepare l'url de connexion au veeam nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): bool|wsclient|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_veeam = $this->getObjetveeamDatas ()
			->valide_presence_data ( $nom );
		if ($liste_data_veeam === false) {
			return $this->onError ( "Aucune definition de veeam pour " . $nom );
		}
		if (! isset ( $liste_data_veeam ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres veeam" );
		}
		if (! isset ( $liste_data_veeam ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres veeam" );
		}
		if (! isset ( $liste_data_veeam ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres veeam" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_veeam )
			->prepare_prepend_url ( $liste_data_veeam ["url"] );
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->userLogin ( array (
				'grant_type' => 'password',
				'username' => $liste_data_veeam ["username"],
				'password' => $liste_data_veeam ["password"]
		) );
		return $this;
	}

	/**
	 * Http Veeam header creator
	 *
	 * @return wsclient
	 */
	public function prepare_html_entete(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getAuth ()) {
			return $this->setHttpHeader ( array (
					"Content-Type: " . $this->getContentType (),
					"Accept: " . $this->getAccept (),
					"Authorization: Bearer " . $this->getAuth (),
					"x-api-version: 1.0-rev1"
			) );
		}
		return $this->setHttpHeader ( array (
				"Content-Type: application/x-www-form-urlencoded",
				"Accept: " . $this->getAccept (),
				"x-api-version: 1.0-rev1"
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
	 * @return array|stdClass|false|SimpleXMLElement
	 */
	public function prepare_retour(
			$retour_wsclient): array|stdClass|false|SimpleXMLElement
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
		bool   $return_array = false): mixed
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
	 * @param $retour
	 * @return wsclient
	 * @throws Exception
	 */
	public function gere_erreur(
			$retour): wsclient|bool
	{
		$this->onDebug ( __METHOD__, 1 );
		if (isset ( $retour->errorCode )) {
			return $this->onError ( "Requete en erreur : " . $retour->message, "Error Code : " . $retour->errorCode, 1 );
		}
		return $this;
	}

	/**
	 * Sends are prepare_requete_json to the veeam API and returns the response as object.
	 *
	 * @return array|string|null API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete(): array|string|null
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) && ($this->getHttpMethod () == 'POST' || $this->getHttpMethod () == 'DELETE')) {
			$this->onInfo ( "DRY RUN :" . $this->getUrl () );
			$this->onInfo ( "DRY RUN :" . print_r ( $this->getParams (), true ) );
		} else {
			$retour_wsclient = $this->prepare_html_entete ()
				->envoi_requete ();
			$this->valide_retour ( $retour_wsclient );
			$retour = $this->traite_retour_json ( $retour_wsclient );
			$this->onDebug ( $retour, 2 );
			$this->gere_erreur ( $retour );
			return $retour;
		}
		return NULL;
	}

	/**
	 * *********************** API veeam **********************
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
		$resultat = $this->setUrl ( 'oauth2/token' )
			->setHttpMethod ( "POST" )
			->setCollectHeader ( true )
			->setPostDatas ( http_build_query ( $params_login ) )
			->prepare_requete ();
		
			if (isset ( $resultat->token_type ) && $resultat->token_type=='bearer') {
				return $this->setAuth ( trim ( $resultat->access_token ) )
				->setSession ( $resultat->refresh_token );
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
		$this->postMethod ( 'oauth2/logout', array () );
		return $this;
	}
	
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	final public function authorizationCode(
		array $params = array ()): static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->postMethod ( 'oauth2/authorization_code', array () );
		return $this;
	}
	
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function ServerTime(
		array $params = array ()): SimpleXMLElement|array|string|null|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'v1/serverTime', $params );
	}
	
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function exportJobs(
		array $params = array ()): SimpleXMLElement|array|string|null|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'v1/automation/jobs/export', $params );
	}
	
	
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listJobs(
		array $params = array ()): SimpleXMLElement|array|string|null|stdClass
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
		array $params = array ()): SimpleXMLElement|array|string|null|stdClass
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
		array $params = array ()): SimpleXMLElement|array|string|null|stdClass
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
		array $params = array ()): SimpleXMLElement|array|string|null|stdClass
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
		array  $params = array ()): SimpleXMLElement|array|string|null|stdClass
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
		array $params = array ()): SimpleXMLElement|array|string|null|stdClass
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
		array $params = array ()): SimpleXMLElement|array|string|null|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupSessions/' . $BackupSessionsId, $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @param $backupSessionsid
	 * @param array $params Request Parameters
	 * @return array|SimpleXMLElement|string|null
	 * @throws Exception
	 */
	public function BackupTaskSessionReferenceList(
		$backupSessionsid,
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array  $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|array|string|null
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'reports/summary/repository', $params );
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function querySvc(): SimpleXMLElement|array|string|null
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
		array $params = array ()): SimpleXMLElement|bool|array|string|null
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
	 * @return array|string|null
	 * @throws Exception
	 */
	public function getMethod(
		string $resource,
		array  $params = array ()): array|string|null
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
	 * @return array|string|null
	 * @throws Exception
	 */
	public function postMethod(
		string $resource,
		array  $params = array ()): bool|array|string|stdClass
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
	 * @return array|string|null
	 * @throws Exception
	 */
	public function deleteMethod(
		string $resource,
		array  $params = array ()): array|string|null
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "DELETE" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * *********************** API veeam **********************
	 */
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas
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
		$help [__CLASS__] ["text"] [] .= "veeam Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		return array_merge ( $help, datas::help () );
	}
}
