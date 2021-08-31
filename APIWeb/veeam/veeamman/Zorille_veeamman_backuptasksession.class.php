<?php

/**
 * Gestion de veeamman.
 * @author dvargas
 */
namespace Zorille\veeamman;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class backuptasksession
 *
 * @package Lib
 * @subpackage veeamman
 */
class backuptasksession extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_backuptasksession = null;
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
	 * Instanbackuptasksessione un objet de type backuptasksession. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return backuptasksession
	 */
	static function &creer_veeamman_backuptasksession(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new backuptasksession ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return backuptasksession
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
		// Gestion de backuptasksession
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Permet de recuperer les donnees d'un backuptasksession dans Veeam
	 * @return backuptasksession
	 * @throws Exception
	 */
	public function recupere_donnees_backuptasksession() {
		$backuptasksession_data = $this->getObjetVeeamWsclientRest ()
			->BackupTaskSession( $this->getId (), array (
				"format" => "Entity"
		) );
		$this->onDebug ( $backuptasksession_data, 2 );
		return $this->setDonnees ( $backuptasksession_data );
	}

	/**
	 * Recupere l'id du backuptasksession et l'ajoute à l'objet
	 * @return backuptasksession
	 * @throws Exception
	 */
	public function recupere_id_du_backuptasksession(
			$backuptasksession) {
		if (preg_match ( '/:BackupTaskSession:(.*)/', $backuptasksession->attributes () ['UID'], $resultat ) === false) {
			return $this->onError ( "Numero de BackupTaskSession introuvable", $resultat );
		}
		return $this->setId ( $resultat [1] );
	}

	/**
	 * Recupere le nom du backuptasksession
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_backuptasksession(
			$backuptasksession) {
		return $backuptasksession->attributes () ['Name'];
	}

	/**
	 * Permet de trouver la liste des backuptasksession dans veeamman et enregistre les donnees des backuptasksession dans l'objet
	 * @return backuptasksession
	 * @throws Exception
	 */
	public function retrouve_backuptasksession() {
		$this->onDebug ( __METHOD__, 1 );
		$backuptasksession = $this->getObjetVeeamWsclientRest ()
			->listBackupTasksSessions ();
		if (! isset ( $backuptasksession->Ref )) {
			// Le backuptasksession n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des backuptasksession." );
		}
		$this->onDebug ( $backuptasksession, 2 );
		return $this->setListeTasks ( $backuptasksession );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

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
		$help [__CLASS__] ["text"] [] .= "backuptasksession :";
		return $help;
	}
}
?>
