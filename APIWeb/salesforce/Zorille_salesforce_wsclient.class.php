<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\salesforce;

use stdClass;
use Zorille\framework as Core;
use Zorille\framework\options;
use \Zorille\salesforce as salesforce;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;

/**
 * class wsclient<br> Renvoi des informations via un webservice.
 * https://salesforce.readme.io/docs/core-api-concepts-requests
 * Endpoints : Activities ActivityFields ActivityTypes CallLogs Currencies Deals
 * DealFields Files Filters GlobalMessages Goals ItemSearch MailMessages MailThreads
 * Notes NoteFields OrganizationFields Organizations OrganizationRelationships PermissionSets
 * Persons PersonFields Pipelines Products ProductFields Recents
 * Roles SearchResults Stages Teams Users UserConnections UserSettings
 *
 * @package Lib
 * @subpackage salesforce
 */
class wsclient extends Core\wsclient {
	private ?salesforce\datas $datas = null;

	private array $defaultParams = [];

	private string $auth = '';

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
		options     &$liste_option, object &$datas = null,
		bool|string $sort_en_erreur = false, string $entete = __CLASS__
	): self {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new wsclient ( $sort_en_erreur, $entete );
		$objet->_initialise ( [
				"options" => $liste_option,
				"datas" => $datas
		] );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return wsclient|bool
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		if (! isset ( $liste_class ["datas"] )) {
			$error = $this->onError ( "il faut un objet de type datas" );
			return $error;
		}
		$this->setObjetsalesforceDatas ( $liste_class ["datas"] )
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
	public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
		// Gestion de wsclient
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au salesforce nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(string $nom): wsclient|bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_salesforce = $this->getObjetsalesforceDatas ()
			->valide_presence_data ( $nom );
		if ($liste_data_salesforce === false) {
			return $this->onError ( "Aucune definition de salesforce pour " . $nom );
		}
		if (! isset ( $liste_data_salesforce ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres salesforce" );
		}
		if (! isset ( $liste_data_salesforce ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres salesforce" );
		}
		if (! isset ( $liste_data_salesforce ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres salesforce" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_salesforce )
			->prepare_prepend_url ( $liste_data_salesforce ["url"] );
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->userLogin ( [
			'login' => $liste_data_salesforce ["username"],
			'password' => $liste_data_salesforce ["password"],
			'grant_type' => $liste_data_salesforce ["grant_type"]
		] );
		return $this;
	}

	/**
	 * Http salesforce params creator
	 *
	 * @return wsclient
	 */
	public function prepare_params(): wsclient {
		$this->onDebug ( __METHOD__, 1 );
		// if ( $this ->getAuth () ) {
		// $this ->setParams ( 'output_mode', 'xml', true );
		// }
		return $this;
	}

    /**
     * Convert return data to array
     *
     * @param $retour_wsclient
     * @return array|stdClass|string|int
     */
	public function prepare_retour($retour_wsclient): array|stdClass|string|int
	{
		$this->onDebug ( __METHOD__, 1 );
        if (!json_validate($retour_wsclient)) {
            return $retour_wsclient;
        }
		return $this->traite_retour_json ( $retour_wsclient, false );
	}

	/**
	 * Http O365 header creator
	 *
	 */
	public function prepare_html_entete(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		if (! empty ( $this->getAuth () )) {
			$this->setHttpHeader ( [
					"Content-Type: " . $this->getContentType (),
					"Authorization: Bearer " . $this->getAuth (),
					"Accept: " . $this->getAccept ()
			] );
		} else {
			$this->setHttpHeader ( [
				"Content-Type: application/x-www-form-urlencoded",
				"Accept: " . $this->getAccept ()
			] );
		}
		$this->onDebug ( $this->getHttpHeader (), 1 );
		return $this;
	}

	/**
	 * Valide le code retour dans une page HTML
	 * @param array|stdClass $retour_wsclient
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_retour(mixed $retour_wsclient): bool
	{
		$this->onDebug ( __METHOD__, 1 );
		// En cas de retour avec un code erreur
		if (isset ( $retour_wsclient->code )) {
			$this->onDebug ( "Code retour " . $retour_wsclient->code, 2 );
			$this->onDebug ( $retour_wsclient, 2 );
			return $this->onError ( $retour_wsclient->code . " : " . $retour_wsclient->message, "", $retour_wsclient->code );
		}
		// En cas de retour avec un code HTTP
		if (isset ( $retour_wsclient->httpStatusCode )) {
			$this->onDebug ( "Code retour " . $retour_wsclient->httpStatusCode, 2 );
			$this->onDebug ( $retour_wsclient, 0 );
			return $this->onError ( $retour_wsclient->messageCode . " : " . $retour_wsclient->messageCode . " DETAILS : " . $retour_wsclient->developerMessage, "", $retour_wsclient->httpStatusCode );
		}
		// En cas de retour
		if (isset ( $retour_wsclient->error )) {
			$this->onDebug ( "Retour en erreur :", 2 );
			$this->onDebug ( $retour_wsclient, 2 );
			$message = "";
			if (is_array ( $retour_wsclient->error )) {
				foreach ( $retour_wsclient->error as $titre => $valeur ) {
					$message .= $titre . " : " . $valeur [0] . ", ";
				}
			} else {
				$message = print_r ( $retour_wsclient->error, true );
			}
			return $this->onError ( $message, $retour_wsclient->error_description, 1 );
		}
		return true;
	}

	/**
	 * Sends are prepare_requete_json to the salesforce API and returns the response as object.
	 *
	 * @return array|string API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) &&
            ($this->getHttpMethod () == 'POST' || $this->getHttpMethod () == 'PUT' || $this->getHttpMethod () == 'DELETE') &&
            !str_contains($this->getUrl(), 'auth')) {
			$this->onInfo ( "DRY RUN :" . $this->getUrl () );
			$this->onInfo ( "DRY RUN :" . print_r ( $this->getParams (), true ) );
		} else {
			$retour_wsclient = $this->prepare_html_entete ()
				->envoi_requete ();
			$retour = $this->prepare_retour ( $retour_wsclient );
			$this->onDebug ( $retour, 2 );
			$this->valide_retour ( $retour );
			return $retour;
		}
		return "";
	}

	/**
	 * @param string $param
	 * @param boolean|integer|string $valeur
	 * @return wsclient
	 * @throws Exception
	 */
	public function modifie_default_param(string $param, bool|int|string $valeur): self {
		$default_params = $this->getDefaultParams ();
		$default_params [$param] = $valeur;
		return $this->setDefaultParams ( $default_params );
	}

	/**
	 * *********************** API salesforce **********************
	 */

	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	final public function userLogin(array $params = []): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$resultat = '';
		if (isset ( $params ['password'] )) {
			$this->onDebug ( $params, 1 );
			$this->setUrl ( "/oauth2/token" )
				->setHttpMethod ( "POST" )
				->setContentType('application/x-www-form-urlencoded')
				->setPostDatas(\http_build_query(array (
					'grant_type' => $params ['grant_type'],
					"client_id" => $params ['login'],
					"client_secret" => $params ['password'],
				)));
			$this->onDebug ( $this->getPostDatas (), 2 );
			$resultat = $this->prepare_requete();

            if (!empty($resultat->error)) {
                return $this->onError($resultat->error_description);
            }

			$this->onDebug ( $resultat, 1 );
			if (isset($resultat->access_token)) {
				$this->setAuth ( $resultat->access_token );
			}
			return $this;
		}
		return $this->onError ( "Erreur durant l'autentification", $resultat );
	}

    /**
     * @codeCoverageIgnore
     * @param string $resource Url Resource
     * @param array $params Data to send
     * @return array|int|string|stdClass
     * @throws Exception
     */
	public function getMethod(string $resource, array $params = []): array|int|string|stdClass
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
     * @param string $resource $resource Url Resource
     * @param array $params Data to send
     * @return array|int|string|stdClass
     * @throws Exception
     */
	public function postMethod(string $resource, array $params = []): array|int|string|stdClass
    {
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( $resource )
			->setHttpMethod ( "POST" )
			->setParams ( $this->getDefaultParams () )
			->setPostDatas ( json_encode ( $params ) );
		$this->onDebug ( $this->getPostDatas (), 2 );
		return $this->prepare_requete ();
	}

    /**
     * @codeCoverageIgnore
     * @param string $resource Url Resource
     * @param array $params Data to send
     * @return array|int|string|stdClass
     * @throws Exception
     */
	public function putMethod(string $resource, array $params = []): array|int|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( $resource )
			->setHttpMethod ( "PUT" )
			->setParams ( $this->getDefaultParams () )
			->setForceParamInUrl ( true )
			->setPostDatas ( json_encode ( $params ) );
		$this->onDebug ( json_encode ( $params ), 2 );
		return $this->prepare_requete ();
	}

    /**
     * @codeCoverageIgnore
     * @param string $resource Url Resource
     * @param array $params Data to send
     * @return array|int|string|stdClass
     * @throws Exception
     */
	public function patchMethod(string $resource, array $params = []): array|int|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( $resource )
			->setHttpMethod ( "PATCH" )
			->setParams ( $this->getDefaultParams () )
			->setForceParamInUrl ( true )
			->setPostDatas ( json_encode ( $params ) );
		return $this->prepare_requete ();
	}

    /**
     * @codeCoverageIgnore
     * @param string $resource Url Resource
     * @param array $params Data to send
     * @return array|int|string|stdClass
     * @throws Exception
     */
	public function deleteMethod(string $resource, array $params = []): array|int|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "DELETE" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * *********************** API salesforce **********************
	 */

	/**
	 * *********************** Accesseurs **********************
	 */

	/**
	 * @codeCoverageIgnore
	 * @return datas|null
	 */
	public function &getObjetsalesforceDatas(): ?datas
	{
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetsalesforceDatas(salesforce\datas &$datas): wsclient
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
	public function &setAuth($auth): wsclient
	{
		$this->auth = $auth;
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
	public function setDefaultParams(array $defaultParams): bool|static
	{
		if (is_array ( $defaultParams ))
			$this->defaultParams = $defaultParams;
		else
			return $this->onError ( 'The argument defaultParams on setDefaultParams() has to be an array.' );
		return $this;
	}

	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = [
			"salesforce Wsclient :",
			"\t--dry-run n'applique pas les changements"
		];
		return array_merge ( $help, datas::help () );
	}
}
