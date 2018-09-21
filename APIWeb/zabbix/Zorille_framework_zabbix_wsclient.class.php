<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_wsclient<br>
 *
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_wsclient extends wsclient {
	/**
	 * var privee
	 * @access private
	 * @var zabbix_datas
	 */
	private $zabbix_datas = null;
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

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param zabbix_datas &$zabbix_datas Reference sur un objet zabbix_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return zabbix_wsclient
	 */
	static function &creer_zabbix_wsclient(&$liste_option, &$zabbix_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_wsclient ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_datas" => $zabbix_datas 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_wsclient
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["zabbix_datas"] )) {
			return $this->onError ( "il faut un objet de type zabbix_datas" );
		}
		$this->setObjetZabbixDatas ( $liste_class ["zabbix_datas"] );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de wsclient
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au zabbix nomme $nom
	 * @param string $nom
	 * @return boolean|zabbix_wsclient
	 * @throws Exception
	 */
	public function prepare_connexion($nom) {
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_zabbix = $this->getObjetZabbixDatas ()
			->valide_presence_zabbix_data ( $nom );
		if ($liste_data_zabbix === false) {
			return $this->onError ( "Aucune definition de zabbix pour " . $nom );
		}
		
		if (! isset ( $liste_data_zabbix ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres zabbix" );
		}
		if (! isset ( $liste_data_zabbix ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres zabbix" );
		}
		if (! isset ( $liste_data_zabbix ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres zabbix" );
		}
		
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_zabbix )
			->prepare_prepend_url ( $liste_data_zabbix ["url"] );
		
		$this->userLogin ( array (
				'user' => $liste_data_zabbix ["username"],
				'password' => $liste_data_zabbix ["password"] 
		) );
		return $this;
	}
	
	/**
	 * 
	 * @param string $method
	 * @param string|array $params
	 * @param boolean $auth
	 * @return string entete json
	 */
	public function encode_json($method,$params, $auth){
		// sanity check and conversion for params array
		if (! $params)
			$params = array ();
		elseif (! is_array ( $params ))
		$params = array (
				$params
		);
			
		// build prepare_requete_json array
		$prepare_requete_json = array (
				'jsonrpc' => '2.0',
				'method' => $method,
				'params' => $params,
				'id' => number_format ( microtime ( true ), 4, '', '' )
		);
		if ($auth) {
			$prepare_requete_json ['auth'] = $this->getAuth();
		}
		$json_prepare_requete_json = json_encode ( $prepare_requete_json );
		
		return $json_prepare_requete_json;
	}

	/**
     * Sends are prepare_requete_json to the zabbix API and returns the response
	 *          as object.
	 *
	 * @param   string $method     Name of the API method.
	 * @param   array $params     Additional parameters.
	 * @param   boolean $auth       Enable auth string (default TRUE).
	 *
	 * @return  stdClass    API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete_json($method, $params = NULL, $resultArrayKey = '', $auth = TRUE) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setHttpMethod ( "POST" );
		
		$json_prepare_requete_json=$this->encode_json($method, $params, $auth);
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) !== false && preg_match ( "/.*\.create$|.*\.delete$|.*\.update|.*\.addmedia|.*\.updatemedia|.*\.deletemedia$|.*\.import$/", $method ) === 1) {
			$this->onInfo ( "DRY RUN :" . print_r ( $params, true ) );
			$this->onDebug ( "DRY RUN :" . $json_prepare_requete_json, 1 );
		} else {
			$this->onDebug ( $json_prepare_requete_json, 2 );
			$this->setPostDatas ( $json_prepare_requete_json );
			
			$retour_json = $this->envoi_requete ( array('Content-type: application/json-rpc') );
			$retour=$this ->traite_retour_json ( $retour_json );
			$this->onDebug ( $retour, 2 );
			
			return $this->gestion_retour($retour,$resultArrayKey);
		}
		
		return "";
	}
	
	/**
	 * Gere les retours : convertie si c'est OK ou emet une exception en cas d'erreur
	 * @param string|array $retour
	 * @param array $resultArrayKey
	 * @return string|array
	 * @throws Exception
	 */
	public function gestion_retour($retour,$resultArrayKey){
		if (is_array ( $retour )) {
			if (isset ( $retour ["error"] )) {
				$message=$retour ["error"] ["message"];
				if (isset ( $retour ["error"] ["data"] )) {
					$message.= " : " . $retour ["error"] ["data"];
				}
				return $this->onError( $message, "",$retour ["error"] ["code"] );
			}
		
			// return response
			if ($resultArrayKey && is_array ( $retour ))
				return $this->__getPrepareRequeteJsonParamsArray ( $retour ["result"], $resultArrayKey );
			else
				return $retour ["result"];
		}
		
		return "";
	}

	/************************* API Zabbix ***********************/
	/**
	 * @codeCoverageIgnore	
     * @brief   Convertes an indexed array to an associative array.
	 *
	 * @param   object $indexedArray           Indexed array with objects.
	 * @param   object $useObjectProperty      Object property to use as array key.
	 *
	 * @retval  associative Array
	 */
	private function __getPrepareRequeteJsonParamsArray($objectArray, $useObjectProperty) {
		$this->onDebug ( __METHOD__, 1 );
		// sanity check
		if (count ( $objectArray ) == 0 || ! property_exists ( $objectArray [0], $useObjectProperty ))
			return $objectArray;
			
			// loop through array and replace keys
		foreach ( $objectArray as $key => $object ) {
			unset ( $objectArray [$key] );
			$objectArray [$object->{$useObjectProperty}] = $object;
		}
		
		// return associative array
		return $objectArray;
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Returns a params array for the prepare_requete_json.
	 *
	 * This method will automatically convert all provided types into a correct
	 * array. Which means:
	 *
	 *      - arrays will not be converted (indexed & associatve)
	 *      - scalar values will be converted into an one-element array (indexed)
	 *      - other values will result in an empty array
	 *
	 * Afterwards the array will be merged with all default params, while the
	 * default params have a lower priority (passed array will overwrite default
	 * params). But there is an exception for merging: If the passed array is an
	 * indexed array, the default params will not be merged. This is because
	 * there are some API methods, which are expecting a simple JSON array (aka
	 * PHP indexed array) instead of an object (aka PHP associative array).
	 * Example for this behaviour are delete operations, which are directly
	 * expecting an array of IDs '[ 1,2,3 ]' instead of '{ ids: [ 1,2,3 ] }'.
	 *
	 * @param   array $params     Params array.
	 *
	 * @retval  Array
	 */
	private function _getPrepareRequeteJsonParamsArray($params) {
		$this->onDebug ( __METHOD__, 1 );
		// if params is a scalar value, turn it into an array
		if (is_scalar ( $params ))
			$params = array (
					$params 
			);
			
			// if params isn't an array, create an empty one (e.g. for booleans, NULL)
		elseif (! is_array ( $params ))
			$params = array ();
			
			// if array isn't indexed, merge array with default params
		if (count ( $params ) == 0 || array_keys ( $params ) !== range ( 0, count ( $params ) - 1 ))
			$params = array_merge ( $this->getDefaultParams (), $params );
			
			// return params
		return $params;
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Login into the API.
	 *
	 * This will also retreive the auth Token, which will be used for any
	 * further prepare_requete_jsons.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	final public function userLogin($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		$this->setAuth ( $this->prepare_requete_json ( 'user.login', $params, $arrayKeyProperty, FALSE ) );
		return $this;
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Logout from the API.
	 *
	 * This will also reset the auth Token.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	final public function userLogout($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		$this->setAuth ( '' );
		return $this->prepare_requete_json ( 'user.logout', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method action.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function actionGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'action.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method action.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function actionExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'action.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method action.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function actionCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'action.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method action.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function actionUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'action.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method action.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function actionDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'action.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method action.validateOperations.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function actionValidateOperations($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'action.validateOperations', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method action.validateConditions.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function actionValidateConditions($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'action.validateConditions', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method action.validateOperationConditions.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function actionValidateOperationConditions($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'action.validateOperationConditions', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method alert.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function alertGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'alert.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method apiinfo.version.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function apiinfoVersion($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'apiinfo.version', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method application.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function applicationGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'application.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method application.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function applicationExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'application.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method application.checkInput.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function applicationCheckInput($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'application.checkInput', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method application.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function applicationCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'application.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method application.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function applicationUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'application.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method application.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function applicationDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'application.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method application.massAdd.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function applicationMassAdd($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'application.massAdd', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method configuration.export.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function configurationExport($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'configuration.export', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method configuration.import.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function configurationImport($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'configuration.import', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method dcheck.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function dcheckGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'dcheck.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method dcheck.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function dcheckIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'dcheck.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method dcheck.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function dcheckIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'dcheck.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method dhost.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function dhostGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'dhost.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method dhost.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function dhostExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'dhost.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.copy.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleCopy($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.copy', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.syncTemplates.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleSyncTemplates($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.syncTemplates', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method discoveryrule.findInterfaceForItem.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function discoveryruleFindInterfaceForItem($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'discoveryrule.findInterfaceForItem', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method drule.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function druleGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'drule.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method drule.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function druleExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'drule.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method drule.checkInput.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function druleCheckInput($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'drule.checkInput', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method drule.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function druleCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'drule.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method drule.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function druleUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'drule.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method drule.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function druleDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'drule.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method drule.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function druleIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'drule.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method drule.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function druleIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'drule.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method dservice.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function dserviceGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'dservice.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method dservice.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function dserviceExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'dservice.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method event.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function eventGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'event.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method event.acknowledge.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function eventAcknowledge($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'event.acknowledge', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graph.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graph.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graph.syncTemplates.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphSyncTemplates($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graph.syncTemplates', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graph.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graph.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graph.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graph.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graph.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graph.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graph.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graph.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graph.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graph.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graphitem.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphitemGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graphitem.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graphprototype.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphprototypeGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graphprototype.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graphprototype.syncTemplates.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphprototypeSyncTemplates($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graphprototype.syncTemplates', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graphprototype.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphprototypeDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graphprototype.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graphprototype.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphprototypeUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graphprototype.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graphprototype.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphprototypeCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graphprototype.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graphprototype.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphprototypeExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graphprototype.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method graphprototype.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function graphprototypeGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'graphprototype.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.massAdd.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostMassAdd($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.massAdd', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.massUpdate.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostMassUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.massUpdate', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.massRemove.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostMassRemove($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.massRemove', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method host.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'host.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.massAdd.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupMassAdd($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.massAdd', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.massRemove.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupMassRemove($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.massRemove', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.massUpdate.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupMassUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.massUpdate', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostgroup.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostgroupIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostgroup.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostprototype.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostprototypeGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostprototype.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostprototype.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostprototypeCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostprototype.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostprototype.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostprototypeUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostprototype.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostprototype.syncTemplates.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostprototypeSyncTemplates($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostprototype.syncTemplates', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostprototype.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostprototypeDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostprototype.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostprototype.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostprototypeIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostprototype.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostprototype.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostprototypeIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostprototype.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method history.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function historyGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'history.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.checkInput.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceCheckInput($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.checkInput', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.massAdd.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceMassAdd($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.massAdd', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.massRemove.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceMassRemove($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.massRemove', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method hostinterface.replaceHostInterfaces.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function hostinterfaceReplaceHostInterfaces($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'hostinterface.replaceHostInterfaces', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method image.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function imageGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'image.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method image.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function imageGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'image.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method image.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function imageExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'image.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method image.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function imageCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'image.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method image.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function imageUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'image.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method image.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function imageDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'image.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method iconmap.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function iconmapGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'iconmap.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method iconmap.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function iconmapCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'iconmap.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method iconmap.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function iconmapUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'iconmap.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method iconmap.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function iconmapDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'iconmap.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method iconmap.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function iconmapIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'iconmap.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method iconmap.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function iconmapIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'iconmap.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.syncTemplates.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemSyncTemplates($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.syncTemplates', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.validateInventoryLinks.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemValidateInventoryLinks($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.validateInventoryLinks', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.addRelatedObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemAddRelatedObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.addRelatedObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.findInterfaceForItem.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemFindInterfaceForItem($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.findInterfaceForItem', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method item.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'item.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.syncTemplates.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeSyncTemplates($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.syncTemplates', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.addRelatedObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeAddRelatedObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.addRelatedObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.findInterfaceForItem.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeFindInterfaceForItem($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.findInterfaceForItem', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method itemprototype.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function itemprototypeIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'itemprototype.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method maintenance.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function maintenanceGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'maintenance.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method maintenance.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function maintenanceExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'maintenance.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method maintenance.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function maintenanceCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'maintenance.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method maintenance.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function maintenanceUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'maintenance.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method maintenance.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function maintenanceDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'maintenance.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.checkInput.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapCheckInput($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.checkInput', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method map.checkCircleSelementsLink.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mapCheckCircleSelementsLink($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'map.checkCircleSelementsLink', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method mediatype.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mediatypeGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'mediatype.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method mediatype.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mediatypeCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'mediatype.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method mediatype.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mediatypeUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'mediatype.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method mediatype.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function mediatypeDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'mediatype.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method proxy.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function proxyGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'proxy.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method proxy.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function proxyCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'proxy.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method proxy.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function proxyUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'proxy.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method proxy.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function proxyDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'proxy.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method proxy.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function proxyIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'proxy.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method proxy.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function proxyIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'proxy.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.validateUpdate.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceValidateUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.validateUpdate', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.validateDelete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceValidateDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.validateDelete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.addDependencies.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceAddDependencies($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.addDependencies', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.deleteDependencies.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceDeleteDependencies($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.deleteDependencies', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.validateAddTimes.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceValidateAddTimes($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.validateAddTimes', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.addTimes.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceAddTimes($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.addTimes', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.getSla.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceGetSla($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.getSla', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.deleteTimes.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceDeleteTimes($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.deleteTimes', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method service.expandPeriodicalTimes.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function serviceExpandPeriodicalTimes($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'service.expandPeriodicalTimes', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screen.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screen.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screen.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screen.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screen.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screen.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screen.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screen.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screen.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screen.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screenitem.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenitemGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screenitem.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screenitem.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenitemCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screenitem.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screenitem.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenitemUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screenitem.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screenitem.updateByPosition.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenitemUpdateByPosition($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screenitem.updateByPosition', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screenitem.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenitemDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screenitem.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screenitem.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenitemIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screenitem.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method screenitem.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function screenitemIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'screenitem.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method script.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function scriptGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'script.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method script.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function scriptCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'script.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method script.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function scriptUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'script.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method script.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function scriptDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'script.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method script.execute.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function scriptExecute($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'script.execute', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method script.getScriptsByHosts.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function scriptGetScriptsByHosts($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'script.getScriptsByHosts', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.pkOption.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templatePkOption($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.pkOption', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.massAdd.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateMassAdd($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.massAdd', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.massUpdate.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateMassUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.massUpdate', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.massRemove.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateMassRemove($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.massRemove', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method template.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templateIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'template.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method templatescreen.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templatescreenGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'templatescreen.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method templatescreen.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templatescreenExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'templatescreen.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method templatescreen.copy.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templatescreenCopy($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'templatescreen.copy', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method templatescreen.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templatescreenCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'templatescreen.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method templatescreen.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templatescreenUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'templatescreen.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method templatescreen.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templatescreenDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'templatescreen.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method templatescreenitem.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function templatescreenitemGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'templatescreenitem.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.checkInput.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerCheckInput($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.checkInput', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.addDependencies.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerAddDependencies($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.addDependencies', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.deleteDependencies.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerDeleteDependencies($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.deleteDependencies', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.syncTemplates.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerSyncTemplates($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.syncTemplates', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.syncTemplateDependencies.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerSyncTemplateDependencies($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.syncTemplateDependencies', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method trigger.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'trigger.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method triggerprototype.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerprototypeGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'triggerprototype.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method triggerprototype.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerprototypeCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'triggerprototype.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method triggerprototype.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerprototypeUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'triggerprototype.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method triggerprototype.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerprototypeDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'triggerprototype.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method triggerprototype.syncTemplates.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function triggerprototypeSyncTemplates($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'triggerprototype.syncTemplates', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.updateProfile.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userUpdateProfile($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.updateProfile', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.addMedia.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userAddMedia($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.addMedia', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.updateMedia.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userUpdateMedia($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.updateMedia', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.deleteMedia.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userDeleteMedia($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.deleteMedia', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.deleteMediaReal.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userDeleteMediaReal($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.deleteMediaReal', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.checkAuthentication.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userCheckAuthentication($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.checkAuthentication', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method user.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function userIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'user.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.getObjects.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupGetObjects($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.getObjects', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.exists.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupExists($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.exists', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.massAdd.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupMassAdd($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.massAdd', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.massUpdate.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupMassUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.massUpdate', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usergroup.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usergroupIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usergroup.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermacro.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermacroGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermacro.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermacro.createGlobal.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermacroCreateGlobal($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermacro.createGlobal', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermacro.updateGlobal.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermacroUpdateGlobal($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermacro.updateGlobal', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermacro.deleteGlobal.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermacroDeleteGlobal($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermacro.deleteGlobal', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermacro.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermacroCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermacro.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermacro.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermacroUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermacro.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermacro.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermacroDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermacro.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermacro.replaceMacros.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermacroReplaceMacros($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermacro.replaceMacros', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method usermedia.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function usermediaGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'usermedia.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method httptest.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function httptestGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'httptest.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method httptest.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function httptestCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'httptest.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method httptest.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function httptestUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'httptest.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method httptest.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function httptestDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'httptest.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method httptest.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function httptestIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'httptest.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method httptest.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function httptestIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'httptest.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method webcheck.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function webcheckGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'webcheck.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method webcheck.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function webcheckCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'webcheck.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method webcheck.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function webcheckUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'webcheck.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method webcheck.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function webcheckDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'webcheck.delete', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method webcheck.isReadable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function webcheckIsReadable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'webcheck.isReadable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore	
     * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method webcheck.isWritable.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function webcheckIsWritable($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'webcheck.isWritable', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method valuemapping.get.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function valuemappingGet($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'valuemapping.get', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method valuemapping.create.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function valuemappingCreate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'valuemapping.create', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method valuemapping.update.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function valuemappingUpdate($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'valuemapping.update', $params, $arrayKeyProperty );
	}

	/**
	 * @codeCoverageIgnore
	 * @brief   Reqeusts the Zabbix API and returns the response of the API
	 *          method valuemapping.delete.
	 *
	 * The $params Array can be used, to pass through params to the Zabbix API.
	 * For more informations about this params, check the Zabbix API
	 * Documentation.
	 *
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an
	 * associatve instead of an indexed array as response. A valid value for
	 * this $arrayKeyProperty is any property of the returned JSON objects
	 * (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param   array $params             Parameters to pass through.
	 * @param   $arrayKeyProperty   Object property for key of array.
	 *
	 * @retval  stdClass
	 *
	 * @throws  Exception
	 */
	public function valuemappingDelete($params = array(), $arrayKeyProperty = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->_getPrepareRequeteJsonParamsArray ( $params );
		
		// prepare_requete_json
		return $this->prepare_requete_json ( 'valuemapping.delete', $params, $arrayKeyProperty );
	}

	/************************* API Zabbix ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return zabbix_datas
	 */
	public function &getObjetZabbixDatas() {
		return $this->zabbix_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetZabbixDatas(&$zabbix_datas) {
		$this->zabbix_datas = $zabbix_datas;
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
	 * @param   $defaultParams  Array with default params.
	 *
	 * @retval  ZabbixApiAbstract
	 *
	 * @throws  Exception
	 */
	public function setDefaultParams($defaultParams) {
		if (is_array ( $defaultParams ))
			$this->defaultParams = $defaultParams;
		else
			throw new Exception ( 'The argument defaultParams on setDefaultParams() has to be an array.' );
		
		return $this;
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, zabbix_datas::help () );
		
		return $help;
	}
}

?>
