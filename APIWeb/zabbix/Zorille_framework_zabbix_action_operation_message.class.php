<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_action_operation_message
 *
 * operationid 	string 	(readonly) ID of the action operation.
 * default_msg 	integer 	Whether to use the default action message text and subject.
 * 		Possible values:
 * 		0 - (default) use the data from the operation;
 * 		1 - use the data from the action.
 * mediatypeid 	string 	ID of the media type that will be used to send the message.
 * message 	string 	Operation message text.
 * subject 	string 	Operation message subject. 
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_action_operation_message extends zabbix_fonctions_standard {
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
	 * @var string|integer
	 */
	private $default_msg = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $mediatypeid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $message = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $subject = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_mediatype
	 */
	private $mediatype = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_action_operation_message.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_action_operation_message
	 */
	static function &creer_zabbix_action_operation_message(options &$liste_option, zabbix_wsclient &$zabbix_ws, bool|string $sort_en_erreur = false, string $entete = __CLASS__): zabbix_action_operation_message
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_action_operation_message ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_action_operation_message
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		$this->setObjetZabbixWsclient ( $liste_class ["zabbix_wsclient"] )
			->setObjetMediatype ( zabbix_mediatype::creer_zabbix_mediatype ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
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
	 * @return bool|zabbix_action_operation_message True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param(): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->setDefaultMsg ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"message",
				"defaultmsg" 
		), "operation" ) );
		$this->setOpMessage ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"message",
				"message" 
		), "" ) );
		$this->setSubject ( $this->_valideOption ( array (
				"zabbix",
				"action",
				"operation",
				"message",
				"subject" 
		), "" ) );
		
		$this->retrouve_mediaTypeId ();
		
		return $this;
	}

	/**
	 * Recupere un mediatypeid a partir du nom du mediatype
	 * @return bool|zabbix_action_operation_message
	 * @throws Exception
	 */
	public function retrouve_mediaTypeId(): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		//gestion du mediaTypeId
		$this->getObjetMediatype ()
			->retrouve_zabbix_param ( true )
			->recherche_mediatypeid_by_Name ();
		$mediatype = $this->getObjetMediatype ()
			->getMediatypeId ();
		if ($mediatype !== "") {
			$this->setMediaTypeId ( $mediatype );
		} else {
			return $this->onError ( "Aucun Mediatype avec le nom " . $this->getObjetMediatype ()
				->getDescription () );
		}
		
		return $this;
	}

	/**
	 * Creer un definition de operation_message sous forme de tableau
	 * @return array|false;
	 * @throws Exception
	 */
	public function creer_definition_zabbix_operation_message_ws(): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getDefaultMsg () === "") {
			return array ();
		}
		if ($this->getMediaTypeId () === "") {
			return $this->onError ( "Il faut un mediatype" );
		}
		$condition = array (
				"default_msg" => $this->getDefaultMsg (),
				"mediatypeid" => $this->getMediaTypeId (),
				"message" => $this->getOpMessage (),
				"subject" => $this->getSubject () 
		);
		if ($this->getOperationId () != "") {
			$condition ["operationid"] = $this->getOperationId ();
		}
		
		return $condition;
	}

	/**
	 * defaultMsg : Possible values for trigger actions: operation/action
	 * 0 - (default) use the data from the operation;
	 * 1 - use the data from the action.
	 * @param string $type operation ou action
	 * @return float|int|string
	 */
	public function retrouve_defaultMsg($type): float|int|string
	{
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		return match (strtolower($type)) {
			"action" => 1,
			default => 0,
		};

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
	public function getDefaultMsg(): int|string
	{
		return $this->default_msg;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDefaultMsg($default_msg): static
	{
		$this->default_msg = $this->retrouve_defaultMsg ( $default_msg );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMediaTypeId(): string
	{
		return $this->mediatypeid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMediaTypeId($mediatypeid): static
	{
		$this->mediatypeid = $mediatypeid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOpMessage(): string
	{
		return $this->message;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOpMessage($message): static
	{
		$this->message = $message;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSubject(): string
	{
		return $this->subject;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSubject($subject): static
	{
		$this->subject = $subject;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetMediatype(): ?zabbix_mediatype
	{
		return $this->mediatype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetMediatype($mediatype): static
	{
		$this->mediatype = $mediatype;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Action Message :";
		$help [__CLASS__] ["text"] [] .= "Cette fonction defini les conditions d'execution de l'operation selectionnee";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_message_defaultmsg Valeurs possible : 'operation/action'";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_message_message '' Message a transmettre";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_action_operation_message_subject '' Sujet du message";
		$help = array_merge ( $help, zabbix_mediatype::help () );
		
		return $help;
	}
}
