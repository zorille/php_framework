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
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $id_task = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $task_donnees = "";

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instanreplicasessione un objet de type replicasession. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return replicasession
	 */
	static function &creer_veeamman_replicasession(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): replicasession
	{
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
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setObjetVeeamWsclientRest ( $liste_class ["wsclient"] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		// Gestion de replicasession
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Permet de recuperer les donnees d'un replicasession dans Veeam
	 * @return replicasession
	 * @throws Exception
	 */
	public function recupere_donnees_replicasession(): replicasession
	{
		$replicasession_data = $this->getObjetVeeamWsclientRest ()
			->ReplicaSessions ( $this->getId (), array (
				"format" => "Entity"
		) );
		$this->onDebug ( $replicasession_data, 2 );
		return $this->setDonnees ( $replicasession_data );
	}

	/**
	 * Recupere l'id du replicasession et l'ajoute à l'objet
	 * @param $replicasession
	 * @return replicasession|bool
	 * @throws Exception
	 */
	public function recupere_id_du_replicasession(
			$replicasession): replicasession|bool
	{
		if (preg_match ( '/:ReplicaJobSession:(.*)/', $replicasession->attributes () ['UID'], $resultat ) === false) {
			return $this->onError ( "Numero de ReplicaSession introuvable", $resultat );
		}
		return $this->setId ( $resultat [1] );
	}

	/**
	 * Recupere le nom du replicasession
	 * @param $replicasession
	 * @return string
	 */
	public function recupere_nom_du_replicasession(
			$replicasession): string
	{
		return $replicasession->attributes () ['Name'];
	}

	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_jobname(): string
	{
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return ( string ) $this->getDonnees ()->JobName;
	}

	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_status(): string
	{
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return ( string ) $this->getDonnees ()->Result;
	}

	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_progress(): string
	{
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return ( string ) $this->getDonnees ()->Progress;
	}

	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie true ou false
	 * @throws Exception
	 */
	public function recupere_retry(): string
	{
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return ( string ) $this->getDonnees ()->IsRetry;
	}

	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_date_creation(): string
	{
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return ( string ) $this->getDonnees ()->CreationTimeUTC;
	}

	/**
	 * Extrait le nom du job dans le backup session
	 * @return string renvoie le nom du job
	 * @throws Exception
	 */
	public function recupere_date_fin(): string
	{
		if (! $this->valide_donnees_existe ()) {
			return $this->onError ( "Pas de donnees de backup session" );
		}
		return ( string ) $this->getDonnees ()->EndTimeUTC;
	}

	/**
	 * Permet de trouver la liste des replicasession dans veeamman et enregistre les donnees des replicasession dans l'objet
	 * @return bool|replicasession
	 * @throws Exception
	 */
	public function retrouve_replicasession(): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$replicasession = $this->getObjetVeeamWsclientRest ()
			->listReplicaSessions ();
		if (! empty ( ( array ) $replicasession ) && ! isset ( $replicasession->Ref )) {
			// Le replicasession n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des replicasession." );
		}
		$this->onDebug ( $replicasession, 2 );
		return $this->setListeReplicaSession ( $replicasession );
	}

	/**
	 * Permet de trouver la liste des replicasession par JobId dans veeamman et enregistre les donnees des replicasession dans l'objet
	 * @param $jobid
	 * @return bool|replicasession
	 * @throws Exception
	 */
	public function retrouve_replicasession_par_jobid(
			$jobid): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$replicasession = $this->getObjetVeeamWsclientRest ()
			->listReplicaSessionsParJob ( $jobid );
		if (! empty ( ( array ) $replicasession ) && ! isset ( $replicasession->Ref )) {
			// Le replicasession n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des replicasession." );
		}
		$this->onDebug ( $replicasession, 2 );
		return $this->setListeReplicaSession ( $replicasession );
	}

	/**
	 * ***************************** Tasks *******************************
	 */
	/**
	 * Permet de trouver la liste des replicasession dans veeamman et enregistre les donnees des replicasession dans l'objet
	 * @return bool|replicasession
	 * @throws Exception
	 */
	public function retrouve_replicatasksession(): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$replicatasksession = $this->getObjetVeeamWsclientRest ()
			->listReplicaTasksSessions ( $this->recupere_id_du_replicasession ( $this->getDonnees () )
			->getId () );
			if (! empty ( ( array ) $replicatasksession ) && ! isset ( $replicatasksession->Ref )) {
			// Le replicasession n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des replicatasksession." );
		}
		$this->onDebug ( $replicatasksession, 2 );
		return $this->setListeTasks ( $replicatasksession );
	}

	/**
	 * Recupere l'id du replicasession et l'ajoute à l'objet
	 * @param $replicatasksession
	 * @return bool|replicasession
	 * @throws Exception
	 */
	public function recupere_id_du_replicatasksession(
			$replicatasksession): bool|static
	{
		if (preg_match ( '/:ReplicaTaskSession:(.*)/', $replicatasksession->attributes () ['UID'], $resultat ) === false) {
			return $this->onError ( "Numero de ReplicaTaskSession introuvable", $resultat );
		}
		return $this->setIdTask ( $resultat [1] );
	}

	/**
	 * Recupere le nom du replicasession
	 * @param $replicatasksession
	 * @return string
	 */
	public function recupere_nom_du_replicatasksession(
			$replicatasksession): string
	{
		return $replicatasksession->attributes () ['Name'];
	}

	/**
	 * Recupere le nom du replicasession
	 * @param $replicatasksession
	 * @return string
	 */
	public function recupere_VmDisplayName_du_replicatasksession(
			$replicatasksession): string
	{
		return $replicatasksession->attributes () ['VmDisplayName'];
	}

	/**
	 * Permet de trouver la liste des replicasession dans veeamman et enregistre les donnees des replicasession dans l'objet
	 * @return bool|replicasession
	 * @throws Exception
	 */
	public function retrouve_replicatasksessiondata(): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( $this->getIdTask (), 2 );
		$replicatasksession = $this->getObjetVeeamWsclientRest ()
			->ReplicaTaskSession ( $this->getIdTask (), array (
				'format' => 'Entity'
		) );
		$this->onDebug ( $replicatasksession, 2 );
		if (! isset ( $replicatasksession->Links )) {
			// Le replicasession n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des replicatasksessiondata." );
		}
		return $this->setTaskDonnees ( $replicatasksession );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeReplicaSession(): ?\SimpleXMLElement
	{
		return $this->liste_replicasession;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeReplicaSession(
			$liste_replicasession): static
	{
		$this->liste_replicasession = $liste_replicasession;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeTasks(): ?\SimpleXMLElement
	{
		return $this->liste_tasks;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeTasks(
			$liste_tasks): static
	{
		$this->liste_tasks = $liste_tasks;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIdTask(): string
	{
		return $this->id_task;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIdTask(
			$id_task): static
	{
		$this->id_task = $id_task;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTaskDonnees(): string
	{
		return $this->task_donnees;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTaskDonnees(
			$task_donnees): static
	{
		$this->task_donnees = $task_donnees;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "replicasession :";
		return $help;
	}
}
