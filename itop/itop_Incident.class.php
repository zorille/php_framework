<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_Incident
 *
 * @package Lib
 * @subpackage itop
 */
class itop_Incident extends itop_ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_Organization
	 */
	private $itop_Organization = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_Incident. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_webservice_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_Incident
	 */
	static function &creer_itop_Incident(&$liste_option, &$itop_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_Incident ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"itop_wsclient_rest" => $itop_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_Incident
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'Incident' ) 
			->setObjetItopOrganization ( itop_Organization::creer_itop_Organization ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) );
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

	public function retrouve_Incident($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	public function creer_oql($name, $not_in_status = 'closed') {
		$where = " WHERE status NOT IN ('" . $not_in_status . "')";
		if (! empty ( $name )) {
			$where .= " AND title='" . $name . "'";
		}
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . $where );
	}

	public function gestion_Incident($title, $org_name, $description, $impact, $urgency, $origin='monitoring', $contacts_list = array(), $functionalcis_list = array(), $workorders_list = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'title' => $title, 
				'description' => $description, 
				'impact' => $impact, 
				'urgency' => $urgency, 
				'origin' => $origin );
		$params ['org_id'] = $this ->getObjetItopOrganization () 
			->creer_oql ( $org_name ) 
			->getOqlCi ();
		if (! empty ( $contacts_list )) {
			$params ['contacts_list'] = $contacts_list;
		}
		if (! empty ( $functionalcis_list )) {
			$params ['functionalcis_list'] = $functionalcis_list;
		}
		if (! empty ( $workorders_list )) {
			$params ['workorders_list'] = $workorders_list;
		}
		
		$this ->creer_oql ( $title ) 
			->creer_ci ( $title, $params );
		
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
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "itop_Incident :";
		
		return $help;
	}
}
?>
