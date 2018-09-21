<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class variables_standards<br>
 *
 * Creer un fichier au format standard.
 * @package Lib
 * @subpackage standard
 */
class variables_standards extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_remplacement = array ();
	/**
	 * var privee
	 * @access private
	 * @var dates
	 */
	private $dates = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type variables_standards.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return variables_standards
	 */
	static function &creer_variables_standards(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new variables_standards ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return variables_standards
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );

		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		return true;
	}

	/**
	 * Creer un tableau de remplacement.<br>
	 * liste des remplacement (chaque parametre est facultatif) : <br>
	 * {CODE_UNIQUE}=>CODE_UNIQUE<br>
	 * {REP_CODE_UNIQUE}=>4digitCODE_UNIQUE/CODE_UNIQUE<br>
	 * {DATE}=>date<br>
	 * {WDATE}=>lundi precedent<br>
	 * {MDATE}=>debut mois<br>
	 * {YEAR}=>annee (from date)<br>
	 * {MONTH}=>mois (from date)<br>
	 * {DAY}=>jour (from date)<br>
	 * {GENDATE}=>date+1<br>
	 * {HOUR}=>--hour<br>
	 * {MIN}=>--min<br>
	 * {SEC}=>--sec<br>
	 *
	 * @param string $code_unique Serial de remplacement.
	 * @param int $date Date de remplacement.
	 * @return variables_standards
	 * @throws Exception
	 */
	public function &prepare_remplacement($code_unique = false, $date = false) {
		$this->setListeRemplacement ( array () );
		
		//{CODE_UNIQUE} et/ou {REP_CODE_UNIQUE}
		if ($code_unique !== false) {
			$this->_remplirCodeUnique ( $code_unique );
		}
		
		//{DATE} et 
		if ($date !== false) {
			if ($this->getListeOptions ()
				->verifie_option_existe ( "genday", true ) !== false) {
				$genday = $this->getListeOptions ()
					->getOption ( "genday" );
			} else {
				$genday = false;
			}
			$this->_remplirDate ( $date, $genday );
		}
		
		//{HOUR}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "hour", true ) !== false)
			$this->_remplirHeure ( $this->getListeOptions ()
				->getOption ( "hour" ) );
			//{MIN}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "min", true ) !== false)
			$this->_remplirMinute ( $this->getListeOptions ()
				->getOption ( "min" ) );
			//{SEC}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "sec", true ) !== false)
			$this->_remplirSeconde ( $this->getListeOptions ()
				->getOption ( "sec" ) );
			//{NOMFIC}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "nom_fichier", true ) !== false) {
			$this->_remplirNomFichier ( $this->getListeOptions ()
				->getOption ( "nom_fichier" ) );
		}
		//{NOMMACHINE}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "nom_machine", true ) !== false) {
			$this->_remplirNomMachine ( $this->getListeOptions ()
				->getOption ( "nom_machine" ) );
		}
		
		$this->onDebug ( "Liste des remplacements : ", 2 );
		$this->onDebug ( $this->getListeRemplacement (), 2 );
		return $this;
	}

	/**
	 * Modifie la valeur de remplacement dans le tableau.
	 *
	 * @param string $code_unique CODE_UNIQUE de remplacement.
	 * @return variables_standards
	 */
	private function &_remplirCodeUnique($code_unique) {
		//{SERIAL}
		$this->ajouteListeRemplacement ( "{CODE_UNIQUE}", $code_unique );
		//{REP_SERIAL}
		$this->ajouteListeRemplacement ( "{REP_CODE_UNIQUE}", substr ( $code_unique, 0, 4 ) . "/" . $code_unique );
		
		return $this;
	}

	/**
	 * Modifie la valeur de remplacement dans le tableau.
	 *
	 * @param int $date Date au format standard.
	 * @return variables_standards
	 * @throws Exception
	 */
	private function &_remplirDate($date, $GENDAY = false) {
		$dates = new dates ( $date );
		//{DATE}
		$this->ajouteListeRemplacement("{DATE}", $dates->recupere_premier_jour());
		//{YEAR}
		$this->ajouteListeRemplacement("{YEAR}", substr ( $dates->recupere_premier_jour(), 0, 4 ));
		//{MONTH}
		$this->ajouteListeRemplacement ("{MONTH}", substr ( $dates->recupere_premier_jour(), 4, 2 ));
		//{DAY}
		$this->ajouteListeRemplacement ("{DAY}", substr ( $dates->recupere_premier_jour(), 6, 2 ));
		//{GENDATE}
		//on calcule le GENDAY a partir du DATADAY
		if ($GENDAY === false) {
			$GENDAY = $dates->retrouve_jour ( $dates->recupere_premier_jour(), 1 );
		}
		$this->ajouteListeRemplacement ("{GENDATE}", $GENDAY);
		//{WDATE}
		$WDATE = $dates->retrouve_lundi_precedent ( $dates->recupere_premier_jour() );
		$this->ajouteListeRemplacement ("{WDATE}", $WDATE);
		//{MDATE}
		$MDATE = $dates->retrouve_month ( $dates->recupere_premier_jour(), 0 );
		$this->ajouteListeRemplacement ("{MDATE}", $MDATE);
		
		return $this;
	}

	/**
	 * Modifie la valeur de remplacement dans le tableau.
	 *
	 * @param int $heure Heure sur 2 chiffres.
	 * @return variables_standards
	 */
	private function &_remplirHeure($heure) {
		$this->ajouteListeRemplacement ( "{HOUR}", $heure );
		return $this;
	}

	/**
	 * Modifie la valeur de remplacement dans le tableau.
	 *
	 * @param int $minute Minute sur 2 chiffres.
	 * @return variables_standards
	 */
	private function &_remplirMinute($minute) {
		$this->ajouteListeRemplacement ( "{MIN}", $minute );
		return $this;
	}

	/**
	 * Modifie la valeur de remplacement dans le tableau.
	 *
	 * @param int $seconde Seconde sur 2 chiffres.
	 * @return variables_standards
	 */
	private function &_remplirSeconde($seconde) {
		$this->ajouteListeRemplacement ( "{SEC}", $seconde );
		return $this;
	}

	/**
	 * Modifie la valeur de remplacement dans le tableau.
	 *
	 * @param int $nom_fichier Nom du fichier en cours.
	 * @return variables_standards
	 */
	private function &_remplirNomFichier($nom_fichier) {
		$this->ajouteListeRemplacement ( "{NOMFIC}", $nom_fichier );
		return $this;
	}

	/**
	 * Modifie la valeur de remplacement dans le tableau.
	 *
	 * @param int $nom_fichier Nom du fichier en cours.
	 * @return variables_standards
	 */
	private function &_remplirNomMachine($nom_machine) {
		$this->ajouteListeRemplacement ( "{NOMMACHINE}", $nom_machine );
		return $this;
	}

	/**
	 * Remplace les variables predefinie dans la chaine $valeur_a_traiter.
	 *
	 * @param string $valeur_a_traiter Ligne contenant des remplacement standard.
	 * @return string
	 */
	public function remplacement_standard($valeur_a_traiter) {
		$liste_remplacement = $this->getListeRemplacement ();
		if (is_array ( $liste_remplacement )) {
			foreach ( $liste_remplacement as $remplace => $donnees ) {
				$valeur_a_traiter = str_replace ( $remplace, $donnees, $valeur_a_traiter );
			}
		}
		return $valeur_a_traiter;
	}

	/**
	 * Met a jour un champ de remplacement standard.<br>
	 * Les types sont :<br>
	 * code_unique<br>
	 * date<br>
	 * heure<br>
	 * minute<br>
	 * seconde<br>
	 *
	 * @param string $type Type de remplacement.
	 * @param string $valeur Valeur de remplacement.
	 * @return variables_standards
	 * @throws Exception
	 */
	public function &modifie_remplacement_standard($type, $valeur) {
		if (is_array ( $this->getListeRemplacement () )) {
			switch ($type) {
				case "code_unique" :
					$this->_remplirCodeUnique ( $valeur );
					break;
				case "date" :
					$this->_remplirDate ( $valeur );
					break;
				case "heure" :
					$this->_remplirHeure ( $valeur );
					break;
				case "minute" :
					$this->_remplirMinute ( $valeur );
					break;
				case "seconde" :
					$this->_remplirSeconde ( $valeur );
					break;
				case "nomfic" :
					$this->_remplirNomFichier ( $valeur );
					break;
				case "nommachine" :
					$this->_remplirNomMachine ( $valeur );
					break;
			}
		}
		
		return $this;
	}

	/**
	 * ************ Accesseurs ***************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeRemplacement() {
		return $this->liste_remplacement;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeRemplacement($liste_remplacement) {
		$this->liste_remplacement = $liste_remplacement;
		return $this;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 */
	public function &ajouteListeRemplacement($type, $valeur) {
		$this->liste_remplacement [$type] = $valeur;
		return $this;
	}

	/************* Accesseurs ****************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "en ligne de commande :";
		$help [__CLASS__] ["text"] [] .= "\t--hour=xx";
		$help [__CLASS__] ["text"] [] .= "\t--min=xx";
		$help [__CLASS__] ["text"] [] .= "\t--sec=xx";
		$help [__CLASS__] ["text"] [] .= "\t--nom_fichier=xxx";
		$help [__CLASS__] ["text"] [] .= "\t--nom_machine=xxx";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "Le code_unique et la date sont fournit a la creation de l'objet";
		
		return $help;
	}
}

?>
