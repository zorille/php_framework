<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_action_condition
 *
 * conditionid 	string 	(readonly) ID of the action condition.
 * conditiontype (required) 	integer 	Type of condition.
 * 		Possible values for trigger actions:
 * 		0 - host group;
 * 		1 - host;
 * 		2 - trigger;
 * 		3 - trigger name;
 * 		4 - trigger severity;
 * 		5 - trigger value;
 * 		6 - time period;
 * 		13 - host template;
 * 		15 - application;
 * 		16 - maintenance status;
 * 		17 - node.
 * 
 * 		Possible values for discovery actions:
 * 		7 - host IP;
 * 		8 - discovered service type;
 * 		9 - discovered service port;
 * 		10 - discovery status;
 * 		11 - uptime or downtime duration;
 * 		12 - received value;
 * 		18 - discovery rule;
 * 		19 - discovery check;
 * 		20 - proxy;
 * 		21 - discovery object.
 * 
 * 		Possible values for auto-registration actions:
 * 		20 - proxy;
 * 		22 - host name;
 * 		24 - host metadata.
 * 
 * 		Possible values for internal actions:
 * 		0 - host group;
 * 		1 - host;
 * 		13 - host template;
 * 		15 - application;
 * 		23 - event type;
 * 		17 - node.
 * 
 * value (required) 	string 	Value to compare with.
 * actionid 	string 	(readonly) ID of the action that the condition belongs to.
 * operator 	integer 	Condition operator.
 * 		Possible values:
 * 		0 - (default) =;
 * 		1 - <>;
 * 		2 - like;
 * 		3 - not like;
 * 		4 - in;
 * 		5 - >=;
 * 		6 - <=;
 * 		7 - not in. 
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_action_condition extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $conditionid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $actionid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $conditiontype = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $value = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $operator = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_action_condition.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_action_condition
	 */
	static function &creer_zabbix_action_condition(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_action_condition ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
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
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @param integer $event_source Id du type d'evenement lie a l'action
	 * @param string $contition creation d'une condition a partir d'une string au format "type|operator|valeur"
	 * @return boolean True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param($event_source, $contition = false) {
		$this->onDebug ( __METHOD__, 1 );
		if ($contition !== false) {
			$liste_condition = explode ( "|", trim ( $contition ) );
			if ($liste_condition === false || count ( $liste_condition ) != 3) {
				return $this->onError ( "Parametre inutilisable : " . $contition );
			}
			$this->setConditionType ( $liste_condition [0], $event_source );
			$this->setOperator ( $liste_condition [1] );
			$this->setValue ( $liste_condition [2] );
		} else {
			$this->setConditionType ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"condition",
					"type" 
			) ), $event_source );
			$this->setOperator ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"condition",
					"operator" 
			), "=" ) );
			$this->setValue ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"condition",
					"value" 
			), "" ) );
		}
		
		return $this;
	}

	/**
	 * Creer un definition de condition sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_action_condition_ws() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getValue () == "") {
			return array ();
		}
		$condition = array (
				"conditiontype" => $this->getConditionType (),
				"operator" => $this->getOperator () 
		);
		if ($this->getConditionType () != 16) {
			$condition ["value"] = $this->getValue ();
		}
		if ($this->getConditionId () != "") {
			$condition ["conditionid"] = $this->getConditionId ();
			$condition ["actionid"] = $this->getActionId ();
		}
		
		return $condition;
	}

	/**
	 * Condition operator.
	 * 
	 * Possible values:
	 * 0 - (default) =;
	 * 1 - <>;
	 * 2 - like;
	 * 3 - not like;
	 * 4 - in;
	 * 5 - >=;
	 * 6 - <=;
	 * 7 - not in. 
	 * @param string $type
	 * @return number
	 */
	public function retrouve_ConditionOperator($operator) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $operator )) {
			return $operator;
		}
		switch (strtolower ( $operator )) {
			case "<>" :
				return 1;
				break;
			case "like" :
				return 2;
				break;
			case "not like" :
				return 3;
				break;
			case "in" :
				return 4;
				break;
			case ">=" :
				return 5;
				break;
			case "<=" :
				return 6;
				break;
			case "not in" :
				return 7;
				break;
			case "=" :
			default :
		}
		
		return 0;
	}

	/**
	 * conditiontype : Possible values for trigger actions:
	 * 0 - host group;
	 * 1 - host;
	 * 2 - trigger;
	 * 3 - trigger name;
	 * 4 - trigger severity;
	 * 5 - trigger value;
	 * 6 - time period;
	 * 13 - host template;
	 * 15 - application;
	 * 16 - maintenance status;
	 * 17 - node.
	 * 
	 * Possible values for discovery actions:
	 * 7 - host IP;
	 * 8 - discovered service type;
	 * 9 - discovered service port;
	 * 10 - discovery status;
	 * 11 - uptime or downtime duration;
	 * 12 - received value;
	 * 18 - discovery rule;
	 * 19 - discovery check;
	 * 20 - proxy;
	 * 21 - discovery object.
	 * 
	 * Possible values for auto-registration actions:
	 * 20 - proxy;
	 * 22 - host name;
	 * 24 - host metadata.
	 * 
	 * Possible values for internal actions:
	 * 0 - host group;
	 * 1 - host;
	 * 13 - host template;
	 * 15 - application;
	 * 23 - event type;
	 * 17 - node. 
	 * @param string $type
	 * @return number
	 */
	public function retrouve_ConditionType($type, $event_source) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		if ($event_source == 0) {
			switch (strtolower ( $type )) {
				case "host" :
					return 1;
					break;
				case "trigger" :
					return 2;
					break;
				case "trigger name" :
					return 3;
					break;
				case "trigger severity" :
					return 4;
					break;
				case "trigger value" :
					return 5;
					break;
				case "time period" :
					return 6;
					break;
				case "host template" :
					return 13;
					break;
				case "application" :
					return 15;
					break;
				case "maintenance status" :
					return 16;
					break;
				case "node" :
					return 17;
					break;
				case "host group" :
				default :
					return 0;
			}
		} elseif ($event_source == 1 || $event_source == 2) {
			switch (strtolower ( $type )) {
				case "discovered service type" :
					return 8;
					break;
				case "discovered service port" :
					return 9;
					break;
				case "discovery status" :
					return 10;
					break;
				case "uptime or downtime duration" :
					return 11;
					break;
				case "received value" :
					return 12;
					break;
				case "discovery rule" :
					return 18;
					break;
				case "discovery check" :
					return 19;
					break;
				case "proxy" :
					return 20;
					break;
				case "discovery object" :
					return 21;
					break;
				case "host ip" :
				default :
					return 7;
			}
		} elseif ($event_source == 3) {
			switch (strtolower ( $type )) {
				case "host name" :
					return 22;
					break;
				case "host metadata" :
					return 24;
					break;
				case "proxy" :
				default :
					return 20;
			}
		} elseif ($event_source == 4 || $event_source == 5) {
			switch (strtolower ( $type )) {
				case "host" :
					return 1;
					break;
				case "host template" :
					return 13;
					break;
				case "application" :
					return 15;
					break;
				case "event type" :
					return 23;
					break;
				case "node" :
					return 17;
					break;
				case "host group" :
				default :
					return 0;
			}
		}
		
		return 0;
	}

	/**
	 * Renvoi la valeur en fonction de ConditionType
	 * @param string $value
	 * @return number|string
	 */
	public function retrouve_Value($value) {
		$this->onDebug ( __METHOD__, 1 );
		switch ($this->getConditionType ()) {
			case 5 :
				switch (strtolower ( $value )) {
					case "problem" :
						return 1;
					case "ok" :
					default :
						return 0;
				}
		}
		
		return $value;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getConditionId() {
		return $this->conditionid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConditionId($conditionid) {
		$this->conditionid = $conditionid;
		return $this;
	}

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
	public function getConditionType() {
		return $this->conditiontype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConditionType($conditiontype, $event_source) {
		$this->conditiontype = $this->retrouve_ConditionType ( $conditiontype, $event_source );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValue($value) {
		$this->value = $this->retrouve_Value ( $value );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOperator() {
		return $this->operator;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOperator($operator) {
		$this->operator = $this->retrouve_ConditionOperator ( $operator );
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Action Condition :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_condition_type '' Type possible en fonction des Events : ";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent trigger :";
		$help [__CLASS__] ["text"] [] .= "\t\t\thost group|host|trigger|trigger name|trigger severity|trigger value|time period|host template|application|maintenance status|node";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent discovered host :";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent discovered service :";
		$help [__CLASS__] ["text"] [] .= "\t\t\thost ip|discovered service type|discovered service port|discovery status|uptime or downtime duration|received value|discovery rule|discovery check|proxy|discovery object";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent auto-registered host :";
		$help [__CLASS__] ["text"] [] .= "\t\t\tproxy|host name|host metadata";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent item :";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent lld rule :";
		$help [__CLASS__] ["text"] [] .= "\t\t\thost group|host|host template|application|event type|node";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_condition_operator '=' Valeur possible : ";
		$help [__CLASS__] ["text"] [] .= "\t\t\t=|<>|like|not like|in|>=|<=|not in";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_condition_value '' Texte qui valide la condition";
		
		return $help;
	}
}
?>
