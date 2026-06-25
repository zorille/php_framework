<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class oql<br>
 * Gere la construction d'une requete SQL.
 * 
 * @package Lib
 * @subpackage SQL
 */
class oql extends xql {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type oql.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return oql
	 */
	static function &creer_oql(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new oql ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return oql
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * @codeCoverageIgnore
	 * @param string $entete
	 * @param string $sort_en_erreur
	 */
	public function __construct($sort_en_erreur = false, string $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}
	
	// Requete SELECT FROM WHERE

	/**
	 * Creer un requete SQL type SELECT...FROM...WHERE.
	 *
	 * @param string $from Liste de champ du FROM.
	 * @param array|string $where Liste de champ du WHERE.
	 * @param string $option Option supplementaire (ORDER BY, GROUP BY ...).
	 * @return bool|oql
	 * @throws Exception
	 */
	public function creer_select(string $from, array|string $where, string $option = ""): bool|static
	{
		// creation de la requete
		$oql = "SELECT ";
		if (is_string ( $from )) {
			$oql .= $from . " ";
		} else {
			return $this->onError("Le FROM ne peut pas etre un tableau");
		}
		$oql .= $this ->creer_where ( $where );
		if ($option != "")
			$oql .= " $option ";
		$this ->setRequete ( $oql );
		
		return $this;
	}

	/**
	 * Creer un requete SQL type FROM .
	 * . JOIN .. ON ...
	 *
	 * @param $from
	 * @return bool|string
	 * @throws Exception
	 */
	public function creer_from_join($from): bool|string
	{
		$from_join = "";
		// creation de la requete
		if (is_array ( $from )) {
			foreach ( $from as $data ) {
				if (! is_array ( $data )) {
					if ($from_join != "") {
						return $this->onError("Il ne peux y avoir qu'un FROM en OQL");
					}
					$from_join .= $data;
				} else {
					$join_op = match ($data ["type"]) {
						"BELOW" => " BELOW ",
						"BELOW STRICT" => " BELOW STRICT ",
						"ABOVE" => " ABOVE ",
						"ABOVE STRICT" => " ABOVE STRICT ",
						default => "=",
					};
					$from_join .= " JOIN " . $data ["table"] . " ON " . $data ["champ1"] . $join_op . $data ["champ2"];
				}
			}
		}
		return $from_join;
	}

	/***************** Accesseurs ********************/
	
	/***************** Accesseurs ********************/

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
