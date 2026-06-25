<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_action_operation_command
 *
 * operationid 	string 	(readonly) ID of the operation.
 * command (required) 	string 	Command to run.
 * type (required) 	integer 	Type of operation command.
 * 		Possible values:
 * 		0 - custom script;
 * 		1 - IPMI;
 * 		2 - SSH;
 * 		3 - Telnet;
 * 		4 - global script.
 * 
 * Required for custom script commands.
 * execute_on 	integer 	Target on which the custom script operation command will be executed.
 * 		Possible values:
 * 		0 - Zabbix agent;
 * 		1 - Zabbix server.
 * 
 * Required for SSH commands.
 * authtype 	integer 	Authentication method used for SSH commands.
 * 		Possible values:
 * 		0 - password;
 * 		1 - public key.
 * 
 * Required for SSH commands with public key authentication.
 * privatekey 	string 	Name of the private key file used for SSH commands with public key authentication.
 * publickey 	string 	Name of the public key file used for SSH commands with public key authentication.
 * 
 * Required for SSH and Telnet commands.
 * username 	string 	User name used for authentication. 
 * password 	string 	Password used for SSH commands with password authentication and Telnet commands.
 * port 	string 	Port number used for SSH and Telnet commands.
 *  
 * Required for global script commands.
 * scriptid 	string 	ID of the script used for global script commands.
 * 
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_action_operation_command extends zabbix_fonctions_standard {
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
	private $command = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $type = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $execute_on = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $authtype = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $username = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $passwd = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $port = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $privatekey = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $publickey = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $scriptid = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_action_operation_command.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_action_operation_command
	 */
	static function &creer_zabbix_action_operation_command(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): zabbix_action_operation_command
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_action_operation_command ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_action_operation_command
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
	 * @return zabbix_action_operation_command|bool True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param(): zabbix_action_operation_command|bool
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setCommand ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"command",
				"command" 
		) ) );
		$this->setType ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"command",
				"type" 
		) ) );
		switch ($this->getType ()) {
			case 0 : //custom
				$this->setExecuteOn ( $this->_valideOption ( array (
						"zabbix",
						"action",
						"operation",
						"command",
						"execute_on" 
				) ) );
				break;
			case 1 : //IPMI
				break;
			case 2 : //SSH
				$this->setAuthtype ( $this->_valideOption ( array (
						"zabbix",
						"action",
						"operation",
						"command",
						"authtype" 
				) ) );
				$this->setAuthtype ( $this->_valideOption ( array (
						"zabbix",
						"action",
						"operation",
						"command",
						"privatekey" 
				) ) );
				$this->setAuthtype ( $this->_valideOption ( array (
						"zabbix",
						"action",
						"operation",
						"command",
						"publickey" 
				) ) );
			case 3 : //telnet et SSH
				$this->setAuthtype ( $this->_valideOption ( array (
						"zabbix",
						"action",
						"operation",
						"command",
						"username" 
				) ) );
				$this->setAuthtype ( $this->_valideOption ( array (
						"zabbix",
						"action",
						"operation",
						"command",
						"password" 
				) ) );
				$this->setAuthtype ( $this->_valideOption ( array (
						"zabbix",
						"action",
						"operation",
						"command",
						"port" 
				) ) );
				break;
			case 4 : //global
				$this->setScriptid ( $this->_valideOption ( array (
						"zabbix",
						"action",
						"operation",
						"command",
						"scriptid" 
				) ) );
				break;
			
			default :
				return $this->onError ( "Type inconnu : " . $this->getType () );
		}
		
		return $this;
	}

	/**
	 * Creer un definition de operation_command sous forme de tableau
	 * @return array|false;
	 * @throws Exception
	 */
	public function creer_definition_zabbix_operation_command_ws(): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getType () === "") {
			return array ();
		}
		if ($this->getCommand () == "") {
			return $this->onError ( "Il faut une commande pour continuer" );
		}
		
		$command = array (
				"type" => $this->getType (),
				"command" => $this->getCommand () 
		);
		switch ($this->getType ()) {
			case 0 :
				$command ["execute_on"] = $this->getExecuteOn ();
				break;
			case 1 :
				break;
			case 2 :
				$command ["authtype"] = $this->getAuthtype ();
				$command ["privatekey"] = $this->getPrivatekey ();
				$command ["publickey"] = $this->getPublickey ();
				;
			case 3 :
				$command ["username"] = $this->getUsername ();
				$command ["passwd"] = $this->getPassword ();
				$command ["port"] = $this->getPort ();
				break;
			case 4 :
				$command ["scriptid"] = $this->getScriptid ();
				break;
			default :
				return $this->onError ( "Type inconnu : " . $this->getType () );
		}
		
		if ($this->getOperationId () != "") {
			$command ["operationid"] = $this->getOperationId ();
		}
		
		return $command;
	}

	/**
	 * Type : Possible values :
	 * 0 - custom_script;
	 * 1 - IPMI;
	 * 2 - SSH;
	 * 3 - Telnet;
	 * 4 - global_script.
	 * @param string $type 
	 * @return float|int|string
	 */
	public function retrouve_Type($type): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "ipmi" :
				return 1;
			case "ssh" :
				return 2;
			case "telnet" :
				return 3;
			case "global_script" :
				return 4;
			case "custom_script" :
			default :
		}
		
		return 0;
	}

	/**
	 * defaultMsg : Possible values :
	 * 0 - Zabbix agent;
	 * 1 - Zabbix server.
	 * @param string $type 
	 * @return float|int|string
	 */
	public function retrouve_ExecuteOn($type): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "server" :
				return 1;
			case "agent" :
			default :
		}
		
		return 0;
	}

	/**
	 * defaultMsg : Possible values for trigger actions: operation/action
	 * 0 - (default) use the data from the operation;
	 * 1 - use the data from the action.
	 * @param string $type operation ou action
	 * @return float|int|string
	 */
	public function retrouve_Authtype($type): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "action" :
				return 1;
			case "operation" :
			default :
		}
		
		return 0;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getOperationId(): string
	{
		return $this->operationid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOperationId($operationid): static
	{
		$this->operationid = $operationid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCommand(): string
	{
		return $this->command;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCommand($command): static
	{
		$this->command = $command;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setType($type): static
	{
		$this->type = $this->retrouve_Type ( $type );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getExecuteOn(): string
	{
		return $this->execute_on;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setExecuteOn($execute_on): static
	{
		$this->execute_on = $this->retrouve_ExecuteOn ( $execute_on );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAuthtype(): string
	{
		return $this->authtype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuthtype($authtype): static
	{
		$this->authtype = $this->retrouve_Authtype ( $authtype );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsername($username): static
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassword(): string
	{
		return $this->passwd;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword($passwd): static
	{
		$this->passwd = $passwd;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPort(): string
	{
		return $this->port;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPort($port): static
	{
		$this->port = $port;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrivatekey(): string
	{
		return $this->privatekey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPrivatekey($privatekey): static
	{
		$this->privatekey = $privatekey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPublickey(): string
	{
		return $this->publickey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPublickey($publickey): static
	{
		$this->publickey = $publickey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getScriptid(): string
	{
		return $this->scriptid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setScriptid($scriptid): static
	{
		$this->scriptid = $scriptid;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Action Operation Command :";
		$help [__CLASS__] ["text"] [] .= "Cette fonction defini les conditions d'execution de l'operation selectionnee";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_command '' Commande a executer";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_type 'custom_script/IPMI/SSH/Telnet/global_script'";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_execute_on 'agent/server' cible Zabbix d'execution pour les custom_scripts";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_authtype 'password/publickey' en cas de SSH";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_privatekey '' en cas de SSH";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_publickey '' en cas de SSH";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_username '' Nom de l'utilisateur pour SSH et TELNET";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_password ''  Mot de passe pour SSH et TELNET";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_port ''  Port pour SSH et TELNET";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_command_scriptid '' pour les global_script";
		
		return $help;
	}
}
