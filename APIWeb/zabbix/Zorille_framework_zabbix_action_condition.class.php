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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_action_condition
	 */
	static function &creer_zabbix_action_condition(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): zabbix_action_condition
	{
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
	 * @return zabbix_action_condition
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @param integer $event_source Id du type d'evenement lie a l'action
	 * @param bool|string $contition creation d'une condition a partir d'une string au format "type|operator|valeur"
	 * @return bool|zabbix_action_condition True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param(int $event_source, bool|string $contition = false): bool|static
	{
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
	public function creer_definition_action_condition_ws(): array
	{
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
	 * @param $operator
	 * @return float|int|string
	 */
	public function retrouve_ConditionOperator($operator): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $operator )) {
			return $operator;
		}
		return match (strtolower($operator)) {
			"<>" => 1,
			"like" => 2,
			"not like" => 3,
			"in" => 4,
			">=" => 5,
			"<=" => 6,
			"not in" => 7,
			default => 0,
		};
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
	 * @param $event_source
	 * @return float|int|string
	 */
	public function retrouve_ConditionType(string $type, $event_source): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		if ($event_source == 0) {
			return match (strtolower($type)) {
				"host" => 1,
				"trigger" => 2,
				"trigger name" => 3,
				"trigger severity" => 4,
				"trigger value" => 5,
				"time period" => 6,
				"host template" => 13,
				"application" => 15,
				"maintenance status" => 16,
				"node" => 17,
				default => 0,
			};
		} elseif ($event_source == 1 || $event_source == 2) {
			return match (strtolower($type)) {
				"discovered service type" => 8,
				"discovered service port" => 9,
				"discovery status" => 10,
				"uptime or downtime duration" => 11,
				"received value" => 12,
				"discovery rule" => 18,
				"discovery check" => 19,
				"proxy" => 20,
				"discovery object" => 21,
				default => 7,
			};
		} elseif ($event_source == 3) {
			return match (strtolower($type)) {
				"host name" => 22,
				"host metadata" => 24,
				default => 20,
			};
		} elseif ($event_source == 4 || $event_source == 5) {
			return match (strtolower($type)) {
				"host" => 1,
				"host template" => 13,
				"application" => 15,
				"event type" => 23,
				"node" => 17,
				default => 0,
			};
		}
		
		return 0;
	}

	/**
	 * Renvoi la valeur en fonction de ConditionType
	 * @param string $value
	 * @return int|string
	 */
	public function retrouve_Value($value): int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getConditionType() == 5) {
			return match (strtolower($value)) {
				"problem" => 1,
				default => 0,
			};
		}
		
		return $value;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getConditionId(): string
	{
		return $this->conditionid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConditionId($conditionid): static
	{
		$this->conditionid = $conditionid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getActionId(): string
	{
		return $this->actionid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setActionId($actionid): static
	{
		$this->actionid = $actionid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getConditionType(): string
	{
		return $this->conditiontype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConditionType($conditiontype, $event_source): static
	{
		$this->conditiontype = $this->retrouve_ConditionType ( $conditiontype, $event_source );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValue(): string
	{
		return $this->value;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValue($value): static
	{
		$this->value = $this->retrouve_Value ( $value );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOperator(): int
	{
		return $this->operator;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOperator($operator): static
	{
		$this->operator = $this->retrouve_ConditionOperator ( $operator );
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
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
