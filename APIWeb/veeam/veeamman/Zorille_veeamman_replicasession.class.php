<?php

/**
 * Gestion de veeamman.
 * @author dvargas
 */
namespace Zorille\veeamman;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class replicasession
 *
 * @package Lib
 * @subpackage veeamman
 */
class replicasession extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_replicasession = null;
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
	 * Instanreplicasessione un objet de type replicasession. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return replicasession
	 */
	static function &creer_veeamman_replicasession(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new replicasession ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return replicasession
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
		// Gestion de replicasession
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Permet de recuperer les donnees d'un replicasession dans Veeam
	 * @return replicasession
	 * @throws Exception
	 */
	public function recupere_donnees_replicasession() {
		$replicasession_data = $this->getObjetVeeamWsclientRest ()
			->ReplicaSessions ( $this->getId (), array (
				"format" => "Entity"
		) );
		$this->onDebug ( $replicasession_data, 2 );
		return $this->setDonnees ( $replicasession_data );
	}

	/**
	 * Recupere l'id du replicasession et l'ajoute à l'objet
	 * @return replicasession
	 * @throws Exception
	 */
	public function recupere_id_du_replicasession(
			$replicasession) {
		if (preg_match ( '/:ReplicaJobSession:(.*)/', $replicasession->attributes () ['UID'], $resultat ) === false) {
			return $this->onError ( "Numero de ReplicaSession introuvable", $resultat );
		}
		return $this->setId ( $resultat [1] );
	}

	/**
	 * Recupere le nom du replicasession
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_replicasession(
			$replicasession) {
		return $replicasession->attributes () ['Name'];
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
	 * Permet de trouver la liste des replicasession dans veeamman et enregistre les donnees des replicasession dans l'objet
	 * @return replicasession
	 * @throws Exception
	 */
	public function retrouve_replicasession() {
		$this->onDebug ( __METHOD__, 1 );
		$replicasession = $this->getObjetVeeamWsclientRest ()
			->listReplicaSessions ();
		if (! isset ( $replicasession->Ref )) {
			// Le replicasession n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des replicasession." );
		}
		$this->onDebug ( $replicasession, 2 );
		return $this->setListeReplicaSession ( $replicasession );
	}

	/**
	 * Permet de trouver la liste des replicasession par JobId dans veeamman et enregistre les donnees des replicasession dans l'objet
	 * @return replicasession
	 * @throws Exception
	 */
	public function retrouve_replicasession_par_jobid(
			$jobid) {
		$this->onDebug ( __METHOD__, 1 );
		$replicasession = $this->getObjetVeeamWsclientRest ()
			->listReplicaSessionsParJob ( $jobid );
		if (! isset ( $replicasession->Ref )) {
			// Le replicasession n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des replicasession." );
		}
		$this->onDebug ( $replicasession, 2 );
		return $this->setListeReplicaSession ( $replicasession );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeReplicaSession() {
		return $this->liste_replicasession;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeReplicaSession(
			$liste_replicasession) {
		$this->liste_replicasession = $liste_replicasession;
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
		$help [__CLASS__] ["text"] [] .= "replicasession :";
		return $help;
	}
}
?>
