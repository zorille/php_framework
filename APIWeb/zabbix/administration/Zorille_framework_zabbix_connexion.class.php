<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_connexion
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_connexion extends zabbix_fonctions_standard {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_connexion.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_connexion
	 */
	static function &creer_zabbix_connexion(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_connexion ( $sort_en_erreur, $entete );
		return $objet ->_initialise ( array (
				"options" => $liste_option ) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->setObjetZabbixWsclient ( zabbix_wsclient::creer_zabbix_wsclient ( $liste_class ['options'], zabbix_datas::creer_zabbix_datas ( $liste_class ['options'] ) ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return $this
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
 	* Connecte le zabbix defini par --zabbix_serveur
 	* @return zabbix_connexion
 	* @throws Exception
 	*/
	public function connect_zabbix() {
		if ($this ->getListeOptions () 
			->verifie_option_existe ( "zabbix_serveur" ) === false) {
			return $this ->onError ( "Il faut un --zabbix_serveur pour travailler." );
		}
		
		//On se connecte au zabbix
		$this ->getObjetZabbixWsclient () 
			->prepare_connexion ( $this ->getListeOptions () 
			->getOption ( "zabbix_serveur" ) );
		
		return $this;
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
		$help [__CLASS__] ["text"] [] .= __CLASS__ . " :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_serveur Nom du zabbix a utiliser";
		$help = array_merge ( $help, zabbix_wsclient::help () );
		
		return $help;
	}
}
?>
