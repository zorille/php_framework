<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_screens
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_screens extends zabbix_fonctions_standard {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_screens.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_screens
	 */
	static function &creer_zabbix_screens(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_screens ( $sort_en_erreur, $entete );
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
	public function __construct($sort_en_erreur = false, $entete = __CLASS__ ) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * @return array
	 */
	public function creer_definition_screenids_sans_champ_screenid_ws() {
		$this->onDebug ( __METHOD__, 1 );
		return array();
	}
	
	/******************************* ACCESSEURS ********************************/


	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Screens :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_screens";
		
		return $help;
	}
}
?>
