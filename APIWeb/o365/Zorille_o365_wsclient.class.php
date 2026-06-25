<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\o365;

use stdClass;
use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;
use Zorille\framework\options;

/**
 * class wsclient<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage o365
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
	 * @var string.
	 */
	private $auth = '';
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $ajout_header = '';
	/**
	 * var privee
	 * @access private
	 * @var array.
	 */
	private $defaultParams = array ();
	/**
	 * var privee
	 * @access private
	 * @var boolean.
	 */
	private $connected = false;
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $type_retour = "json";

	private ?stdClass $datasFromRequest = null;
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param object|null $datas NULL
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return wsclient
	 * @throws Exception
	 */
	static function &creer_wsclient(
		options     &$liste_option,
		object      &$datas = null,
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
	 * Initialisation de l'objet @codeCoverageIgnore
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
		$this->setObjeto365datas ( $liste_class ["datas"] )
			->setContentType ( 'application/json' )
			->setAccept ( 'application/json' );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
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
	 * Prepare l'url de connexion au o365 nomme $nom
	 * @param string $nom
	 * @return wsclient|bool wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): wsclient|bool
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_o365 = $this->getObjeto365datas ()
			->valide_presence_data ( $nom, 'rest' );
		if ($liste_data_o365 === false) {
			return $this->onError ( "Aucune definition de o365 pour " . $nom );
		}
		if (! isset ( $liste_data_o365 ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres o365" );
		}
		if (! isset ( $liste_data_o365 ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres o365" );
		}
		if (! isset ( $liste_data_o365 ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres o365" );
		}
		$this->userLogin ( $liste_data_o365 );
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_o365 )
			->prepare_prepend_url ( $liste_data_o365 ["url"] );
		return $this->setConnected ( true );
	}

	/**
	 * Http O365 header creator
	 *
	 * @return wsclient Http Header
	 */
	public function prepare_html_entete(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getAuth ()) {
			$this->setHttpHeader ( array (
					"Content-Type: " . $this->getContentType (),
					"Authorization: Bearer " . $this->getAuth (),
					"Accept: " . $this->getAccept ()
			) );
		} else {
			$this->setHttpHeader ( array (
					"Accept: " . $this->getAccept ()
			) );
		}
		if (! empty ( $this->getAjoutHeader () )) {
			$header = $this->getHttpHeader ();
			$header [] .= $this->getAjoutHeader ();
			$this->setHttpHeader ( $header );
		}
		$this->onDebug ( $this->getHttpHeader (), 1 );
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
		if (isset ( $retour_wsclient->error )) {
			$this->onDebug ( "Error Detectee", 2 );
			$this->onDebug ( $retour_wsclient, 2 );
			if (is_object ( $retour_wsclient->error ) && isset ( $retour_wsclient->error->message )) {
				return $this->onError ( $retour_wsclient->error->message, $retour_wsclient->error->code, 1 );
			}
			return $this->onError ( $retour_wsclient->error, $retour_wsclient->error_description, $retour_wsclient->error_codes [0] );
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
		bool   $return_array = false): mixed
	{
		$this->onDebug ( __METHOD__, 1 );
		$tableau_resultat = json_decode ( $retour_json, $return_array );
		if ($tableau_resultat == null && ! empty ( $retour_json )) {
			return $this->onError ( "Message dans un format inconnu", $retour_json, 1 );
		}
		return $tableau_resultat;
	}

	public function concatDatasFromRequest(
		$resultats) {
	$old_resultats = $this->getDatasFromRequest ();
	if (isset ( $old_resultats->value )) {
		$final = array_merge ( $old_resultats->value, ($resultats->value ?? (array)$resultats) );
		$resultats->value = $final;
	}
	return $this->setDatasFromRequest ( $resultats );
}
	/**
	 * Sends are prepare_requete_json to the o365 API and returns the response as object.
	 *
	 * @return bool|array|string|stdClass|null API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete(): bool|array|string|stdClass|null
	{
        $this->setDatasFromRequest(null);
		$this->onDebug ( __METHOD__, 1 );
		$url = null;
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) && ($this->getHttpMethod () == 'POST' || $this->getHttpMethod () == 'DELETE')) {
			$this->onInfo ( "DRY RUN :" . $this->getUrl () );
			$this->onInfo ( "DRY RUN :" . print_r ( $this->getParams (), true ) );
		} else {
			do {
				$retour_wsclient = $this->prepare_html_entete ()
				->envoi_requete ($url);
				if ($this->getTypeRetour () == "json") {
					$retour = $this->traite_retour_json ( $retour_wsclient );

					$this->valide_retour ( $retour );
					// On set a datasFromRequest le nouveau retour en plus des anciens
					$this->concatDatasFromRequest($retour);

					if(isset($retour->{'@odata.nextLink'}))
						$url = $retour->{'@odata.nextLink'};
				} else {
					//En cas de retour MIME pour les mail par exemple
					$retour=$retour_wsclient;
				}

			} while(isset($retour->{'@odata.nextLink'}));
			$this->onDebug ( $retour, 2 );
			return $this->getDatasFromRequest();
		}
		return "";
	}

	/**
	 * *********************** API o365 **********************
	 */
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $liste_data_o365 Request Parameters
	 * @throws Exception
	 */
	public function userLogin(
		array $liste_data_o365 = array ()): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_o365 ['host'] = $liste_data_o365 ['login_host'];
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_o365 )
			->prepare_prepend_url ( '/' . $liste_data_o365 ['tenantid'] );
		$resultat = $this->postMethod ( '/oauth2/V2.0/token', array (
				'client_id' => $liste_data_o365 ['username'],
				'client_secret' => $liste_data_o365 ['password'],
				'scope' => $liste_data_o365 ['scope'],
				'grant_type' => $liste_data_o365 ['grant_type']
		) );
		if (isset ( $resultat->access_token )) {
			return $this->setAuth ( $resultat->access_token );
		}
		return $this->onError ( "Erreur durant l'autentification", $resultat );
	}

	/**
	 * *********************** API o365 **********************
	 */
	/**
	 * ************************************* Standard Request ************************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return bool|array|string|stdClass
	 * @throws Exception
	 */
	public function getMethod(
		string $resource,
		array  $params = array ()): bool|array|string|stdClass
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
	 * @return SimpleXMLElement|stdClass|bool|array|string
	 * @throws Exception
	 */
	public function jsonPatchMethod(
		string $resource,
		array  $params = array ()): SimpleXMLElement|stdClass|bool|array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "PATCH" )
			->setPostDatas ( json_encode ( $full_params ) );
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return bool|array|string|stdClass
	 * @throws Exception
	 */
	public function postMethod(
		string $resource,
		array  $params = array ()): bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_array ( $params )) {
			$full_params = array_merge ( $this->getDefaultParams (), $params );
			$this->setUrl ( $resource )
				->setHttpMethod ( "POST" )
				->setPostDatas ( http_build_query ( $full_params ) );
		} else {
			// En cas de plain/text
			$this->setUrl ( $resource )
				->setHttpMethod ( "POST" )
				->setPostDatas ( $params );
		}
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return bool|array|string|stdClass|null
	 * @throws Exception
	 */
	public function jsonPostMethod(
		string $resource,
		array  $params = array ()): bool|array|string|stdClass|null
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "POST" )
			->setPostDatas ( json_encode ( $full_params ) );
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return bool|array|string|stdClass
	 * @throws Exception
	 */
	public function putMethod(
		string $resource,
		array  $params = array ()): bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "PUT" )
			->setPostDatas ( http_build_query ( $full_params ) );
		return $this->prepare_requete ();
	}

	/**
	 * O365 put file content
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param $content
	 * @return bool|array|string|stdClass
	 * @throws Exception
	 */
	public function putContentMethod(
		string $resource,
		       $content): bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( $resource )
			->setHttpMethod ( "PUT" )
			->setPostDatas ( $content );
		return $this->prepare_requete ( 'text/plain' );
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return bool|array|string|stdClass
	 * @throws Exception
	 */
	public function deleteMethod(
		string $resource,
		array  $params = array ()): bool|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "DELETE" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas|null
	 */
	public function &getObjeto365datas(): ?datas
	{
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjeto365datas(
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
	public function getAjoutHeader(): string
	{
		return $this->ajout_header;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutHeader(
			$ajout_header): static
	{
		$this->ajout_header = $ajout_header;
		return $this;
	}

	/**
	 * @codeCoverageIgnore @brief Returns the default params.
	 * @retval array Array with default params.
	 */
	public function getDefaultParams(): array
	{
		return $this->defaultParams;
	}

	/**
	 * @codeCoverageIgnore @brief Sets the default params.
	 *
	 * @param $defaultParams array with default params. @retval o365ApiAbstract
	 * @throws Exception
	 */
	public function setDefaultParams(
		array $defaultParams): static
	{
		if (is_array ( $defaultParams ))
			$this->defaultParams = $defaultParams;
		else
			throw new Exception ( 'The argument defaultParams on setDefaultParams() has to be an array.' );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return bool
	 */
	public function getConnected(): bool
	{
		return $this->connected;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnected(
			$connected): static
	{
		$this->connected = $connected;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getTypeRetour(): string
	{
		return $this->type_retour;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTypeRetour(
			$type_retour): static
	{
		$this->type_retour = $type_retour;
		return $this;
	}

	/**
	 * Summary of setDatasFromRequest
	 * @param stdClass|null $datasFromRequest
	 */
	public function &setDatasFromRequest(stdClass|null $datasFromRequest): static
	{
		$this->datasFromRequest = $datasFromRequest;
		return $this;
	}

	/** 
	 * Summary of getDatasFromRequest
	 * @return stdClass|null
	*/
	public function getDatasFromRequest(): stdClass|null
	{
		return $this->datasFromRequest;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "o365 Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		return array_merge ( $help, datas::help () );
	}
}
