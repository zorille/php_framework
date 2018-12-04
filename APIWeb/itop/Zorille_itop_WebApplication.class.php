<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class WebApplication
 *
 * @package Lib
 * @subpackage itop
 */
class WebApplication extends FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var WebServer
	 */
	private $WebServer = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type WebApplication. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return WebApplication
	 */
	static function &creer_WebApplication(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new WebApplication ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return WebApplication
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'WebApplication' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) ) 
			->setObjetItopWebServer ( WebServer::creer_WebServer ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	/**
	 *
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return WebApplication
	 */
	public function creer_oql (
	    $name,
	    $fields = array()) {
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
	 * @return WebServer
	 */
	public function &getObjetItopWebServer() {
		return $this->WebServer;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopWebServer(&$WebServer) {
		$this->WebServer = $WebServer;
		
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
		$help [__CLASS__] ["text"] [] .= "WebApplication :";
		
		return $help;
	}
}
?>
