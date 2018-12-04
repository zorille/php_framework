<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \MongoId as MongoId;
use \MongoRegex as MongoRegex;
use \MongoDate as MongoDate;
use \MongoException as MongoException;
/**
 * class requeteMongoDB<br>
 * 
 * Construit les requetes et les appliques sur la base MongoDB.
 * @package Lib
 * @subpackage SQL
 */

class requeteMongoDB extends mongoDbAbstract {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requeteMongoDB.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requeteMongoDB
	 */
	static function &creer_requeteMongoDB(&$liste_option, $machine = "", $user = "", $password = "",$port=27017,$options=array(), $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new requeteMongoDB ( $machine, $user, $password, $port, $options, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requeteMongoDB
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet, prepare la valeur du sort_en_erreur
	 * et creer la connexion.
	 * @codeCoverageIgnore
	 * @param string $machine Nom de la machine.
	 * @param string $user User de connexion.
	 * @param string $password Password pour le user.
	 * @param int $port Port de connexion. Par defaut : 27017
	 * @param array $options Options de connexion.
	 * @param string $stop_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($machine="",$user="",$password="",$port=27017,$options=array(),$stop_on_erreur="oui",$entete="requeteMongoDB")
	{
		//Gestion de abstract_log
		parent::__construct($machine,$user,$password,$port,$options,$stop_on_erreur,$entete);

		return true;
	}

	/********************* Creation de requete simple ******************************/
	/**
	 * Creer la partie select pour mongoDb.
	 *
	 * @param string|array $select
	 * @param string $champ Nom du champ a selectionner.
	 * @return true
	 */
	public function fabrique_select(&$select,$champ){
		if(is_array($select)){
			$select[$champ]=1;
		} else {
			return $this->onError("select doit etre un tableau.");
		}

		return true;
	}

	/**
	 * Creer une liste de condition separe par des AND sql.
	 *
	 * @param array $where
	 * @param string|array $champ
	 * @param string|array $valeur
	 * @param string $type_champ Peut etre text/numeric/date
	 * @param string $operateur Reconnu (pour les date et numeric) : =,>,>=,<,<=
	 */
	public function fabrique_where(&$where,$champ,$valeur,$type_champ="text",$operateur="="){
		//La valeur ne peut pas etre vide
		if($valeur!==""){
			if(is_array($where)){
				/*if(is_array($champ)){
					$where[].=$champ;
				} elseif(is_array($valeur)){
				$where[$champ]=$valeur;
				} else {*/
				if(count($where)===0){
					$where=$this->_choisieTypeWhere($champ, $valeur, $type_champ, $operateur);
				} else {
					$obj=$this->_choisieTypeWhere($champ, $valeur, $type_champ, $operateur);
					if(isset($where[$champ])){
						$where[$champ]=array_merge($where[$champ],$obj[$champ]);
					} else {
						$where=array_merge($where,$obj);
					}
				}
				//}
			} else {
				return $this->onError("where doit etre un tableau.");
			}
		}

		return true;
	}

	/**
	 * Creer la partie insert pour mongoDb.
	 *
	 * @param string|array $select
	 * @param string $champ Nom du champ a selectionner.
	 * @return true
	 */
	public function fabrique_insert(&$set,$champ,$valeur){
		if(is_array($set)){
			if($valeur!==""){
				$set[$champ]=$valeur;
			} else {
				return $this->onError("la valeur ne peut pas etre null.");
			}
		} else {
			return $this->onError("le set doit etre un tableau.");
		}

		return true;
	}

	/**
	 * Creer une liste de champ a setter.
	 *
	 * @param array $set Tableau de set
	 * @param string $champ champ du set
	 * @param string $valeur valeur du champ
	 */
	public function fabrique_set(&$set,$champ,$valeur){
		//La valeur ne peut pas etre vide
		if($valeur!==""){
			if(is_array($set)){
				if(count($set)>0){
					foreach($set as $key=>$value){
						if(is_array($value)){
							$set[$key][$champ]=$valeur;
						} else {
							return $this->onError("Les donnees du set ne sont pas conforme.",$value);
						}
					}
				} else {
					$tableau=$this->_creerSet($champ, $valeur);
					if($tableau!==false){
						$set=$tableau;
					}
				}
			} else {
				return $this->onError("set doit etre un tableau.");
			}

			$this->onDebug("fabrique_set output : ".print_r($set,true), 3);
		} else {
			$this->onWarning("La valeur est vide.");
		}

		return true;
	}

	/**
	 * Creer une liste de champ a setter.<br>
	 * Si la valeur vaut "__no_update", le champ n'est pas traite.
	 *
	 * @param array $set Tableau de set
	 * @param string $champ champ du set
	 * @param string $valeur valeur du champ
	 */
	public function prepare_valeur_update(&$set,$champ,$valeur){
		if($valeur!=="__no_update"){
			$this->fabrique_set($set, $champ, $valeur);
		}

		return true;
	}

	/**
	 * Creer la partie select pour mongoDb.
	 *
	 * @param string|array $select
	 * @param string $champ Nom du champ a selectionner.
	 * @return true
	 */
	public function fabrique_order(&$order,$champ){
		if(is_array($order)){
			$order[$champ]=1;
		} else {
			return $this->onError("order doit etre un tableau.");
		}

		return true;
	}

	/**
	 * Creer le bon format en fonction du type du champ et de l'operateur voulu.<br>
	 * Si le type est text et qu'il contient des % en debut ou fin, on fait une regexpr.
	 *
	 * @param string $champ
	 * @param string $valeur
	 * @param string $type_champ Peut etre text/numeric/date/mongoID
	 * @param string $operateur Reconnu (pour les date et numeric) : =,>,>=,<,<=
	 */
	protected function _choisieTypeWhere($champ,$valeur,$type_champ="text",$operateur="="){
		$CODE_RETOUR=false;
		switch($type_champ){
			case "text":
				if(strpos($valeur,"%")!==false){
					$CODE_RETOUR=$this->_creerLike($champ,$valeur);
				} else {
					$CODE_RETOUR=array($champ => $valeur);
				}
				break;
			case "date":
				$valeur = new MongoDate(strtotime($valeur));
				//$valeur devient un numeric
			case "numeric":
				switch ($operateur){
					case ">":
						$CODE_RETOUR=$this->_creerGt($champ, $valeur);
						break;
					case ">=":
						$CODE_RETOUR=$this->_creerGte($champ, $valeur);
						break;
					case "<":
						$CODE_RETOUR=$this->_creerLt($champ, $valeur);
						break;
					case "<=":
						$CODE_RETOUR=$this->_creerLte($champ, $valeur);
						break;
					case "<>":
						$CODE_RETOUR=$this->_creerNe($champ, $valeur);
						break;
					case "=":
						$CODE_RETOUR=array($champ => $valeur);
						break;
					default :
						return $this->onError("Operateur inconnu.".$operateur);
				}
				break;
			case "mongoID" :
				$theObjId = new MongoId($valeur);
				$CODE_RETOUR=array("_id" => $theObjId);
				break;
			case "MongoDBRef" :
				$CODE_RETOUR=array($champ => $valeur);
				break;
			default:
				return $this->onError("Type de champ inconnu : ".$type_champ);
		}

		return $CODE_RETOUR;
	}

	/**
	 * Creer l'equivalent du OR sql.
	 *
	 * @param array $cond1
	 * @param array $cond2
	 * @return array|false
	 */
	public function creer_or($cond1,$cond2){
		//array('$or' => array(array("a" => "t1"), array("b" => "t4")))
		if(is_array($cond1) && is_array($cond2)){
			$retour=array('$or' => array($cond1,$cond2));
		} else {
			return $this->onError("Un des parametres n'est pas un tableau.");
		}

		return $retour;
	}

	/**
	 * Creer l'equivalent du like sql.
	 *
	 * @param string $champ
	 * @param string $valeur
	 * @return array
	 */
	protected function _creerLike($champ,$valeur){
		$regexpr="";
		if(strpos($valeur,'%')===0){
			$regexpr=str_replace("%","/",$valeur);
		} else {
			$regexpr="/^".$valeur;
		}
		$regexpr=str_replace("%","",$regexpr);


		try{
			$retour=array($champ => new MongoRegex($regexpr."/i"));
		} catch(MongoException $e) {
			return $this->onError("Probleme de regexpr : ".$e->getMessage());
		}

		return $retour;
	}

	/**
	 * Creer l'equivalent du <> sql.
	 *
	 * @param string $champ
	 * @param string $valeur
	 * @return array
	 */
	protected function _creerNe($champ,$valeur){

		$retour=array($champ => array('$ne' => $valeur));

		return $retour;
	}

	/**
	 * Creer l'equivalent du > sql.
	 *
	 * @param string $champ
	 * @param string $valeur
	 * @return array
	 */
	protected function _creerGt($champ,$valeur){

		$retour=array($champ => array('$gt' => $valeur));

		return $retour;
	}

	/**
	 * Creer l'equivalent du >= sql.
	 *
	 * @param string $champ
	 * @param string $valeur
	 * @return array
	 */
	protected function _creerGte($champ,$valeur){

		$retour=array($champ => array('$gte' => $valeur));

		return $retour;
	}

	/**
	 * Creer l'equivalent du < sql.
	 *
	 * @param string $champ
	 * @param string $valeur
	 * @return array
	 */
	protected function _creerLt($champ,$valeur){

		$retour=array($champ => array('$lt' => $valeur));

		return $retour;
	}

	/**
	 * Creer l'equivalent du <= sql.
	 *
	 * @param string $champ
	 * @param string $valeur
	 * @return array
	 */
	protected function _creerLte($champ,$valeur){

		$retour=array($champ => array('$lte' => $valeur));

		return $retour;
	}

	/**
	 * Creer l'equivalent du var+=1.
	 *
	 * @param string $champ
	 * @param array $valeur
	 * @return array|false
	 */
	public function _creerInc($champ,$valeur){

		if(is_numeric($valeur)){
			$retour=array('$inc' => array( $champ => $valeur));
		} else {
			return $this->onError("La valeur doit etre un numeric pour incrementer.");
		}

		$this->onDebug("_creerInc output :".print_r($retour,true),3);
		return $retour;
	}

	/**
	 * Creer l'equivalent du SET champ=valeur.
	 *
	 * @param string $champ
	 * @param array $valeur
	 * @return array|false
	 */
	protected function _creerSet($champ,$valeur){
		$this->onDebug("_creerSet : champ=".print_r($champ,true)." valeur=".print_r($valeur,true), 3);

		if($valeur!==""){
			$retour=array('$set' => array($champ => $valeur));
		} else {
			return $this->onError("La valeur doit etre remplie pour setter.",$valeur);
		}

		$this->onDebug("_creerSet output :".print_r($retour,true),3);
		return $retour;
	}

	/**
	 * Creer l'equivalent du champ existe (quelsoit sa valeur).
	 *
	 * @param string $champ
	 * @return array
	 */
	protected function _creerExist($champ){
		//array("age" => array('$exists' => true))
		$retour=array($champ => array('$exists' => true));

		return $retour;
	}
	/********************* Creation de requete simple ******************************/

	/********************* Gestion de requete simple *******************************/
	/**
	 * Creer un requete SQL type INSERT et l'applique a la base.
	 *
	 * @param string $table Table pour l'insertion.
	 * @param array $values Liste des valeurs du VALUES.
	 * @param array $options Condition de l'insert.
	 * @return array Renvoi le resultat au format tableau.
	 */
	public function ajouter($table,&$value,$where=array()) {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if(!$this->verifie_db()) return false;
		$resultat=false;

		if($table!="" && is_array($value)){
			$this->selectCollection($table);

			$resultat=$this->requete_insert($value);
		} elseif(!is_array($value)) {
			return $this->onError("La valeur est un tableau.");
		} else {
			return $this->onError("Il faut une collection dans le 'from'.");
		}

		return $resultat;
	}

	/**
	 * Creer un requete SQL type SELECT...FROM...WHERE et l'applique a la base.
	 *
	 * @param string|array $select Liste de champ du SELECT.
	 * @param string $from Liste de champ du FROM.
	 * @param string|array $where Liste de champ du WHERE.
	 * @param int $type_result Deprecated
	 * @return array Renvoi le resultat au format tableau.
	 */
	public function selectionner($select,$from,$where=array(),$sort=false) {
		$this->onDebug("selectionner",3);

		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if(!$this->verifie_db()) return false;
		$resultat=false;

		if($from!="" && is_array($select) && is_array($where)){
			$this->selectCollection($from);

			$resultat=$this->faire_requete($where,$select);
		} elseif(!(is_array($select) && is_array($where))) {
			return $this->onError("Le select et/ou le where doivent etre des tableaux");
		} else {
			return $this->onError("Il faut une collection dans le 'from'.");
		}

		return $resultat;
	}

	/**
	 * Creer un requete SQL type SELECT...FROM...WHERE et l'applique a la base.
	 *
	 * @param string $select Liste de champ du SELECT.
	 * @param string $from Liste de champ du FROM.
	 * @param string|array $where Liste de champ du WHERE.
	 * @param int $type_result Deprecated
	 * @return array Renvoi le resultat au format tableau.
	 */
	public function selectionner_distinct($select,$from,$where=array(),$sort=false) {
		$this->onDebug("selectionner_distinct",3);

		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if(!$this->verifie_db()) return false;
		$resultat=false;

		if($from!="" && is_string($select) && is_array($where)){
			$resultat=$this->requete_distinct($from,$where,$select);
		} elseif(!(is_string($select) && is_array($where))) {
			return $this->onError("Le select et/ou le where doivent etre une string et un tableaux");
		} else {
			return $this->onError("Il faut une collection dans le 'from'.");
		}

		return $resultat;
	}

	/**
	 * Creer un requete SQL type DELETE et l'applique a la base.
	 *
	 * @param string $table Table pour la suppression.
	 * @param array $supprimer Liste des conditions du WHERE.
	 * @param bool $justOne Limite la suppression a la premiere entree.
	 * @param bool $safe Valide chaque suppression.
	 * @param bool $fsync Attend la validation de l'ecriture sur disque.
	 * @param bool $timeout Timeout pour la suppression.
	 * @return array Renvoi le resultat au format tableau.
	 */
	public function supprimer($table,$where,$justOne=false,$safe=true,$fsync=false,$timeout=false) {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if(!$this->verifie_db()) return false;
		$resultat=false;

		if($table!="" && is_array($where)){
			$this->selectCollection($table);

			$resultat=$this->requete_delete($where,$justOne,$safe,$fsync,$timeout);
		} elseif(!is_array($where)) {
			return $this->onError("Le where n'est pas un tableau.");
		} else {
			return $this->onError("Il faut une collection dans le 'from'.");
		}

		return $resultat;
	}

	/**
	 * Creer un requete SQL type UPDATE et l'applique a la base.
	 *
	 * @param string $table Table pour l'insertion.
	 * @param array $set Liste de champ=valeur a updater.
	 * @param array $where Liste des conditions du WHERE.
	 * @param bool $allTuple Met a jour tous les tuples.
	 * @return array Renvoi le resultat au format tableau.
	 */
	public function updater($table,$set,$where=array(),$allTuple=false,$insertNotExist=false,$safe=true,$fsync=false,$timeout=false) {
		//Si il n'y a pas de connexion a la base, alors pas de requete possible
		if(!$this->verifie_db()) return false;
		$resultat=false;

		if($table!="" && is_array($where) && is_array($set)){
			$this->selectCollection($table);

			$resultat=$this->requete_update($where, $set,$allTuple,$insertNotExist,$safe,$fsync,$timeout);
		} elseif(!(is_array($where) && is_array($set))) {
			return $this->onError("Le set et/ou le where doivent etre des tableaux");
		} else {
			return $this->onError("Il faut une collection dans le 'from'.");
		}

		return $resultat;
	}

	/**
	 * Creer un requete MongoDB type SHOW DATABASES (sql).
	 *
	 * @return array|false Renvoi le resultat au format array.
	 */
	public function liste_db() {
		$dbs = $this->listeDatabases();
		if(isset($dbs["databases"]) && is_array($dbs["databases"])){
			$liste=array();
			foreach($dbs["databases"] as $data){
				$liste[].=$data["name"];
			}
		} else {
			$liste=false;
		}

		return $liste;
	}

	/**
	 * Creer un requete MongoDB type SHOW TABLES (sql).
	 *
	 * @return array|false Renvoi le resultat dans un tableau.
	 */
	public function liste_table() {

		return $this->listeCollections();
	}

	/********************* Gestion de requete simple *******************************/

	/**************************** ACCESSEURS **********************/

	/**************************** ACCESSEURS **********************/

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