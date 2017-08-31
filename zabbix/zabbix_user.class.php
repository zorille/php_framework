<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_user
 *
 * userid 	string 	(readonly) ID of the user.
 * alias (required) 	string 	User alias.
 * attempt_clock 	timestamp 	(readonly) Time of the last unsuccessful login attempt.
 * attempt_failed 	integer 	(readonly) Recent failed login attempt count.
 * attempt_ip 	string 	(readonly) IP address from where the last unsuccessful login attempt came from.
 * autologin 	integer 	Whether to enable auto-login.
 * 		Possible values:
 * 		0 - (default) auto-login disabled;
 * 		1 - auto-login enabled.
 * autologout 	integer 	User session life time in seconds. If set to 0, the session will never expire.
 * 		Default: 900.
 * lang 	string 	Language code of the user's language.
 * 		Default: en_GB.
 * name 	string 	Name of the user.
 * refresh 	integer 	Automatic refresh period in seconds.
 * 		Default: 30.
 * rows_per_page 	integer 	Amount of object rows to show per page.
 * 		Default: 50.
 * surname 	string 	Surname of the user.
 * theme 	string 	User's theme.
 * 		Possible values:
 * 		default - (default) system default;
 * 		classic - Classic;
 * 		originalblue - Original blue;
 * 		darkblue - Black & Blue;
 * 		darkorange - Dark orange.
 * type 	integer 	Type of the user.
 * 		Possible values:
 * 		1 - (default) Zabbix user;
 * 		2 - Zabbix admin;
 * 		3 - Zabbix super admin.
 * url 	string 	URL of the page to redirect the user to after logging in. 
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_user extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $userid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $alias = "";
	/**
	 * var privee
	 * 
	 * @access private
	 * @var integer
	 */
	private $attempt_clock = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $attempt_failed = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $attempt_ip = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $autologin = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $autologout = 900;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $lang = "en_GB";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $name = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $refresh = 30;
	/**
	 * var privee
	 *
	 * @access private
	 * @var integer
	 */
	private $rows_per_page = 50;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $surname = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $theme = "default";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $type = "1";
	/**
	 * var privee
	 * 
	 * @access private
	 * @var string
	 */
	private $url = "";
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
	 * @var zabbix_usermedia
	 */
	private $liste_media = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_usergroups
	 */
	private $liste_usergroups = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_user.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_user
	 */
	static function &creer_zabbix_user(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_user ( $sort_en_erreur, $entete );
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
		
		$this->setObjetZabbixWsclient ( $liste_class ["zabbix_wsclient"] );
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
	 * @throws Exception
	 */
	public function retrouve_zabbix_param() {
		$this->onDebug ( __METHOD__, 1 );
		$this->setName ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"nom" 
		), "" ) );
		$this->setAutologin ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"autologin" 
		), "disabled" ) );
		$this->setAutologout ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"autologout" 
		), 900 ) );
		$this->setLang ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"lang" 
		), "en_GB" ) );
		$this->setRefresh ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"refresh" 
		), 30 ) );
		$this->setRowsPerPage ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"rows_per_page" 
		), 50 ) );
		$this->setSurname ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"surname" 
		), "" ) );
		$this->setTheme ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"theme" 
		), "default" ) );
		$this->setType ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"type" 
		), "zabbix user" ) );
		$this->setUrl ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"url" 
		), "" ) );
		$this->setAlias ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"username" 
		) ) );
		$this->setPassword ( $this->_valideOption ( array (
				"zabbix",
				"user",
				"password" 
		), "" ) );
		
		return $this;
	}

	/**
	 * Creer un definition d'un userGroup sous forme de tableau 
	 * @return array;
	 */
	public function creer_definition_user_create_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$userid = array (
				"alias" => $this->getAlias (),
				"name" => $this->getName (),
				"autologin" => $this->getAutologin (),
				"autologout" => $this->getAutologout (),
				"lang" => $this->getLang (),
				"refresh" => $this->getRefresh (),
				"rows_per_page" => $this->getRowsPerPage (),
				"surname" => $this->getSurname (),
				"theme" => $this->getTheme (),
				"type" => $this->getType (),
				"url" => $this->getUrl (),
				"passwd" => $this->getPassword (),
				"usrgrps" => $this->getObjetListeUserGroups ()
					->creer_definition_usergroupsids_ws (),
				"user_medias" => array (
						$this->getObjetListeMedia ()
							->creer_definition_usermedia_create_ws () 
				) 
		);
		
		return $userid;
	}

	/**
	 * Creer un user dans zabbix
	 * @return array
	 */
	public function creer_user() {
		$this->onDebug ( __METHOD__, 1 );
		$userdata = $this->creer_definition_user_create_ws ();
		$this->onDebug ( $userdata, 1 );
		return $this->getObjetZabbixWsclient ()
			->userCreate ( $userdata );
	}

	/**
	 * Creer un definition d'un userGroup sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_user_delete_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$userid = array ();
		
		if ($this->getUsrId () != "") {
			$userid [] .= $this->getUsrId ();
		}
		
		return $userid;
	}

	/**
	 * supprime un user dans zabbix
	 * @return array
	 */
	public function supprime_user() {
		$this->onDebug ( __METHOD__, 1 );
		$userdata = $this->creer_definition_user_delete_ws ();
		$this->onDebug ( $userdata, 1 );
		return $this->getObjetZabbixWsclient ()
			->userDelete ( $userdata );
	}

	/**
	 * Creer un definition d'un user sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_user_get_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$filter = array (
				"alias" => $this->getAlias () 
		);
		if ($this->getName () != "") {
			$filter ["name"] = $this->getName ();
		}
		
		return array (
				"output" => "userid",
				"filter" => $filter 
		);
	}

	/**
	 * recherche un user dans zabbix a partir de son alias (username)
	 * son nom peux etre ajoute
	 * @return array
	 */
	public function recherche_user() {
		$this->onDebug ( __METHOD__, 1 );
		$userdata = $this->creer_definition_user_get_ws ();
		$this->onDebug ( $userdata, 1 );
		return $this->getObjetZabbixWsclient ()
			->userGet ( $userdata );
	}

	/**
	 * Creer un definition d'un user alias sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_userByAlias_get_ws() {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"output" => "userid",
				"filter" => array (
						"alias" => $this->getAlias () 
				) 
		);
	}

	/**
	 * Creer un definition d'un userid sous forme de tableau
	 * @return array;
	 */
	public function creer_definition_userid_get_ws() {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				"userid" => $this->getUsrId () 
		);
	}

	/**
	 * recherche un host dans zabbix a partir de son alias (username)
	 * @return zabbix_user
	 */
	public function recherche_userid_by_Alias() {
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_userByAlias_get_ws ();
		$this->onDebug ( $datas, 1 );
		$liste_resultat = $this->getObjetZabbixWsclient ()
			->userGet ( $datas );
		if (isset ( $liste_resultat [0] ) && isset ( $liste_resultat [0] ["userid"] )) {
			$this->setUsrId ( $liste_resultat [0] ["userid"] );
		}
		
		return $this;
	}

	/**
	 * 0 - disabled;
	 * 1 - enabled;
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Autologin($autologin) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $autologin )) {
			return $autologin;
		}
		switch (strtolower ( $autologin )) {
			case "enabled" :
				return 1;
				break;
			case "disabled" :
			default :
		}
		
		return 0;
	}

	/**
	 * 1 - (default) Zabbix user;
	 * 2 - Zabbix admin;
	 * 3 - Zabbix super admin.
	 * @param string $type
	 * @return number
	 */
	public function retrouve_Type($type) {
		$this->onDebug ( __METHOD__, 1 );
		if (is_numeric ( $type )) {
			return $type;
		}
		switch (strtolower ( $type )) {
			case "zabbix admin" :
				return 2;
				break;
			case "zabbix super admin" :
				return 3;
				break;
			case "zabbix user" :
			default :
		}
		
		return 1;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getUsrId() {
		return $this->userid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsrId($userid) {
		$this->userid = $userid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAlias() {
		return $this->alias;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAlias($alias) {
		$this->alias = $alias;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAttemptClock() {
		return $this->attempt_clock;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAttemptClock($attempt_clock) {
		$this->attempt_clock = $attempt_clock;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAttemptFailed() {
		return $this->attempt_failed;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAttemptFailed($attempt_failed) {
		$this->attempt_failed = $attempt_failed;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAttemptIp() {
		return $this->attempt_ip;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAttemptIp($attempt_ip) {
		$this->attempt_ip = $attempt_ip;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAutologin() {
		return $this->autologin;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAutologin($autologin) {
		$this->autologin = $this->retrouve_Autologin ( $autologin );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAutologout() {
		return $this->autologout;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAutologout($autologout) {
		$this->autologout = $autologout;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getLang() {
		return $this->lang;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLang($lang) {
		$this->lang = $lang;
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
	public function getRefresh() {
		return $this->refresh;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRefresh($refresh) {
		$this->refresh = $refresh;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRowsPerPage() {
		return $this->rows_per_page;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRowsPerPage($rows_per_page) {
		$this->rows_per_page = $rows_per_page;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSurname() {
		return $this->surname;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSurname($surname) {
		$this->surname = $surname;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTheme() {
		return $this->theme;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTheme($theme) {
		$this->theme = $theme;
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
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUrl($url) {
		$this->url = $url;
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

	/**
	 * @codeCoverageIgnore
	 * @return zabbix_usermedia
	 */
	public function &getObjetListeMedia() {
		return $this->liste_media;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetListeMedia(&$liste_media) {
		$this->liste_media = $liste_media;
		return $this;
	}

	/**
	* @codeCoverageIgnore
	* @return zabbix_usergroups
	*/
	public function &getObjetListeUserGroups() {
		return $this->liste_usergroups;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetListeUserGroups(&$liste_usergroups) {
		$this->liste_usergroups = $liste_usergroups;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix User :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_nom Nom du user";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_username Alias du user";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_autologin enabled/disabled";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_autologout 900 temps max avant logout";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_lang en_GB Langue au format systeme";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_refresh 30 temps max avant refresh";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_rows_per_page 50 ligne par page";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_surname '' Nom a l'affichage de l'utilisateur";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_theme  default Theme de l'interface";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_type 'zabbix user' Type d'utilisateur zabbix user/zabbix admin/zabbix super admin";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_url '' Url de l'utilisateur pour redirection apres connexion";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_user_password 'xxx' Password de l'utilisateur en cas de creation";
		$help = array_merge ( $help, zabbix_usermedia::help () );
		$help = array_merge ( $help, zabbix_usergroups::help () );
		
		return $help;
	}
}
?>
