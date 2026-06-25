<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\opnsense;

use Zorille\framework as Core;
use Exception as Exception;
use Zorille\framework\gestion_connexion_url;
use Zorille\framework\options;

/**
 * class wsclient<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage opnsense
 */
class wsclient extends Core\wsclient {
	/**
	 * var privee
	 * @access private
	 * @var datas
	 */
	private $opnsense_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var array.
	 */
	private $defaultParams = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param object|null &$datas Reference sur un objet opnsense_datas
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
				"opnsense_datas" => $datas
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
		if (! isset ( $liste_class ["opnsense_datas"] )) {
			$r = $this->onError ( "il faut un objet de type opnsense_datas" );
			return $r;
		}
		$this->setObjetOpnsenseDatas ( $liste_class ["opnsense_datas"] )
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
	 * Prepare l'url de connexion au opnsense nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): wsclient|bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_opnsense = $this->getObjetOpnsenseDatas ()
			->valide_presence_data ( $nom );
		if ($liste_data_opnsense === false) {
			return $this->onError ( "Aucune definition de opnsense pour " . $nom );
		}
		if (! isset ( $liste_data_opnsense ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres opnsense" );
		}
		if (! isset ( $liste_data_opnsense ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres opnsense" );
		}
		if (! isset ( $liste_data_opnsense ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres opnsense" );
		}
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->getGestionConnexionUrl ()
			->getObjetUtilisateurs ()
			->setUsername ( $liste_data_opnsense ["username"] )
			->setPassword ( $liste_data_opnsense ["password"] );
		$this->setHttpAuth ( 'basic' );
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_opnsense )
			->prepare_prepend_url ( $liste_data_opnsense ["url"] );
		return $this;
	}

	/**
	 * Sends are prepare_requete_json to the opnsense API and returns the response as object.
	 *
	 * @param string $method Name of the API method.
	 * @return string|array API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete_json(
		string $method): string|array
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) !== false && (preg_match ( "/^set$|^add$|^del$/", $method ) === 1 || $this->getHttpMethod () == "DELETE" || $this->getHttpMethod () == "POST")) {
			$this->onInfo ( "DRY RUN :" . $method . " " . print_r ( $this->getParams (), true ) );
		} else {
			$retour_json = $this->prepare_html_entete ()
				->envoi_requete ();
			$retour = $this->traite_retour_json ( $retour_json );
			$this->onDebug ( $retour, 2 );
			if (is_array ( $retour )) {
				if (isset ( $retour ["status"] ) && (is_numeric ( $retour ["status"] ) && $retour ["status"] != 200)) {
					return $this->onError ( $retour ["status"] . " : " . $retour ["message"], "", $retour ["status"] );
				}
				return $retour;
			}
		}
		return "";
	}

	/**
	 * *********************** API opnsense **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Resource: core
	 *   Method: Get Menu
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getMenu(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'core/menu/search' );
		$this->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'search' );
	}

	/**
	 * *********************** API opnsense **********************
	 */
	/**
	 * *********************** API SockdIOPS opnsense **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Get sockdglobal
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getSockdIOPSGlobal(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/sockdglobal/get' );
		$this->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'get' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Get Clientslist
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getSockdIOPSClients(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/clientslist/get' );
		$this->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'get' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Get Sockslist
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getSockdIOPSSocks(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/sockslist/get' );
		$this->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'get' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Get Routeslist
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getSockdIOPSRoutes(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/routeslist/get' );
		$this->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'get' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Post Clientslist
	 * @param array $params Request Parameters "current"=>1,"rowCount"=>7,"searchPhrase"=>''
	 * @throws Exception
	 */
	public function searchSockdIOPSClients(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/clientslist/searchClient' );
		$this->setHttpMethod ( "POST" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'search' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Post Sockslist
	 * @param array $params Request Parameters "current"=>1,"rowCount"=>7,"searchPhrase"=>''
	 * @throws Exception
	 */
	public function searchSockdIOPSSocks(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/sockslist/searchSock' );
		$this->setHttpMethod ( "POST" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'search' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Post Routeslist
	 * @param array $params Request Parameters "current"=>1,"rowCount"=>7,"searchPhrase"=>''
	 * @throws Exception
	 */
	public function searchSockdIOPSRoutes(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/routeslist/searchRoute' );
		$this->setHttpMethod ( "POST" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'search' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Post Service Status
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function SockdIOPSServiceStatus(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/service/status' );
		$this->setHttpMethod ( "POST" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'get' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: SockdIOPS
	 *   Method: Post Service Dirty
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function SockdIOPSServiceDirty(
		array $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'sockdiops/service/dirty' );
		$this->setHttpMethod ( "get" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'get' );
	}

	/**
	 * *********************** API SockdIOPS opnsense **********************
	 */
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas
	 */
	public function &getObjetOpnsenseDatas(): ?datas
	{
		return $this->opnsense_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetOpnsenseDatas(
			&$opnsense_datas): static
	{
		$this->opnsense_datas = $opnsense_datas;
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
	 * @retval  ZabbixApiAbstract
	 *
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
	 * *********************** Accesseurs **********************
	 */
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "opnsense Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		return array_merge ( $help, datas::help () );
	}
}
