<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * operationid 	string 	(readonly) ID of the action operation.
 * operationtype (required) 	integer 	Type of operation.
 * 		Possible values:
 * 		0 - send message;
 * 		1 - remote command;
 * 		2 - add host;
 * 		3 - remove host;
 * 		4 - add to host group;
 * 		5 - remove from host group;
 * 		6 - link to template;
 * 		7 - unlink from template;
 * 		8 - enable host;
 * 		9 - disable host.
 * actionid 	string 	ID of the action that the operation belongs to.
 * esc_period 	integer 	Duration of an escalation step in seconds. Must be greater than 60 seconds. If set to 0, the default action escalation period will be used. Default: 0.
 * esc_step_from 	integer 	Step to start escalation from. Default: 1.
 * esc_step_to 	integer 	Step to end escalation at. Default: 1.
 * evaltype 	integer 	Operation condition evaluation method.
 * 		Possible values:
 * 		0 - (default) AND / OR;
 * 		1 - AND;
 * 		2 - OR.
 * opcommand 	object 	Object containing the data about the command run by the operation. (zabbix_action_operation_command) Required for remote command operations.
 * opcommand_grp 	array 	Host groups to run remote commands on. Required for remote command operations if opcommand_hst is not set
 * 		Each object has the following properties:
 * 		opcommand_grpid - (string, readonly) ID of the object;
 * 		operationid - (string) ID of the operation;
 * 		groupid - (string) ID of the host group.
 * opcommand_hst 	array 	Host to run remote commands on. Required for remote command operations if opcommand_grp is not set.
 * 		Each object has the following properties:
 * 		opcommand_hstid - (string, readonly) ID of the object;
 * 		operationid - (string) ID of the operation;
 * 		hostid - (string) ID of the host; if set to 0 the command will be run on the current host.
 * opconditions 	array 	Operation conditions used for trigger actions. (zabbix_action_operation_condition)
 * opgroup 	array 	Host groups to add hosts to. Required for “add to host group” and “remove from host group” operations.
 * 		Each object has the following properties:
 * 		operationid - (string) ID of the operation;
 * 		groupid - (string) ID of the host group.
 * opmessage 	object 	Object containing the data about the message sent by the operation. (zabbix_action_operation_message) Required for message operations.
 * opmessage_grp 	array 	User groups to send messages to. Required for message operations if opmessage_usr is not set.
 * 		Each object has the following properties:
 * 		operationid - (string) ID of the operation;
 * 		usrgrpid - (string) ID of the user group.
 * opmessage_usr 	array 	Users to send messages to. Required for message operations if opmessage_grp is not set.
 * 		Each object has the following properties:
 * 		operationid - (string) ID of the operation;
 * 		userid - (string) ID of the user.
 * optemplate 	array 	Templates to link the hosts to to. Required for “link to template” and “unlink from template” operations. 
 * 		Each object has the following properties:
 * 		operationid - (string) ID of the operation;
 * 		templateid - (string) ID of the template.
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_action_operation extends zabbix_fonctions_standard {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $operationid = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $actionid = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $operationtype = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $esc_period = "0";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $esc_step_from = "1";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $esc_step_to = "1";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $evaltype = 1;
	/**
	 * var privee
	 * @access private
	 * @var zabbix_action_operation_command
	 */
	private $opcommand = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $opcommand_grp = array ();
	/**
	 * var privee
	 * @access private
	 * @var zabbix_hostgroup
	 */
	private $hostgroup_ref = null;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $opcommand_hst = array ();
	/**
	 * var privee
	 * @access private
	 * @var zabbix_host
	 */
	private $host_ref = null;
	/**
	 * var privee
	 * @access private
	 * @var array()
	 */
	private $opconditions = array ();
	/**
	 * var privee
	 * @access private
	 * @var zabbix_action_operation_condition
	 */
	private $opcondition_ref = null;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $opgroup = array ();
	/**
	 * var privee
	 * @access private
	 * @var zabbix_action_operation_message
	 */
	private $opmessage = null;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $opmessage_grp = array ();
	/**
	 * var privee
	 * @access private
	 * @var zabbix_usergroup
	 */
	private $usergroup_ref = null;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $opmessage_usr = array ();
	/**
	 * var privee
	 * @access private
	 * @var zabbix_user
	 */
	private $user_ref = null;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $optemplate = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_action_operation.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_action_operation
	 */
	static function &creer_zabbix_action_operation(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_action_operation ( $sort_en_erreur, $entete );
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
			->setObjetOpCommand ( zabbix_action_operation_command::creer_zabbix_action_operation_command ( $liste_class ["options"] ) )
			->setObjetOpConditionRef ( zabbix_action_operation_condition::creer_zabbix_action_operation_condition ( $liste_class ["options"] ) )
			->setObjetOpMessage ( zabbix_action_operation_message::creer_zabbix_action_operation_message ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) )
			->setObjetUserRef ( zabbix_user::creer_zabbix_user ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) )
			->setObjetUserGroupRef ( zabbix_usergroup::creer_zabbix_usergroup ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) )
			->setObjetHostRef ( zabbix_host::creer_zabbix_host ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) )
			->setObjetHostGroupRef ( zabbix_hostgroup::creer_zabbix_hostgroup ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
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
	 * @return zabbix_action_operation
	 * @throws Exception
	 */
	public function retrouve_zabbix_param() {
		$this->onDebug ( __METHOD__, 1 );
		$this->setOperationType ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"operationtype" 
		) ) );
		$this->retrouve_opconditions ();
		$this->setEscPeriod ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"esc_period" 
		), 0 ) );
		$this->setEscStepFrom ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"esc_step_from" 
		), 1 ) );
		$this->setEscStepTo ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"esc_step_to" 
		), 1 ) );
		$this->setEvalType ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"evaltype" 
		), "and/or" ) );
		$this->setOpCommandGrp ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"opcommand_grp" 
		), array () ) );
		$this->setOpCommandHst ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"opcommand_hst" 
		), array () ) );
		$this->setOpGroup ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"opgroup" 
		), array () ) );
		$this->setOpMessageGroup ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"opmessage_grp" 
		), array () ) );
		$this->setOpMessageUser ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"opmessage_usr" 
		), array () ) );
		$this->setOpTemplate ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"optemplate" 
		), "" ) );
		
		switch ($this->getOperationType ()) {
			case 0 :
				//send message
				$this->getObjetOpMessage ()
					->retrouve_zabbix_param ();
				break;
			case 1 :
				$this->getObjetOpCommand ()
					->retrouve_zabbix_param ();
				break;
		}
		
		return $this;
	}

	/**
	 * Retrouve les parametres operation_conditions
	 * @return zabbix_action_operation
	 */
	public function retrouve_opconditions() {
		$this->onDebug ( __METHOD__, 1 );
		$opconditions = $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"conditions" 
		), array () );
		if (! is_array ( $opconditions )) {
			$opconditions = array (
					$opconditions 
			);
		}
		
		foreach ( $opconditions as $condition ) {
			$objet_condition = clone $this->getObjetOpConditionRef ();
			$objet_condition->retrouve_zabbix_param ( $condition );
			$this->setAjoutOpConditions ( $objet_condition );
		}
		
		return $this;
	}

	/**
	 * Creer un definition de l'action operation sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_action_operation_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$action_operation = array (
				"operationtype" => $this->getOperationType (),
				"esc_period" => $this->getEscPeriod (),
				"esc_step_from" => $this->getEscStepFrom (),
				"esc_step_to" => $this->getEscStepTo (),
				"evaltype" => $this->getEvalType (),
				"opgroup" => $this->getOpGroup (),
				"optemplate" => $this->getOpTemplate () 
		);
		$action_operation = array_merge ( $action_operation, $this->creer_definition_action_operation_command_ws () );
		$action_operation = array_merge ( $action_operation, $this->creer_definition_action_operation_conditions_ws () );
		$action_operation = array_merge ( $action_operation, $this->creer_definition_action_operation_message_ws () );
		if ($this->getOperationId () != "") {
			$action_operation ["operationid"] = $this->getOperationId ();
			$action_operation ["actionid"] = $this->getActionId ();
		}
		return $action_operation;
	}

	/**
	 * Creer un definition de l'action conditions sous forme de tableau
	 * @return array;
	 * @throws Exception
	 */
	public function creer_definition_action_operation_conditions_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$opconditions = array (
				"opconditions" => array () 
		);
		foreach ( $this->getOpConditions () as $condition ) {
			$opconditions ["opconditions"] [count ( $opconditions ["opconditions"] )] = $condition->creer_definition_zabbix_operation_condition_ws ();
		}
		
		return $opconditions;
	}

	/**
	 * Creer un definition de l'action operation command sous forme de tableau
	 * @return array;
	 * @throws Exception
	 */
	public function creer_definition_action_operation_command_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$opcommand = array (
				"opcommand" => $this->getObjetOpCommand ()
					->creer_definition_zabbix_operation_command_ws () 
		);
		if (count ( $this->getObjetOpCommand ()
			->creer_definition_zabbix_operation_command_ws () ) > 0) {
			if (count ( $this->getOpCommandGrp () ) > 0) {
				$opcommand ["opcommand_grp"] = $this->getOpCommandGrp ();
			} elseif (count ( $this->getOpCommandHst () ) > 0) {
				$opcommand ["opcommand_hst"] = $this->getOpCommandHst ();
			} else {
				return $this->onError ( "Il faut un zabbix_action_operation_opcommand_grp ou un zabbix_action_operation_opcommand_hst pour un opcommand" );
			}
		}
		
		return $opcommand;
	}

	/**
	 * Creer un definition de l'action operation message sous forme de tableau
	 * @return array;
	 * @throws Exception
	 */
	public function creer_definition_action_operation_message_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$opmessage_local = $this->getObjetOpMessage ()
			->creer_definition_zabbix_operation_message_ws ();
		$opmessage = array (
				"opmessage" => $opmessage_local 
		);
		if (count ( $opmessage_local ) > 0) {
			$this->retrouve_usrgrpId ()
				->retrouve_userId ();
			if (count ( $this->getOpMessageGroup () ) > 0) {
				$opmessage ["opmessage_grp"] = $this->getOpMessageGroup ();
			} elseif (count ( $this->getOpMessageUser () ) > 0) {
				$opmessage ["opmessage_usr"] = $this->getOpMessageUser ();
			} else {
				return $this->onError ( "Il faut un zabbix_action_operation_opmessage_grp ou un zabbix_action_operation_opmessage_usr pour un opcommand" );
			}
		}
		
		return $opmessage;
	}

	/**
	 * Recupere un userid a partir de l'alias du user
	 * @throws Exception
	 * @return zabbix_action_operation
	 */
	public function retrouve_userId() {
		$this->onDebug ( __METHOD__, 1 );
		$userids = array ();
		foreach ( $this->getOpMessageUser () as $user_alias ) {
			//gestion du userid
			$user_local = clone $this->getObjetUserRef ();
			$userid_desc = $user_local->setAlias ( $user_alias )
				->recherche_userid_by_Alias ()
				->creer_definition_userid_get_ws ();
			if ($user_local->getUsrId () !== "") {
				$userids [count ( $userids )] = $userid_desc;
			} else {
				return $this->onError ( "Aucun User avec le nom " . $user_local->getAlias () );
			}
		}
		
		return $this->setOpMessageUser ( $userids );
	}

	/**
	 * Recupere un usergroupid a partir du nom du groupe d'utilisateur
	 * @throws Exception
	 * @return zabbix_action_operation
	 */
	public function retrouve_usrgrpId() {
		$this->onDebug ( __METHOD__, 1 );
		$usergrpids = array ();
		foreach ( $this->getOpMessageGroup () as $usergrp_nom ) {
			//gestion du usergroupid
			$usergroup_local = clone $this->getObjetUserGroupRef ();
			$usergrpid_desc = $usergroup_local->setName ( $usergrp_nom )
				->recherche_userGroupid_by_Name ()
				->creer_definition_usrgrpid_get_ws ();
			if ($usergroup_local->getUsrgrpId () !== "") {
				$usergrpids [count ( $usergrpids )] = $usergrpid_desc;
			} else {
				return $this->onError ( "Aucun UserGroup avec le nom " . $usergroup_local->getName () );
			}
		}
		
		return $this->setOpMessageGroup ( $usergrpids );
	}

	/**
	 * 0 - send message;
	 * 1 - remote command;
	 * 2 - add host;
	 * 3 - remove host;
	 * 4 - add to host group;
	 * 5 - remove from host group;
	 * 6 - link to template;
	 * 7 - unlink from template;
	 * 8 - enable host;
	 * 9 - disable host. 
	 * @param string $type
	 * @return number
	 */
	public function retrouve_OperationType($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "remote command" :
				return 1;
				break;
			case "add host" :
				return 2;
				break;
			case "remove host" :
				return 3;
				break;
			case "add to host group" :
				return 4;
				break;
			case "remove from host group" :
				return 5;
				break;
			case "link to template" :
				return 6;
				break;
			case "unlink from template" :
				return 7;
				break;
			case "enable host" :
				return 8;
				break;
			case "disable host" :
				return 9;
				break;
			case "send message" :
			default :
		}
		
		return 0;
	}

	/**
	 * 0 - (default) AND/OR;
	 * 1 - AND;
	 * 2 - OR.
	 * @param string $type
	 * @return number
	 */
	public function retrouve_EvalType($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
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

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getOperationId() {
		return $this->operationid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOperationId($operationid) {
		$this->operationid = $operationid;
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
	public function getOperationType() {
		return $this->operationtype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOperationType($operationtype) {
		$this->operationtype = $this->retrouve_OperationType ( $operationtype );
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
	public function getEscStepFrom() {
		return $this->esc_step_from;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEscStepFrom($esc_step_from) {
		$this->esc_step_from = $esc_step_from;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getEscStepTo() {
		return $this->esc_step_to;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEscStepTo($esc_step_to) {
		$this->esc_step_to = $esc_step_to;
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
	 * @return zabbix_action_operation_command
	 */
	public function &getObjetOpCommand() {
		return $this->opcommand;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetOpCommand($opcommand) {
		$this->opcommand = $opcommand;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOpCommandGrp() {
		return $this->opcommand_grp;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpCommandGrp($opcommand_grp) {
		if (! is_array ( $opcommand_grp )) {
			$opcommand_grp = array (
					$opcommand_grp 
			);
		}
		$this->opcommand_grp = $opcommand_grp;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_hostgroup
	 */
	public function &getObjetHostGroupRef() {
		return $this->hostgroup_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetHostGroupRef(&$hostgroup) {
		$this->hostgroup_ref = $hostgroup;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOpCommandHst() {
		return $this->opcommand_hst;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpCommandHst($opcommand_hst) {
		if (! is_array ( $opcommand_hst )) {
			$opcommand_hst = array (
					$opcommand_hst 
			);
		}
		$this->opcommand_hst = $opcommand_hst;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_host
	 */
	public function &getObjetHostRef() {
		return $this->host_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetHostRef(&$host) {
		$this->host_ref = $host;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_action_operation_condition
	 */
	public function &getObjetOpConditionRef() {
		return $this->opcondition_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetOpConditionRef(&$opcondition_ref) {
		$this->opcondition_ref = $opcondition_ref;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOpConditions() {
		return $this->opconditions;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpConditions($opconditions) {
		$this->opconditions = $opconditions;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutOpConditions(&$opcondition) {
		$this->opconditions [count ( $this->opconditions )] = $opcondition;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOpGroup() {
		return $this->opgroup;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpGroup($opgroup) {
		$this->opgroup = $opgroup;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_action_operation_message
	 */
	public function &getObjetOpMessage() {
		return $this->opmessage;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetOpMessage($opmessage) {
		$this->opmessage = $opmessage;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOpMessageGroup() {
		return $this->opmessage_grp;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpMessageGroup($opmessage_grp) {
		if (! is_array ( $opmessage_grp )) {
			$opmessage_grp = array (
					$opmessage_grp 
			);
		}
		$this->opmessage_grp = $opmessage_grp;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_usergroup
	 */
	public function &getObjetUserGroupRef() {
		return $this->usergroup_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetUserGroupRef(&$usergroup_ref) {
		$this->usergroup_ref = $usergroup_ref;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOpMessageUser() {
		return $this->opmessage_usr;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpMessageUser($opmessage_usr) {
		if (! is_array ( $opmessage_usr )) {
			$opmessage_usr = array (
					$opmessage_usr 
			);
		}
		$this->opmessage_usr = $opmessage_usr;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_user
	 */
	public function &getObjetUserRef() {
		return $this->user_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetUserRef(&$user) {
		$this->user_ref = $user;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOpTemplate() {
		return $this->optemplate;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpTemplate($optemplate) {
		$this->optemplate = $optemplate;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Action Operation :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_operationtype '' Type possible en fonction des Events : ";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent trigger :";
		$help [__CLASS__] ["text"] [] .= "\t\t\tsend message|remote command";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent discovered host :";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent discovered service :";
		$help [__CLASS__] ["text"] [] .= "\t\t\tsend message|remote command|add host|remove host|add to host group|remove from host group|link to template|unlink from template|enable host|disable host";
		$help [__CLASS__] ["text"] [] .= "\tEvent auto-registered host :";
		$help [__CLASS__] ["text"] [] .= "\t\t\tsend message/remote command/add host|add to host group|link to template|disable host";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent item :";
		$help [__CLASS__] ["text"] [] .= "\t\tEvent lld rule :";
		$help [__CLASS__] ["text"] [] .= "\t\t\tsend message";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_esc_period O par defaut";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_esc_step_from 1 par defaut";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_esc_step_to 1 par defaut";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_evaltype 'AND/OR' par defaut ou AND ou OR";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_opcommand_grp '' hostGroup sur lequel executer la commande ";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_opcommand_hst '' host sur lequel executer la commande";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_opgroup ''  hostGroup ou l'on ajoute le host";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_opmessage_grp ''  userGroup de reception du message";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_opmessage_usr '' User de reception du message";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_optemplate '' template a lier sur le host";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_conditions 'type|operator|value' '' liste de conditions (voir le help de zabbix_action_operation_condition )";
		$help = array_merge ( $help, zabbix_action_operation_command::help () );
		$help = array_merge ( $help, zabbix_action_operation_condition::help () );
		$help = array_merge ( $help, zabbix_action_operation_message::help () );
		
		return $help;
	}
}
?>
