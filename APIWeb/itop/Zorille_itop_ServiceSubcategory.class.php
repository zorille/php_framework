<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class ServiceSubcategory
 *
 * @package Lib
 * @subpackage itop
 */
class ServiceSubcategory extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization
	 */
	private $Organization = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var Service
	 */
	private $Service = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Instancie un objet de type ServiceSubcategory.
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ServiceSubcategory
	 */
	static function &creer_ServiceSubcategory(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ServiceSubcategory ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * @codeCoverageIgnore
	 * Initialisation de l'objet 
	 * @param array $liste_class
	 * @return ServiceSubcategory
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'ServiceSubcategory' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. 
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_ServiceSubcategory($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	/**
	 *
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return ServiceSubcategory
	 */
	public function creer_oql (
	    $name,
	    $fields = array()) {
		$where = "";
		if (! empty ( $name )) {
			$where .= " WHERE name='" . $name . "'";
		}
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . $where );
	}

	public function gestion_ServiceSubcategory($serviceSubcategory_name, $service_name, $org_name, $status) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'name' => $serviceSubcategory_name, 
				'status' => $status );
		$params ['org_id'] = $this ->getObjetItopOrganization () 
			->creer_oql ( $org_name ) 
			->getOqlCi ();
		$params ['service_id'] = $this ->getObjetItopService () 
			->creer_oql ( $org_name ) 
			->getOqlCi ();
		
		$this ->creer_oql ( $serviceSubcategory_name ) 
			->creer_ci ( $serviceSubcategory_name, $params );
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopOrganization() {
		return $this->Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization(&$Organization) {
		$this->Organization = $Organization;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return Service
	 */
	public function &getObjetItopService() {
		return $this->Service;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopService(&$Service) {
		$this->Service = $Service;
		
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
		$help [__CLASS__] ["text"] [] .= "ServiceSubcategory :";
		
		return $help;
	}
}
?>
