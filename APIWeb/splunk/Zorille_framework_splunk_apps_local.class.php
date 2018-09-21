<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class splunk_apps_local
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_apps_local extends splunk_apps {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_apps_local. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_apps_local
	 */
	static function &creer_splunk_apps_local(&$liste_option, &$splunk_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_apps_local ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_apps_local
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		 return $this ->reset_resource();
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
	 * @return splunk_apps_local
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'local' );
	}

	/**
	 * Resource: apps/local
	 *   Method: Get 
	 * List installed apps and properties. 
	 * 
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getAllLocalApps($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results=$this ->reset_resource() 
			->get ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "splunk_apps_local :";
		
		return $help;
	}
}
?>
