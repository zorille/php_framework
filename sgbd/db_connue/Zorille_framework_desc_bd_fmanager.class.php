<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

/**
 * class gestion_fmanager<br>
 * Gere la connexion a une base fmanager.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class desc_bd_fmanager extends gestion_definition_table {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type desc_bd_fmanager.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return desc_bd_fmanager
	 */
	static function &creer_desc_bd_fmanager(
			&$liste_option,
			$sort_en_erreur = true,
			$entete = __CLASS__) {
		$objet = new desc_bd_fmanager ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return desc_bd_fmanager
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Cree l'objet, prepare la valeur du sort_en_erreur et l'entete des logs.
	 *
	 * @param string $entete Entete a afficher dans les logs.
	 * @param string|bool $sort_en_erreur Prend les valeurs oui/non ou true/false.
	 */
	public function __construct(
			$sort_en_erreur = "oui",
			$entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		$this->charge_table ();
		$this->charge_champs ();
	}

	private function charge_table() {
		$this->setTable ( 'fm_dns_domains', "domains" );
		$this->setTable ( 'fm_dns_records', "records" );
	}

	private function charge_champs() {
		$this->charge_champs_domains ();
		$this->charge_champs_records ();
		return true;
	}

	/*
| domain_id                      | int(11)                                 | NO   | PRI | NULL    | auto_increment |
| account_id                     | int(11)                                 | NO   |     | 1       |                |
| domain_template                | enum('yes','no')                        | NO   |     | no      |                |
| domain_default                 | enum('yes','no')                        | NO   |     | no      |                |
| domain_template_id             | int(11)                                 | NO   |     | 0       |                |
| soa_id                         | int(11)                                 | NO   |     | 0       |                |
| soa_serial_no                  | int(2)                                  | NO   |     | 0       |                |
| soa_serial_no_previous         | int(2)                                  | NO   |     | 0       |                |
| domain_name                    | varchar(255)                            | NO   |     |         |                |
| domain_groups                  | varchar(255)                            | NO   |     | 0       |                |
| domain_name_servers            | varchar(255)                            | NO   |     | 0       |                |
| domain_view                    | varchar(255)                            | NO   |     | 0       |                |
| domain_mapping                 | enum('forward','reverse')               | NO   |     | forward |                |
| domain_type                    | enum('master','slave','forward','stub') | NO   |     | master  |                |
| domain_clone_domain_id         | int(11)                                 | NO   |     | 0       |                |
| domain_clone_dname             | enum('yes','no')                        | YES  |     | NULL    |                |
| domain_dynamic                 | enum('yes','no')                        | NO   |     | no      |                |
| domain_dnssec                  | enum('yes','no')                        | NO   |     | no      |                |
| domain_dnssec_generate_ds      | enum('yes','no')                        | NO   |     | no      |                |
| domain_dnssec_ds_rr            | text                                    | YES  |     | NULL    |                |
| domain_dnssec_parent_domain_id | int(11)                                 | NO   |     | 0       |                |
| domain_dnssec_sig_expire       | int(2)                                  | NO   |     | 0       |                |
| domain_dnssec_signed           | int(2)                                  | NO   |     | 0       |                |
| domain_reload                  | enum('yes','no')                        | NO   |     | no      |                |
| domain_status                  | enum('active','disabled','deleted')     | NO   | MUL | active  |                |

	 */
	private function charge_champs_domains() {
		$this->setChamp ( "domain_id", "domain_id", "domains", "numeric" );
		$this->setChamp ( "account_id", "account_id", "domains", "numeric" );
		$this->setChamp ( "domain_template", "domain_template", "domains", "text" );
		$this->setChamp ( "domain_default", "domain_default", "domains", "text" );
		$this->setChamp ( "domain_template_id", "domain_template_id", "domains", "numeric" );
		$this->setChamp ( "soa_id", "soa_id", "domains", "numeric" );
		$this->setChamp ( "soa_serial_no", "soa_serial_no", "domains", "numeric" );
		$this->setChamp ( "soa_serial_no_previous", "soa_serial_no_previous", "domains", "numeric" );
		$this->setChamp ( "domain_name", "domain_name", "domains", "text" );
		$this->setChamp ( "domain_groups", "domain_groups", "domains", "text" );
		$this->setChamp ( "domain_name_servers", "domain_name_servers", "domains", "text" );
		$this->setChamp ( "domain_view", "domain_view", "domains", "text" );
		$this->setChamp ( "domain_mapping", "domain_mapping", "domains", "text" );
		$this->setChamp ( "domain_type", "domain_type", "domains", "text" );
		$this->setChamp ( "domain_clone_domain_id", "domain_clone_domain_id", "domains", "numeric" );
		$this->setChamp ( "domain_clone_dname", "domain_clone_dname", "domains", "text" );
		$this->setChamp ( "domain_dynamic", "domain_dynamic", "domains", "text" );
		$this->setChamp ( "domain_dnssec", "domain_dnssec", "domains", "text" );
		$this->setChamp ( "domain_dnssec_generate_ds", "domain_dnssec_generate_ds", "domains", "text" );
		$this->setChamp ( "domain_dnssec_ds_rr", "domain_dnssec_ds_rr", "domains", "text" );
		$this->setChamp ( "domain_dnssec_parent_domain_id", "domain_dnssec_parent_domain_id", "domains", "numeric" );
		$this->setChamp ( "domain_dnssec_sig_expire", "domain_dnssec_sig_expire", "domains", "numeric" );
		$this->setChamp ( "domain_dnssec_signed", "domain_dnssec_signed", "domains", "numeric" );
		$this->setChamp ( "domain_reload", "domain_reload", "domains", "text" );
		$this->setChamp ( "domain_status", "domain_status", "domains", "text" );
		return true;
	}

	/*
| record_id        | int(11)                                                                                                                                                           | NO   | PRI | NULL                | auto_increment                |
| account_id       | int(11)                                                                                                                                                           | NO   |     | 1                   |                               |
| domain_id        | int(11)                                                                                                                                                           | NO   | MUL | 0                   |                               |
| record_ptr_id    | int(11)                                                                                                                                                           | NO   |     | 0                   |                               |
| record_timestamp | timestamp                                                                                                                                                         | NO   |     | current_timestamp() | on update current_timestamp() |
| record_name      | varchar(255)                                                                                                                                                      | YES  |     | @                   |                               |
| record_value     | text                                                                                                                                                              | YES  |     | NULL                |                               |
| record_ttl       | varchar(50)                                                                                                                                                       | NO   |     |                     |                               |
| record_class     | enum('IN','CH','HS')                                                                                                                                              | NO   |     | IN                  |                               |
| record_type      | enum('A','AAAA','CAA','CERT','CNAME','DHCID','DLV','DNAME','DNSKEY','DS','HINFO','KEY','KX','MX','NAPTR','NS','OPENPGPKEY','PTR','RP','SRV','TLSA','TXT','SSHFP') | NO   | MUL | A                   |                               |
| record_priority  | int(4)                                                                                                                                                            | YES  |     | NULL                |                               |
| record_weight    | int(4)                                                                                                                                                            | YES  |     | NULL                |                               |
| record_port      | int(4)                                                                                                                                                            | YES  |     | NULL                |                               |
| record_params    | varchar(255)                                                                                                                                                      | YES  |     | NULL                |                               |
| record_regex     | varchar(255)                                                                                                                                                      | YES  |     | NULL                |                               |
| record_os        | varchar(255)                                                                                                                                                      | YES  |     | NULL                |                               |
| record_cert_type | tinyint(4)                                                                                                                                                        | YES  |     | NULL                |                               |
| record_key_tag   | int(11)                                                                                                                                                           | YES  |     | NULL                |                               |
| record_algorithm | tinyint(4)                                                                                                                                                        | YES  |     | NULL                |                               |
| record_flags     | enum('0','256','257','','U','S','A','P')                                                                                                                          | YES  |     | NULL                |                               |
| record_text      | varchar(255)                                                                                                                                                      | YES  |     | NULL                |                               |
| record_comment   | varchar(200)                                                                                                                                                      | YES  |     | NULL                |                               |
| record_append    | enum('yes','no')                                                                                                                                                  | NO   |     | yes                 |                               |
| record_status    | enum('active','disabled','deleted')     
	 */
	private function charge_champs_records() {
		$this->setChamp ( "record_id", "record_id", "records", "numeric" );
		$this->setChamp ( "account_id", "account_id", "records", "numeric" );
		$this->setChamp ( "domain_id", "domain_id", "records", "numeric" );
		$this->setChamp ( "record_ptr_id", "record_ptr_id", "records", "numeric" );
		$this->setChamp ( "record_timestamp", "record_timestamp", "records", "numeric" );
		$this->setChamp ( "record_name", "record_name", "records", "text" );
		$this->setChamp ( "record_value", "record_value", "records", "text" );
		$this->setChamp ( "record_ttl", "record_ttl", "records", "text" );
		$this->setChamp ( "record_class", "record_class", "records", "text" );
		$this->setChamp ( "record_type", "record_type", "records", "text" );
		$this->setChamp ( "record_priority", "record_priority", "records", "numeric" );
		$this->setChamp ( "record_weight", "record_weight", "records", "numeric" );
		$this->setChamp ( "record_port", "record_port", "records", "numeric" );
		$this->setChamp ( "record_params", "record_params", "records", "text" );
		$this->setChamp ( "record_regex", "record_regex", "records", "text" );
		$this->setChamp ( "record_os", "record_os", "records", "text" );
		$this->setChamp ( "record_cert_type", "record_cert_type", "records", "numeric" );
		$this->setChamp ( "record_key_tag", "record_key_tag", "records", "numeric" );
		$this->setChamp ( "record_algorithm", "record_algorithm", "records", "numeric" );
		$this->setChamp ( "record_flags", "record_flags", "records", "text" );
		$this->setChamp ( "record_text", "record_text", "records", "text" );
		$this->setChamp ( "record_comment", "record_comment", "records", "text" );
		$this->setChamp ( "record_append", "record_append", "records", "text" );
		$this->setChamp ( "record_status", "record_status", "records", "text" );
		return true;
	}
}
?>