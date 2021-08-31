<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\veeamspc;

use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;

/**
 * class wsclient<br> Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage veeamspc
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
	private $refresh_token = '';

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
			->setContentType ( 'application/x-www-form-urlencoded' )
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
	 * Prepare l'url de connexion au veeamspc nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
			$nom) {
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_veeamspc = $this->getObjetveeamDatas ()
			->valide_presence_data ( $nom );
		if ($liste_data_veeamspc === false) {
			return $this->onError ( "Aucune definition de veeamspc pour " . $nom );
		}
		if (! isset ( $liste_data_veeamspc ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres veeamspc" );
		}
		if (! isset ( $liste_data_veeamspc ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres veeamspc" );
		}
		if (! isset ( $liste_data_veeamspc ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres veeamspc" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_veeamspc )
			->prepare_prepend_url ( $liste_data_veeamspc ["url"] );
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->userLogin ( array (
				'grant_type' => 'password',
				'username' => $liste_data_veeamspc ["username"],
				'password' => $liste_data_veeamspc ["password"]
		) );
		return $this;
	}

	/**
	 * Http Veeam header creator
	 *
	 * @return string Http Header
	 */
	public function prepare_html_entete() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getAuth ()) {
			return $this->setHttpHeader ( array (
					"Content-Type: " . $this->getContentType (),
					"Authorization: Bearer " . $this->getAuth (),
					"Accept: " . $this->getAccept ()
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
		if ($tableau_resultat == null) {
			return $this->onError ( "Message dans un format inconnu", $retour_json, 1 );
		} else if (isset ( $tableau_resultat->status ) && $tableau_resultat->status == 0) {
			return $this->onError ( "Erreur dans le message de retour", $tableau_resultat, 1 );
		}
		return $tableau_resultat;
	}

	/**
	 * Sends are prepare_requete_json to the veeamspc API and returns the response as object.
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
			$retour = $this->traite_retour_json ( $retour_wsclient, false );
			$this->onDebug ( $retour, 2 );
			return $retour;
		}
		return "";
	}

	/**
	 * *********************** API veeamspc **********************
	 */
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function userLogin(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		/* username string Nullable password string <password> rememberMe boolean Nullable Default: false asCurrentUser boolean Nullable Default: false grant_type string Nullable Default: "password" refresh_token string Nullable */
		$resultat = $this->postMethod ( 'token', $params );
		if (isset ( $resultat->access_token )) {
			return $this->setAuth ( $resultat->access_token )
				->setRefreshToken ( $resultat->refresh_token );
		}
		/* { "access_token": "eyJhJGciOiJIUzI1NiIsImtpZCI6IldlYkFwaVNlY3VyaXR5S2V5IiwidHlwIjoiSldUIn0.eyJ1bmlxdWVfbmFtZSI6Ik5cXEFkbWluaXN0cmF0b3IiLCJyb2xlIjoiQWRtaW4iLCJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9zaWQiOiJTLTEtNS0yMS05MDkwMzQ2MzItMjUzODE0NDM1NS0zNjYwNTU1NjE5LTUwMCIsIkFjY2Vzc1Rva2VuSWQiOiI4MGJhNWI2ZS1hMzAyLTRlMTItYTM4NC02M2M5MjlmY2I0YWEiLCJVc2VySWQiOiIyIiwibmJmIjoxNjEwNjU3OTUyLCJleHAiOjE2MTA2NTg4NTIsImlhdCI6MTYxMDY1Nzk1Mn0.vGt0JwmN7zNCsLS-JeDKoZzkEyP6hVDLoRs5sFtL4Ko", "refresh_token": "d65703e0ef294425badecd9b6b5ac963", "token_type": "Bearer", "expires_in": 899, "user": "onesrv\\administrator", "user_role": "Unknown" } */
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
		$this->postMethod ( '/v2/accounts/logout', array () );
		return $this;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listSessions(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v1/sessions', $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getSession(
			$sessionid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v1/sessions/' . $sessionid, $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getSessionDetails(
			$sessionid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v1/sessions/' . $sessionid . "/details", $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupServers(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v2/backupServers', $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackupjobs(
			$agentid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v2/backupAgents/' . $agentid . '/jobs', $params );
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
		$resultat = $this->getMethod ( 'v2/jobs', $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function Job(
			$jobid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v2/jobs/' . $jobid, $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listPolicies(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v2/backupPolicies', $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listTenants(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v2/tenants', $params );
		return $resultat;
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listTenantBackupResources(
			$tenantid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getMethod ( 'v2/tenants/' . $tenantid . '/backupResources', $params );
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
	 * *********************** API veeamspc **********************
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
	public function getRefreshToken() {
		return $this->refresh_token;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRefreshToken(
			$refresh_token) {
		$this->refresh_token = $refresh_token;
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
		$help [__CLASS__] ["text"] [] .= "veeamspc Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, datas::help () );
		return $help;
	}
}
?>
