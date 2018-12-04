<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class NetworkSocket
 *
 * @package Lib
 * @subpackage itop
 */
class NetworkSocket extends ci {
	
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type NetworkSocket. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return NetworkSocket
	 */
	static function &creer_NetworkSocket(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new NetworkSocket ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return NetworkSocket
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'NetworkSocket' );
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

	public function retrouve_NetworkSocket($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	/**
	 *
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return NetworkSocket
	 */
	public function creer_oql (
	    $name,
	    $fields = array()) {
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . " WHERE name='" . $name . "'" );
	}

	public function gestion_NetworkSocket($name, $socketvalue, $socketprotocol, $server_name, $software_friendlyname, $logicalinterface_friendlyname) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'application_id' => "SELECT FunctionalCI WHERE friendlyname = \"" . $software_friendlyname . '"', 
				'name' => $name, 
				'socketvalue' => $socketvalue, 
				'socketprotocol' => $socketprotocol );
		if ($logicalinterface_friendlyname !== '') {
			$params ['networkinterface_id'] = 'SELECT IPInterface WHERE IPInterface.friendlyname = "' . $logicalinterface_friendlyname . '"';
		}
		
		$this ->creer_oql ( $name ) 
			->creer_ci ( $name, $params );
		
		return $this;
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
		$help [__CLASS__] ["text"] [] .= "NetworkSocket :";
		
		return $help;
	}
}
?>
