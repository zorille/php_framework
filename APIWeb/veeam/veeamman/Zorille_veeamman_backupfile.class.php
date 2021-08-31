<?php

/**
 * Gestion de veeamman.
 * @author dvargas
 */
namespace Zorille\veeamman;

use Zorille\framework\abstract_log as abstract_log;
use Zorille\framework\options as options;
use Exception as Exception;

/**
 * class backupfile
 *
 * @package Lib
 * @subpackage veeamman
 */
class backupfile extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_backupfile = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_tasks = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instanbackupfilee un objet de type backupfile. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return backupfile
	 */
	static function &creer_veeamman_backupfile(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new backupfile ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return backupfile
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetVeeamWsclientRest ( $liste_class ["wsclient"] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de backupfile
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Permet de recuperer les donnees d'un backupfile dans Veeam
	 * @return backupfile
	 * @throws Exception
	 */
	public function recupere_donnees_backupfile() {
		$backupfile_data = $this->getObjetVeeamWsclientRest ()
			->BackupFileData ( $this->getId (), array (
				"format" => "Entity"
		) );
		$this->onDebug ( $backupfile_data, 2 );
		return $this->setDonnees ( $backupfile_data );
	}

	public function valide_donnees_backupfile() {
		if (is_object ( $this->getDonnees () )) {
			return true;
		}
		return false;
	}

	/**
	 * Extrait le nom de la VM dans le nom du backup
	 * @return string renvoie le nom de la machine ou le FilePath par defaut
	 * @throws Exception
	 */
	public function recupere_nom_machine_backupee() {
		if (! $this->valide_donnees_backupfile ()) {
			return $this->onError ( "Pas de donnees de fichier de backup" );
		}
		$filepath = trim ( $this->getDonnees ()->FilePath );
		$this->onDebug ( "FilePath : " . $filepath, 1 );
		if (preg_match ( '/(.*)\.vm-/', $filepath, $recherche ) != 0) {
			return $recherche [1];
		} else if (preg_match ( '/.* _ ([a-zA-Z0-9_-]+) .*/', $filepath, $recherche ) != 0) {
			return $recherche [1];
		}
		$this->onWarning ( "Nom introuvable dans : " . $filepath );
		return $filepath;
	}

	/**
	 * Extrait le nom de la VM dans le nom du backup
	 * @return string renvoie le nom de la machine ou le FilePath par defaut
	 * @throws Exception
	 */
	public function recupere_taille_fichier_backupee() {
		if (! $this->valide_donnees_backupfile ()) {
			return $this->onError ( "Pas de donnees de fichier de backup" );
		}
		return $this->getDonnees ()->BackupSize;
	}

	/**
	 * Extrait le nom de la VM dans le nom du backup
	 * @return string renvoie le nom de la machine ou le FilePath par defaut
	 * @throws Exception
	 */
	public function recupere_horaire_fichier_backupee() {
		if (! $this->valide_donnees_backupfile ()) {
			return $this->onError ( "Pas de donnees de fichier de backup" );
		}
		return $this->getDonnees ()->CreationTimeUtc;
	}

	/**
	 * Recupere l'id du backupfile et l'ajoute à l'objet
	 * @return backupfile
	 * @throws Exception
	 */
	public function recupere_id_du_backupfile(
			$backupfile) {
		if (preg_match ( '/:BackupFile:(.*)/', $backupfile->attributes () ['UID'], $resultat ) === false) {
			return $this->onError ( "Numero de BackupFile introuvable", $resultat );
		}
		return $this->setId ( $resultat [1] );
	}

	/**
	 * Recupere le nom du backupfile
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_backupfile(
			$backupfile) {
		return $backupfile->attributes () ['Name'];
	}

	/**
	 * Permet de trouver la liste des backupfile dans veeamman et enregistre les donnees des backupfile dans l'objet
	 * @return backupfile
	 * @throws Exception
	 */
	public function retrouve_backupfiles($params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$backupfile = $this->getObjetVeeamWsclientRest ()
			->listBackupFiles ();
		if (! isset ( $backupfile->Ref )) {
			// Le backupfile n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des backupfiles." );
		}
		$this->onDebug ( $backupfile, 2 );
		return $this->setListeBackupFile ( $backupfile );
	}

	/**
	 * Permet de trouver la liste des backupfile dans veeamman et enregistre les donnees des backupfile dans l'objet
	 * @return backupfile
	 * @throws Exception
	 */
	public function retrouve_backupfiles_par_backup(
			$backupid) {
		$this->onDebug ( __METHOD__, 1 );
		$backupfiles = $this->getObjetVeeamWsclientRest ()
			->BackupFilesbybackup ( $backupid );
		if (! isset ( $backupfiles->Ref )) {
			// Le backupfile n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des backupfile par backup.", $backupfiles );
		}
		$this->onDebug ( $backupfiles, 2 );
		return $this->setListeBackupFile ( $backupfiles );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeBackupFile() {
		return $this->liste_backupfile;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeBackupFile(
			$liste_backupfile) {
		$this->liste_backupfile = $liste_backupfile;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "backupfile :";
		return $help;
	}
}
?>
