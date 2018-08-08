<?php
/**
 * @author dvargas
 * @package Lib
 */
/**
 * class requete<br>
 *
 * Construit les requetes et les appliques sur la base.
 * @package Lib
 * @subpackage SQL
 */
class requete extends connexion {
	/**
 	 * var privee
	 * @access private
	 * @var array|false
	 */
	private $resultat;
	/**
     * var privee
     * @access private
     * @var array|false
     */
	private $renvoi_PDO = false;
	/**
     * var privee
	 * @access private
     * @var int
     */
	private $nb_lignes_traitees;

	/*********************** Creation de l'objet *********************/
	/**
     * Instancie un objet de type requete.
     * @codeCoverageIgnore
     * @param options $liste_option Reference sur un objet options
     * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
     * @param string $entete Entete des logs de l'objet
     * @return requete
     */
	static function &creer_requete(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new requete ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
     * Initialisation de l'objet
     * @codeCoverageIgnore
     * @param array $liste_class
     * @return requete
     */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
     * Creer l'objet et cree la connexion a la base.
     * @codeCoverageIgnore
     * @param string $machine Nom de la machine ayant la base.
     * @param string $user Nom de l'utilisateur de connexion.
     * @param string $password Mot de passe de l'utilisateur.
     * @param string $type Type de base : mysql/sqlite ...
     * @param string $sort_en_erreur Prend les valeurs oui/non ou true/false.
     */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		$this->resultat = false;
		$this->nb_lignes_traitees = 0;
		
		return true;
	}

	/**
     * prepare le tableau de resultat a partir du PDOStatement.
     * @codeCoverageIgnore
     * @param PDOStatement $PDO_result resultat de la requete select.
     * @return bool true si OK, false sinon.
     */
	protected function _prepareResultat($PDO_result) {
		$pos = 0;
		if ($PDO_result instanceof PDOStatement) {
			if ($this->getRenvoiePDO () === true) {
				$this->resultat = $PDO_result;
				return true;
			}
			$this->resultat = array ();
			$RETOUR = true;
			$this->nb_lignes_traitees = $PDO_result->rowCount ();
			if ($this->nb_lignes_traitees > 0) {
				foreach ( $PDO_result as $row ) {
					$this->resultat [$pos] = $row;
					$pos ++;
				}
			}
		} else {
			$this->resultat = false;
			$this->nb_lignes_traitees = 0;
			$RETOUR = false;
		}
		
		return $RETOUR;
	}

	/**
     * Nettoie le tableau de resultat.
     *
     * @return bool true si OK, false sinon.
     */
	public function nettoie_resultat() {
		$this->resultat = false;
		$this->nb_lignes_traitees = 0;
		
		return true;
	}

	/**
     * Creer un requete SQL type INSERT et l'applique a la base.
     *
     * @param string $table Table pour l'insertion.
     * @param string|array $values Liste des valeurs du VALUES.
     * @param string $ignore Option supplementaire (IGNORE ...).
     * @throws Exception
     * @throws PDOException
     */
	public function ajouter($table, $value, $ignore = "") {
		//Si il n'y a pas de connexion et de droit a l'update a la base, alors pas de requete possible
		if (! $this->test_database_active ( true ))
			return false;
		$this->nettoie_resultat ();
		$this->setSql ( "" );
		
		if ($this->_testArgument ( $table ) && $this->_testArgument ( $value )) {
			$this->creer_insert ( $table, $value, $ignore );
			$PDO_result = $this->faire_requete ();
			$this->nb_lignes_traitees = $PDO_result->rowCount ();
		}
		
		return $this->nb_lignes_traitees;
	}

	/**
     * Creer un requete SQL type SELECT...FROM...WHERE et l'applique a la base.
     *
     * @param string|array $select Liste de champ du SELECT.
     * @param string|array $from Liste de champ du FROM.
     * @param string|array $where Liste de champ du WHERE.
     * @param string $option Option supplementaire (ORDER BY, GROUP BY ...).
     * @param string $distinct mettre DISTINCT si on active le distinct
     * @return PDO Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function selectionner($select, $from, $where = "", $option = "", $distinct = "") {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ())
			return false;
		$this->nettoie_resultat ();
		$this->setSql ( "" );
		
		if ($this->_testArgument ( $select ) && $this->_testArgument ( $from )) {
			$this->creer_select ( $select, $from, $where, $option, $distinct );
			$PDO_result = $this->faire_requete ();
			$this->_prepareResultat ( $PDO_result );
		} else {
			$this->resultat = false;
		}
		
		return $this->resultat;
	}

	/**
     * Creer un requete SQL type SELECT...FROM...WHERE et l'applique a la base.
     *
     * @param string|array $select Liste de champ du SELECT.
     * @param string|array $from Liste de champ du FROM.
     * @param string|array $where Liste de champ du WHERE.
     * @param string $option Option supplementaire (ORDER BY, GROUP BY ...).
     * @param string $distinct mettre DISTINCT si on active le distinct
     * @return PDO Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function selectionner_avec_jointure($select, $from, $where = "", $option = "", $distinct = "") {
		$from_join = $this->creer_from_join ( $from );
		return $this->selectionner ( $select, $from_join, $where, $option, $distinct );
	}

	/**
     * Creer un requete SQL type DELETE et l'applique a la base.
     *
     * @param string $table Table pour la suppression.
     * @param string|array $supprimer Liste des conditions du WHERE.
     * @throws Exception
     * @throws PDOException
     */
	public function supprimer($table, $where) {
		//Si il n'y a pas de connexion et de droit a l'update a la base, alors pas de requete possible
		if (! $this->test_database_active ( true ))
			return false;
		$this->nettoie_resultat ();
		$this->setSql ( "" );
		
		if ($this->_testArgument ( $table )) {
			$this->creer_delete ( $table, $where );
			$PDO_result = $this->faire_requete ();
			$this->nb_lignes_traitees = $PDO_result->rowCount ();
		} else {
			$this->nb_lignes_traitees = false;
		}
		
		return $this->nb_lignes_traitees;
	}

	/**
     * Creer un requete SQL type UPDATE et l'applique a la base.
     *
     * @param string $table Table pour l'insertion.
     * @param string|array $set Liste de champ=valeur a updater.
     * @param string|array $where Liste des conditions du WHERE.
     * @return PDO Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function updater($table, $set, $where = "") {
		//Si il n'y a pas de connexion et de droit a l'update a la base, alors pas de requete possible
		if (! $this->test_database_active ( true ))
			return false;
		$this->nettoie_resultat ();
		$this->setSql ( "" );
		
		if ($this->_testArgument ( $set ) && $this->_testArgument ( $table )) {
			$this->creer_update ( $table, $set, $where );
			$PDO_result = $this->faire_requete ();
			$this->nb_lignes_traitees = $PDO_result->rowCount ();
		} else {
			$this->nb_lignes_traitees = false;
		}
		
		return $this->nb_lignes_traitees;
	}

	/**
     * Creer un requete SQL type SELECT...FROM...WHERE et l'applique a la base.
     *
     * @param string|array $select Liste de champ du SELECT.
     * @param string|array $from Liste de champ du FROM.
     * @param string|array $where Liste de champ du WHERE.
     * @param string $option Option supplementaire (ORDER BY, GROUP BY ...).
     * @param int $type_result Deprecated
     * @return PDO Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function updater_avec_jointure($from, $set, $where = "") {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ( true ))
			return false;
		$this->nettoie_resultat ();
		$this->setSql ( "" );
		
		if ($this->_testArgument ( $from )) {
			$from_join = $this->creer_from_join ( $from );
			$this->creer_update ( $from_join, $set, $where );
			$PDO_result = $this->faire_requete ();
			$this->_prepareResultat ( $PDO_result );
		} else
			$this->resultat = false;
		
		return $this->resultat;
	}

	/**
     * Creer un requete SQL type SHOW DATABASES.
     * @param int $local_sql Deprecated
     * @return PDO Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function liste_db($local_sql = "") {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ())
			return false;
		$this->nettoie_resultat ();
		$this->setSql ( "" );
		
		if ($local_sql == "non" || $local_sql == "") {
			$local_sql = $this->creer_show_db ();
		} else {
			$this->setSql ( $local_sql );
		}
		$PDO_result = $this->faire_requete ();
		$this->_prepareResultat ( $PDO_result );
		
		return $this->resultat;
	}

	/**
	 * Retourne le "Last Inserted Id".
	 * @param string $name Nom de la sequence d'objet
	 * @return string Last Inserted Id.
	 * @throws Exception
	 * @throws PDOException
	 */
	public function recupere_last_id($name = "") {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ())
			return false;
		$PDO_result = $this->getDbConnexion ()
			->getPDOConnexion ()
			->lastInsertId ( $name );
		
		return $PDO_result;
	}

	/**
	 * Retourne le "Last Inserted Id".
	 * @param string $name Nom de la sequence d'objet
	 * @return string Last Inserted Id.
	 * @throws Exception
	 * @throws PDOException
	 */
	public function escape_string($data) {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ())
			return false;
		$PDO_result = $this->getDbConnexion ()
			->getPDOConnexion ()
			->quote ( $data );
		
		return $PDO_result;
	}

	/**
     * Creer un requete SQL type SHOW TABLES.
     * @param int $local_sql Deprecated
     * @return PDO Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function liste_table($local_sql = "non") {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ())
			return false;
		$this->nettoie_resultat ();
		$this->setSql ( "" );
		
		if ($local_sql == "non" || $local_sql == "") {
			$local_sql = $this->creer_show_tables ();
		} else {
			$this->setSql ( $local_sql );
		}
		$PDO_result = $this->faire_requete ();
		$this->_prepareResultat ( $PDO_result );
		
		return $this->resultat;
	}

	/**
     * Verifie qu'il y a au moins un tuple correspondant aux conditions donnees.
     *
     * @param string $table Table a tester.
     * @param string $champ Champ a tester.
     * @param string $valeur Valeur du champ a tester.
     * @return Boolean
     * @throws Exception
     * @throws PDOException
     */
	public function verifier_champ_in_database($table, $champ, $valeur) {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ())
			return false;
		$this->nettoie_resultat ();
		$this->setSql ( "" );
		
		$this->selectionner ( "distinct *", $table, $champ . "='" . $valeur . "'" );
		if ($this->nb_lignes_traitees >= 1) {
			return true;
		}
		return false;
	}

	/**************************** ACCESSEURS **********************/
	/**
     * @codeCoverageIgnore
     */
	public function getResultat() {
		return $this->resultat;
	}

	/**
     * @codeCoverageIgnore
     */
	public function getNbLignesTraitees() {
		return $this->nb_lignes_traitees;
	}

	/**
     * @codeCoverageIgnore
     */
	public function getRenvoiePDO() {
		return $this->renvoi_PDO;
	}

	/**
     * @codeCoverageIgnore
     */
	public function &setRenvoiePDO($renvoi_PDO) {
		$this->renvoi_PDO = $renvoi_PDO;
		return $this;
	}

	/**************************** ACCESSEURS **********************/
	/**
 	* @codeCoverageIgnore
 	* @param string|array $arg
 	* @return boolean
 	*/
	private function _testArgument($arg) {
		$flag = false;
		if (is_array ( $arg )) {
			if (count ( $arg ) > 0 && $arg [0] != "") {
				$flag = true;
			}
		} elseif (strlen ( $arg ) > 0) {
			$flag = true;
		}
		
		return $flag;
	}
	//Fin d'execution des requetes
	/**
     * @static
     * @codeCoverageIgnore
     * @param string $echo Affiche le help
     * @return string Renvoi le help
     */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Se connecte a une base avec PDO";
		$help [__CLASS__] ["text"] [] .= "\t--sql=oui";
		$help [__CLASS__] ["text"] [] .= "\t--dbhost=xx";
		$help [__CLASS__] ["text"] [] .= "\t--dbuser=xx";
		$help [__CLASS__] ["text"] [] .= "\t--dbpasswd=xx";
		$help [__CLASS__] ["text"] [] .= "\t--database=xx";
		$help [__CLASS__] ["text"] [] .= "\t--SQL_type=mysql";
		$help [__CLASS__] ["text"] [] .= "\t--SQL_sort_en_erreur=oui";
		$help [__CLASS__] ["text"] [] .= " ";
		$help [__CLASS__] ["text"] [] .= " <sql using=\"oui\">";
		$help [__CLASS__] ["text"] [] .= "  <liste_bases>";
		$help [__CLASS__] ["text"] [] .= "   <mongoDBAbstract using=\"oui\" sort_en_erreur=\"oui\">";
		$help [__CLASS__] ["text"] [] .= " 	<database>nom_database</database>";
		$help [__CLASS__] ["text"] [] .= " 	<dbhost>serveur1</dbhost>";
		$help [__CLASS__] ["text"] [] .= " 	<dbuser></dbuser>";
		$help [__CLASS__] ["text"] [] .= " 	<dbpasswd></dbpasswd>";
		$help [__CLASS__] ["text"] [] .= " 	<port>27017</port>";
		$help [__CLASS__] ["text"] [] .= "    <encode>utf8</encode>";
		$help [__CLASS__] ["text"] [] .= "   </mongoDBAbstract>";
		$help [__CLASS__] ["text"] [] .= "  </liste_bases>";
		$help [__CLASS__] ["text"] [] .= " </sql>";
		
		return $help;
	}
}
?>