<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class splunk_wsclient<br>
 *
 * Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage splunk
 */
class splunk_wsclient extends wsclient {
	/**
	 * var privee
	 * @access private
	 * @var splunk_datas
	 */
	private $splunk_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var array.
	 */
	private $defaultParams = array ( 
			'count' => 30, 
			'offset' => 0, 
			'search' => '', 
			'sort_dir' => 'asc', 
			'sort_key' => 'name', 
			'sort_mode' => 'auto' );
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $auth = '';

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type splunk_wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param splunk_datas &$splunk_datas Reference sur un objet splunk_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_wsclient
	 */
	static function &creer_splunk_wsclient(&$liste_option, &$splunk_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_wsclient ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_datas" => $splunk_datas ) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_wsclient
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["splunk_datas"] )) {
			$this ->onError ( "il faut un objet de type splunk_datas" );
			return false;
		}
		$this ->setObjetsplunkDatas ( $liste_class ["splunk_datas"] );
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
	 * Prepare l'url de connexion au splunk nomme $nom
	 * @param string $nom
	 * @return boolean|splunk_wsclient
	 * @throws Exception
	 */
	public function prepare_connexion($nom) {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_data_splunk = $this ->getObjetsplunkDatas () 
			->valide_presence_splunk_data ( $nom );
		if ($liste_data_splunk === false) {
			return $this ->onError ( "Aucune definition de splunk pour " . $nom );
		}
		
		if (! isset ( $liste_data_splunk ["username"] )) {
			return $this ->onError ( "Il faut un username dans la liste des parametres splunk" );
		}
		if (! isset ( $liste_data_splunk ["password"] )) {
			return $this ->onError ( "Il faut un password dans la liste des parametres splunk" );
		}
		if (! isset ( $liste_data_splunk ["url"] )) {
			return $this ->onError ( "Il faut une url dans la liste des parametres splunk" );
		}
		
		$this ->getGestionConnexionUrl () 
			->retrouve_connexion_params ( $liste_data_splunk ) 
			->prepare_prepend_url ( $liste_data_splunk ["url"] );
		
		//On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this ->userLogin ( array ( 
				'username' => $liste_data_splunk ["username"], 
				'password' => $liste_data_splunk ["password"] ) );
		
		return $this;
	}

	/**
	 * Http Splunk header creator
	 *
	 * @return  string Http Header
	 */
	public function prepare_html_entete() {
		$this ->onDebug ( __METHOD__, 1 );
		
		if ( $this ->getAuth () ) {
			return array ( 
					"Content-Type: application/x-www-form-urlencoded", 
					"Authorization: Splunk " . $this ->getAuth () );
		}
		
		return array ( 
				"Content-Type: application/x-www-form-urlencoded" );
	}

	/**
	 * Http Splunk params creator
	 *
	 * @return splunk_wsclient
	 */
	public function prepare_params() {
		$this ->onDebug ( __METHOD__, 1 );
		
		#		if ( $this ->getAuth () ) {
		#			$this ->setParams ( 'output_mode', 'xml', true );
		#		}
		

		return $this;
	}

	/**
	 * Convert return data to array
	 *
	 * @return array
	 * @throws Exception
	 */
	public function prepare_retour($retour_wsclient) {
		$this ->onDebug ( __METHOD__, 1 );
		
		return simplexml_load_string ( $retour_wsclient );
	}

	/**
	 * Sends are prepare_requete_json to the splunk API and returns the response as object.
	 *
	 * @return  string    API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete() {
		$this ->onDebug ( __METHOD__, 1 );
		
		if ($this ->getListeOptions () 
			->verifie_option_existe ( "dry-run" ) && ($this ->getHttpMethod () == 'POST' || $this ->getHttpMethod () == 'DELETE')) {
			$this ->onInfo ( "DRY RUN :" . $this ->getUrl () );
			$this ->onInfo ( "DRY RUN :" . print_r ( $this ->getParams (), true ) );
		} else {
			$header = $this ->prepare_html_entete ();
			
			$retour_wsclient = $this ->envoi_requete ( $header );
			
			$retour = $this ->prepare_retour ( $retour_wsclient );
			$this ->onDebug ( $retour, 2 );
			
			return $retour;
		}
		
		return "";
	}

	/**
	 *
	 * @param string $param
	 * @param string|integer|boolean $valeur
	 * @return splunk_ci
	 */
	public function modifie_default_param($param, $valeur) {
		$default_params = $this ->getDefaultParams ();
		$default_params [$param] = $valeur;
		
		return $this ->setDefaultParams ( $default_params );
	}

	/**
	 * count 	Number 	30 	Maximum number of entries to return. Set value to -1 to get all available entries.
	 * @param integer $valeur
	 * @return splunk_ci
	 * @throws Exception
	 */
	public function modifie_count($valeur) {
		if (is_integer ( $valeur )) {
			$this ->modifie_default_param ( 'count', $valeur );
		}
		return $this;
	}

	/**
	 * offset 	Number 	code>0</code> 	Index of first item to return.
	 * @param string $valeur
	 * @return splunk_ci
	 * @throws Exception
	 */
	public function modifie_offset($valeur) {
		if (is_integer ( $valeur )) {
			$this ->modifie_default_param ( 'offset', $valeur );
		}
		return $this;
	}

	/**
	 * search 	String 		Response filter, where the response field values are matched against this search expression.
	 * Example:
	 *   search=foo matches on any field with the string foo in the name.
	 *   search=field_name%3Dfield_value restricts the match to a single field. (Requires URI-encoding.)
	 * @param string $valeur
	 * @return splunk_ci
	 * @throws Exception
	 */
	public function modifie_search($valeur) {
		return $this ->modifie_default_param ( 'search', $valeur );
	}

	/**
	 *  sort_dir 	Enum 	asc 	Response sort order:
	 *   asc = ascending
	 *   desc = descending
	 * @param string $valeur
	 * @return splunk_ci
	 * @throws Exception
	 */
	public function modifie_sort_dir($valeur) {
		switch ($valeur) {
			case 'asc' :
			case 'desc' :
				return $this ->modifie_default_param ( 'sort_dir', $valeur );
		}
		return $this ->onError ( 'sort_dir : ' . $valeur . ' n\'existe pas.' );
	}

	/**
	 *  sort_key 	String 	name 	Field name to use for sorting.
	 * @param string $valeur
	 * @return splunk_ci
	 * @throws Exception
	 */
	public function modifie_sort_key($valeur) {
		return $this ->modifie_default_param ( 'sort_key', $valeur );
	}

	/**
	 *  sort_mode 	Enum 	auto 	Collated ordering:
	 *    auto = If all field values are numeric, collate numerically. Otherwise, collate alphabetically.
	 *    alpha = Collate alphabetically, not case-sensitive.
	 *    alpha_case = Collate alphabetically, case-sensitive.
	 *    num = Collate numerically.
	 * @param string $valeur
	 * @return splunk_ci
	 * @throws Exception
	 */
	public function modifie_sort_mode($valeur) {
		switch ($valeur) {
			case 'auto' :
			case 'alpha' :
			case 'alpha_case' :
			case 'num' :
				return $this ->modifie_default_param ( 'sort_mode', $valeur );
		}
		return $this ->onError ( 'sort_mode : ' . $valeur . ' n\'existe pas.' );
	}

	/**
	 *  summarize 	Bool 	false 	Response type:
	 *    true = Summarized response, omitting some index details, providing a faster response.
	 *    false = full response.
	 * @param string $valeur
	 * @return splunk_ci
	 * @throws Exception
	 */
	public function modifie_summarize($valeur) {
		if (is_bool ( $valeur )) {
			$this ->modifie_default_param ( 'summarize', $valeur );
		}
		return $this;
	}

	/************************* API splunk ***********************/
	/**
	 * Resource: auth/login
	 *   Method: Post
	 * Autentification
	 * 
	 * @codeCoverageIgnore
	 * @param   $params				Request Parameters
	 * @throws  Exception
	 */
	final public function userLogin($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$resultat = $this ->postMethod ( 'services/auth/login', $params );
		
		//<response>
		//   <sessionKey>192fd3e46a31246da7ea7f109e7f95fd</sessionKey>
		// </response>
		if (isset ( $resultat->sessionKey ) && ! empty ( $resultat->sessionKey )) {
			return $this ->setAuth ( ( string ) $resultat->sessionKey );
		}
		return $this ->onError ( "Erreur durant l'autentification", $resultat );
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function getMethod($resource, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this ->getDefaultParams (), $params );
		$this ->setUrl ( $resource ) 
			->setHttpMethod ( "GET" ) 
			->setParams ( $full_params );
		
		return $this ->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function postMethod($resource, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this ->getDefaultParams (), $params );
		$this ->setUrl ( $resource ) 
			->setHttpMethod ( "POST" ) 
			->setPostDatas ( http_build_query ( $full_params ) );
		
		return $this ->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function deleteMethod($resource, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this ->getDefaultParams (), $params );
		$this ->setUrl ( $resource ) 
			->setHttpMethod ( "DELETE" ) 
			->setParams ( $full_params );
		
		return $this ->prepare_requete ();
	}

	/************************* API splunk ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return splunk_datas
	 */
	public function &getObjetsplunkDatas() {
		return $this->splunk_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetsplunkDatas(&$splunk_datas) {
		$this->splunk_datas = $splunk_datas;
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
	 * @retrun splunk_wsclient
	 *
	 * @throws  Exception
	 */
	public function setDefaultParams($defaultParams) {
		if (is_array ( $defaultParams ))
			$this->defaultParams = $defaultParams;
		else
			return $this ->onError ( 'The argument defaultParams on setDefaultParams() has to be an array.' );
		
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
		$help [__CLASS__] ["text"] [] .= "splunk Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		$help = array_merge ( $help, splunk_datas::help () );
		
		return $help;
	}
}

?>
