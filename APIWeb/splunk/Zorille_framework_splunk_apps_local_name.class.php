<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class splunk_apps_local_name
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_apps_local_name extends splunk_apps_local {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $app_name = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_apps_local_name. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string $app_name Name de l'instance a traiter
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_apps_local_name
	 */
	static function &creer_splunk_apps_local_name(&$liste_option, &$splunk_webservice_rest, $app_name, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_apps_local_name ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest, 
				'app_name' => $app_name ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_apps_local_name
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->setAppName ( $liste_class ['app_name'] ) 
			->reset_resource ();
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return splunk_apps_local_name
	 */
	public function &reset_resource() {
		$this ->valide_app_name();
		return parent::reset_resource () ->addResource ( $this ->getAppName() );
	}

	/**
	 * Valid if app_name is not empty. Send Exception if false.
	 * @return splunk_apps_local_name
	 * @throws  Exception
	 */
	public function valide_app_name() {
		if (empty ( $this ->getAppName () )) {
			$this ->onError ( "Il faut un app_name pour travailler" );
		}
		
		return $this;
	}

	/**
	 * Resource: apps/local/{name}
	 *   Method: Get
	 * List information about the {name} app. 
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getNamedLocalApps($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource() 
			->get ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getAppName() {
		return $this->app_name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAppName($app_name) {
		$this->app_name = $app_name;
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "splunk_apps_local_name :";
		
		return $help;
	}
}
?>
