<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

/**
 * class gestion_bd_itop<br>
 * Gere la connexion a une base itop.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class desc_bd_itop extends gestion_definition_table {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type desc_bd_itop.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return desc_bd_itop
	 */
	static function &creer_desc_bd_itop(
			&$liste_option,
			$sort_en_erreur = true,
			$entete = __CLASS__) {
		$objet = new desc_bd_itop ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return desc_bd_itop
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
		$this->setTable ( 'INFORMATION_SCHEMA.TABLES', "INFORMATION_SCHEMA.TABLES" );
		$this->setTable ( 'key_value_store', "key_value_store" );
		$this->setTable ( 'ticket', "ticket" );
	}

	private function charge_champs() {
		$this->charge_champs_key_value_store ();
		$this->charge_champs_ticket ();
		$this->charge_champs_schema ();
		return true;
	}

	/*
| id        | int(11)      | NO   | PRI | NULL    | auto_increment |
| namespace | varchar(255) | YES  |     |         |                |
| key_name  | varchar(255) | YES  | MUL |         |                |
| value     | varchar(255) | YES  |     | 0       |                |

	 */
	private function charge_champs_key_value_store() {
		$this->setChamp ( "id", "id", "key_value_store", "numeric" );
		$this->setChamp ( "namespace", "namespace", "key_value_store", "text" );
		$this->setChamp ( "key_name", "key_name", "key_value_store", "text" );
		$this->setChamp ( "value", "value", "key_value_store", "text" );
		return true;
	}
	/*
| id                 | int(11)                             | NO   | PRI | NULL    | auto_increment |
| operational_status | enum('closed','ongoing','resolved') | YES  | MUL | ongoing |                |
| ref                | varchar(255)                        | YES  | MUL |         |                |
| org_id             | int(11)                             | YES  | MUL | 0       |                |
| caller_id          | int(11)                             | YES  | MUL | 0       |                |
| team_id            | int(11)                             | YES  | MUL | 0       |                |
| agent_id           | int(11)                             | YES  | MUL | 0       |                |
| title              | varchar(255)                        | YES  |     |         |                |
| description        | text                                | YES  |     | NULL    |                |
| description_format | enum('text','html')                 | YES  |     | text    |                |
| start_date         | datetime                            | YES  |     | NULL    |                |
| end_date           | datetime                            | YES  |     | NULL    |                |
| last_update        | datetime                            | YES  |     | NULL    |                |
| close_date         | datetime                            | YES  |     | NULL    |                |
| private_log        | longtext                            | YES  |     | NULL    |                |
| private_log_index  | blob                                | YES  |     | NULL    |                |
| finalclass         | varchar(255)                        | YES  | MUL | Ticket  |                |
| archive_flag       | tinyint(1)                          | YES  | MUL | 0       |                |
| archive_date       | date                                | YES  |     | NULL    |                |
| related_project_id | int(11)                             | YES  | MUL | 0       |                |

	 */
	private function charge_champs_ticket() {
		$this->setChamp ( "id", "id", "ticket", "numeric" );
		$this->setChamp ( "operational_status", "operational_status", "ticket", "text" );
		$this->setChamp ( "ref", "ref", "ticket", "text" );
		$this->setChamp ( "org_id", "org_id", "ticket", "numeric" );
		$this->setChamp ( "caller_id", "caller_id", "ticket", "numeric" );
		$this->setChamp ( "team_id", "team_id", "ticket", "numeric" );
		$this->setChamp ( "agent_id", "agent_id", "ticket", "numeric" );
		$this->setChamp ( "title", "title", "ticket", "text" );
		$this->setChamp ( "description", "description", "ticket", "text" );
		$this->setChamp ( "description_format", "description_format", "ticket", "text" );
		$this->setChamp ( "start_date", "start_date", "ticket", "date" );
		$this->setChamp ( "end_date", "end_date", "ticket", "date" );
		$this->setChamp ( "last_update", "last_update", "ticket", "date" );
		$this->setChamp ( "close_date", "close_date", "ticket", "date" );
		$this->setChamp ( "private_log", "private_log", "ticket", "text" );
		$this->setChamp ( "private_log_index", "private_log_index", "ticket", "numeric" );
		$this->setChamp ( "finalclass", "finalclass", "ticket", "text" );
		$this->setChamp ( "archive_flag", "archive_flag", "ticket", "numeric" );
		$this->setChamp ( "archive_date", "archive_date", "ticket", "date" );
		$this->setChamp ( "related_project_id", "related_project_id", "ticket", "numeric" );
		$this->setChamp ( "AUTO_INCREMENT", "AUTO_INCREMENT", "ticket", "numeric" );
		return true;
	}
	
	private function charge_champs_schema() {
		$this->setChamp ( "AUTO_INCREMENT", "AUTO_INCREMENT", "INFORMATION_SCHEMA.TABLES", "numeric" );
		$this->setChamp ( "TABLE_SCHEMA", "TABLE_SCHEMA", "INFORMATION_SCHEMA.TABLES", "text" );
		$this->setChamp ( "TABLE_NAME", "TABLE_NAME", "INFORMATION_SCHEMA.TABLES", "text" );
		return true;
	}

}
?>