<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use \Exception as Exception;
use \PDO as PDO;
use \PDOStatement as PDOStatement;
use \PDOException as PDOException;
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
     * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
     * @param string $entete Entete des logs de l'objet
     * @return requete
     */
	static function &creer_requete(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): requete
	{
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
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et cree la connexion a la base.
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non ou true/false.
	 * @param string $entete
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
	protected function _prepareResultat(PDOStatement $PDO_result): bool
	{
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
	public function nettoie_resultat(): bool
	{
		$this->resultat = false;
		$this->nb_lignes_traitees = 0;
		
		return true;
	}

	/**
	 * Creer un requete SQL type INSERT et l'applique a la base.
	 *
	 * @param string $table Table pour l'insertion.
	 * @param $value
	 * @param string $ignore Option supplementaire (IGNORE ...).
	 * @return false|int
	 * @throws Exception
	 */
	public function ajouter(string $table, $value, string $ignore = ""): bool|int
	{
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
	 * @param array|string $select Liste de champ du SELECT.
	 * @param array|string $from Liste de champ du FROM.
	 * @param array|string $where Liste de champ du WHERE.
	 * @param string $option Option supplementaire (ORDER BY, GROUP BY ...).
	 * @param string $distinct mettre DISTINCT si on active le distinct
	 * @return bool|PDO|array Renvoi le resultat au format PDO.
	 * @throws Exception
	 */
	public function selectionner(array|string $select, array|string $from, array|string $where = "", string $option = "", string $distinct = ""): bool|PDO|array
	{
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
	 * @param array|string $select Liste de champ du SELECT.
	 * @param array|string $from Liste de champ du FROM.
	 * @param array|string $where Liste de champ du WHERE.
	 * @param string $option Option supplementaire (ORDER BY, GROUP BY ...).
	 * @param string $distinct mettre DISTINCT si on active le distinct
	 * @return PDO|bool|array Renvoi le resultat au format PDO.
	 * @throws Exception
	 */
	public function selectionner_avec_jointure(array|string $select, array|string $from, array|string $where = "", string $option = "", string $distinct = ""): PDO|bool|array
	{
		$from_join = $this->creer_from_join ( $from );
		return $this->selectionner ( $select, $from_join, $where, $option, $distinct );
	}

	/**
	 * Creer un requete SQL type DELETE et l'applique a la base.
	 *
	 * @param string $table Table pour la suppression.
	 * @param $where
	 * @return false|int
	 * @throws Exception
	 */
	public function supprimer(string $table, $where): bool|int
	{
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
     * @param array|string $set Liste de champ=valeur a updater.
     * @param array|string $where Liste des conditions du WHERE.
     * @return false|int Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function updater(string $table, array|string $set, array|string $where = ""): bool|int
	{
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
	 * @param array|string $from Liste de champ du FROM.
	 * @param $set
	 * @param string $where Liste de champ du WHERE.
	 * @return bool|PDO|array Renvoi le resultat au format PDO.
	 * @throws Exception
	 */
	public function updater_avec_jointure(array|string $from, $set, $where = ""): bool|PDO|array
	{
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
     * @param int|string $local_sql Deprecated
     * @return array|false Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function liste_db(int|string $local_sql = ""): bool|array
	{
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
	 * @return bool|string Last Inserted Id.
	 * @throws Exception
	 */
	public function recupere_last_id(string $name = ""): bool|string
	{
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ())
			return false;
		return $this->getDbConnexion ()
			->getPDOConnexion ()
			->lastInsertId ( $name );
	}

	/**
	 * Retourne le "Last Inserted Id".
	 * @param $data
	 * @return string Last Inserted Id.
	 * @throws Exception
	 */
	public function escape_string($data): bool|string
	{
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if (! $this->test_database_active ())
			return false;
		return $this->getDbConnexion ()
			->getPDOConnexion ()
			->quote ( $data );
	}

	/**
     * Creer un requete SQL type SHOW TABLES.
     * @param int|string $local_sql Deprecated
     * @return array|false Renvoi le resultat au format PDO.
     * @throws Exception
     * @throws PDOException
     */
	public function liste_table(int|string $local_sql = "non"): bool|array
	{
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
	public function verifier_champ_in_database(string $table, string $champ, string $valeur): bool
	{
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
	public function getResultat(): bool|array
	{
		return $this->resultat;
	}

	/**
     * @codeCoverageIgnore
     */
	public function getNbLignesTraitees(): int
	{
		return $this->nb_lignes_traitees;
	}

	/**
     * @codeCoverageIgnore
     */
	public function getRenvoiePDO(): bool|array
	{
		return $this->renvoi_PDO;
	}

	/**
     * @codeCoverageIgnore
     */
	public function &setRenvoiePDO($renvoi_PDO): static
	{
		$this->renvoi_PDO = $renvoi_PDO;
		return $this;
	}

	/**************************** ACCESSEURS **********************/
	/**
 	* @codeCoverageIgnore
 	* @param string|array $arg
 	* @return boolean
 	*/
	private function _testArgument($arg): bool
	{
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
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string {
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
