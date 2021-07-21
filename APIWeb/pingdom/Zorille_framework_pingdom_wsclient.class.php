<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

use Exception as Exception;

/**
 * class pingdom_wsclient<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage pingdom
 */
class pingdom_wsclient extends wsclient {
	/**
	 * var privee
	 * @access private
	 * @var pingdom_datas
	 */
	private $pingdom_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $App_Key = '';
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
	 * Instancie un objet de type pingdom_wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param pingdom_datas &$pingdom_datas Reference sur un objet pingdom_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return pingdom_wsclient
	 */
	static function &creer_pingdom_wsclient(
			&$liste_option,
			&$pingdom_datas,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new pingdom_wsclient ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"pingdom_datas" => $pingdom_datas
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return pingdom_wsclient
	 * @throws Exception
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		if (! isset ( $liste_class ["pingdom_datas"] )) {
			$this->onError ( "il faut un objet de type pingdom_datas" );
			return false;
		}
		$this->setObjetpingdomDatas ( $liste_class ["pingdom_datas"] )
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
	 * Prepare l'url de connexion au pingdom nomme $nom
	 * @param string $nom
	 * @return boolean|pingdom_wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
			$nom) {
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_pingdom = $this->getObjetpingdomDatas ()
			->valide_presence_pingdom_data ( $nom );
		if ($liste_data_pingdom === false) {
			return $this->onError ( "Aucune definition de pingdom pour " . $nom );
		}
		if (! isset ( $liste_data_pingdom ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres pingdom" );
		}
		if (! isset ( $liste_data_pingdom ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres pingdom" );
		}
		if (! isset ( $liste_data_pingdom ["App-Key"] )) {
			return $this->onError ( "Il faut un App-Key dans la liste des parametres pingdom" );
		}
		if (! isset ( $liste_data_pingdom ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres pingdom" );
		}
		// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->getGestionConnexionUrl ()
			->getObjetUtilisateurs ()
			->setUsername ( $liste_data_pingdom ["username"] )
			->setPassword ( $liste_data_pingdom ["password"] );
		$this->setAppKey ( $this->getGestionConnexionUrl ()
			->getObjetUtilisateurs ()
			->decrypt ( $liste_data_pingdom ["App-Key"] ) );
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_pingdom )
			->prepare_prepend_url ( $liste_data_pingdom ["url"] );
		return $this;
	}

	/**
	 * Http header creator
	 *
	 * @return string Http Header
	 */
	public function prepare_html_entete() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getAppKey ()) {
			return $this->setHttpHeader ( array (
					"Content-Type: " . $this->getContentType (),
					"App-Key: " . $this->getAppKey (),
					"Accept: " . $this->getAccept ()
			) );
		}
		return $this->setHttpHeader ( array (
				"Content-Type: " . $this->getContentType (),
				"Accept: " . $this->getAccept ()
		) );
	}

	/**
	 * Sends are prepare_requete_json to the pingdom API and returns the response as object.
	 *
	 * @param string $method Name of the API method.
	 * @return string API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete_json(
			$method) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) !== false && (preg_match ( "/^Create.*$|^BulkUpdate$/", $method ) === 1 || $this->getHttpMethod () == "DELETE" || $this->getHttpMethod () == "POST")) {
			$this->onInfo ( "DRY RUN :" . $method . " " . print_r ( $this->getParams (), true ) );
		} else {
			$retour_json = $this->prepare_html_entete ()
				->envoi_requete ();
			$retour = $this->traite_retour_json ( $retour_json );
			$this->onDebug ( $retour, 2 );
			if (is_array ( $retour )) {
				if (isset ( $retour ["error"] )) {
					return $this->onError ( $retour ["error"] ["statusdesc"] . " : " . $retour ["error"] ["errormessage"], "", $retour ["error"] ["statuscode"] );
				}
				return $retour;
			}
		}
		return "";
	}

	/**
	 * *********************** API pingdom **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Resource: Actions
	 *   Method: Get Actions (Alerts) List
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getActions(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'actions' )
			->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		$retour = $this->prepare_requete_json ( 'actions' );
		return $retour ['actions'];
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: Analysis
	 *   Method: Get Root Cause Analysis Results List
	 * @param integer $checkid ID to get.
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getAnalysisRootCause(
			$checkid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'analysis/' . $checkid );
		$this->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		$retour = $this->prepare_requete_json ( 'analysis' );
		return $retour ['analysis'];
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: Analysis
	 *   Method: Get Raw Analysis Results
	 * @param integer $checkid ID to get.
	 * @param integer $analisysid ID to get.
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getAnalysisRaw(
			$checkid,
			$analisysid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'analysis/' . $checkid . "/" . $analisysid );
		$this->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		return $this->prepare_requete_json ( 'analysis' );
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: Checks
	 *   Method: Get Check List
	 *   Method: Get Detailed Check Information
	 * @param integer $checkid ID to get.
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getChecks(
			$checkid = '',
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($checkid != '') {
			$this->setUrl ( 'checks/' . $checkid );
		} else {
			$this->setUrl ( 'checks' );
		}
		$this->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		$retour = $this->prepare_requete_json ( 'checks' );
		if ($checkid != '') {
			return $retour ['check'];
		}
		return $retour ['checks'];
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: Contacts
	 *   Method: Get Contacts List
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getContacts(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'contacts' )
			->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		$retour = $this->prepare_requete_json ( 'contacts' );
		return $retour ['contacts'];
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: Credits
	 *   Method: Get Credits List
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getCredits(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setUrl ( 'credits' )
			->setHttpMethod ( "GET" )
			->setParams ( $params );
		// prepare_requete_json
		$retour = $this->prepare_requete_json ( 'credits' );
		return $retour ['credits'];
	}

	/**
	 * Resource: Checks Method: Create New Check Method: Modify Check Method: Modify Multiple Checks Method: Delete Check Method: Delete Multiple Checks Resource: Contacts Method: Create Contact Method: Modify Contact Method: Modify Multiple Contacts Method: Delete Contact Method: Delete Multiple Contacts Resource: Probes Method: Get Probe Server List Resource: Reference Method: Get Reference Resource: Reports.email Method: Get Email Report Subscription List Method: Create Email Report Method: Modify Email Report Method: Delete Email Report Resource: Reports.public Method: Get Public Report List Method: Publish Public Report Method: Withdraw Public Report Resource: Reports.shared Method: Get Shared Reports (Banners) List Method: Create Shared Report (Banner) Method: Delete Shared Report (Banner) Resource: Results Method: Get Raw Check Results Resource: Servertime Method: Get Current Server Time Resource: Settings Method: Get Account Settings Method: Modify Account Settings Resource: Summary.average Method: Get A Response Time / Uptime Average Resource: Summary.hoursofday Method: Get Response Time Averages For Each Hour Of The Day Resource: Summary.outage Method: Get List of Outages Resource: Summary.performance Method: Get Intervals of Average Response Time and Uptime During a Given Interval Resource: Summary.probes Method: Get Active Probes For A Period Resource: Single Method: Make A Single Test Resource: Traceroute Method: Make A Traceroute
	 */
	/**
	 * *********************** API pingdom **********************
	 */
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return pingdom_datas
	 */
	public function &getObjetpingdomDatas() {
		return $this->pingdom_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetpingdomDatas(
			&$pingdom_datas) {
		$this->pingdom_datas = $pingdom_datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAppKey() {
		return $this->App_Key;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAppKey(
			$App_Key) {
		$this->App_Key = $App_Key;
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
	 * @retval  ZabbixApiAbstract
	 *
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
	 * *********************** Accesseurs **********************
	 */
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "pingdom Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, pingdom_datas::help () );
		return $help;
	}
}
?>
