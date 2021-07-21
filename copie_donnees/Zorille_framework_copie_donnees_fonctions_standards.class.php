<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use Zorille\framework\ssh_z as ssh_z;
use Zorille\framework\ftp as ftp;
/**
 * class copie_donnees_fonctions_standards<br>
 *
 * Gere le transfert de fichier.
 * @package Lib
 * @subpackage Copie_Donnees
 */
class copie_donnees_fonctions_standards extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var fonctions_standards
	 */
	private $class_standard;
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type copie_donnees.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param ssh_z|ftp $connexion connexion ftp/ssh existante.
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return copie_donnees
	 */
	static function &creer_copie_donnees_fonctions_standards(&$liste_option, $connexion = false, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new copie_donnees_fonctions_standards ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"connexion"=>$connexion
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return copie_donnees
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
	
		$fichier_a_copier = copie_donnees::creer_copie_donnees ( $liste_class ["options"], $liste_class ["connexion"] );
		$this->setObjetCopieDonneesRef ( $fichier_a_copier );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct( $sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		return $this;
	}
	
	
	/**
	 * Copie les donnees d'un structure standard vers le calaculateur.
	 *
	 * @param options &$liste_option Pointeur sur les arguments.
	 * @param relation_fichier_machine &$fichier_a_telecharger Structure du fichier a telecharger.
	 * @param string $type_copie get ou put.
	 * @param string $noeud Noeud en cours de traitement.
	 * @param string $service Service en cours de traitement.
	 * @param string $regexpr Regexpr pour filtrer le serveur source/dest.
	 * @param int $hour heure du fichier a copier (cookie_log).
	 * @param int $min Minute du fichier a copier (cookie_log).
	 * @param int $sec seconde du fichier a copier (cookie_log).
	 * @param int $nouvelle_date Dte au format standard.
	 * @param string $serial Serial en cours de traitement.
	 * @param int $genday Date de generation au format standard.
	 * @return true
	 */
	public function telecharge_fichier_standard($liste_option, &$fichier_a_telecharger, $type_copie = "get", $noeud = "", $service = "", $regexpr = "", $hour = "", $min = "", $sec = "", $nouvelle_date = "", $serial = false, $genday = false) {

		//On verifie que le fichier n'est pas deja telecharge
		$this->onDebug (  "Donnees local : ", 2 );
		$this->onDebug ( $fichier_a_telecharger->renvoi_structure_fichier (), 2 );
		$this->onDebug (  "Type_copie=" . $type_copie, 2 );
		$this->onDebug (  "Noeud=" . $noeud, 2 );
		$this->onDebug (  "Service=" . $service, 2 );
		$this->onDebug (  "Regexpr=" . $regexpr, 2 );
		$this->onDebug (  "Hour=" . $hour, 2 );
		$this->onDebug (  "Min=" . $min, 2 );
		$this->onDebug (  "Sec=" . $sec, 2 );
		$this->onDebug (  "Nouvelle date=" . $nouvelle_date, 2 );
		$this->onDebug (  "Genday=" . $genday, 2 );
		$this->onDebug (  "Serial=" . $serial, 2 );
		
		//Puis on transfere ce fichier
		$liste_option->setOption ( "hour", $hour );
		$liste_option->setOption ( "min", $min );
		$liste_option->setOption ( "sec", $sec );
		$liste_option->setOption ( "noeud", $noeud );
		if ($service != "") {
			$liste_option->setOption ( "service", $service );
		}
		if ($regexpr != "") {
			$liste_option->setOption ( "regexpr_donnees", $regexpr );
		}
		if ($nouvelle_date != "") {
			$liste_option->setOption ( "date", $nouvelle_date );
		}
		if ($genday !== false) {
			$liste_option->setOption ( "genday", $genday );
		}
		$this->getObjetCopieDonneesRef()->setListeOptions($liste_option);
		
		$fichier_a_telecharger->ajoute_donnees_structure_fichier ( "type_copie", $type_copie );
		$fichier_final = $this->getObjetCopieDonneesRef()->copie_donnees ( $serial, $fichier_a_telecharger );
		$fichier_a_telecharger->modifie_donnees_structure_fichier ( "telecharger", $fichier_final ["telecharger"] );
		
		$this->onDebug (  "Fin du telechargement du fichier :", 1 );
		$this->onDebug ( $fichier_a_telecharger->renvoi_structure_fichier (), 1 );
		
		return true;
	}
	
	/***************** ACCESSEURS *********************/
	/**
	 * @codeCoverageIgnore
	 * @return copie_donnees
	 */
	public function &getObjetCopieDonneesRef() {
		return $this->class_copie_donnees;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetCopieDonneesRef(&$class_copie_donnees) {
		$this->class_copie_donnees = $class_copie_donnees;
	
		return $this;
	}
	/***************** ACCESSEURS *********************/
}
?>