<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\enedis;

use stdClass;
use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;
use Zorille\framework\gestion_connexion_url;
use Zorille\framework\options;

/**
 * class wsclient<br> Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage enedis
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
		$this->setObjetEnedisDatas ( $liste_class ["datas"] )
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
	 * Prepare l'url de connexion au enedis nomme $nom
	 * @param string $nom
	 * @return boolean|wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): wsclient|bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_enedis = $this->getObjetenedisDatas ()
			->valide_presence_data ( $nom );
		if ($liste_data_enedis === false) {
			return $this->onError ( "Aucune definition de enedis pour " . $nom );
		}
		if (! isset ( $liste_data_enedis ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres enedis" );
		}
		if (! isset ( $liste_data_enedis ["password"] )) {
		    return $this->onError ( "Il faut un password dans la liste des parametres enedis" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_enedis )
			->prepare_prepend_url ( $liste_data_enedis ["url"] );
			// On prepare l'objet utilisateurs de la connexion, car l'objet curl ne connait pas l'objet datas
		$this->userLogin ( $liste_data_enedis );
		return $this;
	}

	/**
	 * Http Enedis header creator
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
	    $this->onDebug ( $this->getHttpHeader (), 1 );
	    return $this;
	}

	/**
	 * Convert return data to array
	 *
	 * @param $retour_wsclient
	 * @return array|stdClass|bool
	 * @throws Exception
	 */
	public function prepare_retour(
			$retour_wsclient): array|stdClass|bool|null
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( "Accept Method :" . $this->getAccept (), 1 );
		switch ($this->getAccept ()) {
			default :
				$retour = $this->traite_retour_json ( $retour_wsclient );
				$this->onDebug ( $retour, 2 );
		}
		return $retour;
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
		$this->onDebug ( "Retour JSON selectionne", 2 );
		$this->onDebug ( $retour_json, 2 );
		// si le retour est en JSON, on le decode
		$tableau_resultat = json_decode ( $retour_json, $return_array );
		$this->onDebug ( "tableau JSON de retour :", 2 );
		$this->onDebug ( $tableau_resultat, 2 );
		if(isset($tableau_resultat->code)){
		    return $this->onError($tableau_resultat->message,"",$tableau_resultat->code);
		}
		return $tableau_resultat;
	}

	/**
	 * Sends are prepare_requete_json to the enedis API and returns the response as object.
	 *
	 * @return array|string API JSON response.
	 * @throws Exception
	 */
	public function prepare_requete(): mixed
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) && ($this->getHttpMethod () == 'POST' || $this->getHttpMethod () == 'PUT' || $this->getHttpMethod () == 'DELETE')) {
			$this->onInfo ( "DRY RUN :" . $this->getUrl () );
			$this->onInfo ( "DRY RUN :" . print_r ( $this->getParams (), true ) );
		} else {
		    $this->ondebug ( "URL :" . $this->getUrl (),1 );
			$retour_wsclient = $this->prepare_html_entete ()
				->envoi_requete ();
			$retour = $this->prepare_retour ( $retour_wsclient );
			$this->onDebug ( $retour, 2 );
			return $retour;
		}
		return "";
	}
	
	/**
	 * Resource: auth/login Method: Post Autentification
	 *
	 * @codeCoverageIgnore
	 * @param array $liste_data_enedis Request Parameters
	 * @throws Exception
	 */
	public function userLogin(
	    array $liste_data_enedis = array ()): bool|static
	    {
	        $this->onDebug ( __METHOD__, 1 );
	        $resultat = $this->postMethod ( 'oauth2/v3/token', array (
	            'client_id' => $liste_data_enedis ['username'],
	            'client_secret' => $liste_data_enedis ['password'],
	            'scope' => $liste_data_enedis ['scope'],
	            'grant_type' => $liste_data_enedis ['grant_type']
	        ) );
	        if (isset ( $resultat->access_token )) {
	            return $this->setAuth ( $resultat->access_token );
	        }
	        return $this->onError ( "Erreur durant l'autentification", $resultat );
	}

	/**
	 * *********************** API enedis **********************
	 */
	/**
	 * Collect PDM in account
	 * @param array $params
	 * @return array|string|stdClass
	 */
	public function collect_PDM (array  $params = array ("page_number"=>1)): array|string|stdClass
	{
	    $this->onDebug ( __METHOD__, 1 );
	    $resultat=$this->postMethod("usage_point_id_perimeter/v1/usage_point_id",$params,true);
	    return $resultat;
	}
	
	/**
	 * Collect Daily Consumption in Wh
	 * @param array $params
	 * @return array|string|stdClass
	 */
	public function daily_comsumption (array  $params = array ("usage_point_id"=>"PDM","start"=>"YYYY-MM-DD","end"=>"YYYY-MM-DD")): array|string|stdClass
	{
	    $this->onDebug ( __METHOD__, 1 );
	    $resultat=$this->getMethod("mesures/v2/metering_data/daily_consumption",$params);
	    return $resultat;
	}
	
	/**
	 * Collect Daily Max Powe in W
	 * @param array $params
	 * @return array|string|stdClass
	 */
	public function daily_consumption_max_power (array  $params = array ("usage_point_id"=>"PDM","start"=>"YYYY-MM-DD","end"=>"YYYY-MM-DD")): array|string|stdClass
	{
	    $this->onDebug ( __METHOD__, 1 );
	    $resultat=$this->getMethod("mesures/v2/metering_data/daily_consumption_max_power",$params);
	    return $resultat;
	}
	
	/**
	 * Collect Daily Max Powe in W
	 * @param array $params
	 * @return array|string|stdClass
	 */
	public function consumption_load_curve (array  $params = array ("usage_point_id"=>"PDM","start"=>"YYYY-MM-DD","end"=>"YYYY-MM-DD")): array|string|stdClass
	{
	    $this->onDebug ( __METHOD__, 1 );
	    $resultat=$this->getMethod("mesures/v2/metering_data/consumption_load_curve",$params);
	    return $resultat;
	}
	
	/**
	 * Collect Daily Max Powe in W
	 * @param array $params
	 * @return array|string|stdClass
	 */
	public function production_load_curve (array  $params = array ("usage_point_id"=>"PDM","start"=>"YYYY-MM-DD","end"=>"YYYY-MM-DD")): array|string|stdClass
	{
	    $this->onDebug ( __METHOD__, 1 );
	    $resultat=$this->getMethod("mesures/v2/metering_data/production_load_curve",$params);
	    return $resultat;
	}
	/**
	 * *********************** API enedis **********************
	 */
	/**
	 * *********************** Standard API **********************
	 */
	
	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return array|string
	 * @throws Exception
	 */
	public function getMethod(
		string $resource,
		array  $params = array (),
		       $type_mime = ""): array|string|stdClass
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
	 * @return array|string
	 * @throws Exception
	 */
	public function postMethod(
		string $resource,
	    array  $params = array (),bool $json_encode = false): bool|array|string|stdClass|null
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		// build prepare_requete_json array
		$this->setUrl ( $resource )
			->setHttpMethod ( "POST" );
			if($json_encode){
			    $this->setPostDatas ( json_encode( $full_params ) );
			} else {
			    $this->setPostDatas ( http_build_query ( $full_params ) );
			}
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return array|string
	 * @throws Exception
	 */
	public function putMethod(
		string $resource,
		array  $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "PUT" )
			->setPostDatas ( http_build_query ( $full_params ) );
		return $this->prepare_requete ();
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $resource Url Resource
	 * @param array $params Data to send
	 * @return array|string
	 * @throws Exception
	 */
	public function deleteMethod(
		string $resource,
		array  $params = array ()): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$full_params = array_merge ( $this->getDefaultParams (), $params );
		$this->setUrl ( $resource )
			->setHttpMethod ( "DELETE" )
			->setParams ( $full_params );
		return $this->prepare_requete ();
	}

	/**
	 * *********************** Standard API **********************
	 */


	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas|null
	 */
	public function &getObjetEnedisDatas(): ?datas
	{
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetEnedisDatas(
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
		$help [__CLASS__] ["text"] [] .= "enedis Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		return array_merge ( $help, datas::help () );
	}
}
