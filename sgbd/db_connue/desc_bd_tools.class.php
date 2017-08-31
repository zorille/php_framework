<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class gestion_bd_tools<br>
 *
 * Gere la connexion a une base tools.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class desc_bd_tools extends gestion_definition_table {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type desc_bd_tools.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return desc_bd_tools
	 */
	static function &creer_desc_bd_tools(&$liste_option, $sort_en_erreur = true, $entete = __CLASS__) {
		$objet = new desc_bd_tools ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return desc_bd_tools
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Cree l'objet, prepare la valeur du sort_en_erreur et l'entete des logs.
	 *
	 * @param string $entete Entete a afficher dans les logs.
	 * @param string|bool $sort_en_erreur Prend les valeurs oui/non ou true/false.
	 */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->charge_table ();
		$this->charge_champs ();
	}

	private function charge_table() {
		$this->set_table ( 'get_cacti_info', "get_cacti_info" );
		$this->set_table ( 'hob_client', "hob_client" );
		$this->set_table ( 'hobinv_stars_ci', "hobinv_stars_ci" );
	}

	private function charge_champs() {
		$this->charge_champs_get_cacti_info ();
		$this->charge_champs_hob_client ();
		$this->charge_champs_hobinv_stars_ci ();
		
		return true;
	}

	private function charge_champs_get_cacti_info() {
		$this->set_champ ( "ip_id", "ip_id", "get_cacti_info", "numeric" );
		$this->set_champ ( "ci_id", "ci_id", "get_cacti_info", "numeric" );
		$this->set_champ ( "type_composant", "type_composant", "get_cacti_info", "text" );
		$this->set_champ ( "subtype_composant", "subtype_composant", "get_cacti_info", "text" );
		$this->set_champ ( "os_name", "os_name", "get_cacti_info", "text" );
		$this->set_champ ( "os_version", "os_version", "get_cacti_info", "text" );
		$this->set_champ ( "ci_name", "ci_name", "get_cacti_info", "text" );
		$this->set_champ ( "metro_billing", "metro_billing", "get_cacti_info", "text" );
		$this->set_champ ( "metro_statut", "metro_statut", "get_cacti_info", "text" );
		$this->set_champ ( "metro_typecompo", "metro_typecompo", "get_cacti_info", "text" );
		$this->set_champ ( "client", "client", "get_cacti_info", "text" );
		$this->set_champ ( "code_client", "code_client", "get_cacti_info", "text" );
		$this->set_champ ( "cacti_name", "cacti_name", "get_cacti_info", "text" );
		$this->set_champ ( "ip", "ip", "get_cacti_info", "text" );
		$this->set_champ ( "type_ip", "type_ip", "get_cacti_info", "numeric" );
		
		return true;
	}

	private function charge_champs_hob_client() {
		$this->set_champ ( "client_id", "client_id", "hob_client", "numeric" );
		$this->set_champ ( "client_name", "client_name", "hob_client", "text" );
		$this->set_champ ( "tools_id", "tools_id", "hob_client", "numeric" );
		$this->set_champ ( "tools_type", "tools_type", "hob_client", "text" );
		$this->set_champ ( "tools_name", "tools_name", "hob_client", "text" );
		
		return true;
	}

	private function charge_champs_hobinv_stars_ci() {
		$this->set_champ ( "CI_TYPE", "CI_TYPE", "hobinv_stars_ci", "text" );
		$this->set_champ ( "CI_SUB_TYPE", "CI_SUB_TYPE", "hobinv_stars_ci", "text" );
		$this->set_champ ( "CI_NAME", "CI_NAME", "hobinv_stars_ci", "text" );
		$this->set_champ ( "STARS_CUSTOMER", "STARS_CUSTOMER", "hobinv_stars_ci", "text" );
		$this->set_champ ( "CI_STATUS", "CI_STATUS", "hobinv_stars_ci", "text" );
		$this->set_champ ( "SERVICE_LEVEL", "SERVICE_LEVEL", "hobinv_stars_ci", "text" );
		$this->set_champ ( "SERVICE_PERIODE", "SERVICE_PERIODE", "hobinv_stars_ci", "text" );
		$this->set_champ ( "SERVICE_DESCRIPTION", "SERVICE_DESCRIPTION", "hobinv_stars_ci", "text" );
		
		return true;
	}
}
?>