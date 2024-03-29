<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\coservit;

use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;

/**
 * class wsclient<br> Renvoi des informations via un webservice. https://coservit.readme.io/docs/core-api-concepts-requests Endpoints : Activities ActivityFields ActivityTypes CallLogs Currencies Deals DealFields Files Filters GlobalMessages Goals ItemSearch MailMessages MailThreads Notes NoteFields OrganizationFields Organizations OrganizationRelationships PermissionSets Persons PersonFields Pipelines Products ProductFields Recents Roles SearchResults Stages Teams Users UserConnections UserSettings
 *
 * @package Lib
 * @subpackage coservit
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
			&$datas = null,
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
		$this->setObjetcoservitDatas ( $liste_class ["datas"] )
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
	 * Prepare l'url de connexion au coservit nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
			$nom) {
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_coservit = $this->getObjetcoservitDatas ()
			->valide_presence_data ( $nom );
		if ($liste_data_coservit === false) {
			return $this->onError ( "Aucune definition de coservit pour " . $nom );
		}
		if (! isset ( $liste_data_coservit ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres coservit" );
		}
		if (! isset ( $liste_data_coservit ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres coservit" );
		}
		if (! isset ( $liste_data_coservit ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres coservit" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_coservit )
			->prepare_prepend_url ( $liste_data_coservit ["url"] );
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->userLogin ( array (
				'login' => $liste_data_coservit ["username"],
				'password' => $liste_data_coservit ["password"]
		) );
		return $this;
	}

	/**
	 * Http coservit params creator
	 *
	 * @return wsclient
	 */
	public function prepare_params() {
		$this->onDebug ( __METHOD__, 1 );
		// if ( $this ->getAuth () ) {
		// $this ->setParams ( 'output_mode', 'xml', true );
		// }
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
		return $this->traite_retour_json ( $retour_wsclient, false );
	}

	/**
	 * Http O365 header creator
	 *
	 * @return $this
	 */
	public function prepare_html_entete() {
		$this->onDebug ( __METHOD__, 1 );
		if (! empty ( $this->getAuth () )) {
			$this->setHttpHeader ( array (
					"Content-Type: " . $this->getContentType (),
					"Authorization: Bearer " . $this->getAuth (),
					"Accept: " . $this->getAccept ()
			) );
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
			$retour_wsclient) {
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
			//return $this->onError ( $retour_wsclient->messageCode . " : " . $retour_wsclient->messageCode . " DETAILS : " . $retour_wsclient->developerMessage, "", $retour_wsclient->httpStatusCode );
		}
		// En cas de retour
		if (isset ( $retour_wsclient->errors )) {
			$this->onDebug ( "Retour en erreur :", 2 );
			$this->onDebug ( $retour_wsclient, 2 );
			$message = "";
			if (is_array ( $retour_wsclient->errors )) {
				foreach ( $retour_wsclient->errors as $titre => $valeur ) {
					$message .= $titre . " : " . $valeur [0] . ", ";
				}
			} else {
				$message = print_r ( $retour_wsclient->errors, true );
			}
			return $this->onError ( $message, $retour_wsclient, 1 );
		}
		return true;
	}

	/**
	 * Sends are prepare_requete_json to the coservit API and returns the response as object.
	 *
	 * @return string API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) && ($this->getHttpMethod () == 'POST' || $this->getHttpMethod () == 'PUT' || $this->getHttpMethod () == 'DELETE')) {
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
	 * @param string|integer|boolean $valeur
	 * @return wsclient
	 */
	public function modifie_default_param(
			$param,
			$valeur) {
		$default_params = $this->getDefaultParams ();
		$default_params [$param] = $valeur;
		return $this->setDefaultParams ( $default_params );
	}

	/**
	 * *********************** API coservit **********************
	 */
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	final public function userLogin(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if (isset ( $params ['password'] )) {
			$this->onDebug ( $params, 1 );
			$resultat = $this->postMethod ( "/servicenav/auth/token", array (
					"username" => $params ['login'],
					"password" => $params ['password']
			) );
			$this->onDebug ( $resultat, 1 );
			foreach ( $resultat as $titre => $valeur ) {
				if ($titre == "token") {
					$this->setAuth ( $valeur );
				}
			}
			return $this;
		}
		return $this->onError ( "Erreur durant l'autentification", $resultat );
	}

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
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function putMethod(
			$resource,
			$params = array ()) {
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
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function patchMethod(
			$resource,
			$params = array ()) {
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
	 * *********************** API coservit **********************
	 */
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas
	 */
	public function &getObjetcoservitDatas() {
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetcoservitDatas(
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
		$help [__CLASS__] ["text"] [] .= "coservit Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, datas::help () );
		return $help;
	}
}
?>
