<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class DatabaseSchema
 *
 * @package Lib
 * @subpackage itop
 */
class DatabaseSchema extends FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Server
	 */
	private $Server = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type DatabaseSchema. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return DatabaseSchema
	 */
	static function &creer_DatabaseSchema(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new DatabaseSchema ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return DatabaseSchema
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'DatabaseSchema' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) ) 
			->setObjetItopServer ( Server::creer_Server ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_DatabaseSchema($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	/**
	 *
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return DatabaseSchema
	 */
	public function creer_oql (
	    $name,
	    $fields = array()) {
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . " WHERE friendlyname='" . $name . "'" );
	}

	public function gestion_DatabaseSchema($name, $org_name, $dbserver_name, $business_criticity, $move2production, $description) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'name' => $name, 
				'org_id' => $this ->getObjetItopOrganization () 
					->creer_oql ( $org_name ) 
					->getOqlCi (), 
				'dbserver_id' => $this ->getObjetItopServer () 
					->creer_oql ( $dbserver_name ) 
					->getOqlCi (), 
				'business_criticity' => $business_criticity, 
				'description' => $description, 
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
	 * @return Server
	 */
	public function &getObjetItopServer() {
		return $this->Server;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopServer(&$Server) {
		$this->Server = $Server;
		
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
		$help [__CLASS__] ["text"] [] .= "DatabaseSchema :";
		
		return $help;
	}
}
?>
