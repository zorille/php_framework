<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
/**
 * class xql<br>
 * Gere la construction d'une requete SQL.
 * 
 * @package Lib
 * @subpackage SQL
 */
abstract class xql extends abstract_log {
	/**
	 * var privee
	 * 
	 * @access private
	 * @var string
	 */
	private $requete;

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
			return $where_sql . " " . $this ->creer_liste_and ( $where );
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
		$this ->gestion_not ( $not, $not_text, $valeur );
		
		switch ($type_champ) {
			case "text" :
				return $this ->traite_type_text ( $champ, $valeur, $not, $not_text );
			case "numeric" :
				return $this ->traite_type_numeric ( $champ, $valeur, $not, $not_text );
			case "date" :
				return $this ->traite_type_date ( $champ, $valeur, $not, $not_text );
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
			if (count ( $valeur ) == 1 && $this ->valide_sous_requete ( $valeur [0] )) {
				//un tableau avec un champ contenant une sous requete est une string
				return $this ->traite_type_text ( $champ, $valeur [0], $not, $not_text );
			}
			return $champ . " " . $not . " IN ('" . implode ( "','", $valeur ) . "')";
		} elseif ($this ->valide_sous_requete ( $valeur )) {
			//c'est une sous requete
			return $champ . " " . $not . " IN (" . $valeur . ")";
		} elseif (strpos ( $valeur, "%" ) !== false) {
			return $champ . " " . $not . " LIKE " . $this ->traite_valeur_null ( $valeur );
		} elseif (strpos ( $valeur, "','" ) !== false) {
			return $champ . " " . $not . " IN ('" . $valeur . "')";
		}
		
		return $champ . $not_text . $this ->traite_valeur_null ( addslashes ( $valeur ) );
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
				$CODE_RETOUR = $champ . "=" . $this ->traite_valeur_null ( $valeur );
				break;
			case "date" :
				if ($valeur != "") {
					// si on a affaire a une fonction
					if (strpos ( $valeur, "(" ) !== false && strpos ( $valeur, ")" ) !== false) {
						$CODE_RETOUR = $champ . "=" . $valeur;
					} else {
						$CODE_RETOUR = $champ . "=" . $this ->traite_valeur_null ( $valeur );
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
	public function getRequete() {
		return $this->requete;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRequete($Requete) {
		$this->requete = $Requete;
		return $this;
	}

	/***************** Accesseurs ********************/
}
?>
