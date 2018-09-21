<?php
/**
 * Gestion de fiche_categorie.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class fiche_categorie
 * 
 * @package Lib
 * @subpackage HP
 */
class fiche_categorie extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var hpom
	 */
	private $hpom = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var requete_complexe_tools
	 */
	private $db_tools = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $dossier_FCAT = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $FCAT_generic = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $fcat = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $FA = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $impact = 4;
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $priorite = 2;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $groupe = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fiche_categorie.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fiche_categorie
	 */
	static function &creer_fiche_categorie(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fiche_categorie ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fiche_categorie
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_fiche_categorie_param ();
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 *
	 * @return boolean True est OK, False sinon.
	 */
	public function retrouve_fiche_categorie_param() {
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"fiche_categorie",
				"dossier" 
		) ) === false) {
			return $this->onError ( "Il manque le parametre dossier dans fiche_categorie.", "", 2200 );
		}
		$this->setDossierFCAT ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"fiche_categorie",
				"dossier" 
		) ) );
		
		$this->remplir_variable ( array (
				"fiche_categorie",
				"generique" 
		), "NO_GENERIQUE", "setFCATGeneric" )
			->remplir_variable ( array (
				"fiche_categorie",
				"fiche_alarme" 
		), "NO_FA", "setFA" )
			->remplir_variable ( array (
				"fiche_categorie",
				"impact" 
		), 4, "setImpact" )
			->remplir_variable ( array (
				"fiche_categorie",
				"groupe" 
		), "NO_GRP", "setGroupe" );
		
		return $this;
	}

	/**
	 * Rempli le $fonction avec la valeur trouve ou la valeur par defaut
	 * @param string|array $recherche
	 * @param string $valeurDefaut
	 * @param string $fonction
	 * @return fiche_categorie
	 */
	public function remplir_variable($recherche, $valeurDefaut, $fonction) {
		if ($this->getListeOptions ()
			->verifie_variable_standard ( $recherche ) === false) {
			$this->$fonction ( $valeurDefaut );
		} else {
			$this->$fonction ( $this->getListeOptions ()
				->renvoi_variables_standard ( $recherche ) );
		}
		
		return $this;
	}

	/**
	 * Lit la fiche categorie et la charge en memoire
	 * @param string $fiche_cat
	 * @param boolean $generic
	 * @return fiche_categorie
	 */
	public function lit_fiche_cat($fiche_cat, $generic = false) {
		$donnees = fichier::Lit_integralite_fichier_en_tableau ( $fiche_cat );
		$donnees_FCAT = $this->getFCAT ();
		foreach ( $donnees as $ligne ) {
			$ligne = trim ( $ligne );
			$this->onDebug ( $ligne, 1 );
			if ($ligne == "" || strpos ( $ligne, "#" ) === 0) {
				continue;
			}
			if (strpos ( $ligne, "GENERIQUE:" ) === 0) {
				$this->lit_fiche_cat ( $this->getDossierFCAT () . "/" . str_replace ( "GENERIQUE:", "", $ligne ), true );
				$donnees_FCAT = $this->getFCAT ();
				continue;
			}
			//On traite la ligne de la fiche categorie
			$liste_donnees = explode ( ";", $ligne );
			if (count ( $liste_donnees ) == 5) {
				//Si la ressource n'existe pas, on la cree
				if ($liste_donnees [2] == "") {
					$liste_donnees [2] = "*";
				}
				if ($liste_donnees [3] == "") {
					$liste_donnees [3] = "*";
				}
				if (! isset ( $donnees_FCAT [$liste_donnees [2]] )) {
					$donnees_FCAT [$liste_donnees [2]] = array ();
				}
				//si l'instance existe et qu'on lit une fiche generique, on conserve la valeur de la fiche NODE.txt
				if (isset ( $donnees_FCAT [$liste_donnees [2]] [$liste_donnees [3]] ) && $generic) {
					continue;
				}
				$donnees_FCAT [$liste_donnees [2]] [$liste_donnees [3]] ["FA"] = $liste_donnees [0];
				$donnees_FCAT [$liste_donnees [2]] [$liste_donnees [3]] ["impact"] = $liste_donnees [1];
				$donnees_FCAT [$liste_donnees [2]] [$liste_donnees [3]] ["groupe"] = $liste_donnees [4];
			}
			
			$this->setFCAT ( $donnees_FCAT );
		}
		$this->onDebug ( "Donnees fiche categorie :", 2 );
		$this->onDebug ( $donnees_FCAT, 2 );
		return $this;
	}

	/**
	 * Retrouve une fiche categorie et la charge en memoire
	 * @return fiche_categorie|boolean
	 */
	public function retrouve_fiche_cat() {
		$this->onDebug ( "retrouve_fiche_cat", 1 );
		if (fichier::tester_fichier_existe ( $this->getDossierFCAT () . "/" . $this->getHpomObject ()
			->getCustomer () . "/" . $this->getHpomObject ()
			->getNode () . ".txt" )) {
			$fiche_cat = $this->getDossierFCAT () . "/" . $this->getHpomObject ()
				->getCustomer () . "/" . $this->getHpomObject ()
				->getNode () . ".txt";
			$this->onDebug ( $fiche_cat, 1 );
			return $this->lit_fiche_cat ( $fiche_cat );
		} elseif (fichier::tester_fichier_existe ( $this->getDossierFCAT () . "/" . $this->getHpomObject ()
			->getCustomer () . "/" . $this->getFCATGeneric () )) {
			$fiche_cat = $this->getDossierFCAT () . "/" . $this->getHpomObject ()
				->getCustomer () . "/" . $this->getFCATGeneric ();
			$this->onDebug ( $fiche_cat, 1 );
			return $this->lit_fiche_cat ( $fiche_cat );
		}
		$this->onDebug ( "Pas de fiche categorie", 1 );
		return false;
	}

	/**
	 * Gere les donnees arrivant de Xymon
	 * @return boolean True Donnees Xymon trouvees, False Pas de Xymon dans les CMAs
	 */
	public function valide_xymon() {
		$this->onDebug ( "valide_xymon", 1 );
		//Special Xymon
		$CMAs = $this->getHpomObject ()
			->getCMAs ();
		$this->onDebug ( $CMAs, 2 );
		
		if (isset ( $CMAs ["xymon"] )) {
			$liste_xymon = explode ( "|", $CMAs ["xymon"] );
			if ($liste_xymon !== false && count ( $liste_xymon ) == 3) {
				$this->setFA ( $liste_xymon [0] )
					->setImpact ( $liste_xymon [1] )
					->setGroupe ( $liste_xymon [2] );
				$this->onDebug ( "Xymon FA : " . $this->getFA () . " Impact : " . $this->getImpact () . " Groupe : " . $this->getGroupe (), 2 );
				return true;
			}
			// @codeCoverageIgnoreStart
		}
		// @codeCoverageIgnoreEnd
		

		return false;
	}

	/**
	 * Retrouve les informations de la fiche categorie pour une ressource
	 * @return boolean True si la ressource existe, False si la ressource n'existe pas et pas de valeur par defaut
	 */
	public function valide_donnees_ressource() {
		$donnees_FCAT = $this->getFCAT ();
		//Si on ne trouve pas la ressource dans la liste
		

		if (! isset ( $donnees_FCAT [$this->getHpomObject ()
			->getObjet ()] )) {
			//Si il y a une valeur generique (*)
			if (isset ( $donnees_FCAT ["*"] ["*"] )) {
				$this->onDebug ( "On utilise la ressource par defaut", 2 );
				$this->setFA ( $donnees_FCAT ["*"] ["*"] ["FA"] )
					->setImpact ( $donnees_FCAT ["*"] ["*"] ["impact"] )
					->setGroupe ( $donnees_FCAT ["*"] ["*"] ["groupe"] );
				return true;
			}
			
			//pas de ressource dans la fiche CAT
			return $this->onError ( "pas de ressource dans la fiche CAT", "", 2003 );
		}
		
		//ressource dans la fiche CAT
		$this->onDebug ( "On utilise la ressource : " . $this->getHpomObject ()
			->getObjet (), 2 );
		return true;
	}

	/**
	 * Retrouve les informations de la fiche categorie pour une instance
	 * @return fiche_categorie|boolean
	 */
	public function valide_instance() {
		//On part du principe que la ressource (Objet) est valide
		$intermediaire_FCAT = $this->getFCAT ();
		$donnees_FCAT = $intermediaire_FCAT [$this->getHpomObject ()
			->getObjet ()];
		//Si on ne trouve pas l'instance dans la liste
		if (! isset ( $donnees_FCAT [$this->getHpomObject ()
			->getInstance ()] )) {
			//Si il y a une valeur generique (*), on l'utilise
			if (isset ( $donnees_FCAT ["*"] )) {
				$this->onDebug ( "On utilise l'instance par defaut", 2 );
				$this->setFA ( $donnees_FCAT ["*"] ["FA"] )
					->setImpact ( $donnees_FCAT ["*"] ["impact"] )
					->setGroupe ( $donnees_FCAT ["*"] ["groupe"] );
				return true;
			}
			
			return false;
		}
		//l'instance existe, donc on prend les donnees de cette instance
		$this->onDebug ( "On utilise l'instance : " . $this->getHpomObject ()
			->getInstance (), 2 );
		$this->setFA ( $donnees_FCAT [$this->getHpomObject ()
			->getInstance ()] ["FA"] )
			->setImpact ( $donnees_FCAT [$this->getHpomObject ()
			->getInstance ()] ["impact"] )
			->setGroupe ( $donnees_FCAT [$this->getHpomObject ()
			->getInstance ()] ["groupe"] );
		
		return true;
	}

	/**
	 * Charge la fiche cat et trouve la ligne correspondante
	 * @return fiche_categorie
	 */
	public function gestion_fiche_categorie() {
		if ($this->valide_xymon ()) {
			return $this;
		}
		if ($this->retrouve_fiche_cat ()) {
			//Si on a une ressource autre que *
			$this->valide_donnees_ressource ();
			$this->valide_instance ();
		}
		
		$this->onDebug ( "FA : " . $this->getFA () . " Impact : " . $this->getImpact () . " Groupe : " . $this->getGroupe (), 2 );
		return $this;
	}

	/**
	 * Retrouve la priorite en fonction du niveau de service dans HobInv
	 * @param string $HOB_serv Niveau de service dans HobInv
	 * @return number
	 */
	public function convert_hobinv_service_vers_priorite($HOB_serv) {
		switch ($HOB_serv) {
			Case "GOLD" :
			Case "1" :
				return 1;
			Case "SILVER" :
			Case "2" :
				return 2;
			Case "BRONZE" :
			Case "3" :
				return 3;
		}
		return 4;
	}

	/**
	 * Retrouve la priorite en fonction du niveau de service dans HobInv
	 * @return fiche_categorie|false
	 */
	public function retrouve_priorite() {
		switch ($this->getHpomObject ()
			->getCustomer ()) {
			case "CUSTOMER" :
				$this->setPriorite ( 2 );
				break;
			default :
				$this->setPriorite ( $this->getImpact () );
		}
		
		return $this->onDebug ( "Priorite : " . $this->getPriorite (), 2 );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * @codeCoverageIgnore
	 * @return hpom
	 */
	public function &getHpomObject() {
		return $this->hpom;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHpomObject(&$hpom) {
		$this->hpom = $hpom;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getDbTools() {
		return $this->db_tools;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDbTools(&$db_tools) {
		$this->db_tools = $db_tools;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getDossierFCAT() {
		return $this->dossier_FCAT;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDossierFCAT($dossier_FCAT) {
		$this->dossier_FCAT = $dossier_FCAT;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getFCATGeneric() {
		return $this->FCAT_generic;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFCATGeneric($FCAT_generic) {
		$this->FCAT_generic = $FCAT_generic;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFA() {
		return $this->FA;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFA($FA) {
		$this->FA = $FA;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getImpact() {
		return $this->impact;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setImpact($impact) {
		$this->impact = $impact;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPriorite() {
		return $this->priorite;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPriorite($priorite) {
		$this->priorite = $priorite;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGroupe() {
		return $this->groupe;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGroupe($groupe) {
		$this->groupe = $groupe;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFCAT() {
		return $this->fcat;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFCAT($fcat) {
		$this->fcat = $fcat;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function AjouteEntreeFCAT($champ, $valeur) {
		$this->fcat [$champ] = $valeur;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>
