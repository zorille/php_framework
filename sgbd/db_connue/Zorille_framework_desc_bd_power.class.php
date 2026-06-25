<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

/**
 * class gestion_bd_power<br>
 * Gere la connexion a une base power.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class desc_bd_power extends gestion_definition_table {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type desc_bd_power.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return desc_bd_power
	 */
	static function &creer_desc_bd_power(
		options     &$liste_option,
		bool|string $sort_en_erreur = true,
		string      $entete = __CLASS__): desc_bd_power
	{
		$objet = new desc_bd_power ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return desc_bd_power
	 */
	public function &_initialise(
        array $liste_class): static
	{
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

	private function charge_table(): void
	{
		$this->setTable ( 'rack', "rack" );
		$this->setTable ( 'wago_new', "wago" );
		$this->setTable ( 'customer_DC2', "customer_DC2" );
		$this->setTable ( 'consoDC2', "consoDC2" );
		$this->setTable ( 'customer_DC5', "customer_DC5" );
		$this->setTable ( 'consoDC5', "consoDC5" );
		$this->setTable ( 'customer_DC6', "customer_DC6" );
		$this->setTable ( 'consoDC6', "consoDC6" );
	}

	private function charge_champs(): void
	{
		$this->charge_champs_rack ();
		$this->charge_champs_wago ();
		$this->charge_champs_customer_DC2();
		$this->charge_champs_consoDC2();
		$this->charge_champs_customer_DC5();
		$this->charge_champs_consoDC5();
		$this->charge_champs_customer_DC6();
		$this->charge_champs_consoDC6();
	}

	/*
| compteur | int(10)      | YES  | MUL | NULL    |                |
| row      | varchar(10)  | YES  |     | NULL    |                |
| col      | varchar(10)  | YES  |     | NULL    |                |
| path     | varchar(10)  | YES  |     | NULL    |                |
| customer | varchar(100) | YES  | MUL | NULL    |                |
| phase    | varchar(10)  | YES  |     | NULL    |                |
| oldcoef  | varchar(5)   | YES  |     | NULL    |                |
| coef     | int(5)       | YES  |     | NULL    |                |
| building | varchar(10)  | YES  |     | NULL    |                |
| room     | varchar(10)  | YES  |     | NULL    |                |
| order    | varchar(10)  | YES  |     | NULL    |                |
| status   | varchar(5)   | YES  |     | NULL    |                |
| id       | int(11)      | NO   | PRI | NULL    | auto_increment |
| coeftmp  | varchar(5)   | YES  |     | NULL    |                |

	 */
	private function charge_champs_rack(): void
	{
		$this->setChamp ( "compteur", "compteur", "rack", "numeric" );
		$this->setChamp ( "row", "row", "rack", "text" );
		$this->setChamp ( "col", "col", "rack", "text" );
		$this->setChamp ( "path", "path", "rack", "text" );
		$this->setChamp ( "customer", "customer", "rack", "text" );
		$this->setChamp ( "phase", "phase", "rack", "text" );
		$this->setChamp ( "oldcoef", "oldcoef", "rack", "text" );
		$this->setChamp ( "coef", "coef", "rack", "numeric" );
		$this->setChamp ( "building", "building", "rack", "text" );
		$this->setChamp ( "room", "room", "rack", "text" );
		$this->setChamp ( "order", "order", "rack", "text" );
		$this->setChamp ( "status", "status", "rack", "text" );
		$this->setChamp ( "id", "id", "rack", "numeric" );
		$this->setChamp ( "coeftmp", "coeftmp", "rack", "text" );
	}

	/*
| num      | int(10)    | YES  | MUL | NULL    |       |
| amount   | bigint(20) | YES  |     | NULL    |       |
| date     | datetime   | YES  | MUL | NULL    |       |
| building | varchar(5) | YES  |     | NULL    |       |
| room     | varchar(5) | YES  |     | NULL    |       |
	 */
	private function charge_champs_wago(): void
	{
		$this->setChamp ( "num", "num", "wago", "numeric" );
		$this->setChamp ( "amount", "amount", "wago", "numeric" );
		$this->setChamp ( "date", "date", "wago", "date" );
		$this->setChamp ( "building", "building", "wago", "text" );
		$this->setChamp ( "room", "room", "wago", "text" );
	}
	
	/*
	 customer_DC2;
+----------+--------------+------+-----+---------+-------+
| Field    | Type         | Null | Key | Default | Extra |
+----------+--------------+------+-----+---------+-------+
| name     | varchar(100) | NO   | PRI | NULL    |       |
| customer | varchar(100) | NO   | PRI | NULL    |       |
| pdpm     | varchar(20)  | NO   | PRI | NULL    |       |

	 */
	private function charge_champs_customer_DC2(): void
	{
		$this->setChamp ( "name", "name", "customer_DC2", "text" );
		$this->setChamp ( "customer", "customer", "customer_DC2", "text" );
		$this->setChamp ( "pdpm", "pdpm", "customer_DC2", "text" );
	}
	
	/*
| pdpm     | varchar(100) | NO   | PRI |         |       |
| customer | varchar(100) | NO   | PRI | NULL    |       |
| location | varchar(100) | YES  | MUL | NULL    |       |
| date     | varchar(100) | NO   | PRI |         |       |
| datets   | datetime     | YES  | MUL | NULL    |       |
| amount   | varchar(100) | YES  |     | NULL    |       |

	 */
	private function charge_champs_consoDC2(): void
	{
		$this->setChamp ( "pdpm", "pdpm", "consoDC2", "text" );
		$this->setChamp ( "customer", "customer", "consoDC2", "text" );
		$this->setChamp ( "location", "location", "consoDC2", "text" );
		$this->setChamp ( "date", "date", "consoDC2", "text" );
		$this->setChamp ( "datets", "datets", "consoDC2", "date" );
		$this->setChamp ( "amount", "amount", "consoDC2", "text" );
	}
	
	private function charge_champs_customer_DC5(): void
	{
		$this->setChamp ( "name", "name", "customer_DC5", "text" );
		$this->setChamp ( "customer", "customer", "customer_DC5", "text" );
		$this->setChamp ( "pdpm", "pdpm", "customer_DC5", "text" );
	}
	
	private function charge_champs_consoDC5(): void
	{
		$this->setChamp ( "pdpm", "pdpm", "consoDC5", "text" );
		$this->setChamp ( "customer", "customer", "consoDC5", "text" );
		$this->setChamp ( "location", "location", "consoDC5", "text" );
		$this->setChamp ( "date", "date", "consoDC5", "text" );
		$this->setChamp ( "datets", "datets", "consoDC5", "date" );
		$this->setChamp ( "amount", "amount", "consoDC5", "text" );
	}
	
	private function charge_champs_customer_DC6(): void
	{
		$this->setChamp ( "name", "name", "customer_DC6", "text" );
		$this->setChamp ( "customer", "customer", "customer_DC6", "text" );
		$this->setChamp ( "pdpm", "pdpm", "customer_DC6", "text" );
	}
	
	private function charge_champs_consoDC6(): bool
	{
		$this->setChamp ( "pdpm", "pdpm", "consoDC6", "text" );
		$this->setChamp ( "customer", "customer", "consoDC6", "text" );
		$this->setChamp ( "location", "location", "consoDC6", "text" );
		$this->setChamp ( "date", "date", "consoDC6", "text" );
		$this->setChamp ( "datets", "datets", "consoDC6", "date" );
		$this->setChamp ( "amount", "amount", "consoDC6", "text" );
		return true;
	}
}
