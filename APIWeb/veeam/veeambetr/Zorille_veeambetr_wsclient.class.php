<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\veeambetr;

use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;

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
	 * Prepare l'url de connexion au veeam nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
			$nom) {
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
	public function prepare_html_entete() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getAuth ()) {
			return $this->setHttpHeader ( array (
					"Content-Type: " . $this->getContentType (),
					"Accept: " . $this->getAccept (),
					"Authorization: Bearer " . $this->getAuth (),
					"x-api-version: 1.0-rev1",
					"Connection: close"
			) );
		}
		return $this->setHttpHeader ( array (
				"Content-Type: application/x-www-form-urlencoded",
				"Accept: " . $this->getAccept (),
				"x-api-version: 1.0-rev1",
				"Connection: close"
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
	 * @return \stdClass
	 */
	public function traite_retour_json(
			$retour_json,
			$return_array = false) {
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
	public function gere_erreur(
			$retour) {
		$this->onDebug ( __METHOD__, 1 );
		if (isset ( $retour->errorCode )) {
			return $this->onError ( "Requete en erreur : " . $retour->message, "Error Code : " . $retour->errorCode, 1 );
		}
		return $this;
	}

	/**
	 * Sends are prepare_requete_json to the veeam API and returns the response as object.
	 *
	 * @return string API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete() {
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
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	final public function userLogin(
			$params_login = array ()) {
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
			$params = array ()) {
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
			$params = array ()) {
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
			$params = array ()) {
				$this->onDebug ( __METHOD__, 1 );
				$resultat = $this->getMethod ( 'v1/serverTime', $params );
				return $resultat;
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
	public function BackupTaskSessionReferenceList(
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
	 * *********************** API veeam **********************
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
		$help [__CLASS__] ["text"] [] .= "veeam Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, datas::help () );
		return $help;
	}
}
?>
