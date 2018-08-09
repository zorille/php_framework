<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class mongoDbAbstract<br>
 * 
 * Gere la connexion a une base SQL type STORAGE ENGINE.
 * @package Lib
 * @subpackage SQL
 */
class mongoDbAbstract extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var Mongo
	 */
	private $connexion;
	/**
	 * var privee
	 * @access private
	 * @var MongoDB
	 */
	private $db = "";
	/**
	 * var privee
	 * @access private
	 * @var MongoCollection
	 */
	private $collection = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type mongoDbAbstract.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return mongoDbAbstract
	 */
	static function &creer_mongoDbAbstract(&$liste_option, $machine = "", $user = "", $password = "", $port = "27017", $options = array(), $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new mongoDbAbstract ( $machine, $user, $password, $port, $options, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return mongoDbAbstract
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet, prepare la valeur du sort_en_erreur
	 * et creer la connexion.
	 *
	 * @param string $machine Nom de la machine.
	 * @param string $user User de connexion.
	 * @param string $password Password pour le user.
	 * @param int $port Port de connexion. Par defaut : 27017
	 * @param array $options Options de connexion.
	 * @param string $stop_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($machine = "", $user = "", $password = "", $port = 27017, $options = array(), $stop_on_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $entete, $stop_on_erreur );
		$retry = 0;
		$sleep = 0;
		while ( ! $this->getConnexion () instanceof Mongo && $retry < 3 ) {
			sleep ( $sleep );
			$this->creer_connexion ( $machine, $user, $password, $port, $options );
			$retry ++;
			$sleep ++;
		}
		
		return true;
	}

	/************************************* Gestion de la connexion ********************/
	/**
	 * Creer une connexion sur un serveur MongoDB.
	 *
	 * @param string $machine Nom de la machine.
	 * @param string $user User de connexion.
	 * @param string $password Password pour le user.
	 * @param int $port Port de connexion. Par defaut : 27017
	 * @param array $options Options de connexion.
	 * @return Bool True si OK, False sinon.
	 */
	public function creer_connexion($machine = "", $user = "", $password = "", $port = 27017, $options = array()) {
		$retour = false;
		$nb_retry = 3;
		$retry = 0;
		$sleep = 0;
		
		if ($machine == "") {
			$lien = "localhost";
		} else {
			$lien = $machine;
		}
		
		if ($port != "") {
			$lien .= ":" . $port;
		}
		
		if ($user != "" && $password != "") {
			$lien = $user . ":" . $password . "@" . $lien;
		}
		
		while ( $retour !== true && $retry < $nb_retry ) {
			sleep ( $sleep );
			$retour = $this->connexion ( $lien, $options );
			$retry ++;
			$sleep ++;
		}
		
		if (! $this->verifie_connexion ()) {
			return $this->onError ( "Connexion sur " . $machine . " non reussi." );
		}
		
		return true;
	}

	/**
	 * Instancie la connexion sur la mongo
	 *
	 * @param string $lien url de la mongo a connecter
	 * @param array $options options de connexion mongoDB
	 * @return Bool TRUE si ok, FALSE en cas d'erreur
	 */
	private function _connexion($lien, $options) {
		try {
			$this->connexion = new Mongo ( "mongodb://" . $lien, $options );
		} catch ( MongoConnectionException $e ) {
			$this->onWarning ( "Connexion sur " . $lien . " impossible : " . $e->getMessage () );
			return false;
		}
		
		return true;
	}

	/**
	 * Verifie l'existance de la connexion.
	 */
	public function verifie_connexion() {
		if ($this->connexion instanceof Mongo) {
			$retour = true;
		} else {
			$retour = false;
		}
		
		return $retour;
	}

	/************************************* Gestion de la connexion ********************/
	
	/************************************* Gestion des Databases **********************/
	/**
	 * Selectionne une base.<br>
	 * Le fonctionnement par defaut de MongoDG est la creation de la base si elle n'existe pas.<br>
	 * Le parametre $forceCreate sert a eviter cette fonctionnalite.
	 *
	 * @param string $db_local nom de la db.
	 * @param Bool $forceCreate Force la creation de la db si elle n'existe pas.
	 */
	public function selectDatabase($db_local, $forceCreate = false) {
		$retour = false;
		
		if ($this->verifie_connexion () && $db_local != "") {
			/* Probleme de perenite de la base si on fait rien
			 if(!$forceCreate){
			$dbs = $this->listeDatabases();
			if(isset($dbs["databases"]) && is_array($dbs["databases"])){
			foreach($dbs["databases"] as $data){
			if($db_local==$data["name"]){
			$forceCreate=true;
			}
			}
			}
			if(!$forceCreate){
			$this->onWarning("Database ".$db_local." non trouvee.");
			}
			}

			if($forceCreate){
			$this->db=$this->connexion->selectDB($db_local);
			$retour=true;
			}*/
			try {
				$this->db = $this->connexion->selectDB ( $db_local );
			} catch ( InvalidArgumentException $e ) {
				return $this->onError ( $e->getMessage () );
			} catch ( Exception $e ) {
				return $this->onError ( $e->getMessage () );
			}
			$retour = true;
		} else {
			return $this->onError ( "Aucune connexion activee." );
		}
		
		return $retour;
	}

	/**
	 * Renvoi la liste des databases.
	 *
	 * @return array|false Liste des databases, false sinon.
	 */
	public function listeDatabases() {
		if ($this->verifie_connexion ()) {
			$liste = $this->connexion->listDBs ();
		} else {
			return $this->onError ( "Aucune connexion activee." );
		}
		
		return $liste;
	}

	/**
	 * Supprime la db.
	 *
	 */
	public function dropDatabase() {
		if ($this->verifie_db ()) {
			$resultat = $this->db->drop ();
		} else {
			$resultat = false;
		}
		
		return $resultat;
	}

	public function verifie_db() {
		if ($this->db instanceof MongoDB) {
			$retour = true;
		} else {
			$retour = false;
		}
		
		return $retour;
	}

	/************************************* Gestion des Databases **********************/
	
	/************************************* Gestion des Collections ********************/
	public function selectCollection($collection_local) {
		if ($this->verifie_db ()) {
			$this->collection = $this->db->selectCollection ( $collection_local );
		} else {
			return $this->onError ( "Aucune database selectionnee." );
		}
		
		return true;
	}

	/**
	 * Renvoi la liste des collections.
	 *
	 * @return array|false Liste des collections (tables), false sinon.
	 */
	public function listeCollections() {
		if ($this->verifie_db ()) {
			$liste = $this->db->listCollections ();
		} else {
			return $this->onError ( "Aucune database selectionnee." );
		}
		
		return $liste;
	}

	public function supprimeCollection($collection_local) {
		if ($this->selectCollection ( $collection_local )) {
			$this->collection->remove ();
		}
		
		return true;
	}

	public function verifie_collection() {
		if ($this->collection instanceof MongoCollection) {
			$retour = true;
		} else {
			$retour = false;
		}
		
		return $retour;
	}

	/************************************* Gestion des Collections ********************/
	
	/************************************* requetes sur les collections ***************/
	
	/**
	 * Prepare les parametres les plus utilises.
	 *
	 * @param bool $safe Valide chaque suppression.
	 * @param bool $fsync Attend la validation de l'ecriture sur disque.
	 * @param bool $timeout Timeout pour la suppression.
	 */
	private function _prepareOptionsStandard(&$options, $safe, $fsync, $timeout) {
		$options ["safe"] = $safe;
		$options ["fsync"] = $fsync;
		if ($timeout !== false) {
			$options ["timeout"] = $timeout;
		}
		
		return true;
	}

	/**
	 * Fonction permettant d executer la requete sur la base avec la commande find.<br>
	 *
	 * @param array $sql requete au format mongoDB.
	 * @param Bool $renvoieTableau Force le renvoi en tableau.
	 * @return MongoCursor|array|false Renvoi le resultat.
	 */
	function faire_requete($where = array(), $select = array(), $renvoieTableau = false) {
		$resultat = false;
		if ($this->verifie_collection ()) {
			$resultat_local = $this->collection->find ( $where, $select );
			if ($renvoieTableau) {
				$pos = 0;
				if ($resultat_local instanceof MongoCursor) {
					$resultat = array ();
					foreach ( $resultat_local as $row ) {
						$resultat [$pos] = $row;
						$pos ++;
					}
				}
			} else {
				$resultat = &$resultat_local;
			}
		}
		return $resultat;
	}

	/**
	 * Fonction permettant d executer la requete sur la base avec la commande find.<br>
	 *
	 * @param array $sql requete au format mongoDB.
	 * @param Bool $renvoieTableau Force le renvoi en tableau.
	 * @return MongoCursor|array|false Renvoi le resultat.
	 */
	function requete_distinct($collection, $where = array(), $select = "", $renvoieTableau = false) {
		/*
		 array(
		 		"distinct" => "people",
		 		"key" => "age",
		 		"query" => array("age" => array('$gte' => 18))
		 )
		*/
		$resultat = false;
		if ($this->verifie_db ()) {
			$query = array (
					"distinct" => $collection,
					"key" => $select,
					"query" => $where 
			);
			$resultat_local = $this->db->command ( $query );
			if ($renvoieTableau) {
				$pos = 0;
				if ($resultat_local instanceof MongoCursor) {
					$resultat = array ();
					foreach ( $resultat_local as $row ) {
						$resultat [$pos] = $row;
						$pos ++;
					}
				}
			} else {
				$resultat = &$resultat_local;
			}
		}
		return $resultat;
	}

	/**
	 * Fais l'equivalent sql du insert.
	 *
	 * @param array $sql donnees a ajouter.
	 * @param bool $safe Valide chaque suppression.
	 * @param bool $fsync Attend la validation de l'ecriture sur disque.
	 * @param bool $timeout Timeout pour la suppression.
	 * @return array|false Resultat du replace
	 */
	public function requete_insert(&$obj, $safe = true, $fsync = false, $timeout = false) {
		$resultat = false;
		$options = array ();
		$retry = 0;
		$done = false;
		if ($this->verifie_collection ()) {
			while ( $retry < 3 && $done === false ) {
				try {
					$this->_prepareOptionsStandard ( $options, $safe, $fsync, $timeout );
					$set = $obj;
					$resultat = $this->collection->insert ( $set, $options );
					$obj ["_id"] = $set ["_id"];
					$done = true;
				} catch ( MongoCursorException $e ) {
					switch ($e->getCode ()) {
						case 13 :
							//Interrupted system call
							$this->onWarning ( "Warning ( code retour : " . $e->getCode () . ") lors de l'ajout : " . $e->getMessage () );
						case 4 :
							//couldn't get response header
							$retry ++;
							sleep ( $retry );
							continue 2;
							break; //inutile mais bon :)
						case 11000 :
						case 11001 :
							$this->onWarning ( "duplicate entry" );
							return array (
									"ok" => 1 
							);
							break; //inutile mais bon :)
						default :
							return $this->onError ( "Erreur ( code retour : " . $e->getCode () . ") lors de l'ajout de " . print_r ( $obj, true ), $e->getMessage () );
					}
				} catch ( MongoCursorTimeoutException $e ) {
					return $this->onError ( "Timeout atteint.", $e->getMessage () );
				}
			}
		}
		
		return $resultat;
	}

	/**
	 * Fais l'equivalent sql de l'update.
	 *
	 * @param array $sql condition pour les entrees a modifier.
	 * @param array $newObj donnees a updater.
	 * @param bool $allTuple update tous les objets qui match les criteres.
	 * @param bool $insertNotExist Insert si le critere est introuvable.
	 * @param bool $safe Valide chaque suppression.
	 * @param bool $fsync Attend la validation de l'ecriture sur disque.
	 * @param bool $timeout Timeout pour la suppression.
	 * @return array|false Resultat du replace
	 */
	public function requete_update($sql, $updateDef, $allTuple = false, $insertNotExist = false, $safe = true, $fsync = false, $timeout = false) {
		$resultat = false;
		$options = array ();
		$retry = 0;
		$done = false;
		if ($this->verifie_collection ()) {
			while ( $retry < 3 && $done === false ) {
				try {
					$options ["upsert"] = $insertNotExist;
					$options ["multiple"] = $allTuple;
					$this->_prepareOptionsStandard ( $options, $safe, $fsync, $timeout );
					
					$this->onDebug ( "requete_update WHERE :" . print_r ( $sql, true ), 2 );
					$this->onDebug ( "requete_update SET :" . print_r ( $updateDef, true ), 2 );
					
					$resultat = $this->collection->update ( $sql, $updateDef, $options );
					//tout vas
					$done = true;
				} catch ( MongoCursorException $e ) {
					switch ($e->getCode ()) {
						case 13 :
							//Interrupted system call
							$this->onWarning ( "Warning ( code retour : " . $e->getCode () . ") lors de l'ajout : " . $e->getMessage () );
						case 4 :
							//couldn't get response header
							$retry ++;
							sleep ( $retry );
							continue 2;
							break; //inutile mais bon :)
						case 11000 :
						case 11001 :
							$this->onWarning ( "duplicate entry" );
							return array (
									"ok" => 1,
									"updatedExisting" => 0 
							);
							break; //inutile mais bon :)
						default :
							return $this->onError ( "Erreur lors de la mise a jour ( code retour : " . $e->getCode () . ") de " . print_r ( $updateDef, true ), $e->getMessage () );
					}
				} catch ( MongoCursorTimeoutException $e ) {
					return $this->onError ( "Timeout atteint.", $e->getMessage () );
				}
			}
		}
		
		return $resultat;
	}

	/**
	 * Fais l'equivalent sql du replace.
	 *
	 * @param array $sql condition pour les entrees a supprimer.
	 * @param bool $safe Valide chaque suppression.
	 * @param bool $fsync Attend la validation de l'ecriture sur disque.
	 * @param bool $timeout Timeout pour la suppression.
	 * @return array|false Resultat du replace
	 */
	public function requete_replace($obj, $safe = true, $fsync = false, $timeout = false) {
		$resultat = false;
		$options = array ();
		if ($this->verifie_collection ()) {
			try {
				$this->_prepareOptionsStandard ( $options, $safe, $fsync, $timeout );
				$resultat = $this->collection->save ( $obj, $options );
			} catch ( MongoCursorException $e ) {
				return $this->onError ( "Erreur lors du replace de " . print_r ( $obj, true ), $e->getMessage () );
			} catch ( MongoCursorTimeoutException $e ) {
				return $this->onError ( "Timeout atteint.", $e->getMessage () );
			}
		}
		
		return $resultat;
	}

	/**
	 * Supprime les entrees de la collection suivant des criteres.
	 *
	 * @param array $sql condition pour les entrees a supprimer.
	 * @param bool $justOne Limite la suppression a la premiere entree.
	 * @param bool $safe Valide chaque suppression.
	 * @param bool $fsync Attend la validation de l'ecriture sur disque.
	 * @param bool $timeout Timeout pour la suppression.
	 * @return array|false Resultat du nettoyage
	 */
	public function requete_delete($sql = array(), $justOne = false, $safe = true, $fsync = false, $timeout = false) {
		$resultat = false;
		$options = array ();
		if ($this->verifie_collection ()) {
			if (! is_array ( $sql ) && $sql == "") {
				$sql = array ();
			}
			$options ["justOne"] = $justOne;
			$this->_prepareOptionsStandard ( $options, $safe, $fsync, $timeout );
			try {
				$resultat = $this->collection->remove ( $sql, $options );
			} catch ( MongoCursorException $e ) {
				return $this->onError ( "Erreur lors de la suppression de " . print_r ( $sql, true ), $e->getMessage () );
			} catch ( MongoCursorTimeoutException $e ) {
				return $this->onError ( "Timeout atteint.", $e->getMessage () );
			}
		}
		
		return $resultat;
	}

	/**
	 * Supprime les entrees de la collection suivant des criteres.
	 *
	 * @return array|false Resultat du nettoyage
	 */
	public function requete_drop_collection() {
		if ($this->verifie_collection ()) {
			$resultat = $this->collection->drop ();
		} else {
			$resultat = false;
		}
		
		return $resultat;
	}

	/**
	 * Reproduit la fonction count() sql.
	 *
	 * @param MongoCursor $resultat
	 * @return int|false nb resultat.
	 */
	public function compte_resultat($resultat) {
		if ($resultat instanceof MongoCursor) {
			$retour = $resultat->count ();
		} else {
			$retour = false;
		}
		
		return $retour;
	}

	/**
	 * Suit un reference sur la db.
	 *
	 * @param array $ref
	 * @return int|false nb resultat.
	 */
	public function recupere_dbRef($ref) {
		if (is_array ( $ref )) {
			$retour = $this->db->getDBRef ( $ref );
		} else {
			$retour = false;
		}
		
		return $retour;
	}

	/**
	 * Reproduit la fonction ORDER BY sql.
	 *
	 * @param MongoCursor $resultat
	 * @return MongoCursor|false resultat sorted
	 */
	public function order_resultat($resultat, $order) {
		if ($resultat instanceof MongoCursor) {
			$retour = $resultat->sort ( $order );
		} else {
			$retour = false;
		}
		
		return $retour;
	}

	/************************************* requetes sur les collections ***************/
	
	/**
	 * Ferme la connexion.
	 */
	function close() {
		if ($this->verifie_connexion ()) {
			$this->connexion->close ();
		}
	}

	function __destruct() {
		$this->close ();
	}

	/*********************** ACCESSEURS ***************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDb() {
		return $this->db;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getConnexion() {
		return $this->connexion;
	}

	/*********************** ACCESSEURS ***************/
	
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