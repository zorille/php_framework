<?php

/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;

use Exception as Exception;

/**
 * class gestion_definition_table<br> Gere les abstractions des tables.
 *
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class gestion_definition_table extends requete {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $table = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $champs = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type gestion_definition_table.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return gestion_definition_table
	 */
	static function &creer_gestion_definition_table(
			&$liste_option,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		$objet = new gestion_definition_table ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return gestion_definition_table
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
	 * @codeCoverageIgnore
	 * @param string $entete Entete a afficher dans les logs.
	 * @param string|bool $sort_en_erreur Prend les valeurs oui/non ou true/false.
	 */
	public function __construct(
			$sort_en_erreur,
			$entete) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Fabrique chaque champ select
	 * @param array $select
	 * @param string $table
	 * @param string $champ
	 * @param string $as
	 * @return gestion_definition_table
	 * @throws Exception
	 */
	public function fabrique_select(
			&$select,
			$table,
			$champ = "",
			$as = "") {
		if ($table != "") {
			if ($champ != "") {
				$champ_local = $this->renvoi_champ ( $table, $champ );
				if ($champ_local != "") {
					if ($as != "") {
						$champ_local .= " AS '" . $as . "'";
					}
					$select [] .= $champ_local;
				}
			} else {
				$select = array_merge ( $select, $this->renvoi_liste_champs ( $table ) );
				if (is_array ( $select )) {
					$select = array_values ( $select );
				}
			}
		}
		return $this;
	}

	/**
	 * Fabrique chaque champ from
	 * @param array $from
	 * @param string $table
	 * @return gestion_definition_table
	 */
	public function fabrique_from(
			&$from,
			$table) {
		if ($table != "") {
			$reel_table = $this->renvoi_table ( $table );
			if ($reel_table !== false) {
				$from [] .= $reel_table;
			} else {
				$from [] .= $table;
			}
		}
		return $this;
	}

	/**
	 * Fabrique chaque champ from avec une jointure
	 * @param array $from
	 * @param string $table_name1
	 * @param string $champ1
	 * @param string $table_name2
	 * @param string $champ2
	 * @param string $type BOTH,LEFT,RIGHT
	 * @return gestion_definition_table
	 * @throws Exception
	 */
	public function fabrique_from_jointure(
			&$from,
			$table_name1,
			$champ1,
			$table_name2,
			$champ2,
			$type = "BOTH") {
		if ($table_name1 != "" && $table_name2 != "") {
			$table1 = $this->renvoi_table ( $table_name1 );
			$champ1 = $this->renvoi_champ ( $table_name1, $champ1 );
			$champ2 = $this->renvoi_champ ( $table_name2, $champ2 );
			if ($table1 != "" && $champ1 != "" && $champ2 != "") {
				$pos = count ( $from );
				$from [$pos] ["table"] = $table1;
				$from [$pos] ["champ1"] = $champ1;
				$from [$pos] ["champ2"] = $champ2;
				$from [$pos] ["type"] = $type;
			}
		}
		return $this;
	}

	/**
	 * Creer un where et l'ajoute a la liste des where du tableau
	 * @param array $liste
	 * @param string $table
	 * @param string $champ
	 * @param string $valeur
	 * @return gestion_definition_table
	 * @throws Exception
	 */
	public function fabrique_where(
			&$liste,
			$table,
			$champ,
			$valeur) {
		if ($valeur != "" && $table != "" && $champ != "") {
			$retour = $this->choisie_type_where ( $this->renvoi_champ ( $table, $champ ), $valeur, $this->renvoi_type ( $table, $champ ) );
			if ($retour != "") {
				$liste [] .= $retour;
			}
		}
		return $this;
	}

	/**
	 * Creer un set et l'ajoute a la liste
	 * @param array $liste
	 * @param string $table
	 * @param string $champ
	 * @param string $valeur
	 * @return gestion_definition_table
	 * @throws Exception
	 */
	public function fabrique_set(
			&$liste,
			$table,
			$champ,
			$valeur) {
		if ($table != "" && $champ != "") {
			$retour = $this->choisie_type_set ( $this->renvoi_champ ( $table, $champ ), $valeur, $this->renvoi_type ( $table, $champ ) );
			if ($retour != "") {
				$liste [] .= $retour;
			}
		}
		return $this;
	}

	/**
	 * Creer un update et l'ajoute a la liste
	 * @param array $liste
	 * @param string $table
	 * @param string $champ
	 * @param string $valeur
	 * @return gestion_definition_table
	 * @throws Exception
	 */
	public function prepare_valeur_update(
			&$liste,
			$table,
			$champ,
			$valeur) {
		if ($table != "" && $champ != "" && $valeur !== "__no_update") {
			$retour = $this->choisie_type_set ( $this->renvoi_champ ( $table, $champ ), $valeur, $this->renvoi_type ( $table, $champ ) );
			if ($retour != "") {
				$liste [] .= $retour;
			}
		}
		return $this;
	}

	/**
	 * Creer un orderBy
	 * @param string $champ
	 * @param string $table
	 * @param string $fonction
	 * @return array
	 * @throws Exception
	 */
	public function fabrique_order_by(
			$champ,
			$table,
			$fonction = "") {
		$order = array ();
		if ($champ != "") {
			$order ["type"] = $this->retrouve_type_order ( $champ );
			$liste = explode ( " ", trim ( $champ ) );
			if (count ( $liste ) > 0) {
				if ($this->renvoi_type ( $table, $liste [0] ) == 'text' && $fonction == "") {
					$fonction = "LCASE";
				}
				$order ["champ"] = $this->renvoi_champ ( $table, $liste [0] );
			}
			if ($fonction != "") {
				$order ["champ"] = $fonction . "(" . $order ["champ"] . ")";
			}
		}
		return $order;
	}

	/**
	 * Retrouve le type de orderBy (ASC/DESC)
	 * @param string $type ' asc'/' desc' ou ' ASC'/' DESC'
	 * @return string
	 */
	public function retrouve_type_order(
			$type) {
		if (strripos ( $type, " ASC" ) !== false) {
			$retour = " ASC";
		} elseif (strripos ( $type, " DESC" ) !== false) {
			$retour = " DESC";
		} else {
			$retour = " ASC";
		}
		return $retour;
	}

	/**
	 * Cree et applique une requete de type select standard.
	 *
	 * @param array $liste_champs_where liste des champs du where
	 * @return array false un tableau de resultat, FALSE sinon.
	 * @throws Exception
	 */
	public function requete_select_standard(
			$table,
			$liste_champs_where = array (),
			$order = "ORDER BY",
			$distinct = true) {
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$select = array ();
		$tab_table = $this->renvoi_donnees_table ( $table );
		if ($tab_table !== false) {
			// renvoi les noms virtuel de chaque champ en fonction de leurs definitions
			foreach ( $tab_table as $champ => $valeur ) {
				$this->fabrique_select ( $select, $table, $champ, $valeur ["as"] );
			}
		} else {
			// Renvoi le nom reel du champ car il n'y a pas de definition
			$this->fabrique_select ( $select, $table );
		}
		$where = array ();
		foreach ( $liste_champs_where as $champ => $valeur ) {
			$this->fabrique_where ( $where, $table, $champ, $valeur );
		}
		if ($order == "ORDER BY") {
			$order = "";
		} elseif (strripos ( $order, "ORDER BY" ) === false) {
			$order_liste [0] = $this->fabrique_order_by ( $order, $table );
			$order = $this->prepare_order_by ( $order_liste );
		}
		if ($distinct) {
			$distinct = "DISTINCT";
		} else {
			$distinct = "";
		}
		$this->onDebug ( $select, 1 );
		$this->onDebug ( $from, 1 );
		$this->onDebug ( $where, 1 );
		$resultat = $this->selectionner ( $select, $from, $where, $order, $distinct );
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Cree et applique une requete de type select standard.
	 *
	 * @param array $liste_champs_set liste des champs du set pour 1 tuple
	 * @return array false un tableau de resultat, FALSE sinon.
	 * @throws Exception
	 */
	public function requete_insert_standard(
			$table,
			$liste_champs_set = array ()) {
		$liste_champs_table = $this->renvoi_donnees_table ( $table );
		// On valide que la liste des champs est complete
		foreach ( $liste_champs_table as $as => $liste_data ) {
			$liste_data;
			if (! isset ( $liste_champs_set [$as] )) {
				// sinon on met vide par defaut
				$liste_champs_set [$as] = "";
				// $this->onWarning ( "Le champ " . $as . " n'existe pas dans le tableau liste_champs_set" );
			}
		}
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$set = array ();
		foreach ( $liste_champs_set as $champ => $value ) {
			$this->fabrique_set ( $set, $table, $champ, $value );
		}
		$resultat = $this->ajouter ( $from [0], $set );
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Cree et applique une requete de type update standard.
	 *
	 * @param array $liste_champs_set liste des champs du set
	 * @param array $liste_champs_where liste des champs du where
	 * @return array false un tableau de resultat, FALSE sinon.
	 * @throws Exception
	 */
	public function requete_update_standard(
			$table,
			$liste_champs_set = array (),
			$liste_champs_where = array ()) {
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$set = array ();
		foreach ( $liste_champs_set as $champ => $value ) {
			$this->fabrique_set ( $set, $table, $champ, $value );
		}
		$where = array ();
		foreach ( $liste_champs_where as $champ => $valeur ) {
			$this->fabrique_where ( $where, $table, $champ, $valeur );
		}
		$resultat = $this->updater ( $from [0], $set, $where );
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Vide la table demande.
	 *
	 * @param string $table table a nettoyer.
	 * @param array $liste_champs_where liste des champs du where
	 * @return array false un tableau de resultat, FALSE sinon.
	 * @throws Exception
	 */
	public function requete_delete_standard(
			$table,
			$liste_champs_where = array ()) {
		$from = array ();
		$this->fabrique_from ( $from, $table );
		$where = array ();
		foreach ( $liste_champs_where as $champ => $valeur ) {
			$this->fabrique_where ( $where, $table, $champ, $valeur );
		}
		$resultat = $this->supprimer ( $from [0], $where );
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Ferme la connexion.
	 * @codeCoverageIgnore
	 */
	public function close() {
	}

	/**
	 * *************** Accesseurs ***********
	 */
	/**
	 * Renvoi le nom reel a partir du nom virtuel
	 * @param string $table_name
	 * @return string|false
	 */
	public function renvoi_table(
			$table_name) {
		if (isset ( $this->table [$table_name] )) {
			return $this->table [$table_name];
		}
		return false;
	}

	/**
	 * Associe un nom virtuel ($table_name) avec un nom reel de table ($nom_reel)
	 * @param string $nom_reel
	 * @param string $table_name
	 * @return gestion_definition_table
	 */
	public function setTable(
			$nom_reel,
			$table_name) {
		if (! isset ( $this->table [$table_name] )) {
			$this->table [$table_name] = $nom_reel;
		}
		return $this;
	}

	/**
	 * Renvoi le tableau de valeur pour un champ virtuel
	 * @param string $table Nom virtuel de la table
	 * @param string $champ Nom virtuel du champ
	 * @return array|false
	 */
	public function renvoi_donnees_champ(
			$table,
			$champ) {
		if ($table !== false && $table != "" && ! is_array ( $table ) && ! is_array ( $champ )) {
			if (isset ( $this->champs [$table] ) && isset ( $this->champs [$table] [$champ] )) {
				return $this->champs [$table] [$champ];
			}
		}
		return false;
	}

	/**
	 * Associe un nom de champ virtuel ($champ_name) a un nom de champ reel ($nom_reel) et une table (nom virtuel de la table)
	 * @param string $nom_reel
	 * @param string $champ_name
	 * @param string $table_name
	 * @param string $type
	 * @return gestion_definition_table
	 */
	public function setChamp(
			$nom_reel,
			$champ_name,
			$table_name,
			$type = "text") {
		$table = $this->renvoi_table ( $table_name );
		if (! isset ( $this->champs [$table_name] [$champ_name] ) && $table != "") {
			// Gere les champs avec un espace
			$this->champs [$table_name] [$champ_name] ["nom"] = $table . ".`" . $nom_reel . "`";
			$this->champs [$table_name] [$champ_name] ["nom_sans_table"] = "`" . $nom_reel . "`";
			$this->champs [$table_name] [$champ_name] ["type"] = $type;
			$this->champs [$table_name] [$champ_name] ["as"] = $champ_name;
		}
		return $this;
	}

	/**
	 * Renvoi les donnees d'une table (nom virtuel de la table)
	 * @param string $table
	 * @return array|false
	 */
	public function renvoi_donnees_table(
			$table) {
		if ($table !== false && $table != "" && ! is_array ( $table )) {
			if (isset ( $this->champs [$table] )) {
				return $this->champs [$table];
			}
		}
		return false;
	}

	/**
	 * Renvoi la valeur du 'AS' pour un nom virtuel de table/champ
	 * @param string $table
	 * @param string $champ
	 * @return string|false
	 */
	public function renvoi_as(
			$table,
			$champ) {
		$tab_champ = $this->renvoi_donnees_champ ( $table, $champ );
		if ($tab_champ !== false && isset ( $tab_champ ["as"] ) && $tab_champ ["as"] != "") {
			return $tab_champ ["as"];
		}
		return false;
	}

	/**
	 * Renvoi le type d'un champ pour un nom virtuel de table/champ
	 * @param string $table
	 * @param string $champ
	 * @return string "text" par defaut
	 */
	public function renvoi_type(
			$table,
			$champ) {
		$tab_champ = $this->renvoi_donnees_champ ( $table, $champ );
		if ($tab_champ !== false && isset ( $tab_champ ["type"] ) && $tab_champ ["type"] != "") {
			return $tab_champ ["type"];
		}
		return "text";
	}

	/**
	 * Renvoi la liste complete des champs d'une table
	 *
	 * @param string $table
	 * @return array Renvoi "*" par defaut
	 */
	public function renvoi_liste_champs(
			$table) {
		$tab_table = $this->renvoi_donnees_table ( $table );
		if ($tab_table !== false) {
			$CODE_RETOUR = array ();
			foreach ( $tab_table as $champ => $valeur ) {
				$CODE_RETOUR [$champ] = $valeur ["nom"];
			}
			return $CODE_RETOUR;
		}
		return array (
				"*"
		);
	}

	/**
	 * Renvoi le nom reel et complet (avec la table) d'un champ pour un nom virtuel de table/champ
	 *
	 * @param string $table
	 * @param string $champ
	 * @return string|false False en cas d'erreur
	 * @throws Exception
	 */
	public function renvoi_champ(
			$table,
			$champ) {
		$tab_champ = $this->renvoi_donnees_champ ( $table, $champ );
		if ($tab_champ !== false && isset ( $tab_champ ["nom"] ) && $tab_champ ["nom"] != "") {
			return $tab_champ ["nom"];
		}
		return $this->onError ( "Champ " . $champ . " introuvable dans la definition de la table " . $table );
	}

	/**
	 * Renvoi le nom reel sans la table d'un champ pour un nom virtuel de table/champ
	 * @param string $table
	 * @param string $champ
	 * @return string "text" par defaut
	 */
	public function renvoi_champ_sans_table(
			$table,
			$champ) {
		$tab_champ = $this->renvoi_donnees_champ ( $table, $champ );
		if ($tab_champ !== false && isset ( $tab_champ ["nom_sans_table"] ) && $tab_champ ["nom_sans_table"] != "") {
			return $tab_champ ["nom_sans_table"];
		}
		return false;
	}
/**
 * *************** Accesseurs ***********
 */
}
?>