<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use Exception;
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
	 * @param bool|ftp|ssh_z $connexion connexion ftp/ssh existante.
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return copie_donnees|copie_donnees_fonctions_standards
	 * @throws Exception
	 */
	static function &creer_copie_donnees_fonctions_standards(options &$liste_option, \Zorille\framework\ftp|\Zorille\framework\ssh_z|bool $connexion = false, bool|string $sort_en_erreur = false, string $entete = __CLASS__): copie_donnees|copie_donnees_fonctions_standards
	{
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
	 * @return copie_donnees_fonctions_standards
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise ( $liste_class );
	
		$fichier_a_copier = copie_donnees::creer_copie_donnees ( $liste_class ["options"], $liste_class ["connexion"] );
		$this->setObjetCopieDonneesRef ( $fichier_a_copier );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 */
	public function __construct(bool|string $sort_en_erreur = false, $entete = __CLASS__) {
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
	 * @param int|string $hour heure du fichier a copier (cookie_log).
	 * @param int|string $min Minute du fichier a copier (cookie_log).
	 * @param int|string $sec seconde du fichier a copier (cookie_log).
	 * @param int|string $nouvelle_date Dte au format standard.
	 * @param bool|string $serial Serial en cours de traitement.
	 * @param bool|int $genday Date de generation au format standard.
	 * @return true
	 * @throws Exception
	 */
	public function telecharge_fichier_standard(options $liste_option, relation_fichier_machine &$fichier_a_telecharger, string $type_copie = "get", string $noeud = "", string $service = "", string $regexpr = "", int|string $hour = "", int|string $min = "", int|string $sec = "", int|string $nouvelle_date = "", bool|string $serial = false, bool|int $genday = false): bool
	{

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
	public function &getObjetCopieDonneesRef(): copie_donnees
	{
		return $this->class_copie_donnees;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetCopieDonneesRef(&$class_copie_donnees): static
	{
		$this->class_copie_donnees = $class_copie_donnees;
	
		return $this;
	}
	/***************** ACCESSEURS *********************/
}
