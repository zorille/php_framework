<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class gestion_bd_cacti<br>
 *
 * Gere la connexion a une base cacti.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class desc_bd_cacti extends gestion_definition_table {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type desc_bd_cacti.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return desc_bd_cacti
	 */
	static function &creer_desc_bd_cacti(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new desc_bd_cacti ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return desc_bd_cacti
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Cree l'objet, prepare la valeur du sort_en_erreur et l'entete des logs.
	 *
	 * @param string $entete Entete a afficher dans les logs.
	 * @param string|bool $sort_en_erreur Prend les valeurs oui/non ou true/false.
	 */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->_chargeTable ();
		$this->_chargeChamps ();
	}

	private function _chargeTable() {
		$this->setTable ( 'host', "host" );
		$this->setTable ( 'host_template', "host_template" );
	}

	private function _chargeChamps () {
		$this->_chargeChampsHost ();
		$this->_chargeChampsHostTemplate ();
		
		return true;
	}

	private function _chargeChampsHost() {
		$this->setChamp ( "id", "id", "host", "numeric" );
		$this->setChamp ( "host_template_id", "host_template_id", "host", "numeric" );
		$this->setChamp ( "description", "description", "host", "text" );
		$this->setChamp ( "hostname", "hostname", "host", "text" );
		$this->setChamp ( "notes", "notes", "host", "text" );
		$this->setChamp ( "snmp_community", "snmp_community", "host", "text" );
		$this->setChamp ( "snmp_version", "snmp_version", "host", "numeric" );
		$this->setChamp ( "snmp_username", "snmp_username", "host", "text" );
		$this->setChamp ( "snmp_password", "snmp_password", "host", "text" );
		$this->setChamp ( "snmp_auth_protocol", "snmp_auth_protocol", "host", "text" );
		$this->setChamp ( "snmp_priv_passphrase", "snmp_priv_passphrase", "host", "text" );
		$this->setChamp ( "snmp_priv_protocol", "snmp_priv_protocol", "host", "text" );
		$this->setChamp ( "snmp_context", "snmp_context", "host", "text" );
		$this->setChamp ( "snmp_port", "snmp_port", "host", "numeric" );
		$this->setChamp ( "snmp_timeout", "snmp_timeout", "host", "numeric" );
		$this->setChamp ( "availability_method", "availability_method", "host", "numeric" );
		$this->setChamp ( "ping_method", "ping_method", "host", "numeric" );
		$this->setChamp ( "ping_port", "ping_port", "host", "numeric" );
		$this->setChamp ( "ping_timeout", "ping_timeout", "host", "numeric" );
		$this->setChamp ( "ping_retries", "ping_retries", "host", "numeric" );
		$this->setChamp ( "max_oids", "max_oids", "host", "numeric" );
		$this->setChamp ( "device_threads", "device_threads", "host", "numeric" );
		$this->setChamp ( "disabled", "disabled", "host", "text" );
		$this->setChamp ( "status", "status", "host", "numeric" );
		$this->setChamp ( "status_event_count", "status_event_count", "host", "numeric" );
		$this->setChamp ( "status_fail_date", "status_fail_date", "host", "date" );
		$this->setChamp ( "status_rec_date", "status_rec_date", "host", "date" );
		$this->setChamp ( "status_last_error", "status_last_error", "host", "text" );
		$this->setChamp ( "min_time", "min_time", "host", "numeric" );
		$this->setChamp ( "max_time", "max_time", "host", "numeric" );
		$this->setChamp ( "cur_time", "cur_time", "host", "numeric" );
		$this->setChamp ( "avg_time", "avg_time", "host", "numeric" );
		$this->setChamp ( "total_polls", "total_polls", "host", "numeric" );
		$this->setChamp ( "failed_polls", "failed_polls", "host", "numeric" );
		$this->setChamp ( "availability", "availability", "host", "numeric" );
		
		return true;
	}

	private function _chargeChampsHostTemplate() {
		$this->setChamp ( "id", "id", "host_template", "numeric" );
		$this->setChamp ( "hash", "hash", "host_template", "text" );
		$this->setChamp ( "name", "name", "host_template", "text" );
		
		return true;
	}

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>