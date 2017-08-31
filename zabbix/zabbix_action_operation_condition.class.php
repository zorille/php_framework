<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_action_operation_condition
 *
 * opconditionid 	string 	(readonly) ID of the action operation condition
 * conditiontype (required) 	integer 	Type of condition.
 * 		Possible values:
 * 		14 - event acknowledged.
 * value (required) 	string 	Value to compare with.
 * operationid 	string 	(readonly) ID of the operation.
 * operator 	integer 	Condition operator.
 * 		Possible values:
 * 		0 - (default) =. 
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_action_operation_condition extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $opconditionid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $operationid = "";
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
	 * Instancie un objet de type zabbix_action_operation_condition.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_action_operation_condition
	 */
	static function &creer_zabbix_action_operation_condition(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_action_operation_condition ( $sort_en_erreur, $entete );
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
	 * @param string $contition creation d'une condition a partir d'une string au format "type|operator|valeur"
	 * @return boolean True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param($contition = false) {
		$this->onDebug ( __METHOD__, 1 );
		if ($contition !== false) {
			$liste_condition = explode ( "|", trim ( $contition ) );
			if ($liste_condition === false || count ( $liste_condition ) != 3) {
				return $this->onError ( "Parametre inutilisable : " . $contition );
			}
			$this->setConditionType ( $liste_condition [0] );
			$this->setOperator ( $liste_condition [1] );
			$this->setValue ( $liste_condition [2] );
		} else {
			$this->setConditionType ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"operation",
					"condition",
					"type" 
			), "" ) );
			$this->setOperator ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"operation",
					"condition",
					"operator" 
			), "=" ) );
			$this->setValue ( $this->_valideOption ( array (
					"zabbix",
					"action",
					"operation",
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
	public function creer_definition_zabbix_operation_condition_ws() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getValue () == "") {
			return array ();
		}
		$condition = array (
				"conditiontype" => $this->getConditionType (),
				"operator" => $this->getOperator (),
				"value" => $this->getValue () 
		);
		if ($this->getOpConditionId () != "") {
			$condition ["opconditionid"] = $this->getOpConditionId ();
			$condition ["conditionid"] = $this->getConditionId ();
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
			/* NON utilise pour ce type de condition
			 * case "<>" :
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
				break;*/
			case "=" :
			default :
		}
		
		return 0;
	}

	/**
	 * conditiontype : Possible values for trigger actions:
	 * 14 - event acknowledged
	 * @param string $type
	 * @return number
	 */
	public function retrouve_ConditionType($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "event acknowledged" :
			default :
		}
		
		return 14;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getOpConditionId() {
		return $this->opconditionid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpConditionId($opconditionid) {
		$this->opconditionid = $opconditionid;
		return $this;
	}

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
	public function getConditionType() {
		return $this->conditiontype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConditionType($conditiontype) {
		$this->conditiontype = $this->retrouve_ConditionType ( $conditiontype );
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
		$this->value = $value;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Action Operation Condition :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_condition_type '' Type possible : 'event acknowledged'";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_condition_operator '=' Valeur possible : =";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_condition_value '' Valeurs possible : Ack/'Not Ack'";
		
		return $help;
	}
}
?>
