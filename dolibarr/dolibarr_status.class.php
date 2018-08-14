<?php
/**
 * Gestion de dolibarr.
 * @author dvargas
 */
/**
 * class dolibarr_status
 *
 * @package Lib
 * @subpackage dolibarr
 */
class dolibarr_status extends dolibarr_ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type dolibarr_status. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param dolibarr_wsclient $dolibarr_webservice_rest Reference sur un objet dolibarr_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return dolibarr_status
	 */
	static function &creer_dolibarr_status(&$liste_option, &$dolibarr_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new dolibarr_status ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"dolibarr_wsclient" => $dolibarr_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet 
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return dolibarr_status
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
	 * @return dolibarr_status
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'status' );
	}

	/**
	 * Resource: status
	 *   Method: Get 
	 * Get details of all current searches.  
	 * 
	 * @codeCoverageIgnore
	 * @param   array $params				Request Parameters
	 * @throws  Exception
	 */
	public function getStatus($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource () 
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
		$help [__CLASS__] ["text"] [] .= "dolibarr_status :";
		
		return $help;
	}
}
?>
