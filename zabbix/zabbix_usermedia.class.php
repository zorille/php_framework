<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_usermedia
 *
 * mediaid 	string 	(readonly) ID of the media.
 * active (required) 	integer 	Whether the media is enabled.
 * 		Possible values:
 * 		0 - enabled;
 * 		1 - disabled.
 * mediatypeid (required) 	string 	ID of the media type used by the media.
 * period (required) 	string 	Time when the notifications can be sent as a time period.
 * sendto (required) 	string 	Address, user name or other identifier of the recipient.
 * severity (required) 	integer 	Trigger severities to send notifications about.
 * 		Severities are stored in binary form with each bit representing the corresponding severity. For example, 12 equals 1100 in binary and means, that notifications will be sent from triggers with severities warning and average.
 * 		Refer to the trigger object page for a list of supported trigger severities.
 * userid (required) 	string 	ID of the user that uses the media. 
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_usermedia extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $mediaid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $active = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $period = "1-7,00:00-24:00";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $sendto = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $severity = "63";
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
	private $userid = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_usermedia.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_usermedia
	 */
	static function &creer_zabbix_usermedia(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_usermedia ( $sort_en_erreur, $entete );
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
	 * @return boolean True est OK, False sinon.
	 */
	public function retrouve_zabbix_param() {
		$this->onDebug ( __METHOD__, 1 );
		$this->setActive ( $this->_valideOption ( array (
				"zabbix",
				"usermedia",
				"active" 
		), "enabled" ) );
		$this->setPeriod ( $this->_valideOption ( array (
				"zabbix",
				"usermedia",
				"period" 
		), "1-7,00:00-24:00" ) );
		$this->setSendto ( $this->_valideOption ( array (
				"zabbix",
				"usermedia",
				"sendto" 
		) ) );
		$this->setSeverity ( $this->_valideOption ( array (
				"zabbix",
				"usermedia",
				"severity" 
		), "63" ) );
		
		return $this;
	}

	/**
	 * Creer un definition d'un usermedia sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_usermedia_create_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$mediaid = array (
				"active" => $this->getActive (),
				"period" => $this->getPeriod (),
				"sendto" => $this->getSendto (),
				"severity" => $this->getSeverity (),
				"mediatypeid" => $this->getMediaTypeId () 
		);
		
		if ($this->getUserId () != "") {
			$mediaid ["userid"] = $this->getUserId ();
		}
		
		if ($this->getMediaId () != "") {
			$mediaid ["mediaid"] = $this->getMediaId ();
		}
		
		return $mediaid;
	}

	/**
	 * Creer un usermedia dans zabbix
	 * @return array
	 */
	public function creer_usermedia() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_usermedia_create_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->userAddMedia ( $datas );
	}

	/**
	 * Creer un definition d'un usermedia sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_usermedia_delete_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$mediaid = array ();
		
		if ($this->getMediaId () != "") {
			$mediaid [] .= $this->getMediaId ();
		}
		
		return $mediaid;
	}

	/**
	 * supprime un usermedia dans zabbix
	 * @return array
	 */
	public function supprime_usermedia() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_mediaids = $this->recherche_usermedia ();
		foreach ( $liste_mediaids as $mediaid ) {
			if (isset ( $mediaid ['mediaid'] )) {
				$this->setMediaId ( $mediaid ['mediaid'] );
				$datas = $this->creer_definition_usermedia_delete_ws ();
				$this->onDebug ( $datas, 1 );
				return $this->getObjetZabbixWsclient ()
					->userDeleteMedia ( $datas );
			}
		}
		return array ();
	}

	/**
	 * Creer un definition d'un usermedia sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_usermedia_get_ws() {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"output" => "mediaid",
				"filter" => array (
						"sendto" => $this->getSendto () 
				) 
		);
	}

	/**
	 * recherche un usermedia dans zabbix a partir de son sendto
	 * @return array
	 */
	public function recherche_usermedia() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_usermedia_get_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->usermediaGet ( $datas );
	}

	/**
	 * 0 - enabled;
	 * 1 - disabled;
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Active($type) {
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
		}
		
		return 0;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getMediaId() {
		return $this->mediaid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMediaId($mediaid) {
		$this->mediaid = $mediaid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setActive($active) {
		$this->active = $this->retrouve_Active ( $active );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPeriod() {
		return $this->period;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPeriod($period) {
		$this->period = $period;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSendto() {
		return $this->sendto;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSendto($sendto) {
		$this->sendto = $sendto;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSeverity() {
		return $this->severity;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSeverity($severity) {
		$this->severity = $severity;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMediaTypeId() {
		return $this->mediatypeid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMediaTypeId($mediatypeid) {
		$this->mediatypeid = $mediatypeid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUserId() {
		return $this->userid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUserId($userid) {
		$this->userid = $userid;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix UserMedia :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usermedia_active status du usermedia : enabled/disabled ";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usermedia_period '1-7,00:00-24:00'";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usermedia_sendto mail@mail.com Email";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_usermedia_severity 63";
		
		return $help;
	}
}
?>
