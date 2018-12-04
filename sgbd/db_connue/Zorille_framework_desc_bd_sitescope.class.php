<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class gestion_bd_sitescope<br>
 *
 * Gere la connexion a une base sitescope.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class desc_bd_sitescope extends gestion_definition_table {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type desc_bd_sitescope.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return desc_bd_sitescope
	 */
	static function &creer_desc_bd_sitescope(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new desc_bd_sitescope ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return desc_bd_sitescope
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
		
		$this->_chargeTable ();
		$this->charge_champs ();
	}

	private function _chargeTable() {
		$this->setTable ( 'alert', "alert" );
		$this->setTable ( 'alert_props', "alert_props" );
		$this->setTable ( 'ci', "ci" );
		$this->setTable ( 'credentials', "credentials" );
		$this->setTable ( 'histo_planning', "histo_planning" );
		$this->setTable ( 'itemRef', "itemRef" );
		$this->setTable ( 'leaf', "leaf" );
		$this->setTable ( 'multiRef', "multiRef" );
		$this->setTable ( 'planning', "planning" );
		$this->setTable ( 'preferences', "preferences" );
		$this->setTable ( 'props', "props" );
		$this->setTable ( 'runtime', "runtime" );
		$this->setTable ( 'schedules', "schedules" );
		$this->setTable ( 'serveur', "serveur" );
		$this->setTable ( 'sitescope_ci', "sitescope_ci" );
		$this->setTable ( 'tasks', "tasks" );
		$this->setTable ( 'tasks_elements', "tasks_elements" );
		$this->setTable ( 'servers_elements', "servers_elements" );
		$this->setTable ( 'tree', "tree" );
		$this->setTable ( 'vcenter', "vcenter" );
	}

	private function _chargeChamps () {
		$this->_chargeChampsAlert ();
		$this->_chargeChampsAlertProps ();
		$this->_chargeChampsCi ();
		$this->_chargeChampsCredentials ();
		$this->_chargeChampsHistoPlanning ();
		$this->_chargeChampsItemRef ();
		$this->_chargeChampsLeaf ();
		$this->_chargeChampsMultiRef ();
		$this->_chargeChampsPlanning ();
		$this->_chargeChampsPreferences ();
		$this->_chargeChampsProps ();
		$this->_chargeChampsRuntime ();
		$this->_chargeChampsSchedules ();
		$this->_chargeChampsServeur ();
		$this->_chargeChampsSitescopeCi ();
		$this->_chargeChampsTasks ();
		$this->_chargeChampsTasksElements ();
		$this->_chargeChampsServersElements ();
		$this->_chargeChampsTree ();
		$this->_chargeChampsVcenter ();
		
		return true;
	}

	private function _chargeChampsAlert() {
		$this->setChamp ( "id", "id", "alert", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "alert", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "alert", "text" );
		$this->setChamp ( "_name", "name", "alert", "text" );
		$this->setChamp ( "_fullpathname", "fullpathname", "alert", "text" );
		$this->setChamp ( "deleted", "deleted", "alert", "numeric" );
		
		return true;
	}

	private function _chargeChampsAlertProps() {
		$this->setChamp ( "id", "id", "alert_props", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "alert_props", "numeric" );
		$this->setChamp ( "alert_id", "alert_id", "alert_props", "numeric" );
		$this->setChamp ( "_key", "key", "alert_props", "text" );
		$this->setChamp ( "_value", "value", "alert_props", "text" );
		$this->setChamp ( "deleted", "deleted", "alert_props", "numeric" );
		
		return true;
	}

	private function _chargeChampsCi() {
		$this->setChamp ( "id", "id", "ci", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "ci", "numeric" );
		$this->setChamp ( "_name", "name", "ci", "text" );
		
		return true;
	}

	private function _chargeChampsCredentials() {
		$this->setChamp ( "id", "id", "credentials", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "credentials", "numeric" );
		$this->setChamp ( "type", "type", "credentials", "text" );
		$this->setChamp ( "name", "name", "credentials", "text" );
		$this->setChamp ( "_key", "key", "credentials", "text" );
		$this->setChamp ( "_value", "value", "credentials", "text" );
		
		return true;
	}

	private function _chargeChampsHistoPlanning() {
		$this->setChamp ( "histo_id", "histo_id", "histo_planning", "numeric" );
		$this->setChamp ( "id", "id", "histo_planning", "numeric" );
		$this->setChamp ( "task_id", "task_id", "histo_planning", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "histo_planning", "numeric" );
		$this->setChamp ( "_fullpathname", "fullpathname", "histo_planning", "text" );
		$this->setChamp ( "user", "user", "histo_planning", "text" );
		$this->setChamp ( "reason", "reason", "histo_planning", "text" );
		$this->setChamp ( "_fixed", "fixed", "histo_planning", "numeric" );
		$this->setChamp ( "duration", "duration", "histo_planning", "numeric" );
		$this->setChamp ( "_unit", "unit", "histo_planning", "text" );
		$this->setChamp ( "_until", "until", "histo_planning", "date" );
		$this->setChamp ( "_operation", "operation", "histo_planning", "text" );
		$this->setChamp ( "_type", "type", "histo_planning", "text" );
		$this->setChamp ( "_isgroup", "isgroup", "histo_planning", "numeric" );
		$this->setChamp ( "_immediate", "immediate", "histo_planning", "numeric" );
		$this->setChamp ( "_when", "when", "histo_planning", "date" );
		$this->setChamp ( "_done", "done", "histo_planning", "numeric" );
		$this->setChamp ( "_has_error", "has_error", "histo_planning", "numeric" );
		$this->setChamp ( "_error_log", "error_log", "histo_planning", "text" );
		$this->setChamp ( "customer", "customer", "histo_planning", "text" );
		
		return true;
	}

	private function _chargeChampsItemRef() {
		$this->setChamp ( "id", "id", "itemRef", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "itemRef", "numeric" );
		$this->setChamp ( "ref_id", "ref_id", "itemRef", "numeric" );
		$this->setChamp ( "item_key", "item_key", "itemRef", "text" );
		$this->setChamp ( "item_value", "item_value", "itemRef", "text" );
		
		return true;
	}

	private function _chargeChampsLeaf() {
		$this->setChamp ( "id", "id", "leaf", "text" );
		$this->setChamp ( "serveur_id", "serveur_id", "leaf", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "leaf", "text" );
		$this->setChamp ( "_name", "name", "leaf", "text" );
		$this->setChamp ( "deleted", "deleted", "leaf", "numeric" );
		
		return true;
	}

	private function _chargeChampsMultiRef() {
		$this->setChamp ( "id", "id", "multiRef", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "multiRef", "numeric" );
		$this->setChamp ( "ref_id", "ref_id", "multiRef", "text" );
		$this->setChamp ( "ref_key", "ref_key", "multiRef", "text" );
		$this->setChamp ( "ref_name", "ref_name", "multiRef", "text" );
		$this->setChamp ( "parent_ref_id", "parent_ref_id", "multiRef", "text" );
		
		return true;
	}

	private function _chargeChampsPlanning() {
		$this->setChamp ( "id", "id", "planning", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "planning", "numeric" );
		$this->setChamp ( "_fullpathname", "fullpathname", "planning", "text" );
		$this->setChamp ( "user", "user", "planning", "text" );
		$this->setChamp ( "reason", "reason", "planning", "text" );
		$this->setChamp ( "_fixed", "fixed", "planning", "numeric" );
		$this->setChamp ( "duration", "duration", "planning", "numeric" );
		$this->setChamp ( "_unit", "unit", "planning", "text" );
		$this->setChamp ( "_until", "until", "planning", "date" );
		$this->setChamp ( "_operation", "operation", "planning", "text" );
		$this->setChamp ( "_type", "type", "planning", "text" );
		$this->setChamp ( "_isgroup", "isgroup", "planning", "numeric" );
		$this->setChamp ( "_immediate", "immediate", "planning", "numeric" );
		$this->setChamp ( "_when", "when", "planning", "date" );
		$this->setChamp ( "_done", "done", "planning", "numeric" );
		$this->setChamp ( "_has_error", "has_error", "planning", "numeric" );
		$this->setChamp ( "source_id", "source_id", "planning", "text" );
		$this->setChamp ( "customer", "customer", "planning", "text" );
		$this->setChamp ( "orderby", "orderby", "planning", "numeric" );
		
		return true;
	}

	private function _chargeChampsPreferences() {
		$this->setChamp ( "id", "id", "preferences", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "preferences", "numeric" );
		$this->setChamp ( "type", "type", "preferences", "text" );
		$this->setChamp ( "_key", "key", "preferences", "text" );
		$this->setChamp ( "_value", "value", "preferences", "text" );
		
		return true;
	}

	private function _chargeChampsProps() {
		$this->setChamp ( "id", "id", "props", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "props", "text" );
		$this->setChamp ( "table_parent", "table_parent", "props", "text" );
		$this->setChamp ( "_key", "key", "props", "text" );
		$this->setChamp ( "_value", "value", "props", "text" );
		
		return true;
	}

	private function _chargeChampsRuntime() {
		$this->setChamp ( "id", "id", "runtime", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "runtime", "text" );
		$this->setChamp ( "table_parent", "table_parent", "runtime", "text" );
		$this->setChamp ( "_key", "key", "runtime", "text" );
		$this->setChamp ( "_value", "value", "runtime", "text" );
		
		return true;
	}

	private function _chargeChampsSchedules() {
		$this->setChamp ( "id", "id", "schedules", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "schedules", "numeric" );
		$this->setChamp ( "schedule_id", "schedule_id", "schedules", "text" );
		$this->setChamp ( "name", "name", "schedules", "text" );
		$this->setChamp ( "schedule", "schedule", "schedules", "text" );
		
		return true;
	}

	private function _chargeChampsServeur() {
		$this->setChamp ( "id", "id", "serveur", "numeric" );
		$this->setChamp ( "name", "name", "serveur", "text" );
		$this->setChamp ( "last_check", "last_check", "serveur", "date" );
		$this->setChamp ( "actif", "actif", "serveur", "numeric" );
		$this->setChamp ( "customer", "customer", "serveur", "text" );
		$this->setChamp ( "_doing", "doing", "serveur", "numeric" );
		$this->setChamp ( "_error", "error", "serveur", "numeric" );
		$this->setChamp ( "log_error", "log_error", "serveur", "text" );
		
		return true;
	}

	private function _chargeChampsSitescopeCi() {
		$this->setChamp ( "id", "id", "sitescope_ci", "numeric" );
		$this->setChamp ( "customer", "customer", "sitescope_ci", "text" );
		$this->setChamp ( "ci_name", "ci_name", "sitescope_ci", "text" );
		$this->setChamp ( "trouve", "trouve", "sitescope_ci", "numeric" );
		
		return true;
	}

	private function _chargeChampsTasks() {
		$this->setChamp ( "id", "id", "tasks", "numeric" );
		$this->setChamp ( "user", "user", "tasks", "text" );
		$this->setChamp ( "reason", "reason", "tasks", "text" );
		$this->setChamp ( "_fixed", "fixed", "tasks", "numeric" );
		$this->setChamp ( "duration", "duration", "tasks", "numeric" );
		$this->setChamp ( "_unit", "unit", "tasks", "text" );
		$this->setChamp ( "_until", "until", "tasks", "date" );
		$this->setChamp ( "_operation", "operation", "tasks", "text" );
		$this->setChamp ( "_type", "type", "tasks", "text" );
		$this->setChamp ( "_immediate", "immediate", "tasks", "numeric" );
		$this->setChamp ( "_when", "when", "tasks", "date" );
		$this->setChamp ( "_done", "done", "tasks", "numeric" );
		$this->setChamp ( "_schedule_name", "schedule_name", "tasks", "text" );
		$this->setChamp ( "_schedule_type", "schedule_type", "tasks", "text" );
		$this->setChamp ( "_schedule_cycle", "schedule_cycle", "tasks", "numeric" );
		$this->setChamp ( "_schedule_cycle_type", "schedule_cycle_type", "tasks", "text" );
		$this->setChamp ( "_schedule_times", "schedule_times", "tasks", "text" );
		$this->setChamp ( "_schedule_days_of_week", "schedule_days_of_week", "tasks", "text" );
		$this->setChamp ( "_schedule_days_of_month", "schedule_days_of_month", "tasks", "text" );
		$this->setChamp ( "_schedule_months_of_year", "schedule_months_of_year", "tasks", "text" );
		$this->setChamp ( "_schedule_enable", "schedule_enable", "tasks", "numeric" );
		$this->setChamp ( "_last_done", "last_done", "tasks", "date" );
		$this->setChamp ( "_last_check", "last_check", "tasks", "date" );
		$this->setChamp ( "customer", "customer", "tasks", "text" );
		
		return true;
	}

	private function _chargeChampsTasksElements() {
		$this->setChamp ( "ele_id", "ele_id", "tasks_elements", "numeric" );
		$this->setChamp ( "task_id", "task_id", "tasks_elements", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "tasks_elements", "numeric" );
		$this->setChamp ( "_fullpathname", "fullpathname", "tasks_elements", "text" );
		$this->setChamp ( "_isgroup", "isgroup", "tasks_elements", "numeric" );
		$this->setChamp ( "_has_error", "has_error", "tasks_elements", "numeric" );
		$this->setChamp ( "_error_log", "error_log", "tasks_elements", "text" );
		$this->setChamp ( "source_id", "source_id", "tasks_elements", "text" );
		$this->setChamp ( "not_exist", "not_exist", "tasks_elements", "numeric" );
		$this->setChamp ( "not_exist_since", "not_exist_since", "tasks_elements", "date" );
		
		return true;
	}
	
	private function _chargeChampsServersElements() {
		$this->setChamp ( "srv_id", "srv_id", "servers_elements", "numeric" );
		$this->setChamp ( "task_id", "task_id", "servers_elements", "numeric" );
		$this->setChamp ( "serveur_id", "serveur_id", "servers_elements", "numeric" );
		$this->setChamp ( "vcenter_id", "vcenter_id", "servers_elements", "numeric" );
		$this->setChamp ( "_fullpathname", "fullpathname", "servers_elements", "text" );
		$this->setChamp ( "poweron_order", "poweron_order", "servers_elements", "numeric" );
		$this->setChamp ( "poweroff_order", "poweroff_order", "servers_elements", "numeric" );
		$this->setChamp ( "_has_error", "has_error", "servers_elements", "numeric" );
		$this->setChamp ( "_error_log", "error_log", "servers_elements", "text" );
		$this->setChamp ( "source_id", "source_id", "servers_elements", "text" );
		$this->setChamp ( "not_exist", "not_exist", "servers_elements", "numeric" );
		$this->setChamp ( "not_exist_since", "not_exist_since", "servers_elements", "date" );
	
		return true;
	}

	private function _chargeChampsTree() {
		$this->setChamp ( "id", "id", "tree", "text" );
		$this->setChamp ( "serveur_id", "serveur_id", "tree", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "tree", "text" );
		$this->setChamp ( "_name", "name", "tree", "text" );
		$this->setChamp ( "_fullpathname", "fullpathname", "tree", "text" );
		
		return true;
	}
	
	private function _chargeChampsVcenter() {
		$this->setChamp ( "id", "id", "vcenter", "numeric" );
		$this->setChamp ( "name", "name", "vcenter", "text" );
		$this->setChamp ( "actif", "actif", "vcenter", "numeric" );
		$this->setChamp ( "customer", "customer", "vcenter", "text" );
	
		return true;
	}

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>