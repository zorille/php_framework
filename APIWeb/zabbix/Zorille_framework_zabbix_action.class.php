<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_action
 *
 * actionid 	string 	(readonly) ID of the action.
 * esc_period (required) 	integer 	Default operation step duration. Must be greater than 60 seconds.
 * evaltype (required) 	integer 	Action condition evaluation method.
 * 		Possible values:
 * 		0 - AND / OR;
 * 		1 - AND;
 * 		2 - OR.
 * 
 * eventsource (required) 	integer 	(constant) Type of events that the action will handle.
 * 		Refer to the event "source" property for a list of supported event types.
 * name (required) 	string 	Name of the action.
 * def_longdata 	string 	Problem message text.
 * def_shortdata 	string 	Problem message subject.
 * r_longdata 	string 	Recovery message text.
 * r_shortdata 	string 	Recovery message subject.
 * recovery_msg 	integer 	Whether recovery messages are enabled.
 * 		Possible values:
 * 		0 - (default) disabled;
 * 		1 - enabled.
 * 
 * status 	integer 	Whether the action is enabled or disabled.
 * 		Possible values:
 * 		0 - (default) enabled;
 * 		1 - disabled.
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_action extends zabbix_fonctions_standard {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $actionid = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $esc_period = 3600;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $evaltype = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $eventsource = "0";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $name = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $def_longdata = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $def_shortdata = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $r_longdata = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $r_shortdata = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $recovery_msg = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $status = 1;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $conditions = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $operations = array ();
	/**
	 * var privee
	 * @access private
	 * @var zabbix_action_condition
	 */
	private $action_condition = null;
	/**
	 * var privee
	 * @access private
	 * @var zabbix_action_operation
	 */
	private $action_operation = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_action.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_action
	 */
	static function &creer_zabbix_action(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_action ( $sort_en_erreur,$entete  );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjetZabbixWsclient ( $liste_class ["zabbix_wsclient"] )
			->setObjetActionConditionRef ( zabbix_action_condition::creer_zabbix_action_condition ( $liste_class ["options"] ) )
			->setObjetActionOperationRef ( zabbix_action_operation::creer_zabbix_action_operation ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($sort_en_erreur = false,$entete = __CLASS__ ) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return zabbix_action_operation
	 * @throws Exception
	 */
	public function retrouve_zabbix_param($nom_seulement = false) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setName ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"name" 
		) ) );
		if ($nom_seulement === false) {
			$this->setEscPeriod ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"esc_period" 
			), 3600 ) );
			$this->setEvalType ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"evaltype" 
			), "and/or" ) );
			$this->setEventSource ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"eventsource" 
			), 0 ) );
			$this->setDefLongdata ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"def_longdata" 
			) ) );
			$this->setDefShortdata ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"def_shortdata" 
			) ) );
			$this->setRLongData ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"r_longdata" 
			), "" ) );
			$this->setRShortData ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"r_shortdata" 
			), "" ) );
			$this->setRecoveryMsg ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"recovery_msg" 
			), "disabled" ) );
			$this->setStatus ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"status" 
			), "enabled" ) );
			
			$opconditions = $this->_valideOption ( array (
					"zabbix",
					"action",
					"conditions" 
			) );
			if (! is_array ( $opconditions )) {
				$opconditions = array (
						$opconditions 
				);
			}
			
			foreach ( $opconditions as $condition ) {
				$objet_condition = clone $this->getObjetActionConditionRef ();
				$objet_condition->retrouve_zabbix_param ( $this->getEventSource (), $condition );
				$this->setAjoutConditions ( $objet_condition );
			}
			
			//On prend qu'une operation a la fois
			$objet_operation = clone $this->getObjetActionOperationRef ();
			$objet_operation->retrouve_zabbix_param ();
			$this->setAjoutOperations ( $objet_operation );
		}
		
		return $this;
	}

	/**
	 * Creer un definition de l'action sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_action_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$action = array (
				"esc_period" => $this->getEscPeriod (),
				"evaltype" => $this->getEvalType (),
				"eventsource" => $this->getEventSource (),
				"name" => $this->getName (),
				"def_longdata" => $this->getDefLongdata (),
				"def_shortdata" => $this->getDefShortdata (),
				"r_longdata" => $this->getRLongData (),
				"r_shortdata" => $this->getRShortData (),
				"recovery_msg" => $this->getRecoveryMsg (),
				"status" => $this->getStatus () 
		);
		$action = array_merge ( $action, $this->creer_definition_action_conditions_ws () );
		$action = array_merge ( $action, $this->creer_definition_action_operations_ws () );
		if ($this->getActionId () != "") {
			$action ["actionid"] = $this->getActionId ();
		}
		
		return $action;
	}

	/**
	 * Creer un definition de l'action conditions sous forme de tableau
	 * @return array;
	 * @throws Exception
	 */
	public function creer_definition_action_conditions_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$conditions = array (
				"conditions" => array () 
		);
		foreach ( $this->getConditions () as $condition ) {
			$conditions ["conditions"] [count ( $conditions ["conditions"] )] = $condition->creer_definition_action_condition_ws ();
		}
		
		return $conditions;
	}

	/**
	 * Creer un definition de l'action operation sous forme de tableau
	 * @return array;
	 * @throws Exception
	 */
	public function creer_definition_action_operations_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$operations = array (
				"operations" => array () 
		);
		foreach ( $this->getOperations () as $condition ) {
			$operations ["operations"] [count ( $operations ["operations"] )] = $condition->creer_definition_action_operation_ws ();
		}
		
		return $operations;
	}

	/**
	 * Creer un action dans zabbix
	 * @return array
	 */
	public function creer_action() {
		$this->onDebug ( __METHOD__, 1 );
		$actiondata = $this->creer_definition_action_ws ();
		$this->onDebug ( $actiondata, 1 );
		return $this->getObjetZabbixWsclient ()
			->actionCreate ( $actiondata );
	}

	/**
	 * Creer un definition d'un actionId sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_action_delete_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$actionid = array ();
		
		if ($this->getActionId () != "") {
			$actionid [] .= $this->getActionId ();
		}
		
		return $actionid;
	}

	/**
	 * supprime une action dans zabbix
	 * @return array
	 */
	public function supprime_action() {
		$this->onDebug ( __METHOD__, 1 );
		$actiondata = $this->creer_definition_action_delete_ws ();
		$this->onDebug ( $actiondata, 1 );
		return $this->getObjetZabbixWsclient ()
			->actionDelete ( $actiondata );
	}

	/**
	 * Creer un definition de recherche d'une action par son nom sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_actionByName_get_ws() {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"output" => "actionid",
				"filter" => array (
						"name" => $this->getName () 
				) 
		);
	}

	/**
	 * recherche une action dans zabbix a partir de son nom
	 * @return zabbix_action
	 */
	public function recherche_actionid_by_Name() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_actionByName_get_ws ();
		$this->onDebug ( $datas, 1 );
		$liste_resultat = $this->getObjetZabbixWsclient ()
			->actionGet ( $datas );
		if (isset ( $liste_resultat [0] ) && isset ( $liste_resultat [0] ["actionid"] )) {
			$this->setActionId ( $liste_resultat [0] ["actionid"] );
		}
		
		return $this;
	}

	/**
	 * Possible values for trigger events:
	 * 0 - trigger.
	 * Possible values for discovery events:
	 * 1 - discovered host;
	 * 2 - discovered service.
	 * Possible values for auto-registration events:
	 * 3 - auto-registered host.
	 * Possible values for internal events:
	 * 0 - trigger;
	 * 4 - item;
	 * 5 - LLD rule.
	 * @param string $type
	 * @return number
	 */
	public function retrouve_EventSource($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "discovered host" :
				return 1;
				break;
			case "discovered service" :
				return 2;
				break;
			case "auto-registered host" :
				return 3;
				break;
			case "item" :
				return 4;
				break;
			case "lld rule" :
				return 5;
				break;
			case "trigger" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - AND / OR;
 	 * 1 - AND;
 	 * 2 - OR.
	 * @param string $type
	 * @return number
	 */
	public function retrouve_EvalType($evaltype) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $evaltype )) {
			return $evaltype;
		}
		switch (strtolower ( $evaltype )) {
			case "and" :
				return 1;
				break;
			case "or" :
				return 2;
				break;
			case "and/or" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - enabled;
	 * 1 - disabled;
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Status($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "disabled" :
				return 1;
				break;
			case "enabled" :
			default :
				return 0;
		}
		
		return 0;
	}

	/**
	 * 0 - disabled;
	 * 1 - enabled;
	 * @param string $type
	 * @return number
	 */
	public function retrouve_RecoveryMsg($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "enabled" :
				return 1;
				break;
			case "disabled" :
			default :
				return 0;
		}
		
		return 0;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getActionId() {
		return $this->actionid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setActionId($actionid) {
		$this->actionid = $actionid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getEscPeriod() {
		return $this->esc_period;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEscPeriod($esc_period) {
		$this->esc_period = $esc_period;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getEvalType() {
		return $this->evaltype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEvalType($evaltype) {
		$this->evaltype = $this->retrouve_EvalType ( $evaltype );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getEventSource() {
		return $this->eventsource;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEventSource($eventsource) {
		$this->eventsource = $this->retrouve_EventSource ( $eventsource );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDefLongdata() {
		return $this->def_longdata;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDefLongdata($def_longdata) {
		$this->def_longdata = $def_longdata;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDefShortdata() {
		return $this->def_shortdata;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDefShortdata($def_shortdata) {
		$this->def_shortdata = $def_shortdata;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRLongData() {
		return $this->r_longdata;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRLongData($r_longdata) {
		$this->r_longdata = $r_longdata;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRShortData() {
		return $this->r_shortdata;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRShortData($r_shortdata) {
		$this->r_shortdata = $r_shortdata;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRecoveryMsg() {
		return $this->recovery_msg;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRecoveryMsg($recovery_msg) {
		$this->recovery_msg = $this->retrouve_RecoveryMsg ( $recovery_msg );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setStatus($status) {
		$this->status = $this->retrouve_Status ( $status );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getConditions() {
		return $this->conditions;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConditions($conditions) {
		$this->conditions = $conditions;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutConditions(&$condition) {
		$this->conditions [count ( $this->conditions )] = $condition;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOperations() {
		return $this->operations;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOperations($operations) {
		$this->operations = $operations;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutOperations(&$operation) {
		$this->operations [count ( $this->operations )] = $operation;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetActionConditionRef() {
		return $this->action_condition;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_action_condition
	 */
	public function &setObjetActionConditionRef($action_condition) {
		$this->action_condition = $action_condition;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetActionOperationRef() {
		return $this->action_operation;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_action_operation
	 */
	public function &setObjetActionOperationRef($action_operation) {
		$this->action_operation = $action_operation;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Action :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_name '' Nom de l'action";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_esc_period 3600 par defaut";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_evaltype 'AND/OR' par defaut ou AND ou OR";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_eventsource '' Type d'eventSource :";
		$help [__CLASS__] ["text"] [] .= "	trigger|discovered host|discovered service|auto-registered host|item|lld rule";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_def_longdata '' Message en cas de probleme";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_def_shortdata ''  Titre du message d'erreur";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_recovery_msg 'disabled'  active ou pas le recovery message enabled|disabled";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_r_longdata '' Message en cas de recovery";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_r_shortdata '' Titre du message de recovery";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_status 'enabled'  active ou pas l'action enabled|disabled";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_conditions 'type|operator|value' '' liste des conditions (voir le help de zabbix_action_condition )";
		$help  = array_merge ( $help , zabbix_action_condition::help () );
		$help  = array_merge ( $help , zabbix_action_operation::help () );
		
		return $help;
	}
}
?>
