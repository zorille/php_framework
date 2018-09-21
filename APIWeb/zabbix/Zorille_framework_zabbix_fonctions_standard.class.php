<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_fonctions_standard
 * 
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_fonctions_standard extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_wsclient
	 */
	private $zabbix_wsclient = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_fonctions_standard.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_fonctions_standard
	 */
	static function &creer_zabbix_fonctions_standard(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_fonctions_standard ( $sort_en_erreur, $entete  );
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
		
		if(isset($liste_class ["zabbix_wsclient"])) {
			$this->setObjetZabbixWsclient ( $liste_class ["zabbix_wsclient"] );
		}
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
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Extrait des parametres d'une liste d'option
	 * @codeCoverageIgnore
	 * @param string|array $chemin_option
	 * @return boolean string array
	 * @throws Exception
	 */
	public function _valideOption($chemin_option, $valeur_par_defaut = null) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_variable_standard ( $chemin_option ) === false && $valeur_par_defaut === null) {
			if(is_array($chemin_option)){
				$chemin_option=implode("_",$chemin_option);
			}
			return $this->onError ( "Il manque le parametre : " . $chemin_option );
		}
		
		$datas = $this->getListeOptions ()
			->renvoi_variables_standard ( $chemin_option, $valeur_par_defaut );
		
		if (is_array($datas) && isset ( $datas ["#comment"] )) {
			unset ( $datas ["#comment"] );
		}
		
		return $datas;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetZabbixWsclient() {
		return $this->zabbix_wsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetZabbixWsclient(&$zabbix_wsclient) {
		$this->zabbix_wsclient = $zabbix_wsclient;
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
		$help = array_merge ( $help, zabbix_wsclient::help () );
		
		return $help;
	}
}
?>
