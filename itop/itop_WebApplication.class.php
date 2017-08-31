<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_WebApplication
 *
 * @package Lib
 * @subpackage itop
 */
class itop_WebApplication extends itop_FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_WebServer
	 */
	private $itop_WebServer = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_WebApplication. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_webservice_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_WebApplication
	 */
	static function &creer_itop_WebApplication(&$liste_option, &$itop_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_WebApplication ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"itop_wsclient_rest" => $itop_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_WebApplication
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'WebApplication' ) 
			->setObjetItopOrganization ( itop_Organization::creer_itop_Organization ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) ) 
			->setObjetItopWebServer ( itop_WebServer::creer_itop_WebServer ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) );
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
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_WebApplication($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	public function creer_oql($name) {
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . " WHERE name='" . $name . "'" );
	}

	public function gestion_WebApplication($name, $org_name, $webserver_friendlyname, $business_criticity, $move2production) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'name' => $name, 
				'org_id' => $this ->getObjetItopOrganization () 
					->creer_oql ( $org_name ) 
					->getOqlCi (), 
				'webserver_id' => $this ->getObjetItopWebServer () 
					->creer_oql ( $webserver_friendlyname ) 
					->getOqlCi (), 
				'business_criticity' => $business_criticity, 
				'move2production' => $move2production );
		
		$this ->creer_oql ( $name ) 
			->creer_ci ( $name, $params );
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * @codeCoverageIgnore
	 * @return itop_WebServer
	 */
	public function &getObjetItopWebServer() {
		return $this->itop_WebServer;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopWebServer(&$itop_WebServer) {
		$this->itop_WebServer = $itop_WebServer;
		
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
		$help [__CLASS__] ["text"] [] .= "itop_WebApplication :";
		
		return $help;
	}
}
?>
