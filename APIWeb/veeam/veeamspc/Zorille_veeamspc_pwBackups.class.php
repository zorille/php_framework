<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class pwBackups
 *
 * @package Lib
 * @subpackage Veeam
 */
class pwBackups extends virtualMachines {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $pwBackup_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_pwBackups = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_includes = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * InstanpwBackupse un objet de type pwBackups. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return pwBackups
	 */
	static function &creer_veeamspc_pwBackups(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new pwBackups ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return pwBackups
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
		// Gestion de pwBackups
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du pwBackup, l'ajoute à l'objet et renvoi l'Id
	 * @return string
	 * @throws Exception
	 */
	public function recupere_id_du_pwBackup(
			$pwBackup) {
		$this->setPwBackupId ( $this->recupere_instanceUid ( $pwBackup ) );
		return $this->getPwBackupId ();
	}

	/**
	 * Recupere le nom du pwBackup
	 * @return string
	 * @throws Exception
	 */
	public function recupere_backupType_du_pwBackup(
			$pwBackup) {
		return ( string ) $pwBackup->backupType;
	}

	/**
	 * Recupere le nom du pwBackup
	 * @return string
	 * @throws Exception
	 */
	public function recupere_virtualMachineUid_du_pwBackup(
			$pwBackup) {
		return ( string ) $pwBackup->virtualMachineUid;
	}

	/**
	 * Recupere le nom du pwBackup
	 * @return string
	 * @throws Exception
	 */
	public function recupere_latestRestorePointDate_du_pwBackup(
			$pwBackup) {
		return ( string ) $pwBackup->latestRestorePointDate;
	}

	/**
	 * Recupere le nom du pwBackup
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nb_restorePoints_du_pwBackup(
			$pwBackup) {
		return ( int ) $pwBackup->restorePoints;
	}

	/**
	 * Recupere le nom du pwBackup
	 * @return string
	 * @throws Exception
	 */
	public function recupere_totalRestorePointSize_du_pwBackup(
			$pwBackup) {
		return ( int ) $pwBackup->totalRestorePointSize;
	}

	/**
	 * Recupere le nom du pwBackup
	 * @return string
	 * @throws Exception
	 */
	public function recupere_latestRestorePointSize_du_pwBackup(
			$pwBackup) {
		return ( int ) $pwBackup->latestRestorePointSize;
	}

	/**
	 * Recupere le nom du pwBackup
	 * @return string
	 * @throws Exception
	 */
	public function recupere_targetType_du_pwBackup(
			$pwBackup) {
		return ( string ) $pwBackup->targetType;
	}

	/**
	 * Permet de trouver la liste des pwBackups dans veeamspc et enregistre les donnees des pwBackups dans l'objet
	 * @return pwBackups
	 * @throws Exception
	 */
	public function retrouve_pwBackups(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$pwBackups = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_VMs = $this->getObjetVeeamWsclientRest ()
				->getMethod ( $this->pwBackups_list_uri (), $params );
			$this->onDebug ( $liste_res_VMs, 2 );
			if(!isset($liste_res_VMs->data)){
				return $this->onError($liste_res_VMs);
			}
			foreach ( $liste_res_VMs->data as $VM ) {
				$pwBackups [$this->recupere_id_du_pwBackup ( $VM )] = $VM;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setId ( "" )
			->setListePwBackups ( $pwBackups );
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un pwBackup id est rempli/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_pwBackupid(
			$error = true) {
		if (empty ( $this->getPwBackupId () )) {
			$this->onDebug ( $this->getPwBackupId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un pwBackup-id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	public function pwBackups_list_uri() {
		return $this->virtualMachines_list_uri () . '/backups';
	}

	public function pwBackup_id_uri() {
		if ($this->valide_pwBackupid () == false) {
			return $this->onError ( "Il n'y pas d'id pwBackup selectionne" );
		}
		return $this->virtualMachines_id_uri () () . '/backups/' . $this->getPwBackupId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getPwBackupId() {
		return $this->pwBackup_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPwBackupId(
			$pwBackup_id) {
		$this->pwBackup_id = $pwBackup_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListePwBackups() {
		return $this->liste_pwBackups;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListePwBackups(
			$liste_pwBackups) {
		$this->liste_pwBackups = $liste_pwBackups;
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
		$help [__CLASS__] ["text"] [] .= "pwBackups :";
		return $help;
	}
}
?>
