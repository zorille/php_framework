<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class sql<br>
 * Gere la construction d'une requete SQL.
 * 
 * @package Lib
 * @subpackage SQL
 */
class sql extends abstract_log {
	/**
	 * var privee
	 * 
	 * @access private
	 * @var string
	 */
	private $sql;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type sql.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return sql
	 */
	static function &creer_sql(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new sql ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return sql
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * @codeCoverageIgnore
	 * @param string $entete
	 * @param string $sort_en_erreur
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Creer une liste de condition WHERE a partir d'une ligne ou d'un tableau.
	 *
	 * @param string|array $where Liste de condition WHERE.
	 * @return string Ligne WHERE.
	 */
	public function creer_where($where) {
		$where_sql = " WHERE";
		if (is_array ( $where )) {
			$size = sizeof ( $where );
			if ($size == 0 || $where [0] == "")
				return;
			return $where_sql . " " . $this->creer_liste_and ( $where );
		} else {
			if ($where == "")
				return "";
			$where_sql .= " " . $where;
		}
		
		return $where_sql;
	}

	/**
	 * Creer une liste de condition WHERE a partir d'une ligne ou d'un tableau.
	 *
	 * @param array $where 	Liste de condition WHERE.
	 * @return string Ligne WHERE.
	 */
	public function creer_liste_and($where) {
		$where_sql = "";
		
		if (is_array ( $where )) {
			$size = sizeof ( $where );
			if ($size == 0 || $where [0] == "")
				return;
			$where_sql = $where [0];
			for($i = 1; $i < $size; $i ++) {
				$where_sql .= " AND " . $where [$i];
			}
		}
		return $where_sql;
	}

	/**
	 * Creer une liste de condition WHERE a partir d'une ligne ou d'un tableau.
	 *
	 * @param array $where 	Liste de condition WHERE.
	 * @return string Ligne WHERE.
	 */
	public function creer_liste_or($where) {
		$where_sql = "";
		
		if (is_array ( $where )) {
			$size = sizeof ( $where );
			if ($size == 0 || $where [0] == "")
				return;
			$where_sql = $where [0];
			for($i = 1; $i < $size; $i ++) {
				$where_sql .= " OR " . $where [$i];
			}
		}
		return $where_sql;
	}

	/**
	 * Creer une liste de condition WHERE a partir d'une ligne ou d'un tableau.
	 *
	 * @param array $tableau Liste de valeur.
	 * @param string $guillemet Type de guillement a ajouter.
	 * @return string Ligne de valeur separe par de ",".
	 */
	public function creer_liste($tableau, $guillemet = "") {
		if (! is_array ( $tableau )) {
			return false;
		}
		$size = sizeof ( $tableau );
		if ($size == 0)
			return;
		$liste = "$guillemet$tableau[0]$guillemet";
		for($i = 1; $i < $size; $i ++) {
			$liste .= ",$guillemet$tableau[$i]$guillemet";
		}
		return $liste;
	}
	// Fin des fonctions commune de creation des requetes
	

	// fonction de creation des requetes
	

	// Requete SELECT FROM WHERE
	/**
	 * Creer un requete SQL type SELECT...FROM...WHERE.
	 *
	 * @param string|array $select Liste de champ du SELECT.
	 * @param string|array $from Liste de champ du FROM.
	 * @param string|array $where Liste de champ du WHERE.
	 * @param string $option Option supplementaire (ORDER BY, GROUP BY ...).
	 * @param string $distinct Si on veut un distinct
	 */
	public function creer_select($select, $from, $where, $option = "", $distinct = "") {
		// creation de la requete
		$sql = "SELECT " . $distinct . " ";
		if (is_array ( $select )) {
			$sql .= $this->creer_liste ( $select );
		} else {
			$sql .= $select;
		}
		$sql .= " FROM ";
		if (is_array ( $from )) {
			$sql .= $this->creer_liste ( $from );
		} else {
			$sql .= $from . " ";
		}
		$sql .= $this->creer_where ( $where );
		if ($option != "")
			$sql .= " $option ";
		$this->setSql ( $sql );
		
		return true;
	}

	/**
	 * Creer un requete SQL type FROM .
	 * . JOIN .. ON ...
	 *
	 * @param string|array $fromListe de champ du FROM.
	 */
	public function creer_from_join($from) {
		$from_join = "";
		// creation de la requete
		if (is_array ( $from )) {
			foreach ( $from as $data ) {
				if (! is_array ( $data )) {
					if ($from_join != "") {
						$from_join .= ",";
					}
					$from_join .= $data;
				} else {
					switch ($data ["type"]) {
						case "LEFT" :
						case "left" :
							$join = "LEFT JOIN";
							break;
						case "RIGHT" :
						case "right" :
							$join = "RIGHT JOIN";
							break;
						case "BOTH" :
						default :
							$join = "JOIN";
					}
					$from_join .= " " . $join . " " . $data ["table"] . " ON " . $data ["champ1"] . "=" . $data ["champ2"];
				}
			}
		}
		return $from_join;
	}
	
	// Requete INSERT
	/**
	 * Creer un requete SQL type INSERT.
	 *
	 * @param string $table Table pour l'insertion.
	 * @param string|array $values Liste des valeurs du VALUES.
	 * @param string $ignore Option supplementaire (IGNORE ...).
	 */
	public function creer_insert($table, $values, $ignore = "") {
		// creation de la requete
		$sql = "INSERT $ignore INTO $table ";
		
		if (is_array ( $values )) {
			if (strpos ( $values [0], "'" ) !== 0 && strpos ( $values [0], "=" ) !== false) {
				$sql .= "SET " . $this->creer_liste ( $values );
			} else {
				$sql .= "VALUES (" . $this->creer_liste ( $values ) . ") ";
			}
		} else {
			$sql .= "VALUES (" . $values . ") ";
		}
		
		$this->setSql ( $sql );
		
		return true;
	}
	
	// Requete REPLACE
	/**
	 * Creer un requete SQL type REPLACE.
	 *
	 * @param string $table Table pour l'insertion.
	 * @param string|array $into Liste de champ a updater.
	 * @param string|array $values Liste des valeurs du VALUES.
	 * @param string $ignore Option supplementaire (IGNORE ...).
	 */
	public function creer_replace($table, $into, $values, $option = "") {
		$sql = "REPLACE $option INTO $table (";
		if (is_array ( $into ))
			$sql .= $this->creer_liste ( $into );
		else
			$sql .= $into;
		$sql .= ") ";
		$sql .= "VALUES (";
		if (is_array ( $values ))
			$sql .= $this->creer_liste ( $values, "'" );
		else
			$sql .= $values . " ";
		$sql .= ") ";
		$this->setSql ( $sql );
		
		return true;
	}
	
	// Requete UPDATE
	/**
	 * Creer un requete SQL type UPDATE.
	 *
	 * @param string $table Table pour l'insertion.
	 * @param string|array $update Liste de champ=valeur a updater.
	 * @param string|array $where Liste des conditions du WHERE.
	 * @param string $ignore Option supplementaire (IGNORE ...).
	 */
	public function creer_update($table, $update, $where, $option = "") {
		$sql = "UPDATE $option $table SET ";
		if (is_array ( $update )) {
			$sql .= $this->creer_liste ( $update );
		} else {
			$sql .= $update;
		}
		
		$sql .= $this->creer_where ( $where );
		
		$this->setSql ( $sql );
		
		return true;
	}
	
	// Requete DELETE
	/**
	 * Creer un requete SQL type DELETE.
	 *
	 * @param string $table Table pour la suppression.
	 * @param string|array $where Liste des conditions du WHERE.
	 * @param string $ignore Option supplementaire (IGNORE ...).
	 */
	public function creer_delete($table, $where, $option = "") {
		$sql = "DELETE $option FROM $table ";
		$sql .= $this->creer_where ( $where );
		$this->setSql ( $sql );
		
		return true;
	}
	
	// Requete ALTER
	/**
	 * Creer un requete SQL type ALTER.
	 *
	 * @param string $table Table pour la modification.
	 * @param string $spec Type d'alteration a faire sur la table.
	 * @param string $variable Donnees supplementaire en fin de SQL.
	 */
	public function creer_alter($table, $spec, $variable) {
		$sql = "ALTER TABLE $table $spec $variable";
		$this->setSql ( $sql );
		
		return true;
	}
	
	// Requete DROP
	/**
	 * Creer un requete SQL type DROP DATABASE.
	 *
	 * @param string $database
	 *        	Database a dropper.
	 */
	public function creer_drop($database) {
		$sql = "DROP DATABASE $database ";
		$this->setSql ( $sql );
		
		return true;
	}
	
	// Requete SHOW DB
	/**
	 * Creer un requete SQL type SHOW DATABASES.
	 */
	public function creer_show_db() {
		$sql = "SHOW DATABASES";
		$this->setSql ( $sql );
		
		return true;
	}

	/**
	 * Creer un requete SQL type SHOW DATABASES.
	 */
	public function creer_show_tables() {
		$sql = "SHOW TABLES";
		$this->setSql ( $sql );
		
		return true;
	}

	/**
	 * Renvoi une string de type :<br>
	 * LIKE si on trouve un % dans la chaine $valeur.<br>
	 * IN si on trouve ',' ou plusieurs int separes par des ,<br>
	 * la valeur autrement.
	 * 
	 * @param string $champ champ pour le where.
	 * @param string|int $valeur valeur a tester.
	 * @param string $type_champ peut prendre les valeurs text, numeric ou other.
	 * @return string
	 */
	public function choisie_type_where($champ, $valeur, $type_champ = "text") {
		$not = "";
		$not_text = "";
		$this->gestion_not ( $not, $not_text, $valeur );
		
		switch ($type_champ) {
			case "text" :
				return $this->traite_type_text ( $champ, $valeur, $not, $not_text );
			case "numeric" :
				return $this->traite_type_numeric ( $champ, $valeur, $not, $not_text );
			case "date" :
				return $this->traite_type_date ( $champ, $valeur, $not, $not_text );
			default :
				return $champ . $not_text . $valeur;
		}
		
		return "";
	}

	/**
	 * Valide la presence du ! pour mettre le not mysql en place
	 * @param string $not
	 * @param string $not_text
	 * @param string|array $valeur
	 * @return sql
	 */
	public function gestion_not(&$not, &$not_text, &$valeur) {
		$not = "";
		$not_text = "=";
		if (is_string ( $valeur )) {
			if (strpos ( $valeur, "!" ) === 0) {
				$not = " NOT ";
				$not_text = "<>";
				$valeur = substr ( $valeur, 1 );
			}
		} elseif (is_array ( $valeur ) && isset ( $valeur [0] )) {
			if (strpos ( $valeur [0], "!" ) === 0) {
				$not = " NOT ";
				$not_text = "<>";
				$valeur [0] = substr ( $valeur [0], 1 );
			}
		}
		
		return $this;
	}

	/**
	 * Valide que la valeur commence par "SELECT "
	 * @param string $valeur
	 * @return boolean
	 */
	public function valide_sous_requete($valeur) {
		if (strpos ( $valeur, "SELECT " ) === 0) {
			//c'est une sous requete
			return true;
		}
		
		return false;
	}

	/**
	 * Gere les champs de type texte
	 * @param string $champ
	 * @param array $valeur
	 * @param string $not
	 * @param string $not_text
	 * @return string ligne sql construite
	 */
	public function traite_type_text($champ, $valeur, $not, $not_text) {
		if (is_array ( $valeur )) {
			if (count ( $valeur ) == 1 && $this->valide_sous_requete ( $valeur [0] )) {
				//un tableau avec un champ contenant une sous requete est une string
				return $this->traite_type_text ( $champ, $valeur [0], $not, $not_text );
			}
			return $champ . " " . $not . " IN ('" . implode ( "','", $valeur ) . "')";
		} elseif ($this->valide_sous_requete ( $valeur )) {
			//c'est une sous requete
			return $champ . " " . $not . " IN (" . $valeur . ")";
		} elseif (strpos ( $valeur, "%" ) !== false) {
			return $champ . " " . $not . " LIKE " . $this->traite_valeur_null ( $valeur );
		} elseif (strpos ( $valeur, "','" ) !== false) {
			return $champ . " " . $not . " IN ('" . $valeur . "')";
		}
		
		return $champ . $not_text . $this->traite_valeur_null ( addslashes ( $valeur ) );
	}

	/**
	 * Gere les champs de type numeric/date
	 * @param string $champ
	 * @param array $valeur
	 * @param string $not
	 * @param string $not_text
	 * @return string ligne sql construite
	 */
	public function traite_type_numeric($champ, $valeur, $not, $not_text) {
		if (is_array ( $valeur )) {
			return $champ . " " . $not . " IN (" . implode ( ",", $valeur ) . ")";
		} elseif (strripos ( $valeur, "BETWEEN" ) !== false) {
			return $champ . " " . $valeur;
		} elseif (strripos ( $valeur, ">" ) !== false || strripos ( $valeur, "<" ) !== false) {
			return $champ . " " . $valeur;
		} elseif (strpos ( $valeur, "," ) !== false) {
			return $champ . " " . $not . " IN (" . $valeur . ")";
		}
		return $champ . $not_text . $valeur;
	}

	/**
	 * Gere les champs de type numeric/date
	 * @param string $champ
	 * @param array $valeur
	 * @param string $not
	 * @param string $not_text
	 * @return string ligne sql construite
	 */
	public function traite_type_date($champ, $valeur, $not, $not_text) {
		if (is_array ( $valeur )) {
			return $champ . " " . $not . " IN ('" . implode ( "','", $valeur ) . "')";
		} elseif (strripos ( $valeur, "BETWEEN" ) !== false) {
			return $champ . " " . $valeur;
		} elseif (strripos ( $valeur, ">" ) !== false || strripos ( $valeur, "<" ) !== false) {
			return $champ . " " . $valeur;
		} elseif (strpos ( $valeur, "," ) !== false) {
			return $champ . " " . $not . " IN ('" . $valeur . "')";
		}
		return $champ . $not_text . "'" . $valeur . "'";
	}

	/**
	 * creer une string de type text ou numeric pour le set.<br>
	 *
	 * @param string $champ champ pour le where.
	 * @param string|int $valeur valeur a tester.
	 * @param string $type_champ peut prendre les valeurs text, numeric ou other.
	 * @return string
	 */
	public function choisie_type_set($champ, $valeur, $type_champ = "text") {
		$CODE_RETOUR = "";
		switch ($type_champ) {
			case "text" :
				$CODE_RETOUR = $champ . "=" . $this->traite_valeur_null ( $valeur );
				break;
			case "date" :
				if ($valeur != "") {
					// si on a affaire a une fonction
					if (strpos ( $valeur, "(" ) !== false && strpos ( $valeur, ")" ) !== false) {
						$CODE_RETOUR = $champ . "=" . $valeur;
					} else {
						$CODE_RETOUR = $champ . "=" . $this->traite_valeur_null ( $valeur );
					}
				}
				break;
			case "numeric" :
				if (strlen ( trim ( $valeur ) ) > 0) {
					$CODE_RETOUR = $champ . "=" . $valeur;
				}
				break;
			default :
				$CODE_RETOUR = $champ . "=" . $valeur;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * creer un order by.
	 *
	 * @param string $order        	
	 * @return string
	 */
	public function prepare_order_by($liste_order) {
		$order = "";
		if (is_array ( $liste_order )) {
			foreach ( $liste_order as $order_data ) {
				if (isset ( $order_data ["type"] ) && isset ( $order_data ["champ"] ) && ($order_data ["type"] == " ASC" || $order_data ["type"] == " DESC") && $order_data ["champ"] != "") {
					if ($order == "") {
						$order = "ORDER BY ";
					} else {
						$order .= ",";
					}
					$order .= $order_data ["champ"] . " " . $order_data ["type"];
				}
			}
		}
		return $order;
	}

	/**
	 */
	public function traite_valeur_null($valeur) {
		if ($valeur != "NULL" && $valeur != "null") {
			if (is_string ( $valeur ) && strpos ( $valeur, "'" ) !== false) {
				$valeur = "\"" . $valeur . "\"";
			} else {
				$valeur = "'" . $valeur . "'";
			}
		}
		return $valeur;
	}

	/***************** Accesseurs ********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getSql() {
		return $this->sql;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSql($sql) {
		$this->onDebug ( "Requete SQL : " . $sql, 2 );
		$this->sql = $sql;
		return $this;
	}

	/***************** Accesseurs ********************/
	
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
