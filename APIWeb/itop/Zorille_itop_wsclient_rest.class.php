<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\itop;

use stdClass;
use Zorille\framework as Core;
use Exception as Exception;
use Zorille\framework\gestion_connexion_url;
use Zorille\framework\options;

/**
 * class wsclient_rest<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage itop
 */
class wsclient_rest extends Core\wsclient {
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
	 * @var array.
	 */
	private $defaultParams = array ();

	private array $pagination = [
		'page' => 1,
		'limit' => 0
	];

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type wsclient_rest. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param datas &$datas Reference sur un objet datas
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return wsclient_rest
	 * @throws Exception
	 */
	static function &creer_wsclient_rest(
		Core\options &$liste_option,
		datas        &$datas,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): wsclient_rest
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new wsclient_rest ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"datas" => $datas
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return wsclient_rest|bool
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		if (! isset ( $liste_class ["datas"] )) {
			$this->onError ( "il faut un objet de type datas" );
			$return = false;
			return $return;
		}
		$this->setObjetItopdatas ( $liste_class ["datas"] )
			->setContentType ( 'plain/text' )
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
	 * Prepare l'url de connexion au itop nomme $nom
	 * @param string $nom
	 * @return bool|wsclient_rest wsclient_rest
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_itop = $this->getObjetItopdatas ()
			->valide_presence_data ( $nom, 'rest' );
		if (!$liste_data_itop) {
			return $this->onError ( "Aucune definition de itop pour " . $nom );
		}
		if (! isset ( $liste_data_itop ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres itop" );
		}
		if (! isset ( $liste_data_itop ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres itop" );
		}
		if (! isset ( $liste_data_itop ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres itop" );
		}
		$this->getGestionConnexionUrl ()
			->retrouve_connexion_params ( $liste_data_itop )
			->prepare_prepend_url ( $liste_data_itop ["url"] );
		$this->setAuth ( array (
				'auth_user' => $liste_data_itop ["username"],
				'auth_pwd' => $liste_data_itop ["password"]
		) );
		return $this;
	}

	/**
	 * Creation d'entete HTTP standard
	 * @return wsclient_rest
	 */
	public function prepare_html_entete(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->setHttpHeader ( array (
				"Accept: " . $this->getAccept ()
		) );
	}

	/**
	 * Sends are prepare_requete_json to the itop API and returns the response as object.
	 *
	 * @param null $params array Additional parameters.
	 * @return array|string
	 * @throws Exception
	 */
	public function prepare_requete_json(
			$params = NULL): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setHttpMethod ( "POST" );
		// build prepare_requete_json array
		$prepare_requete_json ['version'] = '1.3';
		$prepare_requete_json = array_merge ( $prepare_requete_json, $this->getAuth () );
		$prepare_requete_json ['json_data'] = json_encode ( $params );
		if ($this->getListeOptions ()
			->verifie_option_existe ( "dry-run" ) !== false && preg_match ( "/.*create$|.*delete$|.*update|.*apply_stimulus$/", $params ['operation'] ) === 1) {
			$this->onInfo ( "DRY RUN :" . print_r ( $params, true ) );
			$this->onDebug ( "DRY RUN :" . print_r ( $prepare_requete_json, true ), 1 );
			return '';
		} else {
			$debug_tableau = $prepare_requete_json;
			$debug_tableau ['auth_pwd'] = '******************************';
			$this->onDebug ( $debug_tableau, 2 );
			$this->setPostDatas ( $prepare_requete_json );
			$retour_json = $this->prepare_html_entete ()
				->envoi_requete ();
            if (str_contains($retour_json, "Disk full")) {
                throw new Exception("Disk size too low");
            }
			$retour = $this->traite_retour_json ( $retour_json );
			$this->onDebug ( $retour, 2 );
			if (is_array ( $retour )) {
				$error = true;
				if (str_contains($retour ["message"], 'is not writable because it is mastered by the data synchronization')) {
					$error = false;
				}
				if (isset ( $retour ["code"] ) && $retour ["code"] != 0 && $error) {
					return $this->onError ( $retour ["message"], "", $retour ["code"] );
				}
				return $retour;
			}
		}
		return $this->onError ( "Pas de retour du Webservice" );
	}

	/**
	 * *********************** API itop **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Requests the itop API and returns the response of the API method action.get.
	 * The $params Array can be used, to pass through params to the itop API. For more informations about this params, check the itop API Documentation.
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an associatve instead of an indexed array as response. A valid value for this $arrayKeyProperty is any property of the returned JSON objects (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @return array|string
	 * @throws Exception
	 */
	public function list_operations(): array|stdClass|string
	{
		$this->onDebug ( __METHOD__, 1 );
		// prepare_requete_json
		return $this->prepare_requete_json ( array (
				'operation' => 'list_operations'
		) );
	}

	/**
	 * @codeCoverageIgnore
	 * Reqeusts the itop API and returns the response of the API method action.get.
	 * The $params Array can be used, to pass through params to the itop API. For more informations about this params, check the itop API Documentation.
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an associatve instead of an indexed array as response. A valid value for this $arrayKeyProperty is any property of the returned JSON objects (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param $class
	 * @param string $comment
	 * @param array $fields
	 * @return array|string
	 * @throws Exception
	 */
	public function core_create(
		$class,
		string $comment = '',
		array $fields = array ()): array|stdClass|string
	{
		$this->onDebug ( __METHOD__, 1 );
		// prepare_requete_json
		return $this->prepare_requete_json ( array (
				'operation' => 'core/create',
				'comment' => $comment,
				'class' => $class,
				'output_fields' => '*',
				'fields' => $fields
		) );
	}

	/**
	 * @codeCoverageIgnore
	 * Reqeusts the itop API and returns the response of the API method action.get.
	 * The $params Array can be used, to pass through params to the itop API. For more informations about this params, check the itop API Documentation.
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an associatve instead of an indexed array as response. A valid value for this $arrayKeyProperty is any property of the returned JSON objects (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param $class
	 * @param $key
	 * @param string $output_fields
	 * @return array|string
	 * @throws Exception
	 */
	public function core_get(
		$class,
		$key,
		string $output_fields = '*',
        bool $force_pagination_reset = true
    ): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
        if ($force_pagination_reset) {
            $this->pagination['page'] = 1;
        }

		if ($this->pagination['limit'] !== 0) {
			// prepare_requete_json
			return $this->prepare_requete_json ( array (
				'operation' => 'core/get',
				'class' => $class,
				'key' => $key,
				'output_fields' => $output_fields,
				'limit' => $this->pagination['limit'],
				'page' => $this->pagination['page']
			) );
		}

		// prepare_requete_json
		return $this->prepare_requete_json ( array (
				'operation' => 'core/get',
				'class' => $class,
				'key' => $key,
				'output_fields' => $output_fields
		) );
	}

	/**
	 * @codeCoverageIgnore
	 * Reqeusts the itop API and returns the response of the API method action.get.
	 * The $params Array can be used, to pass through params to the itop API. For more informations about this params, check the itop API Documentation.
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an associatve instead of an indexed array as response. A valid value for this $arrayKeyProperty is any property of the returned JSON objects (e.g. name, host, hostid, graphid, screenitemid).
	 * @param string $class
	 * @param string $stimulus
	 * @param int|string $key
	 * @param string|string[] $fields
	 * @param string $output_fields
	 * @param string $comment
	 * @return array|string
	 * @throws Exception
	 */
	public function core_stimulus(
		string     $class,
		string     $stimulus,
		int|string $key, array|string $fields,
		string     $output_fields = '*',
		string     $comment = ''): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		// prepare_requete_json
		return $this->prepare_requete_json ( array (
			'operation' => 'core/apply_stimulus',
			"stimulus" => $stimulus,
			'comment' => $comment,
			'class' => $class,
			'key' => $key,
			'output_fields' => $output_fields,
			'fields' => $fields
		) );
	}

	/**
	 * @codeCoverageIgnore
	 * Reqeusts the itop API and returns the response of the API method action.get.
	 * The $params Array can be used, to pass through params to the itop API. For more informations about this params, check the itop API Documentation.
	 * The $arrayKeyProperty is "PHP-internal" and can be used, to get an associatve instead of an indexed array as response. A valid value for this $arrayKeyProperty is any property of the returned JSON objects (e.g. name, host, hostid, graphid, screenitemid).
	 *
	 * @param $class
	 * @param $key
	 * @param $fields
	 * @param string $output_fields
	 * @param string $comment
	 * @return array|string
	 * @throws Exception
	 */
	public function core_update(
		$class,
		$key,
		$fields,
		string $output_fields = '*',
		string $comment = ''): array|string
	{
		$this->onDebug ( __METHOD__, 1 );
		// prepare_requete_json
		return $this->prepare_requete_json ( array (
				'operation' => 'core/update',
				'comment' => $comment,
				'class' => $class,
				'key' => $key,
				'output_fields' => $output_fields,
				'fields' => $fields
		) );
	}

	/**
	 * *********************** API itop **********************
	 */
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return datas
	 */
	public function &getObjetItopdatas(): ?datas
	{
		return $this->datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopdatas(
			&$datas): static
	{
		$this->datas = $datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string|array
	 */
	public function getAuth(): string|array
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
	 * @codeCoverageIgnore @brief Returns the default params.
	 * @retval array Array with default params./
	 *	public function getDefaultParams() {
	 *		return $this->defaultParams;
	 *	}
	 *
	 *	/**
	 * @codeCoverageIgnore @brief Sets the default params.
	 *
	 * @param $defaultParams array with default params. @retval itopApiAbstract
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
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "itop Wsclient :";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run n'applique pas les changements";
		return array_merge ( $help, datas::help () );
	}

	public function paginate(int $page = 1, int $limit = 10): self
	{
		$this->pagination = [
			'page' => $page,
			'limit' => $limit
		];

		return $this;
	}
}
