<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_mediatype
 *
 * mediatypeid 	string 	(readonly) ID of the media type.
 * description (required) 	string 	Name of the media type.
 * type (required) 	integer 	Transport used by the media type.
 * 						Possible values:
 * 						0 - email;
 * 						1 - script;
 * 						2 - SMS;
 * 						3 - Jabber;
 * 						100 - Ez Texting.
 * status 	integer 	Whether the media type is enabled.
 * 						Possible values:
 * 						0 - (default) enabled;
 * 						1 - disabled.
 * 
 * Required for script and Ez Texting media types.
 * exec_path 	string 	For script media types exec_path contains the name of the executed script.
 * 						For Ez Texting exec_path contains the message text limit.
 * 						Possible text limit values:
 * 						0 - USA (160 characters);
 * 						1 - Canada (136 characters).
 * 
 * Required for SMS media types.
 * gsm_modem 	string 	Serial device name of the GSM modem.
 * 
 * Required for email media types.
 * smtp_helo 	string 	SMTP HELO.
 * smtp_server 	string 	SMTP server.
 * smtp_email 	string 	Email address from which notifications will be sent.
 * 
 * Required for Jabber and Ez Texting media types.
 * username 	string 	Username or Jabber identifier.
 * passwd 	string 	Authentication password.
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_mediatype extends zabbix_fonctions_standard {
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
	private $description = "";
	/**
	 * var privee
	 * 
	 * @access private
	 * @var string
	 */
	private $type = "0";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $exec_path = "";
	/**
	 * var privee
	 * 1=inactif par default,0 actif
	 * @access private
	 * @var integer
	 */
	private $status = 1;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $gsm_modem = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $smtp_helo = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $smtp_server = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $smtp_email = "";
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

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_mediatype.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_mediatype
	 */
	static function &creer_zabbix_mediatype(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_mediatype ( $sort_en_erreur, $entete );
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
	 * @param boolean $nom_seulement valide uniquement le nom (description) du mediatype
	 * @return boolean True est OK, False sinon.
	 */
	public function retrouve_zabbix_param($nom_seulement = false) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setDescription ( $this->_valideOption ( array (
				"zabbix",
				"mediatype",
				"nom" 
		) ) );
		if ($nom_seulement === false) {
			$this->setType ( $this->_valideOption ( array (
					"zabbix",
					"mediatype",
					"type" 
			) ) );
			$this->setStatus ( $this->_valideOption ( array (
					"zabbix",
					"mediatype",
					"status" 
			), "enable" ) );
			
			switch ($this->getType ()) {
				case 0 :
					$this->setSmtpHelo ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"smtp_helo" 
					) ) );
					$this->setSmtpServer ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"smtp_server" 
					) ) );
					$this->setSmtpEmail ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"smtp_email" 
					) ) );
					break;
				case 1 :
					$this->setExecPath ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"exec_path" 
					) ) );
					break;
				case 2 :
					$this->setGsmModem ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"gsm_modem" 
					) ) );
					break;
				case 3 :
					$this->setUsername ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"username" 
					) ) );
					$this->setPassword ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"passwd" 
					) ) );
					break;
				case 100 :
					$this->setExecPath ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"exec_path" 
					) ) );
					$this->setUsername ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"username" 
					) ) );
					$this->setPassword ( $this->_valideOption ( array (
							"zabbix",
							"mediatype",
							"password" 
					) ) );
					break;
				default :
					return $this->onError ( "Type inconnu : " . $this->getType () );
			}
		}
		
		return $this;
	}

	/**
	 * Creer un definition d'un mediatype sous forme de tableau
	 * @return array;
	 * @throws Exception
	 */
	public function creer_definition_mediatype_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$mediatypeid = array (
				"description" => $this->getDescription (),
				"type" => $this->getType (),
				"status" => $this->getStatus () 
		);
		switch ($this->getType ()) {
			case 0 :
				$mediatypeid ["smtp_helo"] = $this->getSmtpHelo ();
				$mediatypeid ["smtp_server"] = $this->getSmtpServer ();
				$mediatypeid ["smtp_email"] = $this->getSmtpEmail ();
				break;
			case 1 :
				$mediatypeid ["exec_path"] = $this->getExecPath ();
				break;
			case 2 :
				$mediatypeid ["gsm_modem"] = $this->getGsmModem ();
				break;
			case 3 :
				$mediatypeid ["username"] = $this->getUsername ();
				$mediatypeid ["passwd"] = $this->getPassword ();
				break;
			case 100 :
				$mediatypeid ["exec_path"] = $this->getExecPath ();
				$mediatypeid ["username"] = $this->getUsername ();
				$mediatypeid ["passwd"] = $this->getPassword ();
				break;
			default :
				return $this->onError ( "Type inconnu : " . $this->getType () );
		}
		
		if ($this->getMediatypeId () != "") {
			$mediatypeid ["mediatypeid"] = $this->getMediatypeId ();
		}
		
		return $mediatypeid;
	}

	/**
	 * Creer un mediatype dans zabbix
	 * @return array
	 */
	public function creer_mediatype() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_mediatype_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->mediatypeCreate ( $datas );
	}

	/**
	 * Creer un definition d'un mediatype sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_mediatype_delete_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$mediatypeid = array ();
		
		if ($this->getMediatypeId () != "") {
			$mediatypeid [] .= $this->getMediatypeId ();
		}
		
		return $mediatypeid;
	}

	/**
	 * supprime un mediatype dans zabbix
	 * @return array
	 */
	public function supprime_mediatype() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_mediatype = $this->recherche_mediatype ();
		foreach ( $liste_mediatype as $mediatypeids ) {
			if (isset ( $mediatypeids ["mediatypeid"] ) && $mediatypeids ["mediatypeid"] != "") {
				$this->setMediatypeId ( $mediatypeids ["mediatypeid"] );
			}
		}
		$datas = $this->creer_definition_mediatype_delete_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->mediatypeDelete ( $datas );
	}

	/**
	 * Creer un definition d'un mediatype sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_mediatype_get_ws() {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"output" => "mediatypeid",
				"filter" => array (
						"description" => $this->getDescription () 
				) 
		);
	}

	/**
	 * recherche un mediatype dans zabbix a partir de sa description
	 * @return array
	 */
	public function recherche_mediatype() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_mediatype_get_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->mediatypeGet ( $datas );
	}

	/**
	 * recherche un mediatype dans zabbix a partir de sa description et ajoute le mediatypeId dans l'objet
	 * Le mot All renvoi l'id 0
	 * @return array
	 */
	public function recherche_mediatypeid_by_Name() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getDescription () == "All") {
			return $this->setMediatypeId ( 0 );
		}
		$datas = $this->creer_definition_mediatype_get_ws ();
		$this->onDebug ( $datas, 1 );
		$liste_mediatype = $this->getObjetZabbixWsclient ()
			->mediatypeGet ( $datas );
		foreach ( $liste_mediatype as $mediatypeids ) {
			if (isset ( $mediatypeids ["mediatypeid"] ) && $mediatypeids ["mediatypeid"] != "") {
				$this->setMediatypeId ( $mediatypeids ["mediatypeid"] );
			}
		}
		
		return $this;
	}

	/**
	 * 0 - email;
	 * 1 - script;
	 * 2 - SMS;
	 * 3 - Jabber;
	 * 100 - Ez Texting
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Type($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "script" :
				return 1;
				break;
			case "sms" :
				return 2;
				break;
			case "jabber" :
				return 3;
				break;
			case "ez texting" :
				return 100;
				break;
			case "email" :
			default :
				return 0;
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
	 * @param string $type
	 * @return number|string en fonction du type de mediatype
	 */
	public function retrouve_ExecPath($ExecPath) {
		$this->onDebug ( __METHOD__, 1 );
		switch ($this->getType ()) {
			case 1 :
				return $ExecPath;
				break;
			case 100 :
				switch (strtolower ( $ExecPath )) {
					case "canada" :
						return 1;
						break;
					case "usa" :
					default :
				}
				return 0;
			default :
				$this->onWarning ( "Pas d'ExecPath pour ce type : " . $this->getType () );
		}
		
		return "";
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getMediatypeId() {
		return $this->mediatypeid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMediatypeId($mediatypeid) {
		$this->mediatypeid = $mediatypeid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setType($type) {
		$this->type = $this->retrouve_Type ( $type );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getExecPath() {
		return $this->exec_path;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setExecPath($exec_path) {
		$this->exec_path = $this->retrouve_ExecPath ( $exec_path );
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
	public function getGsmModem() {
		return $this->gsm_modem;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGsmModem($gsm_modem) {
		$this->gsm_modem = $gsm_modem;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSmtpHelo() {
		return $this->smtp_helo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSmtpHelo($smtp_helo) {
		$this->smtp_helo = $smtp_helo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSmtpServer() {
		return $this->smtp_server;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSmtpServer($smtp_server) {
		$this->smtp_server = $smtp_server;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSmtpEmail() {
		return $this->smtp_email;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSmtpEmail($smtp_email) {
		$this->smtp_email = $smtp_email;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassword() {
		return $this->passwd;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword($passwd) {
		$this->passwd = $passwd;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix MediaType :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_nom Nom du mediaType";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_type type du mediaType : email/script/sms/jabber/ez texting ";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_status status du mediaType : enabled/disabled ";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_exec_path script : chemin complet du script. Ez Texting : longueur du message usa/canada ";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_gsm_modem chemin du device modem (/dev/xxx)";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_smtp_helo message du HELO";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_smtp_server FQDN du serveur";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_smtp_email email To";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_username Utilisateur pour jabber et Ez Texting";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mediatype_password Mot de passe pour jabber et Ez Texting";
		
		return $help;
	}
}
?>
