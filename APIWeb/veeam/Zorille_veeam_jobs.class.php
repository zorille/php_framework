<?php

/**
 * Gestion de veeam.
 * @author dvargas
 */
namespace Zorille\veeam;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class jobs
 *
 * @package Lib
 * @subpackage veeam
 */
class jobs extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_jobs = null;
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
	 * Instanjobse un objet de type jobs. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return jobs
	 */
	static function &creer_veeam_jobs(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new jobs ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return jobs
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
		// Gestion de jobs
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Permet de recuperer les donnees d'un job dans Veeam
	 * @return jobs
	 * @throws Exception
	 */
	public function recupere_donnees_job() {
		$job_data = $this->getObjetVeeamWsclientRest ()
			->Job ( $this->getId (), array (
				"format" => "Entity"
		) );
		return $this->setDonnees ( $job_data );
	}

	/**
	 * Permet de recuperer la liste des objets d'un job dans Veeam
	 * @return jobs
	 * @throws Exception
	 */
	public function recupere_liste_objets_du_job() {
		$job_ObjectInJob = $this->getObjetVeeamWsclientRest ()
			->listeJobIncludes ( $this->getId () );
		if (isset ( $job_ObjectInJob->Ref )) {
			return $this->setListeIncludes ( $job_ObjectInJob );
		}
		return $this->onError ( "Pas de reference dans le job" );
	}

	/**
	 * Permet de recuperer les donnees d'un objet du job dans Veeam
	 * @return jobs
	 * @throws Exception
	 */
	public function recupere_donnees_objet_du_job(
			$ObjectInJobId) {
		return $this->getObjetVeeamWsclientRest ()
			->JobInclude ( $this->getId (), $ObjectInJobId );
	}

	/**
	 * Recupere l'id du job et l'ajoute à l'objet
	 * @return jobs
	 * @throws Exception
	 */
	public function recupere_id_du_job(
			$job) {
		if (preg_match ( '/:Job:(.*)/', $job->attributes () ['UID'], $resultat ) === false) {
			return $this->onError ( "Numero de Job (jobid) introuvable", $resultat );
		}
		return $this->setId ( $resultat [1] );
	}

	/**
	 * Recupere le nom du job
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_job(
			$job) {
		return (string) $job->attributes () ['Name'];
	}

	/**
	 * Permet de trouver la liste des jobs dans veeam et enregistre les donnees des jobs dans l'objet
	 * @return jobs
	 * @throws Exception
	 */
	public function retrouve_jobs() {
		$this->onDebug ( __METHOD__, 1 );
		$jobs = $this->getObjetVeeamWsclientRest ()
			->listJobs ();
		if (! isset ( $jobs->Ref )) {
			// Le jobs n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des jobs." );
		}
		$this->onDebug ( $jobs, 2 );
		return $this->setListeJobs ( $jobs );
	}

	/**
	 * Valide que le jobs existe et est unique dans veeam et enregistre les donnees du jobs dans l'objet s'il est trouve
	 * @return jobs|null
	 */
	public function valide_jobs_existe(
			$nom) {
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeJobs() {
		return $this->liste_jobs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeJobs(
			$liste_jobs) {
		$this->liste_jobs = $liste_jobs;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeIncludes() {
		return $this->liste_includes;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeIncludes(
			$liste_includes) {
		$this->liste_includes = $liste_includes;
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
		$help [__CLASS__] ["text"] [] .= "jobs :";
		return $help;
	}
}
?>
