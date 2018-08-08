<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_ServiceSubcategory
 *
 * @package Lib
 * @subpackage itop
 */
class itop_ServiceSubcategory extends itop_ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_Organization
	 */
	private $itop_Organization = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_Service
	 */
	private $itop_Service = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Instancie un objet de type itop_ServiceSubcategory.
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_wsclient_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_ServiceSubcategory
	 */
	static function &creer_itop_ServiceSubcategory(&$liste_option, &$itop_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_ServiceSubcategory ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"itop_wsclient_rest" => $itop_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * @codeCoverageIgnore
	 * Initialisation de l'objet 
	 * @param array $liste_class
	 * @return itop_ServiceSubcategory
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'ServiceSubcategory' ) 
			->setObjetItopOrganization ( itop_Organization::creer_itop_Organization ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) );
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
	 * @return itop_ServiceSubcategory
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
	 * @return itop_Organization
	 */
	public function &getObjetItopOrganization() {
		return $this->itop_Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization(&$itop_Organization) {
		$this->itop_Organization = $itop_Organization;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return itop_Service
	 */
	public function &getObjetItopService() {
		return $this->itop_Service;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopService(&$itop_Service) {
		$this->itop_Service = $itop_Service;
		
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
		$help [__CLASS__] ["text"] [] .= "itop_ServiceSubcategory :";
		
		return $help;
	}
}
?>
