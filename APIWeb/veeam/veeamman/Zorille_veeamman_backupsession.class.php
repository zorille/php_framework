<?php

/**
 * Gestion de veeamman.
 * @author dvargas
 */
namespace Zorille\veeamman;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class backupsession
 *
 * @package Lib
 * @subpackage veeamman
 */
class backupsession extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_backupsession = null;
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
	 * Instanbackupsessione un objet de type backupsession. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return backupsession
	 */
	static function &creer_veeamman_backupsession(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
				Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
				$objet = new backupsession ( $sort_en_erreur, $entete );
				$objet->_initialise ( array (
						"options" => $liste_option,
						"wsclient" => $webservice_rest
				) );
				return $objet;
	}
	
	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return backupsession
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
				// Gestion de backupsession
				parent::__construct ( $sort_en_erreur, $entete );
	}
	
	/**
	 * Permet de recuperer les donnees d'un backupsession dans Veeam
	 * @return backupsession
	 * @throws Exception
	 */
	public function recupere_donnees_backupsession() {
		$backupsession_data = $this->getObjetVeeamWsclientRest ()
		->BackupSessions ( $this->getId (), array (
				"format" => "Entity"
		) );
		$this->onDebug ( $backupsession_data, 2 );
		return $this->setDonnees ( $backupsession_data );
	}
	
	/**
	 * Permet de recuperer la liste des objets d'un backupsession dans Veeam
	 * @return backupsession
	 * @throws Exception
	 */
	public function recupere_liste_tasks_du_backupsession() {
		$backupsession_Tasks = $this->getObjetVeeamWsclientRest ()
		->BackupTaskSessionReferenceList ( $this->getId () );
		if (isset ( $backupsession_Tasks->Ref )) {
			return $this->setListeTasks ( $backupsession_Tasks );
		}
		return $this->onError ( "Pas de reference dans le backupsession" );
	}
	
	/**
	 * Recupere l'id du backupsession et l'ajoute à l'objet
	 * @return backupsession
	 * @throws Exception
	 */
	public function recupere_id_du_backupsession(
			$backupsession) {
				if (preg_match ( '/:BackupJobSession:(.*)/', $backupsession->attributes () ['UID'], $resultat ) === false) {
					return $this->onError ( "Numero de BackupSession introuvable", $resultat );
				}
				return $this->setId ( $resultat [1] );
	}
	
	/**
	 * Recupere le nom du backupsession
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_backupsession(
			$backupsession) {
				return $backupsession->attributes () ['Name'];
	}
	
	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_jobname() {
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return (string) $this->getDonnees ()->JobName;
	}
	
	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_status() {
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return (string) $this->getDonnees ()->Result;
	}
	
	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie true ou false
	 * @throws Exception
	 */
	public function recupere_retry() {
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return (string) $this->getDonnees ()->IsRetry;
	}
	
	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_date_creation() {
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return (string) $this->getDonnees ()->CreationTimeUTC;
	}
	
	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_date_fin() {
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return (string) $this->getDonnees ()->EndTimeUTC;
	}
	
	/**
	 * Permet de trouver la liste des backupsession dans veeamman et enregistre les donnees des backupsession dans l'objet
	 * @return backupsession
	 * @throws Exception
	 */
	public function retrouve_backupsession() {
		$this->onDebug ( __METHOD__, 1 );
		$backupsession = $this->getObjetVeeamWsclientRest ()
		->listBackupSessions ();
		if (! isset ( $backupsession->Ref )) {
			// Le backupsession n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des backupsession." );
		}
		$this->onDebug ( $backupsession, 2 );
		return $this->setListeBackupSession ( $backupsession );
	}
	
	/**
	 * Permet de trouver la liste des backupsession par JobId dans veeamman et enregistre les donnees des backupsession dans l'objet
	 * @return backupsession
	 * @throws Exception
	 */
	public function retrouve_backupsession_par_jobid(
			$jobid) {
				$this->onDebug ( __METHOD__, 1 );
				$backupsession = $this->getObjetVeeamWsclientRest ()
				->listBackupSessionsParJob ( $jobid );
				if (! isset ( $backupsession->Ref )) {
					// Le backupsession n'existe pas donc on emet une exception
					return $this->onError ( "Probleme avec la recuperation des backupsession." );
				}
				$this->onDebug ( $backupsession, 2 );
				return $this->setListeBackupSession ( $backupsession );
	}
	
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeBackupSession() {
		return $this->liste_backupsession;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeBackupSession(
			$liste_backupsession) {
				$this->liste_backupsession = $liste_backupsession;
				return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeTasks() {
		return $this->liste_tasks;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeTasks(
			$liste_tasks) {
				$this->liste_tasks = $liste_tasks;
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
		$help [__CLASS__] ["text"] [] .= "backupsession :";
		return $help;
	}
}
?>