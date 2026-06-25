<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use SimpleXMLElement;
use stdClass;
use Zorille\framework as Core;
use Exception as Exception;

/**
 * class jobs
 *
 * @package Lib
 * @subpackage veeamspc
 */
class jobs extends backupServers {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $job_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var SimpleXMLElement
	 */
	private $liste_jobs = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var SimpleXMLElement
	 */
	private $liste_includes = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instanjobse un objet de type jobs. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return jobs
	 */
	static function &creer_veeamspc_jobs(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): jobs
	{
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
		// Gestion de jobs
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du job, l'ajoute à l'objet et renvoi l'Id
	 * @param $job
	 * @return string|null
	 * @throws Exception
	 */
	public function recupere_id_de_job(
			$job): ?string
	{
		$this->setJobId ( $this->recupere_instanceUid ( $job ) );
		return $this->getJobId ();
	}

	/**
	 * Recupere le nom du job
	 * @param $job
	 * @return string
	 */
	public function recupere_nom_du_job(
			$job): string
	{
		return ( string ) $job->name;
	}

	/**
	 * Permet de trouver la liste des jobs dans veeamspc et enregistre les donnees des jobs dans l'objet
	 * @param array $params
	 * @return jobs
	 * @throws Exception
	 */
	public function retrouve_jobs(
			$params = array ()): jobs
	{
		$this->onDebug ( __METHOD__, 1 );
		$jobs = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_tenants = $this->listJobs ( $params );
			$this->onDebug ( $liste_res_tenants, 2 );
			foreach ( $liste_res_tenants->data as $tenant ) {
				$jobs [$this->recupere_id_de_job ( $tenant )] = $tenant;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setJobId ( "" )
			->setListeJobs ( $jobs );
	}

	/**
	 * Liste les organisations
	 *
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function listJobs(
		array $params = array ()): SimpleXMLElement|bool|array|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjetVeeamWsclientRest ()
			->getMethod ( $this->jobs_list_uri (), $params );
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un job id est rempli/existe
	 * @param bool $error
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_jobid(
		bool $error = true): bool
	{
		if (empty ( $this->getJobId () )) {
			$this->onDebug ( $this->getJobId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un job id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	/**
	 * @throws Exception
	 */
	public function jobs_list_uri(): string
	{
		return $this->backupServers_list_uri().'/jobs';
	}

	/**
	 * @throws Exception
	 */
	public function job_id_uri(): bool|string
	{
		if (!$this->valide_jobid()) {
			return $this->onError ( "Il n'y pas d'id d'job selectionne" );
		}
		return $this->jobs_list_uri () . '/' . $this->getJobId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getJobId(): ?string
	{
		return $this->job_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setJobId(
			$job_id): static
	{
		$this->job_id = $job_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeJobs(): ?SimpleXMLElement
	{
		return $this->liste_jobs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeJobs(
			$liste_jobs): static
	{
		$this->liste_jobs = $liste_jobs;
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
		$help [__CLASS__] ["text"] [] .= "jobs :";
		return $help;
	}
}
