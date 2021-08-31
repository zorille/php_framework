<?php

/**
 * Gestion de veeamman.
 * @author dvargas
 */
namespace Zorille\veeamman;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class backupservers
 *
 * @package Lib
 * @subpackage veeamman
 */
class backupservers extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_backupservers = null;
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
	 * Instanbackupserverse un objet de type backupservers. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return backupservers
	 */
	static function &creer_veeamman_backupservers(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new backupservers ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return backupservers
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
		// Gestion de backupservers
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Permet de recuperer les donnees d'un backupservers dans Veeam
	 * @return backupservers
	 * @throws Exception
	 */
	public function recupere_donnees_backupservers() {
		$backupservers_data = $this->getObjetVeeamWsclientRest ()
			->BackupServerData ( $this->getId (), array (
				"format" => "Entity"
		) );
		$this->onDebug ( $backupservers_data, 2 );
		return $this->setDonnees ( $backupservers_data );
	}

	/**
	 * Recupere l'id du backupservers et l'ajoute à l'objet
	 * @return backupservers
	 * @throws Exception
	 */
	public function recupere_id_du_backupservers(
			$backupservers) {
		if (preg_match ( '/:BackupServer:(.*)/', $backupservers->attributes () ['UID'], $resultat ) === false) {
			return $this->onError ( "Numero de BackupServers introuvable", $resultat );
		}
		return $this->setId ( $resultat [1] );
	}

	/**
	 * Recupere le nom du backupservers
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_backupservers(
			$backupservers) {
		return $backupservers->attributes () ['Name'];
	}

	/**
	 * Permet de trouver la liste des backupservers dans veeamman et enregistre les donnees des backupservers dans l'objet
	 * @return backupservers
	 * @throws Exception
	 */
	public function retrouve_backupservers() {
		$this->onDebug ( __METHOD__, 1 );
		$backupservers = $this->getObjetVeeamWsclientRest ()
			->listBackupServers ();
		if (! isset ( $backupservers->Ref )) {
			// Le backupservers n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des backupservers." );
		}
		$this->onDebug ( $backupservers, 2 );
		return $this->setListeBackupServers ( $backupservers );
	}
	
	/**
	 * Permet de trouver la liste des JobId par backupservers dans veeamman et enregistre les donnees des backupserver dans l'objet
	 * @return \SimpleXMLElement
	 * @throws Exception
	 */
	public function retrouve_jobid_par_backupserver() {
				$this->onDebug ( __METHOD__, 1 );
				$listeJobs = $this->getObjetVeeamWsclientRest ()
				->BackupServerListJobs ( $this->getId() );
				if (! isset ( $listeJobs->Ref )) {
					// Le backupserver n'existe pas donc on emet une exception
					return $this->onError ( "Probleme avec la recuperation des jobs par backupserver." );
				}
				$this->onDebug ( $listeJobs, 2 );
				return $listeJobs;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeBackupServers() {
		return $this->liste_backupservers;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeBackupServers(
			$liste_backupservers) {
		$this->liste_backupservers = $liste_backupservers;
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
		$help [__CLASS__] ["text"] [] .= "backupservers :";
		return $help;
	}
}
?>
