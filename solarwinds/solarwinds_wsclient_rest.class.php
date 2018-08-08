<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class solarwinds_wsclient_rest<br>
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage solarwinds
 */
class solarwinds_wsclient_rest extends wsclient {
	/**
	 * var privee
	 * @access private
	 * @var solarwinds_datas
	 */
	private $solarwinds_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $auth = '';
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
	 * Instancie un objet de type solarwinds_wsclient_rest. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param solarwinds_datas &$solarwinds_datas Reference sur un objet solarwinds_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return solarwinds_wsclient_rest
	 */
	static function &creer_solarwinds_wsclient_rest(&$liste_option, &$solarwinds_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new solarwinds_wsclient_rest ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"solarwinds_datas" => $solarwinds_datas ) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return solarwinds_wsclient_rest
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["solarwinds_datas"] )) {
			$this ->onError ( "il faut un objet de type solarwinds_datas" );
			return false;
		}
		$this ->setObjetSolarwindsDatas ( $liste_class ["solarwinds_datas"] );
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
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de wsclient
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au solarwinds nomme $nom
	 * @param string $nom
	 * @return boolean|solarwinds_wsclient_soap
	 * @throws Exception
	 */
	public function prepare_connexion($nom) {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_data_solarwinds = $this ->getObjetSolarwindsDatas () 
			->valide_presence_solarwinds_data ( $nom, 'rest' );
		if ($liste_data_solarwinds === false) {
			return $this ->onError ( "Aucune definition de solarwinds pour " . $nom );
		}
		
		if (! isset ( $liste_data_solarwinds ["username"] )) {
			return $this ->onError ( "Il faut un username dans la liste des parametres solarwinds" );
		}
		if (! isset ( $liste_data_solarwinds ["password"] )) {
			return $this ->onError ( "Il faut un password dans la liste des parametres solarwinds" );
		}
		if (! isset ( $liste_data_solarwinds ["url"] )) {
			return $this ->onError ( "Il faut une url dans la liste des parametres solarwinds" );
		}
		
		//On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this ->getGestionConnexionUrl () 
			->getObjetUtilisateurs () 
			->setUsername ( $liste_data_solarwinds ["username"] ) 
			->setPassword ( $liste_data_solarwinds ["password"] );
		
		$this ->getGestionConnexionUrl () 
			->retrouve_connexion_params ( $liste_data_solarwinds ) 
			->prepare_prepend_url ( $liste_data_solarwinds ["url"] );
		
		return $this;
	}

	/**
     * Sends are prepare_requete_json to the solarwinds API and returns the response as object.
	 *
	 * @param   string $method     Name of the API method.
	 * @return  string    API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete_json($method) {
		$this ->onDebug ( __METHOD__, 1 );
		
		if ($this ->getListeOptions () 
			->verifie_option_existe ( "dry-run" ) !== false && (preg_match ( "/^Create.*$|^Update.*$|^BulkUpdate$|^Delete.*$|^BulkDelete.*$/", $method ) === 1 || $this ->getHttpMethod () == "DELETE" || $this ->getHttpMethod () == "POST")) {
			$this ->onInfo ( "DRY RUN :" . $method . " " . print_r ( $this ->getParams (), true ) );
		} else {
			$retour_json = $this ->envoi_requete ( array ( 
					"Content-Type: application/json" ) );
			
			$retour = $this ->traite_retour_json ( $retour_json );
			$this ->onDebug ( $retour, 2 );
			
			return $this->gestion_retour($retour);
		}
		
		return array();
	}
	
	/**
	 * Gere les retours : convertie si c'est OK ou emet une exception en cas d'erreur
	 * @param string|array $retour
	 * @return string|array
	 * @throws Exception
	 */
	public function gestion_retour($retour){
		if (is_array ( $retour )) {
			if (isset ( $retour ["FullException"] )) {
				return $this->onError( $retour ["Message"], $retour ["FullException"] );
			}
	
			// return response
			return $retour ;
		}
	
		return array();
	}
	

	/**
	 * *********************** API solarwinds **********************
	 */
	
	/**
	 * @codeCoverageIgnore
	 * Resource: Actions
	 *   Method: Get Actions (Alerts) List
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getQuery($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$this ->setUrl ( 'Query' ) 
			->setHttpMethod ( "GET" ) 
			->setParams ( $params );
		
		// prepare_requete_json
		$retour = $this ->prepare_requete_json ( 'Query' );
		if (isset ( $retour ["results"] )) {
			return $retour ["results"];
		}
		
		return $retour;
	}

	/**
	 * @codeCoverageIgnore
	 * Resource: Actions
	 *   Method: Get Actions (Alerts) List
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function postQuery($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$this ->setUrl ( 'Query' ) 
			->setHttpMethod ( "POST" ) 
			->setPostDatas ( json_encode ( $params ) );
		
		// prepare_requete_json
		$retour = $this ->prepare_requete_json ( 'Query' );
		return $retour ["results"];
	}

	/**
	 * *********************** API solarwinds **********************
	 */
	
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return solarwinds_datas
	 */
	public function &getObjetSolarwindsDatas() {
		return $this->solarwinds_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSolarwindsDatas(&$solarwinds_datas) {
		$this->solarwinds_datas = $solarwinds_datas;
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
	public function &setAuth($auth) {
		$this->auth = $auth;
		return $this;
	}

	/**
	 * @codeCoverageIgnore @brief Returns the default params.
	 * @retval array Array with default params./
	public function getDefaultParams() {
		return $this->defaultParams;
	}

	/**
	 * @codeCoverageIgnore @brief Sets the default params.
	 *
	 * @param $defaultParams Array with default params. @retval solarwindsApiAbstract
	 * @throws Exception
	 */
	public function setDefaultParams($defaultParams) {
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
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "solarwinds Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, solarwinds_datas::help () );
		
		return $help;
	}
}

?>
