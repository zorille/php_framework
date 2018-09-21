<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class gestion_bd_mongoDB<br>
 *
 * Gere la connexion a une base de type MongoDB.
 * @package Lib
 * @subpackage SQL-dbconnue
 */

class gestion_bd_mongoDB extends requete_base_mongoDB {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type gestion_bd_mongoDB.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return gestion_bd_mongoDB
	 */
	static function &creer_gestion_bd_mongoDB(&$liste_option, $machine = "", $user = "", $password = "",$port=27017,$options=array(),$maj_bd="oui", $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new gestion_bd_mongoDB ( $machine, $user, $password, $port, $options,$maj_bd, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return gestion_bd_mongoDB
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
	 * @param string $database Nom de la base a connecter.
	 * @param string|bool $maj_bd Prend les valeurs oui/non
	 * @param string $stop_en_erreur Prend les valeurs oui/non
	 * @param string $maj_bd Prend les valeurs oui/non
	 */
	public function __construct($machine="",$user="",$password="",$port=27017,$database="standard",$options=array(),$maj_bd="oui",$stop_on_erreur="oui", $entete = __CLASS__)
	{
		//Gestion de abstract_log
		parent::__construct($machine,$user,$password,$port,$database,$options,$maj_bd,$stop_on_erreur,$entete);

		return true;
	}

	public function retrouve_distinct_splited_serial($nom='',
			$date="",$serial="",$num_rotate="",$noeud="",$service=""){

		$select=array();
		$where=array();
		$this->fabrique_where($where,"type",'splited',"text");
		$this->fabrique_where($where,"nom",(string) $nom,"text");
		$this->fabrique_where($where,"date",$date,"date");
		$this->fabrique_where($where,"serial",(string) $serial,"text");
		$this->fabrique_where($where,"numero_rotate",$num_rotate,"numeric");
		$this->fabrique_where($where,"noeud",(string) $noeud,"text");
		$this->fabrique_where($where,"service",(string) $service,"text");

		$this->onDebug("requete_select_spliteds where : ".print_r($where,true), 2);

		$resultat=$this->selectionner_distinct($select,$this->getsplitedsCollection(),$where);

		$this->onDebug("requete_select_spliteds output : ".$resultat->count(), 2);

		return $resultat;
	}

	public function retrouve_liste_noeud_par_periode($date_debut,$date_fin,
			$num_rotate="",$noeud="",$service="",$type="noeud",$order="") {

		$select=array();
		$where=array();
		$this->fabrique_where($where,"type",(string) $type,"text");
		$this->fabrique_where($where,"date",$date_debut,"date",">");
		$this->fabrique_where($where,"date",$date_fin,"date","<");
		$this->fabrique_where($where,"numero_rotate",$num_rotate,"numeric");
		$this->fabrique_where($where,"noeud",(string) $noeud,"text");
		$this->fabrique_where($where,"service",(string) $service,"text");

		$this->onDebug("requete_select_noeuds where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getnoeudsCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		$this->onDebug("retrouve_liste_noeud_par_periode output : ", 2);
		$this->onDebug($resultat->count(), 2);

		return $resultat;
	}

	/**
	 * Retrouve la liste des jobs en erreur sur une periode
	 *
	 * @param int|string $date_debut
	 * @param int|string $date_fin
	 */
	public function retrouve_liste_jobs_en_erreur($date_debut,$date_fin){

		$select=array();
		$where=array();
		$order=array();
		$this->fabrique_where($where,"date_debut",$date_debut,"date",">=");
		$this->fabrique_where($where,"date_fin",$date_fin,"date","<=");
		$array_ne=$this->creer_ne("etat", "ok");
		$where=array_merge($where,$array_ne);
			
		$this->onDebug("retrouve_liste_jobs_en_erreur where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getJobsCollection(),$where,$order);

		return $resultat;
	}

	/**
	 * Retrouve la liste des jobs en erreur sur une periode
	 *
	 * @param int|string $date_debut
	 * @param int|string $date_fin
	 */
	public function retrouve_liste_jobs($date_debut,$date_fin,$type_traitement=""){

		$select=array();
		$where=array();
		$order=array();
		$this->fabrique_where($where,"type",(string) $type_traitement,"text");
		$this->fabrique_where($where,"date_debut",$date_debut,"date",">");
		$this->fabrique_where($where,"date_fin",$date_fin,"date","<");

		$this->onDebug("retrouve_liste_jobs where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getJobsCollection(),$where,$order);

		return $resultat;
	}

	/**
	 * recupere la ram et le time dans la collection services de la base runtime.
	 *
	 * @return array Renvoi un tableau de resultat.
	 */
	public function recupereRamTime($service,
			$serial) {
		$retour=array('serial'=>$serial,'ram'=>50,'time'=>1440,'disk'=>50, 'cpu'=>1);
		$resultat=$this->requete_select_RessourcesUsage('',$service,$serial);

		try{
			if($resultat instanceOf MongoCursor){
				foreach($resultat as $row){

					if(isset($row['ram']) && $row['ram']!=""){
						$retour['ram']=$row['ram'];
					}
					if(isset($row['ram']) && $row['ram']!=""){
						$retour['ram']=$row['ram'];
					}
					if(isset($row['cpu']) && $row['cpu']!=""){
						$retour['cpu']=$row['cpu'];
					}
				}
			}
		} catch(MongoCursorException $e) {
			$this->onWarning("Erreur lors de la recuperation des donnees ram/time ( code retour : "
					.$e->getCode().") ".$e->getMessage());
		} catch (MongoCursorTimeoutException $e){
			return $this->onError("Timeout atteint.",$e->getMessage());
		}

		$this->onDebug("recupereRamTime output : ", 2);
		$this->onDebug($retour, 2);

		return $retour;
	}

	/***************** extracts *********************/
	/**
	 * Retrouve la liste des extracts sur une periode
	 *
	 * @param int|string $date_debut
	 * @param int|string $date_fin
	 */
	public function retrouve_liste_extracts($date_debut,$date_fin,$serial,$type){

		$select=array();
		$where=array();
		$order=array();
		$this->fabrique_where($where,"format",(string) $type,"text");
		$this->fabrique_where($where,"serial",(string) $serial,"text");
		$this->fabrique_where($where,"date",$date_debut,"date",">=");
		$this->fabrique_where($where,"date",$date_fin,"date","<=");

		$this->onDebug("retrouve_liste_extracts where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getextractsCollection(),$where,$order);

		return $resultat;
	}
	/***************** extracts *********************/

	/***************** mergeds *********************/
	/**
	 * Cree et applique cette requete de type update sur la collection mergeds.
	 *
	 * @return int nb ligne updated,false si not OK.
	 */
	public function update_ou_insert_dans_mergeds($nom,$date,$num_rotate,$serial,$nblignes) {

		$set=array();
		$this->prepare_valeur_update($set,"nblines",(int) $nblignes);
		$this->prepare_valeur_update($set,"date_insertion",new MongoDate(strtotime(date("Ymd H:i:s"))));

		$where=array();
		$this->fabrique_where($where,"nom",(string) $nom,"text");
		$this->fabrique_where($where,"type",'merged',"text");
		$this->fabrique_where($where,"date",$date,"date");
		$this->fabrique_where($where,"numero_rotate",$num_rotate,"numeric");
		$this->fabrique_where($where,"serial",(string) $serial,"text");

		$resultat=$this->updater($this->getmergedsCollection(),$set,$where,false,true);
		if(is_array($resultat)){
			if($resultat["ok"]==1){
				$retour=$resultat["updatedExisting"];
			} else {
				$retour=false;
			}
		} else {
			$retour=false;
		}

		return $retour;
	}
	/***************** mergeds *********************/

	/***************** reports *********************/
	/**
	 * Retrouve la liste des rapports sur une periode
	 *
	 * @param int|string $date_debut
	 * @param int|string $date_fin
	 */
	public function retrouve_liste_rapports($date_debut,$date_fin,$serial,$type){

		$select=array();
		$where=array();
		$order=array();
		$this->fabrique_where($where,"format",(string) $type,"text");
		$this->fabrique_where($where,"serial",(string) $serial,"text");
		$this->fabrique_where($where,"date",$date_debut,"date",">=");
		$this->fabrique_where($where,"date",$date_fin,"date","<=");

		$this->onDebug("retrouve_liste_rapports where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getreportsCollection(),$where,$order);

		return $resultat;
	}
	/***************** reports *********************/

	/***************** segmentations *********************/
	/**
	 * Retrouve la liste des segmentations en erreur sur une periode
	 *
	 * @param int|string $date_debut
	 * @param int|string $date_fin
	 */
	public function retrouve_liste_segmentations($date_demande_mini,$date_demande_maxi){

		$select=array();
		$where=array();
		$order=array();
		$this->fabrique_where($where,"date_demande",$date_demande_mini,"date",">=");
		$this->fabrique_where($where,"date_demande",$date_demande_maxi,"date","<=");

		$this->onDebug("retrouve_liste_segmentations where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getsegmentationsCollection(),$where,$order);

		return $resultat;
	}
	/***************** segmentations *********************/

	/***************** traitements *********************/
	/**
	 * Cree et applique une requete de type select sur la collection traitements.
	 *
	 * @param int $id Valeur du champ _id si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_traitements($collection
			,$id="",$jobid="",$order="") {

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"jobs",MongoDBRef::create($this->getJobsCollection(),$jobid),"MongoDBRef");

		$this->onDebug("requete_select_traitements where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$collection,$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		$this->onDebug("requete_select_traitements output : ".$resultat->count(), 2);

		return $resultat;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection en argument.
	 *
	 * @return int nb ligne updated,false si not OK.
	 */
	public function requete_update_traitements($collection,&$donnees_fichier,$jobid) {

		if(!isset($donnees_fichier["jobs"])){
			$tableau=array();
		} else {
			$tableau=$donnees_fichier["jobs"];
		}
		$tableau[count($tableau)]=MongoDBRef::create($this->getJobsCollection(),new MongoId($jobid));

		$set=array();
		$this->prepare_valeur_update($set,"jobs",$tableau);

		$where=array();
		$this->fabrique_where($where,"_id",(string) $donnees_fichier["_id"],"mongoID");

		$resultat=$this->updater($collection,$set,$where);
		if(is_array($resultat)){
			if($resultat["ok"]==1){
				$retour=$resultat["updatedExisting"];
				$donnees_fichier["jobs"]=$tableau;
			} else {
				$retour=false;
			}
		} else {
			$retour=false;
		}

		$this->onDebug("requete_update_traitements output : ", 2);
		$this->onDebug($resultat, 2);

		return $retour;
	}

	/**
	 * Renvoi 0 si tout est OK.
	 * Renvoi 1 en cas d'erreur.
	 * Renvoi 154 en cas de traitement en cours.
	 * Renvoi 155 en cas de distribution en cours.
	 * Renvoi 156 en cas de warning en cours.
	 *
	 * @param array &$job job a valider
	 * @return int
	 */
	public function valide_etat_traitement(&$job){
		switch ($job['etat']){
			case "ok":
			case "corriger":
			case "annuler":
				return 0;
			case "en cours":
				//Le fichier est en traitement
				return 154;
			case "distribution":
				//Le fichier a en cours d'attribution
				return 155;
			case "warning":
				//Le fichier a en cours d'attribution
				return 156;
			case "erreur":
			default :
				return 1;
		}
	}


	/**
	 * Valide qu'il n'y pas de traitement termine correctement pour un type de traitement donne.
	 *
	 * @param array $fichier "tuple" du fichier qui doit etre traite
	 * @param string $type_traitement type de traitement donne
	 */
	public function valide_non_traitement(&$fichier,$type_traitement,$reference=""){
		//Si il n'y a pas de traitement dans le "tuple"
		if(!isset($fichier["jobs"])){
			return true;
		} elseif(!is_array($fichier["jobs"])) {
			//Si l'entree "jobs" exite mais n'a pas de donnees, il n'y a pas de traitement
			return true;
		} elseif(count($fichier["jobs"])===0) {
			//Ou si le traitement n'a pas de jobid dans le "tuple"
			return true;
		} else {
			//On verifie que les traitements pour le type_traitement ne sont pas en erreur
			foreach($fichier["jobs"] as $jobRef){
				$job=$this->getDb()->getDBRef($jobRef);
				if($reference!="" && $reference!=$job["reference"]){
					continue;
				}
				if($job["type"]==$type_traitement){
					switch ($job['etat']){
						case "ok":
						case "en cours":
						case "distribution":
						case "warning":
						case "annuler":
							//Le fichier a eu un traitement
							return false;
						case "corriger":
						case "erreur":
						default :
							//On passe a la validation du job suivant
					}
				}
			}
			return true;
		}

		//au cas ou
		return false;
	}

	/***************** traitements *********************/

	/**
	 * Cree et applique cette requete de type update sur la collection en argument.
	 *
	 * @return int nb ligne updated,false si not OK.
	 */
	public function requete_ajoute_metrics_dans_job($donnees_metrics,$jobid) {

		$set=array();
		$this->prepare_valeur_update($set,"metrics",$donnees_metrics);

		$where=array();
		$this->fabrique_where($where,"_id",(string) $jobid,"mongoID");

		$resultat=$this->updater($this->getJobsCollection(),$set,$where);
		if(is_array($resultat)){
			if($resultat["ok"]==1){
				$retour=$resultat["updatedExisting"];
			} else {
				$retour=false;
			}
		} else {
			$retour=false;
		}

		$this->onDebug("requete_ajoute_metrics_job output : ", 2);
		$this->onDebug($resultat, 2);

		return $retour;
	}

	/***************** liaison traitements/pilotes *********************/
	/**
	 * Cree et applique cette requete de type update sur la collection en argument.
	 *
	 * @return int nb ligne updated,false si not OK.
	 */
	public function ajoute_liste_jobs_dans_pilotes($pilote_id ,$liste_jobid) {
		$tableau=array();
		if(!is_array($liste_jobid)){
			return false;
		}

		foreach($liste_jobid as $jobid){
			$tableau[count($tableau)]=MongoDBRef::create($this->getJobsCollection(),new MongoId($jobid));
		}

		$set=array();
		$this->prepare_valeur_update($set,"jobs",$tableau);

		$where=array();
		$this->fabrique_where($where,"_id",(string) $pilote_id,"mongoID");

		$resultat=$this->updater($this->getPiloteCollection(),$set,$where);
		if(is_array($resultat)){
			if($resultat["ok"]==1){
				$retour=$resultat["updatedExisting"];
			} else {
				$retour=false;
			}
		} else {
			$retour=false;
		}

		return $retour;
	}
	/***************** liaison traitements/pilotes *********************/

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help()
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>