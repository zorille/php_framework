<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\veeamone;

use stdClass;
use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;
use Zorille\framework\gestion_connexion_url;
use Zorille\framework\options;

/**
 * class wsclient<br> Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage veeamone
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
	 * Prepare l'url de connexion au veeamone nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): bool|wsclient|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_veeamone = $this->getObjetveeamDatas ()
			->valide_presence_data ( $nom );
		if ($liste_data_veeamone === false) {
			return $this->onError ( "Aucune definition de veeamone pour " . $nom );
		}
		if (! isset ( $liste_data_veeamone ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres veeamone" );
		}
		if (! isset ( $liste_data_veeamone ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres veeamone" );
		}
		if (! isset ( $liste_data_veeamone ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres veeamone" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_veeamone )
			->prepare_prepend_url ( $liste_data_veeamone ["url"] );
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->userLogin ( array (
				'grant_type' => 'password',
				'username' => $liste_data_veeamone ["username"],
				'password' => $liste_data_veeamone ["password"]
		) );
		return $this;
	}

	/**
	 * Http Veeam header creator
	 *
	 * @return wsclient Http Header
	 */
	public function prepare_html_entete(): static
	{
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
	public function prepare_params(): static
	{
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
		mixed $retour_wsclient): bool
	{
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
	 * @return mixed
	 * @throws Exception
	 */
	public function traite_retour_json(
		string $retour_json,
		bool   $return_array = true): mixed
	{
		$this->onDebug ( __METHOD__, 1 );
		$tableau_resultat = json_decode ( $retour_json, $return_array );
		if ($tableau_resultat == null) {
			return $this->onError ( "Message dans un format inconnu", $retour_json, 1 );
		} else if (isset ( $tableau_resultat ['status'] ) && $tableau_resultat ['status'] == 0) {
			return $this->onError ( "Erreur dans le message de retour", $retour_json, 1 );
		}
		return $tableau_resultat;
	}

	/**
	 * Sends are prepare_requete_json to the veeamone API and returns the response as object.
	 *
	 * @return bool|array|string API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete(): bool|array|string
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
			return $retour;
		}
		return "";
	}

	/**
	 * *********************** API veeamone **********************
	 */
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function userLogin(
		array $params = array ()): bool|wsclient
	{
		$this->onDebug ( __METHOD__, 1 );
		/* username string Nullable password string <password> rememberMe boolean Nullable Default: false asCurrentUser boolean Nullable Default: false grant_type string Nullable Default: "password" refresh_token string Nullable */
		$resultat = $this->postMethod ( 'token', $params );
		if (isset ( $resultat ['access_token'] )) {
			return $this->setAuth ( $resultat ['access_token'] )
				->setRefreshToken ( $resultat ['refresh_token'] );
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
		array $params = array ()): static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->postMethod ( 'revoke' , array () );
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
		array $params = array ()): SimpleXMLElement|bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'v1/sessions', $params );
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
		array $params = array ()): SimpleXMLElement|bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'v1/sessions/' . $sessionid, $params );
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
		array $params = array ()): SimpleXMLElement|bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'v1/sessions/' . $sessionid . "/details", $params );
	}

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listBackups(
		array $params = array ()): SimpleXMLElement|bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getMethod ( 'backupFiles', $params );
	}

	/**
	 * ************************************* Standard Request ************************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return array|bool|string
	 * @throws Exception
	 */
	public function getMethod(
		string $resource,
		array  $params = array ()): bool|array|string
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
	 * @return array|bool|string
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
	 * @return array|bool|string
	 * @throws Exception
	 */
	public function deleteMethod(
		string $resource,
		array  $params = array ()): bool|array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "DELETE" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * *********************** API veeamone **********************
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
	public function getRefreshToken(): string
	{
		return $this->refresh_token;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRefreshToken(
			$refresh_token): static
	{
		$this->refresh_token = $refresh_token;
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
		$help [__CLASS__] ["text"] [] .= "veeamone Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		return array_merge ( $help, datas::help () );
	}
}
