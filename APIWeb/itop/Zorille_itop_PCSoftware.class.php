<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class PCSoftware
 *
 * @package Lib
 * @subpackage itop
 */
class PCSoftware extends FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Software
	 */
	private $Software = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type PCSoftware. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return PCSoftware
	 */
	static function &creer_PCSoftware(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new PCSoftware ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return PCSoftware
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'PCSoftware' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) ) 
			->setObjetItopSoftware ( Software::creer_Software ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_PCSoftware($name, $server_name) {
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

	public function gestion_PCSoftware($name, $org_name, $status, $business_criticity, $server_name, $software_friendlyname, $path, $move2production) {
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
	 * @return Organization
	 */

	/**
	 * @codeCoverageIgnore
	 * @return Software
	 */
	public function &getObjetItopSoftware() {
		return $this->Software;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopSoftware(&$Software) {
		$this->Software = $Software;
		
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
		$help [__CLASS__] ["text"] [] .= "PCSoftware :";
		
		return $help;
	}
}
?>
