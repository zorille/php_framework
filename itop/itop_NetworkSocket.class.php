<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_NetworkSocket
 *
 * @package Lib
 * @subpackage itop
 */
class itop_NetworkSocket extends itop_ci {
	
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_NetworkSocket. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_webservice_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_NetworkSocket
	 */
	static function &creer_itop_NetworkSocket(&$liste_option, &$itop_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_NetworkSocket ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"itop_wsclient_rest" => $itop_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_NetworkSocket
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

	public function creer_oql($name) {
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
		$help [__CLASS__] ["text"] [] .= "itop_NetworkSocket :";
		
		return $help;
	}
}
?>
