<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
/**
 * class splunk_services_admin
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_services_admin extends splunk_services {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_services_admin. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_services_admin
	 */
	static function &creer_splunk_services_admin(&$liste_option, &$splunk_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_services_admin ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet 
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_services_admin
	 */
	public function &_initialise($liste_class) {
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
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return splunk_services_admin
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'admin' );
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
		$help [__CLASS__] ["text"] [] .= "splunk_services_admin :";
		
		return $help;
	}
}
?>
