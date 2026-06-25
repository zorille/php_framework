<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

use Exception;

/**
 * class requete_complexe_itop<br>
 *
 * Gere la connexion a une base itop.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class requete_complexe_itop extends desc_bd_itop {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type requete_complexe_itop.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_complexe_itop
	 */
	static function &creer_requete_complexe_itop(
		options     &$liste_option,
		bool|string $sort_en_erreur = true,
		string      $entete = __CLASS__): requete_complexe_itop
	{
		$objet = new requete_complexe_itop ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_complexe_itop
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @throws Exception
	 */
	public function select_table_params(
			$table_recherche): \PDO|bool|array
	{
		$table = 'INFORMATION_SCHEMA.TABLES';
		$select = array ();
		$this->fabrique_select ( $select, $table, "AUTO_INCREMENT" );
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$where = array ();
		$this->fabrique_where ( $where, $table, "TABLE_SCHEMA", $this->getDatabase () );
		$this->fabrique_where ( $where, $table, "TABLE_NAME", $table_recherche );
		return $this->selectionner ( $select, $from, $where );
		// SHOW TABLE STATUS FROM `itop_test` WHERE `name` LIKE 'ticket'
		/* SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'DatabaseName' AND TABLE_NAME = 'TableName'; */
	}
}
