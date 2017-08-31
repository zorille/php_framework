<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_DBServer
 *
 * @package Lib
 * @subpackage itop
 */
class itop_DBServer extends itop_FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_Software
	 */
	private $itop_Software = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_DBServer. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_webservice_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_DBServer
	 */
	static function &creer_itop_DBServer(&$liste_option, &$itop_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_DBServer ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"itop_wsclient_rest" => $itop_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_DBServer
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'DBServer' ) 
			->setObjetItopOrganization ( itop_Organization::creer_itop_Organization ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) ) 
			->setObjetItopSoftware ( itop_Software::creer_itop_Software ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) );
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

	public function retrouve_DBServer($name, $server_name) {
		return $this ->creer_oql ( $name, $server_name ) 
			->retrouve_ci ();
	}

	public function creer_oql($name, $server_name='') {
		if(empty($server_name)){
			$oql="SELECT " . $this ->getFormat () . " WHERE friendlyname='" . $name . "'";
		} else {
			$oql="SELECT " . $this ->getFormat () . " WHERE friendlyname='" . $name . " " . $server_name . "'";
		}
		return $this ->setOqlCi ( $oql );
	}

	public function gestion_DBServer($name, $org_name, $status, $business_criticity, $server_name, $software_friendlyname, $path, $move2production) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'name' => $name, 
				'org_id' => $this ->getObjetItopOrganization () 
					->creer_oql ( $org_name ) 
					->getOqlCi (), 
				'status' => $status, 
				'business_criticity' => $business_criticity, 
				'system_id' => 'SELECT FunctionalCI WHERE finalclass IN (\'Server\',\'VirtualMachine\',\'PC\') AND name = "' . $server_name . '"', 
				'software_id' => $this ->getObjetItopSoftware () 
					->creer_oql ( $software_friendlyname ) 
					->getOqlCi (), 
				'path' => $path, 
				'move2production' => $move2production );
		
		$this ->creer_oql ( $name, $server_name ) 
			->creer_ci ( $name . " " . $server_name, $params );
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * @codeCoverageIgnore
	 * @return itop_Software
	 */
	public function &getObjetItopSoftware() {
		return $this->itop_Software;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopSoftware(&$itop_Software) {
		$this->itop_Software = $itop_Software;
		
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
		$help [__CLASS__] ["text"] [] .= "itop_DBServer :";
		
		return $help;
	}
}
?>
