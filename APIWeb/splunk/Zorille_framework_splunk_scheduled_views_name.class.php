<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class splunk_scheduled_views_name
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_scheduled_views_name extends splunk_scheduled_views {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $views_name = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_scheduled_views_name. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string $views_name Id de l'instance a traiter
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_scheduled_views_name
	 */
	static function &creer_splunk_scheduled_views_name(&$liste_option, &$splunk_webservice_rest, $views_name, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_scheduled_views_name ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest, 
				'views_name' => $views_name ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_scheduled_views_name
	 */
	public function &_initialise($liste_class) {
		$this ->setViewsName ( $liste_class ['views_name'] );
		parent::_initialise ( $liste_class );
		
		return $this ->reset_resource ();
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return splunk_scheduled_views_name
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return splunk_scheduled_views_name
	 * @throws  Exception
	 */
	public function &reset_resource() {
		$this ->valid_views_name ();
		return parent::reset_resource () ->addResource ( $this ->getViewsName () );
	}

	/**
	 * Valid if views_name is not empty. Send Exception if false.
	 * @return splunk_scheduled_views_name
	 * @throws  Exception
	 */
	public function valid_views_name() {
		if (empty ( $this ->getViewsName () )) {
			$this ->onError ( "Il faut un views_name pour travailler" );
		}
		
		return $this;
	}

	/**
	 * Resource: scheduled/views/{views_name}
	 *   Method: Get 
	 * Access a scheduled view 
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getScheduledViewsInformations($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$results = $this ->reset_resource () 
			->get ( $params );
		
		return $results;
	}

	/**
	 * Resource: scheduled/views/{views_name}/history
	 *   Method: Get 
	 * List search jobs used to render the {name} scheduled view
	 *
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getScheduledViewHistory($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource () 
			->addResource ( 'history' ) 
			->get ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getViewsName() {
		return $this->views_name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setViewsName($views_name) {
		$this->views_name = $views_name;
		
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
		$help [__CLASS__] ["text"] [] .= "splunk_scheduled_views_name :";
		
		return $help;
	}
}
?>
