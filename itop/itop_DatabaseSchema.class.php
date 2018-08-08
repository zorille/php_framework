<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_DatabaseSchema
 *
 * @package Lib
 * @subpackage itop
 */
class itop_DatabaseSchema extends itop_FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_Server
	 */
	private $itop_Server = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_DatabaseSchema. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_wsclient_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_DatabaseSchema
	 */
	static function &creer_itop_DatabaseSchema(&$liste_option, &$itop_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_DatabaseSchema ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"itop_wsclient_rest" => $itop_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_DatabaseSchema
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'DatabaseSchema' ) 
			->setObjetItopOrganization ( itop_Organization::creer_itop_Organization ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) ) 
			->setObjetItopServer ( itop_Server::creer_itop_Server ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) );
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
	 * @return itop_DatabaseSchema
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
	 * @return itop_Server
	 */
	public function &getObjetItopServer() {
		return $this->itop_Server;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopServer(&$itop_Server) {
		$this->itop_Server = $itop_Server;
		
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
		$help [__CLASS__] ["text"] [] .= "itop_DatabaseSchema :";
		
		return $help;
	}
}
?>
