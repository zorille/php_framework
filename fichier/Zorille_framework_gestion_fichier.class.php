<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class gestion_fichier<br>
 *
 * Gere un fichier.
 * @package Lib
 * @subpackage Fichier
 */
class gestion_fichier extends variables_standards {
	/**
	 * var privee
	 * @access private
	 * @var ssh_z|ftp
	 */
	private $connexion = false;
	/**
	 * var privee
	 * @access private
	 * @var options
	 */
	private $liste_option_temporaire = null;
	/**
	 * var privee
	 * @access private
	 * @var copie_donnees
	 */
	private $copie_donnees = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type gestion_fichier.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return gestion_fichier
	 */
	static function &creer_gestion_fichier(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new gestion_fichier ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return gestion_fichier
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 *
	 * @param bool $sort_en_erreur
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		
		return true;
	}

	/**
	 * Copie les donnees d'un structure standard vers le calaculateur.
	 *
	 * @param relation_fichier_machine &$fichier_a_telecharger Structure du fichier a telecharger.
	 * @param string $type_copie get ou put.
	 * @param int $date Date au format standard.
	 * @param string $uuid Code unique du fichier en cours de traitement.
	 * @param string $nomFichierSource Nom du fichier source pour la var {NOMFIC}.
	 * @param string $dossier_service Nom du dossier source pour la var {REPSERVICE}.
	 * @param int $hour heure du fichier a copier (cookie_log).
	 * @param int $min Minute du fichier a copier (cookie_log).
	 * @param int $sec seconde du fichier a copier (cookie_log).
	 * @param int $genday Date de generation au format standard.
	 * @return true
	 */
	public function transfert_standard(&$fichier_a_telecharger, $type_copie = "get", $date = false, $uuid = false, $nomFichierSource = false, $dossier_service = false, $hour = false, $min = false, $sec = false, $genday = false, $nomFichierForce = false, $nomMachineSource = false) {
		
		//On verifie que le fichier n'est pas deja telecharge
		$this->onDebug ( "Donnees local : ", 2 );
		$this->onDebug ( $fichier_a_telecharger->renvoi_structure_fichier (), 2 );
		$this->onDebug ( "Type_copie=" . $type_copie, 2 );
		$this->onDebug ( "date=" . $date, 2 );
		$this->onDebug ( "Genday=" . $genday, 2 );
		$this->onDebug ( "uuid=" . $uuid, 2 );
		$this->onDebug ( "DossierService=" . $dossier_service, 2 );
		$this->onDebug ( "Hour=" . $hour, 2 );
		$this->onDebug ( "Min=" . $min, 2 );
		$this->onDebug ( "Sec=" . $sec, 2 );
		$this->onDebug ( "NomFicSource=" . $nomFichierSource, 2 );
		$this->onDebug ( "NomMachineSource=" . $nomMachineSource, 2 );
		$this->onDebug ( "NomFicForce=" . $nomFichierForce, 2 );
		
		$this->setListeOptionTemporaire ();
		
		//Puis on transfere ce fichier
		if ($uuid === false) {
			$uuid = "";
		}
		if ($date !== false) {
			$this->getListeOptionTemporaire ()
				->setOption ( "date", $date );
			$this->getListeOptionTemporaire ()
				->supprime_element ( "date_debut" );
			$this->getListeOptionTemporaire ()
				->supprime_element ( "date_fin" );
		}
		if ($hour !== false) {
			$this->getListeOptionTemporaire ()
				->setOption ( "hour", $hour );
		}
		if ($min !== false) {
			$this->getListeOptionTemporaire ()
				->setOption ( "min", $min );
		}
		if ($sec !== false) {
			$this->getListeOptionTemporaire ()
				->setOption ( "sec", $sec );
		}
		if ($nomFichierSource !== false) {
			$this->getListeOptionTemporaire ()
				->setOption ( "nom_fichier", $nomFichierSource );
		}
		if ($nomMachineSource !== false) {
			$this->getListeOptionTemporaire ()
				->setOption ( "nom_machine", $nomMachineSource );
		}
		if ($nomFichierForce !== false) {
			$this->getListeOptionTemporaire ()
				->setOption ( "nom_fichier_force", $nomFichierForce );
		}
		if ($genday !== false) {
			$this->getListeOptionTemporaire ()
				->setOption ( "genday", $genday );
		}
		
		//On fini de preparer la definition du fichier
		$fichier_a_telecharger->modifie_donnees_structure_fichier ( "type_copie", $type_copie );
		
		//On fait la copie
		$fichier_a_copier = $this->getCopieDonnees ();
		$fichier_final = $fichier_a_copier->copie_donnees ( $uuid, $fichier_a_telecharger );
		$fichier_a_telecharger->modifie_donnees_structure_fichier ( "telecharger", $fichier_final ["telecharger"] );
		
		$this->onDebug ( "Fin du telechargement du fichier.", 1 );
		$this->onDebug ( $fichier_a_telecharger->renvoi_structure_fichier (), 1 );
		
		return true;
	}

	/***************** ACCESSEURS *********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnexion($connexion) {
		$this->connexion = $connexion;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getConnexion() {
		return $this->connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getListeOptionTemporaire() {
		return $this->liste_option_temporaire;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeOptionTemporaire() {
		$this->liste_option_temporaire = clone $this->getListeOptions ();
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getCopieDonnees() {
		if ($this->copie_donnees instanceof copie_donnees) {
			return $this->copie_donnees;
		}
		$this->copie_donnees = copie_donnees::creer_copie_donnees ( $this->getListeOptionTemporaire (), $this->getConnexion () );
		return $this->copie_donnees;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCopieDonnees(&$copie_donnees) {
		$this->copie_donnees = $copie_donnees;
		
		return $this;
	}

	/***************** ACCESSEURS *********************/
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des transferts de fichier";
		
		return $help;
	}
}
?>