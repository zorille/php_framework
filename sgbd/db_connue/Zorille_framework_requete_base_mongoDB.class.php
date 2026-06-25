<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use Exception;
use \MongoCursor as MongoCursor;
use \MongoDate as MongoDate;
/**
 * class requete_base_mongoDB<br>
 *
 * Gere la connexion a une base de type MongoDB.
 * @package Lib
 * @subpackage SQL-dbconnue
 */

class requete_base_mongoDB extends requeteMongoDB {
	/**
	 * var privee
	 * @access private
	 * @var Bool
	 */
	private $mise_a_jour=false;
	/**
	 * var privee
	 * @access private
	 * @var Bool
	 */
	private $database="";
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requete_base_mongoDB.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $machine
	 * @param string $user
	 * @param string $password
	 * @param int $port
	 * @param array $options
	 * @param string $maj_bd
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_base_mongoDB
	 */
	static function &creer_requete_base_mongoDB(options &$liste_option, string $machine = "", string $user = "", string $password = "", int $port=27017, array $options=array(), $maj_bd="oui", bool|string $sort_en_erreur = false, string $entete = __CLASS__): requete_base_mongoDB
	{
		$objet = new requete_base_mongoDB ( $machine, $user, $password, $port, $options,$maj_bd, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_base_mongoDB
	 */
	public function &_initialise(array $liste_class): static {
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
	 * @param string $database Nom de la base a connecter.
	 * @param array $options Options de connexion.
	 * @param string $maj_bd Prend les valeurs oui/non
	 * @param string $stop_on_erreur
	 * @param string $entete
	 * @throws Exception
	 */
	public function __construct($machine="", $user="", $password="", $port=27017, $database="", $options=array(), $maj_bd="oui", string $stop_on_erreur="oui", string $entete = __CLASS__)
	{
		//Gestion de abstract_log
		parent::__construct($machine,$user,$password,$port,$options,$stop_on_erreur, $entete);

		if($maj_bd=="oui" || $maj_bd===true){
			$this->mise_a_jour=true;
		}

		$this->database=$database;

		if(!$this->selectDatabase($database,false)){
			return $this->onError("Aucune DB selectionnee.");
		}
		$this->verifie_db();

		return true;
	}

	/***************** extracts *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getextractsCollection(): string
	{
		return 'extracts';
	}
	/**
	 * Cree et applique une requete de type select sur la collection extracts.
	 *
	 * @param int|string $id Valeur du champ clientid si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_extracts(int|string $id="", $nom='',
	                                                   $date="", $serial="", $format="", $order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"nom",(string) $nom,"text");
		$this->fabrique_where($where,"date",$date,"date");
		$this->fabrique_where($where,"serial",(string) $serial,"text");
		$this->fabrique_where($where,"format",(string) $format,"text");

		$this->onDebug("requete_select_extracts where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getextractsCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection extracts.
	 *
	 * @param string $nom Nom du fichier
	 * @param string $date Date du fichier
	 * @param string $serial Serial du fichier
	 * @param string $format Peut-etre day/week/month/quarter
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_extracts(string $nom, string $date,
	                                             string $serial, string $format): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"nom",(string) $nom);
		$this->fabrique_insert($set,"date",new MongoDate(strtotime($date)));
		$this->fabrique_insert($set,"date_insertion",new MongoDate(strtotime(date("Ymd H:i:s"))));
		$this->fabrique_insert($set,"serial",(string) $serial);
		$this->fabrique_insert($set,"format",(string) $format);

		$this->onDebug("requete_insere_dans_extracts set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getextractsCollection(),$set);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection extracts.
	 *
	 * @return int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_extracts($id="",
			$date="__no_update",$serial="__no_update",
			$format="__no_update"): bool|int
	{

		$set=array();
		if($date!=="__no_update"){
			$this->prepare_valeur_update($set,"date",new MongoDate(strtotime($date)));
		}
		$this->prepare_valeur_update($set,"serial",(string) $serial);
		$this->prepare_valeur_update($set,"format",(string) $format);

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

		$resultat=$this->updater($this->getextractsCollection(),$set,$where);
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

	/**
	 * Vide la collection extracts.
	 *
	 * @param int|string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_extracts(int|string $id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getextractsCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** extracts *********************/

	/***************** mergeds *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getmergedsCollection(): string
	{
		return 'mergeds';
	}
	/**
	 * Cree et applique une requete de type select sur la collection mergeds.
	 *
	 * @param int $id Valeur du champ clientid si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_mergeds($id="",$nom='',
			$date="",$num_rotate="",$serial="",$nblignes="",$order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"nom",(string) $nom,"text");
		$this->fabrique_where($where,"date",$date,"date");
		$this->fabrique_where($where,"numero_rotate",$num_rotate,"numeric");
		$this->fabrique_where($where,"serial",(string) $serial,"text");
		$this->fabrique_where($where,"nblines",$nblignes,"numeric");

		$this->onDebug("requete_select_mergeds where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getmergedsCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection mergeds.
	 *
	 * @param string $nom	  Nom du fichier
	 * @param string $date    Date du fichier
	 * @param int $num_rotate Numero de rotate du fichier
	 * @param string $serial  Serial du fichier
	 * @return int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_mergeds($nom,$date,$num_rotate,
			$serial,$nblignes): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"nom",(string) $nom);
		$this->fabrique_insert($set,"date",new MongoDate(strtotime($date)));
		$this->fabrique_insert($set,"date_insertion",new MongoDate(strtotime(date("Ymd H:i:s"))));
		$this->fabrique_insert($set,"numero_rotate",$num_rotate);
		$this->fabrique_insert($set,"serial",(string) $serial);
		$this->fabrique_insert($set,"nblines",(int) $nblignes);

		$this->onDebug("requete_insere_dans_mergeds set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getmergedsCollection(),$set);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection mergeds.
	 *
	 * @param string $id
	 * @param string $date
	 * @param string $num_rotate
	 * @param string $serial
	 * @param string $nblignes
	 * @param bool $insertNotExist
	 * @return bool|int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_mergeds(string $id="",
	                                            string $date="__no_update", $num_rotate="__no_update", $serial="__no_update",
	                                            string $nblignes="__no_update", $insertNotExist=false): bool|int
	{

		$set=array();
		if($date!=="__no_update"){
			$this->prepare_valeur_update($set,"date",new MongoDate(strtotime($date)));
		}
		$this->prepare_valeur_update($set,"numero_rotate",$num_rotate);
		$this->prepare_valeur_update($set,"serial",$serial);
		$this->prepare_valeur_update($set,"nblines",(int) $nblignes);

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

		$resultat=$this->updater($this->getmergedsCollection(),$set,$where,false,$insertNotExist);
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

	/**
	 * Vide la collection mergeds.
	 *
	 * @param string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_mergeds($id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getmergedsCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** mergeds *********************/

	/***************** noeuds *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getnoeudsCollection(): string
	{
		return 'noeuds';
	}
	/**
	 * Cree et applique une requete de type select sur la collection noeuds.
	 *
	 * @param int $id Valeur du champ clientid si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_noeuds($id="",$nom='',
			$date="",$num_rotate="",$noeud="",$service="",$type="noeud",$order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"type",(string) $type,"text");
		$this->fabrique_where($where,"nom",(string) $nom,"text");
		$this->fabrique_where($where,"date",$date,"date");
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

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection noeuds.
	 *
	 * @param string $nom Nom du fichier
	 * @param string $date Date du fichier
	 * @param int $num_rotate Numero de rotate du fichier
	 * @param string $noeud Noeud du fichier
	 * @param string $service Service du fichier
	 * @param string $type
	 * @param int $nblignes
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_noeuds(string $nom, string $date, int $num_rotate,
	                                           string $noeud, string $service, string $type="noeud", int $nblignes=1): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"nom",(string) $nom);
		$this->fabrique_insert($set,"type",(string) $type);
		$this->fabrique_insert($set,"date",new MongoDate(strtotime($date)));
		$this->fabrique_insert($set,"date_insertion",new MongoDate(strtotime(date("Ymd H:i:s"))));
		$this->fabrique_insert($set,"numero_rotate",$num_rotate);
		$this->fabrique_insert($set,"noeud",(string) $noeud);
		$this->fabrique_insert($set,"service",(string) $service);
		$this->fabrique_insert($set,"nblines",(int) $nblignes);

		$this->onDebug("requete_insere_dans_noeuds set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getnoeudsCollection(),$set);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection noeuds.
	 *
	 * @return int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_noeuds($id="",
			$date="__no_update",$num_rotate="__no_update",$noeud="__no_update",
			$service="__no_update",$type="__no_update"): bool|int
	{

		$set=array();
		if($date!=="__no_update"){
			$this->prepare_valeur_update($set,"date",new MongoDate(strtotime($date)));
		}
		$this->prepare_valeur_update($set,"numero_rotate",$num_rotate);
		$this->prepare_valeur_update($set,"noeud",(string) $noeud);
		$this->prepare_valeur_update($set,"service",(string) $service);
		$this->prepare_valeur_update($set,"type",(string) $type);

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

		$resultat=$this->updater($this->getnoeudsCollection(),$set,$where);
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

	/**
	 * Vide la collection noeuds.
	 *
	 * @param int|string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_noeuds(int|string $id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getnoeudsCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** noeuds *********************/

	/***************** reports *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getreportsCollection(): string
	{
		return 'reports';
	}
	/**
	 * Cree et applique une requete de type select sur la collection reports.
	 *
	 * @param int|string $id Valeur du champ clientid si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_reports(int|string $id="", $nom='',
	                                                  $date="", $serial="", $type_fichier="", string $order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"nom",(string) $nom,"text");
		$this->fabrique_where($where,"date",$date,"date");
		$this->fabrique_where($where,"serial",(string) $serial,"text");
		$this->fabrique_where($where,"format",(string) $type_fichier,"text");

		$this->onDebug("requete_select_reports where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getreportsCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection reports.
	 *
	 * @param string $nom Nom du fichier
	 * @param string $date Date du fichier
	 * @param string $serial Serial du fichier
	 * @param string $type_fichier Peut-etre day/week/month/quarter
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_reports(string $nom, string $date,
	                                            string $serial, string $type_fichier): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"nom",(string) $nom);
		$this->fabrique_insert($set,"date",new MongoDate(strtotime($date)));
		$this->fabrique_insert($set,"date_insertion",new MongoDate(strtotime(date("Ymd H:i:s"))));
		$this->fabrique_insert($set,"serial",(string) $serial);
		$this->fabrique_insert($set,"format",(string) $type_fichier);

		$this->onDebug("requete_insere_dans_reports set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getreportsCollection(),$set);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection reports.
	 *
	 * @param string $id
	 * @param string $nom
	 * @param string $date
	 * @param string $serial
	 * @param string $type_fichier
	 * @return bool|int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_reports(string $id=""
			, string                                   $nom="__no_update"
			, string                                   $date="__no_update", string $serial="__no_update"
			, string                                   $type_fichier="__no_update"): bool|int
	{

		$set=array();
		$this->prepare_valeur_update($set,"nom",(string) $nom);
		if($date!=="__no_update"){
			$this->prepare_valeur_update($set,"date",new MongoDate(strtotime($date)));
		}
		$this->prepare_valeur_update($set,"serial",(string) $serial);
		$this->prepare_valeur_update($set,"format",(string) $type_fichier);

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

		$resultat=$this->updater($this->getreportsCollection(),$set,$where);
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

	/**
	 * Vide la collection reports.
	 *
	 * @param int|string $id id a vider.
	 * @param string $serial
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_reports(int|string $id="", string $serial=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);
		$this->fabrique_where($where,"serial",(string) $serial,"text");

		$resultat=$this->supprimer($this->getreportsCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** reports *********************/

	/***************** segmentations *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getsegmentationsCollection(): string
	{
		return 'segmentations';
	}
	/**
	 * Cree et applique une requete de type select sur la collection segmentations.
	 *
	 * @param int|string $id Valeur du champ clientid si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_segmentations(int|string $id="", $seg_id='', $type=''
			,                                               $etat='', $date_debut="", $date_fin=""
			,                                               $serial_source="", $serial_destination=""
			,                                               $identitaire="", $comportemental="", string $order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"seg_id",(string) $seg_id,"text");
		$this->fabrique_where($where,"type",(string) $type,"text");
		$this->fabrique_where($where,"etat",(string) $etat,"text");
		$this->fabrique_where($where,"date_debut",$date_debut,"date");
		$this->fabrique_where($where,"date_fin",$date_fin,"date");
		$this->fabrique_where($where,"serial_source",(string) $serial_source,"text");
		$this->fabrique_where($where,"serial_destination",(string) $serial_destination,"text");
		$this->fabrique_where($where,"identitaire",(string) $identitaire,"text");
		$this->fabrique_where($where,"comportemental",(string) $comportemental,"text");

		$this->onDebug("requete_select_segmentations where : ".print_r($where,true), 1);

		$resultat=$this->selectionner($select,$this->getsegmentationsCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection segmentations.
	 *
	 * @param $seg_id
	 * @param $type
	 * @param $date_debut
	 * @param $date_fin
	 * @param $serial_source
	 * @param $serial_destination
	 * @param $identitaire
	 * @param $comportemental
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_segmentations($seg_id, $type
			,$date_debut,$date_fin
			,$serial_source,$serial_destination
			,$identitaire,$comportemental): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"seg_id",(string) $seg_id);
		$this->fabrique_insert($set,"type",(string) $type);
		$this->fabrique_insert($set,"etat","REQUESTED");
		$this->fabrique_insert($set,"date_demande",new MongoDate(strtotime(date("Ymd H:i:s"))));
		$this->fabrique_insert($set,"date_debut",new MongoDate(strtotime($date_debut)));
		$this->fabrique_insert($set,"date_fin",new MongoDate(strtotime($date_fin)));
		$this->fabrique_insert($set,"serial_source",(string) $serial_source);
		$this->fabrique_insert($set,"serial_destination",(string) $serial_destination);
		$this->fabrique_insert($set,"identitaire",(string) $identitaire);
		$this->fabrique_insert($set,"comportemental",(string) $comportemental);

		$this->onDebug("requete_insere_dans_segmentations set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getsegmentationsCollection(),$set);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection segmentations.
	 *
	 * @param string $id
	 * @param string $seg_id
	 * @param string $etat
	 * @param string $type
	 * @param string $date_debut
	 * @param string $date_fin
	 * @param string $serial_source
	 * @param string $serial_destination
	 * @param string $identitaire
	 * @param string $comportemental
	 * @return bool|int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_segmentations(string $id=""
			, string                                         $seg_id="__no_update", string $etat="__no_update", string $type="__no_update"
			, string                                         $date_debut="__no_update", string $date_fin="__no_update"
			, string                                         $serial_source="__no_update", string $serial_destination="__no_update"
			, string                                         $identitaire="__no_update", string $comportemental="__no_update"): bool|int
	{

		$set=array();
		$this->prepare_valeur_update($set,"seg_id",(string) $seg_id);
		$this->prepare_valeur_update($set,"type",(string) $type);
		$this->prepare_valeur_update($set,"etat",(string) $etat);
		if($date_debut!=="__no_update"){
			$this->prepare_valeur_update($set,"date_debut",new MongoDate(strtotime($date_debut)));
		}
		if($date_fin!=="__no_update"){
			$this->prepare_valeur_update($set,"date_fin",new MongoDate(strtotime($date_fin)));
		}
		$this->prepare_valeur_update($set,"serial_source",(string) $serial_source);
		$this->prepare_valeur_update($set,"serial_destination",(string) $serial_destination);
		$this->prepare_valeur_update($set,"identitaire",(string) $identitaire);
		$this->prepare_valeur_update($set,"comportemental",(string) $comportemental);

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

		$resultat=$this->updater($this->getsegmentationsCollection(),$set,$where);
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

	/**
	 * Vide la collection segmentations.
	 *
	 * @param int|string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_segmentations(int|string $id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getsegmentationsCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** segmentations *********************/

	/***************** spliteds *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getsplitedsCollection(): string
	{
		return 'spliteds';
	}
	/**
	 * Cree et applique une requete de type select sur la collection spliteds.
	 *
	 * @param int|string $id Valeur du champ clientid si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_spliteds(int|string $id="", $nom='',
	                                                   $date="", $serial="", $num_rotate="", $noeud="", $service="", string $order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"nom",(string) $nom,"text");
		$this->fabrique_where($where,"date",$date,"date");
		$this->fabrique_where($where,"serial",(string) $serial,"text");
		$this->fabrique_where($where,"numero_rotate",$num_rotate,"numeric");
		$this->fabrique_where($where,"noeud",(string) $noeud,"text");
		$this->fabrique_where($where,"service",(string) $service,"text");

		$this->onDebug("requete_select_spliteds where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getsplitedsCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection spliteds.
	 *
	 * @param string $nom Nom du fichier
	 * @param string $date Date du fichier
	 * @param $serial
	 * @param int $num_rotate Numero de rotate du fichier
	 * @param string $noeud Noeud du fichier
	 * @param string $service Service du fichier
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_spliteds(string $nom
			, string                                    $date, $serial, int $num_rotate,
			                                     string $noeud, string $service): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"nom",(string) $nom);
		$this->fabrique_insert($set,"date",new MongoDate(strtotime($date)));
		$this->fabrique_insert($set,"date_insertion",new MongoDate(strtotime(date("Ymd H:i:s"))));
		$this->fabrique_insert($set,"serial",(string) $serial);
		$this->fabrique_insert($set,"numero_rotate",$num_rotate);
		$this->fabrique_insert($set,"noeud",(string) $noeud);
		$this->fabrique_insert($set,"service",(string) $service);

		$this->onDebug("requete_insere_dans_spliteds set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getsplitedsCollection(),$set);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection spliteds.
	 *
	 * @param string $id
	 * @param string $date
	 * @param string $serial
	 * @param string $num_rotate
	 * @param string $noeud
	 * @param string $service
	 * @return bool|int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_spliteds(string $id="",
	                                             string $date="__no_update", string $serial="__no_update"
			, string                                    $num_rotate="__no_update", string $noeud="__no_update",
			                                     string $service="__no_update"): bool|int
	{

		$set=array();
		if($date!=="__no_update"){
			$this->prepare_valeur_update($set,"date",new MongoDate(strtotime($date)));
		}
		$this->prepare_valeur_update($set,"serial",(string) $serial);
		$this->prepare_valeur_update($set,"numero_rotate",$num_rotate);
		$this->prepare_valeur_update($set,"noeud",(string) $noeud);
		$this->prepare_valeur_update($set,"service",(string) $service);

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

		$resultat=$this->updater($this->getsplitedsCollection(),$set,$where);
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

	/**
	 * Vide la collection spliteds.
	 *
	 * @param int|string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_spliteds(int|string $id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getsplitedsCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** spliteds *********************/

	/***************** streaminglines *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getstreaminglinesCollection(): string
	{
		return 'streaminglines';
	}
	/**
	 * Cree et applique une requete de type select sur la collection streaminglines.
	 *
	 * @param int $id Valeur du champ clientid si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_streaminglines($id="",$serial=''
			, $date="", $nblines='',$order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"serial",(string) $serial,"text");
		$this->fabrique_where($where,"nblines",$nblines,"numeric");
		$this->fabrique_where($where,"date",$date,"date");

		$this->onDebug("requete_select_streaminglines where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getstreaminglinesCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection streaminglines.
	 *
	 * @param $serial
	 * @param string $date Date du fichier
	 * @param int $nblines
	 * @return int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_streaminglines($serial, string $date, int $nblines=0): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"serial",(string) $serial);
		$this->fabrique_insert($set,"nblines",(int) $nblines);
		$this->fabrique_insert($set,"date",new MongoDate(strtotime($date)));
		$this->fabrique_insert($set,"date_insertion",new MongoDate(strtotime(date("Ymd H:i:s"))));

		$this->onDebug("requete_insere_dans_streaminglines set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getstreaminglinesCollection(),$set);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection streaminglines.
	 *
	 * @param string $id
	 * @param string $serial
	 * @param string $date
	 * @param string $nblines
	 * @return bool|int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_streaminglines(string $id=""
			, string                                          $serial="__no_update", string $date="__no_update", string $nblines="__no_update"): bool|int
	{

		$set=array();
		if($date!=="__no_update"){
			$this->prepare_valeur_update($set,"date",new MongoDate(strtotime($date)));
		}
		$this->prepare_valeur_update($set,"serial",(string) $serial);
		$this->prepare_valeur_update($set,"nblines",(int) $nblines);

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

		$resultat=$this->updater($this->getstreaminglinesCollection(),$set,$where);
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

	/**
	 * Vide la collection streaminglines.
	 *
	 * @param int|string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_streaminglines(int|string $id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getstreaminglinesCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** streaminglines *********************/


	/***************** RUNTIME ****************************/
	/***************** RessourcesUsage *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getRessourcesUsageCollection(): string
	{
		return 'RessourcesUsage';
	}

	/**
	 * Cree et applique une requete de type select sur la collection RessourcesUsage.
	 *
	 * @param int|string $id Valeur du champ clientid si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_RessourcesUsage(int|string $id="", $service='',
	                                                          $serial="", string $order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"service",(string) $service,"text");
		$this->fabrique_where($where,"serial",(string) $serial,"text");

		$this->onDebug("requete_select_RessourcesUsage where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getRessourcesUsageCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection services.
	 *
	 * @param $service
	 * @param string $serial Serial du fichier
	 * @param int $ram Ram utilisee.
	 * @param int $time Time utilise.
	 * @param $cpu
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_RessourcesUsage($service,
	                                                    string $serial, int $ram, int $time, $cpu): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"service",(string) $service);
		$this->fabrique_insert($set,"serial",(string) $serial);
		$this->fabrique_insert($set,"ram",(int) $ram);
		$this->fabrique_insert($set,"time",(int) $time);
		$this->fabrique_insert($set,"cpu",(int) $cpu);

		$this->onDebug("requete_insere_dans_RessourcesUsage set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getRessourcesUsageCollection(),$set);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection services.
	 *
	 * @param string $id
	 * @param string $service
	 * @param string $serial
	 * @param string $ram
	 * @param string $time
	 * @param string $cpu
	 * @return bool|int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_RessourcesUsage(string $id="",
	                                                    string $service="__no_update", string $serial="__no_update",
	                                                    string $ram="__no_update", string $time="__no_update", string $cpu="__no_update"): bool|int
	{

		$set=array();
		$this->prepare_valeur_update($set,"service",(string) $service);
		$this->prepare_valeur_update($set,"serial",(string) $serial);
		$this->prepare_valeur_update($set,"ram",(int) $ram);
		$this->prepare_valeur_update($set,"time",(int) $time);
		$this->prepare_valeur_update($set,"cpu",(int) $cpu);

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

		$resultat=$this->updater($this->getRessourcesUsageCollection(),$set,$where);
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

	/**
	 * Vide la collection services.
	 *
	 * @param string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_RessourcesUsage(string $id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getRessourcesUsageCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** RessourcesUsage *********************/

	/***************** jobs *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getJobsCollection(): string
	{
		return 'jobs';
	}

	/**
	 * Cree et applique une requete de type select sur la collection jobs.
	 *
	 * @param int|string $id Valeur du champ _id si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_jobs(int|string $id=""
			,                                      $type_traitement="", $slurmid="", $etat="", $code_retour=""
			,                                      $ref="", $date_ref="", $date_debut="", $date_fin="", string $order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"type",(string) $type_traitement,"text");
		$this->fabrique_where($where,"slurmid",(string) $slurmid,"text");
		$this->fabrique_where($where,"etat",(string) $etat,"text");
		$this->fabrique_where($where,"code_retour",$code_retour,"numeric");
		$this->fabrique_where($where,"reference",(string) $ref,"text");
		$this->fabrique_where($where,"date_ref",$date_ref,"date");
		$this->fabrique_where($where,"date_debut",$date_debut,"date");
		$this->fabrique_where($where,"date_fin",$date_fin,"date");

		$this->onDebug("requete_select_jobs where : ".print_r($where,true), 1);

		$resultat=$this->selectionner($select,$this->getJobsCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection jobs.
	 *
	 * @param $type_traitement
	 * @param $slurmid
	 * @param $etat
	 * @param $ref
	 * @param $date_ref
	 * @param $date_debut
	 * @param $date_fin
	 * @param int $code_retour
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_jobs($type_traitement, $slurmid
			,                                $etat, $ref, $date_ref, $date_debut, $date_fin, int $code_retour=0): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"type",(string) $type_traitement);
		$this->fabrique_insert($set,"slurmid",(string) $slurmid);
		$this->fabrique_insert($set,"etat",(string) $etat);
		$this->fabrique_insert($set,"code_retour",(int) $code_retour);
		$this->fabrique_insert($set,"reference",(string) $ref);
		$this->fabrique_insert($set,"date_ref",new MongoDate(strtotime($date_ref)));
		$this->fabrique_insert($set,"date_debut",new MongoDate(strtotime($date_debut)));
		$this->fabrique_insert($set,"date_fin",new MongoDate(strtotime($date_fin)));

		$this->onDebug("requete_insere_dans_jobs set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getJobsCollection(),$set);
		if(is_array($resultat) && $resultat["ok"]==1){
			$retour=$set["_id"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection jobs.
	 *
	 * @param string $id
	 * @param string $type_traitement
	 * @param string $slurmid
	 * @param string $etat
	 * @param string $code_retour
	 * @param string $ref
	 * @param string $date_debut
	 * @param string $date_fin
	 * @return bool|int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_jobs(string $id="",
	                                         string $type_traitement="__no_update", string $slurmid="__no_update",
	                                         string $etat="__no_update", string $code_retour="__no_update", string $ref="__no_update",
	                                         string $date_debut="__no_update", string $date_fin="__no_update"): bool|int
	{

		$set=array();
		$this->prepare_valeur_update($set,"type",(string) $type_traitement);
		$this->prepare_valeur_update($set,"slurmid",(string) $slurmid);
		$this->prepare_valeur_update($set,"etat",(string) $etat);
		$this->prepare_valeur_update($set,"code_retour",(int) $code_retour);
		$this->prepare_valeur_update($set,"reference",(string) $ref);
		if($date_debut!="__no_update"){
			$this->prepare_valeur_update($set,"date_debut",new MongoDate(strtotime($date_debut)));
		}
		if($date_fin!="__no_update"){
			$this->prepare_valeur_update($set,"date_fin",new MongoDate(strtotime($date_fin)));
		}

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

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

		return $retour;
	}

	/**
	 * Vide la collection jobs.
	 *
	 * @param int|string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_jobs(int|string $id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getJobsCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** jobs *********************/

	/***************** pilotes *********************/
	/**
	 * ACCESSEURS get
	 */
	public function getPiloteCollection(): string
	{
		return 'pilotes';
	}

	/**
	 * Cree et applique une requete de type select sur la collection pilotes.
	 *
	 * @param int|string $id Valeur du champ _id si necessaire.
	 * @param string $order Champ pour le ORDER BY.
	 * @return MongoCursor|false Renvoi un tableau de resultat, FALSE sinon.
	 */
	public function requete_select_pilotes(int|string $id=""
			,                                         $type_traitement="", $chaine_prod="", $nbjobs="", $etat=""
			,                                         $date_traitement="", $date_debut="", $date_fin=""
			, string                                  $order=""): bool|MongoCursor
	{

		$select=array();
		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");
		$this->fabrique_where($where,"type",(string) $type_traitement,"text");
		$this->fabrique_where($where,"chaine_prod",(string) $chaine_prod,"text");
		$this->fabrique_where($where,"nbjobs",$nbjobs,"numeric");
		$this->fabrique_where($where,"etat",(string) $etat,"text");
		$this->fabrique_where($where,"date_traitement",$date_traitement,"date");
		$this->fabrique_where($where,"date_debut",$date_debut,"date");
		$this->fabrique_where($where,"date_fin",$date_fin,"date");

		$this->onDebug("requete_select_pilotes where : ".print_r($where,true), 2);

		$resultat=$this->selectionner($select,$this->getPiloteCollection(),$where,$order);

		if($order!="" && $resultat){
			$liste_order=array();
			$this->fabrique_order($liste_order,$order);
			$resultat=$this->order_resultat($resultat, $liste_order);
		}

		return $resultat;
	}

	/**
	 * Insere une entree dans la collection pilotes.
	 *
	 * @param $type_traitement
	 * @param $chaineprod
	 * @param $nbjobs
	 * @param $etat
	 * @param $date_traitement
	 * @param $date_debut
	 * @param $date_fin
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function requete_insere_dans_pilotes($type_traitement,$chaineprod,$nbjobs,$etat
			,$date_traitement,$date_debut,$date_fin): bool|int
	{

		$set=array();
		$this->fabrique_insert($set,"type",(string) $type_traitement);
		$this->fabrique_insert($set,"chaine_prod",(string) $chaineprod);
		$this->fabrique_insert($set,"nbjobs",(int) $nbjobs);
		$this->fabrique_insert($set,"etat",(string) $etat);
		$this->fabrique_insert($set,"date_traitement",new MongoDate(strtotime($date_traitement)));
		$this->fabrique_insert($set,"date_debut",new MongoDate(strtotime($date_debut)));
		$this->fabrique_insert($set,"date_fin",new MongoDate(strtotime($date_fin)));

		$this->onDebug("requete_insere_dans_pilotes set : ".print_r($set,true), 2);

		$resultat=$this->ajouter($this->getPiloteCollection(),$set);
		if(is_array($resultat) && $resultat["ok"]==1){
			$retour=$set["_id"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/**
	 * Cree et applique cette requete de type update sur la collection pilotes.
	 *
	 * @param string $id
	 * @param string $type_traitement
	 * @param string $chaine_prod
	 * @param string $nbjobs
	 * @param string $etat
	 * @param string $date_traitement
	 * @param string $date_debut
	 * @param string $date_fin
	 * @return bool|int nb ligne updated,false si not OK.
	 */
	public function requete_update_dans_pilotes(string $id=""
			, string                                   $type_traitement="__no_update", string $chaine_prod="__no_update", string $nbjobs="__no_update"
			, string                                   $etat="__no_update", string $date_traitement="__no_update"
			, string                                   $date_debut="__no_update", string $date_fin="__no_update"): bool|int
	{

		$set=array();
		$this->prepare_valeur_update($set,"type",(string) $type_traitement);
		$this->prepare_valeur_update($set,"chaine_prod",(string) $chaine_prod);
		$this->prepare_valeur_update($set,"nbjobs",(int) $nbjobs);
		$this->prepare_valeur_update($set,"etat",(string) $etat);
		if($date_traitement!="__no_update"){
			$this->prepare_valeur_update($set,"date_traitement",new MongoDate(strtotime($date_traitement)));
		}
		if($date_debut!="__no_update"){
			$this->prepare_valeur_update($set,"date_debut",new MongoDate(strtotime($date_debut)));
		}
		if($date_fin!="__no_update"){
			$this->prepare_valeur_update($set,"date_fin",new MongoDate(strtotime($date_fin)));
		}

		$where=array();
		$this->fabrique_where($where,"_id",$id,"mongoID");

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

	/**
	 * Vide la collection pilotes.
	 *
	 * @param int|string $id id a vider.
	 * @return bool|int 1 vaut OK, 0 vaut false.
	 */
	public function vide_pilotes(int|string $id=""): bool|int
	{
		$where=array();
		$this->fabrique_where($where,"_id",$id);

		$resultat=$this->supprimer($this->getPiloteCollection(),$where);
		if(is_array($resultat)){
			$retour=$resultat["ok"];
		} else {
			$retour=false;
		}

		return $retour;
	}

	/***************** pilotes *********************/

	/***************** RUNTIME ****************************/

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
