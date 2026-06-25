<?php

/**
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
use \PDO as PDO;
use \PDOException as PDOException;
use \PDOStatement as PDOStatement;
/**
 * class sql<br>
 * Gere la connexion a une base SQL.
 * @package Lib
 * @subpackage SQL
 */
class connexion extends sql {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $user;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $password;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $base = "NO_DB";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $machine;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $type;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $encodage = "utf8";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $socket = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $port;
	/**
	 * var privee
	 * @access private
	 * @var PDO_local
	 */
	private $connexion = false;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $options_pdo = array ();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $requete_pdo = "";
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	var $option_maj_bd = true;
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	var $db_connecte = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type connexion.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return connexion
	 */
	static function &creer_connexion(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new connexion ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return connexion
	 */
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise ( $liste_class );
		
		$this->setDbConnexion ( PDO_local::creer_PDO_local ( $liste_class ['options'] ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet, prepare la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		return $this;
	}

	/**
	 * creer la connexion.
	 * @return connexion|bool
	 */
	public function &prepare_connexion(): static|bool
	{
		$this->prepare_ligne_pdo ();
		
		//On se connecte a la base de donnee
		$retour = false;
		$nb_retry = 3;
		$retry = 0;
		$sleep = 0;
		while ( $retour === false && $retry < $nb_retry ) {
			sleep ( $sleep );
			$retour = $this->getDbConnexion ()
				->connexion ( $this->getPdoRequete (), $this->getDbUsername (), $this->getDbPassword (), $this->getPdoOptions () );
			$retry ++;
			$sleep ++;
		}
		
		//On valide la connexion avant de continuer
		$this->test_connexion_active ();
		
		$this->onDebug ( "User : " . $this->getDbUsername () . " Password : *********** Machine : " . $this->getDbServeur (), 2 );
		return $this;
	}

	/**
	 * Creer la ligne PDO en fonction du type de base mysql/pgsql/sqlite
	 * @return connexion
	 * @throws Exception
	 */
	public function &prepare_ligne_pdo(): bool|static
	{
		if ($this->getDbServeur () == "") {
			$r = $this->onError ( "Probleme avec le serveur Database !", "", 3001 );
			return $r;
		}
		
		switch ($this->getDbType ()) {
			case "mysql" :
				$this->setPdoRequete ( "mysql:dbname=" . $this->getDatabase () . ";host=" . $this->getDbServeur () . $this->prepare_socket () . $this->prepare_port () );
				$this->setDbSelected ( true );
				if ($this->getDbEncodage () != "") {
					$this->setPdoOptions ( array (
							PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $this->getDbEncodage () 
					) );
				}
				break;
			case "pgsql" :
				$this->setPdoRequete ( "pgsql:dbname=" . $this->getDatabase () . ";host=" . $this->getDbServeur () . $this->prepare_socket () . $this->prepare_port () );
				$this->setDbSelected ( true );
				break;
			case "sqlite" :
				$this->setPdoRequete ( "sqlite:" . $this->getDbServeur () );
				$this->setDbSelected ( true );
				break;
			case "mssql" :
				$this->setPdoRequete ( "sqlsrv:Server=".$this->getDbServeur () . ",". $this->prepare_port ().";Database=" . $this->getDatabase () );
				$this->setDbSelected ( true );
				break;
			default :
				$this->setPdoRequete ( $this->getDbType () . ":host=" . $this->getDbServeur () );
				$this->setPdoOptions ( array () );
		}
		
		return $this;
	}

	/**
	 * Permet de selectionner un base Sur le serveur connecte.
	 *
	 * @param string $base Nom de la machine ayant la base.
	 * @throws Exception
	 */
	public function selection_base(string $base = ""): bool|static
	{
		//Necessite $base
		if ($base == "") {
			return $this->onError ( "La variable base est vide !", "", 3002 );
		} else {
			$this->setDatabase ( $base );
			// on choisit la bonne base
			if ($this->getDbType () == "mysql") {
				$this->onDebug ( "Base : " . $base, 2 );
				if (! $this->getDbConnexion ()
					->getPDOConnexion ()
					->query ( "use " . $base )) {
					return $this->onError ( print_r($this->getDbConnexion ()
						->getPDOConnexion ()
						->errorInfo (), true), "", $this->getDbConnexion ()
						->getPDOConnexion ()
						->errorCode () );
				}
			}
		}
		
		return $this;
	}

	/**
	 * Test si base de donnee est connectee.
	 *
	 * @return Bool TRUE si la base est connectee, FALSE sinon.
	 * @throws Exception
	 */
	public function test_connexion_active(): bool
	{
		if ($this->getDbConnexion () instanceof PDO_local) {
			return true;
		}
		return $this->onError ( "Pas de connexion active !", "", 3003 );
	}

	/**
	 * Fonction permettant d executer la requete sur la base avec la commande query.<br>
	 * Fonction la plus utilisee.
	 *
	 * @param string $request Surcharge la requete a execute.
	 * @return PDOStatement|bool Renvoi le resultat au format PDO.
	 * @throws Exception
	 */
	public function faire_requete(string $request = "NOREQUEST"): PDOStatement|bool
	{
		if ($request == "NOREQUEST" || $request == "") {
			$request = $this->getSql ();
		}
		//
		//On reinitialise pour eviter les problemes de connexion a la base
		//$this->connexion->closeCursor();
		//On fait la requete
		$this->onDebug ( "Requete en cours : ", 1 );
		$this->onDebug ( $request, 1 );
		$db_result = $this->getDbConnexion ()
			->getPDOConnexion ()
			->query ( $request );
		
		//on verifie la requete
		if ($db_result === false) {
			return $this->onError ( "Erreur durant la requete query : " . $request, $this->getDbConnexion ()
				->getPDOConnexion ()
				->errorInfo (), $this->getDbConnexion ()
				->getPDOConnexion ()
				->errorCode () );
		}
		
		$this->onDebug ( "Resultat de la requete PDO : ", 2 );
		$this->onDebug ( $db_result, 2 );
		return $db_result;
	}

	/**
	 * Fonction permettant d executer la requete sur la base avec la commande exec.
	 *
	 * @param string $request Surcharge la requete a execute.
	 * @return bool|PDOStatement Renvoi le resultat au format PDO.
	 * @throws Exception
	 */
	public function faire_requete_exec(string $request = "NOREQUEST"): bool|PDOStatement
	{
		if ($request == "NOREQUEST" || $request == "") {
			$request = $this->getSql ();
		}
		//
		//On reinitialise pour eviter les problemes de connexion a la base
		//$this->connexion->closeCursor();
		//On fait la requete
		$db_result = $this->getDbConnexion ()
			->getPDOConnexion ()
			->exec ( $request );
		
		//on verifie la requete
		if ($db_result === false) {
			return $this->onError ( "Erreur durant la requete exec : " . $request, $this->getDbConnexion ()
				->getPDOConnexion ()
				->errorInfo (), $this->getDbConnexion ()
				->getPDOConnexion ()
				->errorCode () );
		}
		
		$this->onDebug ( "Resultat de la requete PDO : ", 2 );
		$this->onDebug ( $db_result, 2 );
		return $db_result;
	}

	/**
	 * Fonction permettant de preparer la requete en procedure stocke.<br>
	 *
	 * @param string $request Surcharge la requete a execute.
	 * @return PDOStatement|false Renvoi le resultat au format PDO.
	 * @throws Exception
	 */
	public function preparer_requete(string $request = "NOREQUEST"): bool|PDOStatement
	{
		if ($request == "NOREQUEST" || $request == "") {
			$request = $this->getSql ();
		}
		try {
			$CODE_RETOUR = $this->getDbConnexion ()
				->getPDOConnexion ()
				->prepare ( $request );
		} catch ( PDOException $Exception ) {
			return $this->onError ( "Erreur durant la preparation de la requete", $Exception->getMessage (), ( int ) $Exception->getCode () );
		}
		return $CODE_RETOUR;
	}

	/**
	 * Demarre un transaction securise.
	 *
	 * @return boolean Resultat du debut de la transaction PDO.
	 */
	public function beginTransaction(): bool
	{
		return $this->getDbConnexion ()
			->getPDOConnexion ()
			->beginTransaction ();
	}

	/**
	 * Commit une transaction securise.
	 *
	 * @return boolean Resultat du Commit PDO.
	 */
	public function commit(): bool
	{
		return $this->getDbConnexion ()
			->getPDOConnexion ()
			->commit ();
	}

	/**
	 * Rollback une transaction securise.
	 *
	 * @return boolean Resultat du Rollback PDO.
	 */
	public function rollback(): bool
	{
		return $this->getDbConnexion ()
			->getPDOConnexion ()
			->rollBack ();
	}

	/**
	 * Prepare la commande pour une socket.
	 *
	 * @return string Resulat du Rollback PDO.
	 */
	public function prepare_socket(): string
	{
		if ($this->getDbSocket () != "") {
			$active_socket = ";unix_socket=" . $this->getDbSocket ();
		} else {
			$active_socket = "";
		}
		
		return $active_socket;
	}

	/**
	 * Prepare la commande pour une socket.
	 *
	 * @return string Resulat du Rollback PDO.
	 */
	public function prepare_port(): string
	{
		if ($this->getDbPort () != "") {
			$active_port = ";port=" . $this->getDbPort ();
		} else {
			$active_port = "";
		}
		
		return $active_port;
	}

	/**
	 * Test si base de donnee est connectee.
	 *
	 * @return Bool TRUE si la base est connectee, FALSE sinon.
	 * @throws Exception
	 */
	public function test_database_active($verifie_maj = false): bool
	{
		if ($verifie_maj && ! $this->getDbMaj ()) {
			return false;
		}
		if (!$this->getDbSelected ()) {
			return $this->onError ( "Base de donnees non connectee !", "", 3000 );
		}
		
		return true;
	}

	/**
	 * active la MAJ de la base de donnees
	 */
	public function active_maj_db(): static
	{
		return $this->setDbMaj ( true );
	}

	/**
	 * Desactive la MAJ de la base de donnees
	 */
	public function desactive_maj_db(): static
	{
		return $this->setDbMaj ( false );
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct() {
		$this->close ();
	}

	/**
	 * Ferme la connexion.
	 * @codeCoverageIgnore
	 */
	public function close() {
		//Pas de fermeture
		
	}

	/************** Accesseur ****************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDbUsername(): string
	{
		return $this->user;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbUsername($user): static
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbPassword(): string
	{
		return $this->password;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbPassword($password): static
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDatabase(): string
	{
		return $this->base;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatabase($database): static
	{
		$this->base = $database;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbServeur(): string
	{
		return $this->machine;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbServeur($machine): static
	{
		$this->machine = $machine;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbType(): string
	{
		return $this->type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbType($type): static
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return PDO_local
	 */
	public function &getDbConnexion(): PDO_local|bool
	{
		return $this->connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbConnexion($connexion): static
	{
		$this->connexion = $connexion;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbEncodage(): string
	{
		return $this->encodage;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbEncodage($encodage): static
	{
		$this->encodage = $encodage;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbSocket(): string
	{
		return $this->socket;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbSocket($socket): static
	{
		$this->socket = $socket;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbPort(): int
	{
		return $this->port;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbPort($port): static
	{
		$this->port = $port;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPdoOptions(): array
	{
		return $this->options_pdo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPdoOptions($options_pdo): static
	{
		$this->options_pdo = $options_pdo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPdoRequete(): string
	{
		return $this->requete_pdo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPdoRequete($requete_pdo): static
	{
		$this->requete_pdo = $requete_pdo;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbMaj(): bool
	{
		return $this->option_maj_bd;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbMaj($maj_db): static
	{
		$this->option_maj_bd = $maj_db;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDbSelected(): bool
	{
		return $this->db_connecte;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbSelected($db_connecte): static
	{
		$this->db_connecte = $db_connecte;
		return $this;
	}

	/************** Accesseur ****************/

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
