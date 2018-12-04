<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class contraintesHoraire<br>
 * Gere les contraintes horaires de monitoring.
 *
 * @package Lib
 * @subpackage Monitoring
 */
class contraintesHoraire extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $timestamp_jour;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $timestamp_debut_alarme = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $timestamp_fin_alarme = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $timestamp_debut_max = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $timestamp_fin_max = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $date;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type contraintesHoraire.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return contraintesHoraire
	 */
	static function &creer_contraintesHoraire(&$liste_option, $date, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new contraintesHoraire ( $date, $entete, $sort_en_erreur );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return contraintesHoraire
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		$this->prepare_horaire ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Constructeur, verifie et prepare les variables de base.
	 * @codeCoverageIgnore
	 * @param string $entete
	 * @param string|bool $sort_en_erreur
	 * @return true
	 */
	public function __construct($date, $entete = __CLASS__, $sort_en_erreur = false) {
		
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->setTimestampJour ( time () )
			->setDate ( $date )
			->setThrowException ( true );
	}

	/**
	 * Prepare les timestamps des contraintes horaire
	 * @return contraintesHoraire|false
	 */
	public function prepare_horaire() {
		$date = $this->getDate ();
		if ($date == "") {
			return false;
		}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "heure_debut_max" ) !== false) {
			$this->setTimestampDebutMax ( strtotime ( $date . " " . $this->getListeOptions ()
				->getOption ( "heure_debut_max" ) ) );
		} else {
			$this->setTimestampDebutMax ( strtotime ( $date . " 00:00:00" ) );
		}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "heure_fin_max" ) !== false) {
			$this->setTimestampFinMax ( strtotime ( $date . " " . $this->getListeOptions ()
				->getOption ( "heure_fin_max" ) ) );
		} else {
			$this->setTimestampFinMax ( strtotime ( $date . " 23:59:59" ) );
		}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "heure_debut_alarme" ) !== false) {
			$this->setTimestampDebutAlarme ( strtotime ( $date . " " . $this->getListeOptions ()
				->getOption ( "heure_debut_alarme" ) ) );
		} else {
			$this->setTimestampDebutAlarme ( strtotime ( $date . " 00:00:00" ) );
		}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "heure_fin_alarme" ) !== false) {
			$this->setTimestampFinAlarme ( strtotime ( $date . " " . $this->getListeOptions ()
				->getOption ( "heure_fin_alarme" ) ) );
		} else {
			$this->setTimestampFinAlarme ( strtotime ( $date . " 23:59:59" ) );
		}
		
		return $this;
	}

	/**
	 * Indique si l'on a passe l'heure maximal de debut
	 *
	 * @return bool true=on a passe l'heure de debut max , false=on est avant l'heure de debut max
	 */
	public function valideHeureDebutGlobal() {
		if ($this->getTimestampJour () > $this->getTimestampDebutMax ()) {
			return true;
		} //sinon on attend le debut
		return false;
	}

	/**
	 * Indique si l'on a passe l'heure maximal de fin
	 *
	 * @return bool true=on a passe l'heure de fin max , false=on est avant l'heure de fin max
	 */
	public function valideHeureFinGlobal() {
		if ($this->getTimestampJour () > $this->getTimestampFinMax ()) {
			return true;
		} //sinon on attend le fin
		return false;
	}

	/**
	 * Indique si l'on a passe l'heure maximal de debut d'alarme
	 *
	 * @return bool true=on a passe l'heure de debut max , false=on est avant l'heure de debut max
	 */
	public function valideHeureDebutAlarmeGlobal() {
		if ($this->getTimestampJour () > $this->getTimestampDebutAlarme ()) {
			return true;
		} //sinon on attend le debut
		return false;
	}

	/**
	 * Indique si l'on a passe l'heure maximal de fin d'alarme
	 *
	 * @return bool true=on a passe l'heure de fin max , false=on est avant l'heure de fin max
	 */
	public function valideHeureFinAlarmeGlobal() {
		if ($this->getTimestampJour () > $this->getTimestampFinAlarme ()) {
			return true;
		} //sinon on attend le fin
		return false;
	}

	/**
	 * Indique si l'on a passe l'heure de debut et de debut d'alarme
	 * heure de debut max > heure courante et<br />
	 * heure de debut d'alarme > heure courante
	 * @return boolean true valide les conditions precedentes, false sinon 
	 */
	public function activeAlarme() {
		//Si le script doit avoir demarre et l'heure de debut d'alarme est passee
		if ($this->valideHeureDebutGlobal () && $this->valideHeureDebutAlarmeGlobal ()) {
			return true;
		}
		return false;
	}

	/************************* Accesseurs ************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getTimestampJour() {
		return $this->timestamp_jour;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimestampJour($timestamp_jour) {
		$this->timestamp_jour = $timestamp_jour;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTimestampDebutMax() {
		return $this->timestamp_debut_max;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimestampDebutMax($timestamp_debut_max) {
		$this->timestamp_debut_max = $timestamp_debut_max;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHoraireDebutMax() {
		return date ( "H:i d/m/Y", $this->timestamp_debut_max );
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTimestampFinMax() {
		return $this->timestamp_fin_max;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimestampFinMax($timestamp_fin_max) {
		$this->timestamp_fin_max = $timestamp_fin_max;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHoraireFinMax() {
		return date ( "H:i d/m/Y", $this->timestamp_fin_max );
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTimestampDebutAlarme() {
		return $this->timestamp_debut_alarme;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimestampDebutAlarme($timestamp_debut_alarme) {
		$this->timestamp_debut_alarme = $timestamp_debut_alarme;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTimestampFinAlarme() {
		return $this->timestamp_fin_alarme;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimestampFinAlarme($timestamp_fin_alarme) {
		$this->timestamp_fin_alarme = $timestamp_fin_alarme;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDate($date) {
		$this->date = $date;
		return $this;
	}

	/************************* Accesseurs ************************/
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gere les contrainte horaire";
		$help [__CLASS__] ["text"] [] .= "WARNING : il faut un date pour fonctionner";
		$help [__CLASS__] ["text"] [] .= "\t--heure_debut_max 00:00:00     heure max de debut du job";
		$help [__CLASS__] ["text"] [] .= "\t--heure_fin_max   23:59:59     heure max de fin du job";
		$help [__CLASS__] ["text"] [] .= "\t--heure_debut_alarme 01:00:00  heure minimale du debut de l'alarme";
		$help [__CLASS__] ["text"] [] .= "\t--heure_fin_alarme   23:59:59  heure max de l'alarme";
		
		return $help;
	}
}
?>
