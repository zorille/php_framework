<?php

/**
 * Gestion de veeamman.
 * @author dvargas
 */
namespace Zorille\veeamman;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class backups
 *
 * @package Lib
 * @subpackage veeamman
 */
class backups extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_backups = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_tasks = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var backupfile
	 */
	private $backupFile = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instanbackupse un objet de type backups. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return backups
	 */
	static function &creer_veeamman_backups(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new backups ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return backups
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetVeeamWsclientRest ( $liste_class ["wsclient"] )
			->setObjetVeeamBackupFile ( backupfile::creer_veeamman_backupfile ( $liste_class ["options"], $liste_class ["wsclient"] ) );
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
		// Gestion de backups
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Permet de recuperer les donnees d'un backups dans Veeam
	 * @return backups
	 * @throws Exception
	 */
	public function recupere_donnees_backups() {
		$backups_data = $this->getObjetVeeamWsclientRest ()
			->BackupData ( $this->getId (), array (
				"format" => "Entity"
		) );
		$this->onDebug ( $backups_data, 2 );
		return $this->setDonnees ( $backups_data );
	}

	/**
	 * Recupere l'id du backups et l'ajoute à l'objet
	 * @return backups
	 * @throws Exception
	 */
	public function recupere_id_du_backups(
			$backups) {
		if (preg_match ( '/:Backup:(.*)/', $backups->attributes () ['UID'], $resultat ) === false) {
			return $this->onError ( "Numero de backup introuvable", $resultat );
		}
		return $this->setId ( $resultat [1] );
	}

	/**
	 * Recupere le nom du backups
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_backups(
			$backups) {
		return ( string ) $backups->attributes () ['Name'];
	}

	/**
	 * Permet de trouver la liste des backups dans veeamman et enregistre les donnees des backups dans l'objet
	 * @return backups
	 * @throws Exception
	 */
	public function retrouve_backups() {
		$this->onDebug ( __METHOD__, 1 );
		$backups = $this->getObjetVeeamWsclientRest ()
			->listbackups ();
		if (! isset ( $backups->Ref )) {
			// Le backups n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des backups." );
		}
		$this->onDebug ( $backups, 2 );
		return $this->setListebackups ( $backups );
	}

	/**
	 * Permet de trouver la liste des backupfile dans veeamman et enregistre les donnees des backupfile dans l'objet
	 * @return backupfile
	 * @throws Exception
	 */
	public function retrouve_backupfiles_par_backup() {
		return $this->getObjetVeeamBackupFile ()
			->retrouve_backupfiles_par_backup ( $this->getId () );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListebackups() {
		return $this->liste_backups;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListebackups(
			$liste_backups) {
		$this->liste_backups = $liste_backups;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return backupfile
	 */
	public function &getObjetVeeamBackupFile() {
		return $this->backupFile;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetVeeamBackupFile(
			&$backupFile) {
		$this->backupFile = $backupFile;
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
		$help [__CLASS__] ["text"] [] .= "backups :";
		return $help;
	}
}
?>
