<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class Server
 *
 * @package Lib
 * @subpackage itop
 */
class Server extends FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var OSFamily
	 */
	private $OSFamily = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var OSVersion
	 */
	private $OSVersion = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Server. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Server
	 */
	static function &creer_Server(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Server ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Server
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'Server' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) ) 
			->setObjetItopOSFamily ( OSFamily::creer_OSFamily ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) ) 
			->setObjetItopOSVersion ( OSVersion::creer_OSVersion ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_Server($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	/**
	 *
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Server
	 */
	public function creer_oql (
	    $name,
	    $fields = array()) {
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . " WHERE name='" . $name . "'" );
	}

	public function gestion_Server($server_name, $org_name, $os_type, $os_version, $status, $business_criticity, $managementip, $cpu, $mem, $move2production, $fqdn) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'org_id' => $this ->getObjetItopOrganization () 
					->creer_oql ( $org_name ) 
					->getOqlCi (), 
				'osfamily_id' => $this ->getObjetItopOSFamily () 
					->creer_oql ( $os_type ) 
					->getOqlCi (), 
				'name' => $server_name, 
				'status' => $status, 
				'business_criticity' => $business_criticity, 
				'managementip' => $managementip, 
				'cpu' => $cpu, 
				'ram' => $mem, 
				'move2production' => $move2production, 
				'description' => 'FQDN:' . $fqdn );
		
		if (! empty ( $os_version )) {
			$params ['osversion_id'] = $this ->getObjetItopOSVersion () 
				->creer_oql ( $os_version ) 
				->getOqlCi ();
		}
		return $this ->creer_oql ( $server_name ) 
			->creer_ci ( $server_name, $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * @codeCoverageIgnore
	 * @return OSFamily
	 */
	public function &getObjetItopOSFamily() {
		return $this->OSFamily;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOSFamily(&$OSFamily) {
		$this->OSFamily = $OSFamily;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return OSVersion
	 */
	public function &getObjetItopOSVersion() {
		return $this->OSVersion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOSVersion(&$OSVersion) {
		$this->OSVersion = $OSVersion;
		
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
		$help [__CLASS__] ["text"] [] .= "Server :";
		
		return $help;
	}
}
?>
