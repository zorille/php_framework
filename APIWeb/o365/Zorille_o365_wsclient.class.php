<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;

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

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type wsclient.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param object $datas NULL
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
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
	 * Initialisation de l'objet @codeCoverageIgnore
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
	 * Prepare l'url de connexion au o365 nomme $nom
	 * @param string $nom
	 * @return boolean wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
			$nom) {
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
	 * @return string Http Header
	 */
	public function prepare_html_entete() {
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
			$retour_wsclient) {
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
	 * @return array
	 */
	public function traite_retour_json(
			$retour_json,
			$return_array = false) {
		$this->onDebug ( __METHOD__, 1 );
		$tableau_resultat = json_decode ( $retour_json, $return_array );
		if ($tableau_resultat == null && ! empty ( $retour_json )) {
			return $this->onError ( "Message dans un format inconnu", $retour_json, 1 );
		}
		return $tableau_resultat;
	}

	/**
	 * Sends are prepare_requete_json to the o365 API and returns the response as object.
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
			if ($this->getTypeRetour () == "json") {
				$retour = $this->traite_retour_json ( $retour_wsclient );
				$this->valide_retour ( $retour );
			} else {
				//En cas de retour MIME pour les mail par exemple
				$retour=$retour_wsclient;
			}
			$this->onDebug ( $retour, 2 );
			return $retour;
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
			$liste_data_o365 = array ()) {
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
	public function jsonPatchMethod(
			$resource,
			$params = array ()) {
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
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function postMethod(
			$resource,
			$params = array ()) {
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
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function jsonPostMethod(
			$resource,
			$params = array ()) {
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
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function putMethod(
			$resource,
			$params = array ()) {
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
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function putContentMethod(
			$resource,
			$content) {
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
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas
	 */
	public function &getObjeto365datas() {
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjeto365datas(
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
	public function getAjoutHeader() {
		return $this->ajout_header;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutHeader(
			$ajout_header) {
		$this->ajout_header = $ajout_header;
		return $this;
	}

	/**
	 * @codeCoverageIgnore @brief Returns the default params.
	 * @retval array Array with default params.
	 */
	public function getDefaultParams() {
		return $this->defaultParams;
	}

	/**
	 * @codeCoverageIgnore @brief Sets the default params.
	 *
	 * @param $defaultParams Array with default params. @retval o365ApiAbstract
	 * @throws Exception
	 */
	public function setDefaultParams(
			$defaultParams) {
		if (is_array ( $defaultParams ))
			$this->defaultParams = $defaultParams;
		else
			throw new Exception ( 'The argument defaultParams on setDefaultParams() has to be an array.' );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getConnected() {
		return $this->connected;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnected(
			$connected) {
		$this->connected = $connected;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getTypeRetour() {
		return $this->type_retour;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTypeRetour(
			$type_retour) {
		$this->type_retour = $type_retour;
		return $this;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "o365 Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, datas::help () );
		return $help;
	}
}
?>
