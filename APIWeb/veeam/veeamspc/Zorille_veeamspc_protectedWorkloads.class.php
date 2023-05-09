<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class protectedWorkloads
 *
 * @package Lib
 * @subpackage Veeam
 */
class protectedWorkloads extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $protectedWorkload_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_protectedWorkloads = null;
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
	 * InstanprotectedWorkloadse un objet de type protectedWorkloads. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return protectedWorkloads
	 */
	static function &creer_veeamspc_protectedWorkloads(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new protectedWorkloads ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return protectedWorkloads
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
		// Gestion de protectedWorkloads
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du protectedWorkload, l'ajoute à l'objet et renvoi l'Id
	 * @return string
	 * @throws Exception
	 */
	public function recupere_id_du_protectedWorkload(
			$protectedWorkload) {
		$this->setProtectedWorkloadId ( $this->recupere_instanceUid ( $protectedWorkload ) );
		return $this->getProtectedWorkloadId ();
	}

	/**
	 * Recupere le nom du protectedWorkload
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nom_du_protectedWorkload(
			$protectedWorkload) {
		return ( string ) $protectedWorkload->name;
	}

	/**
	 * Recupere le nom du protectedWorkload
	 * @return string
	 * @throws Exception
	 */
	public function recupere_OrganizationUID_du_protectedWorkload(
			$protectedWorkload) {
		return ( string ) $protectedWorkload->organizationUid;
	}

	/**
	 * Recupere le nom du protectedWorkload
	 * @return string
	 * @throws Exception
	 */
	public function recupere_latestRestorePointDate_du_protectedWorkload(
			$protectedWorkload) {
		return ( string ) $protectedWorkload->latestRestorePointDate;
	}

	/**
	 * Recupere le nom du protectedWorkload
	 * @return string
	 * @throws Exception
	 */
	public function recupere_nb_restorePoints_du_protectedWorkload(
			$protectedWorkload) {
		return ( int ) $protectedWorkload->restorePoints;
	}

	/**
	 * Recupere le nom du protectedWorkload
	 * @return string
	 * @throws Exception
	 */
	public function recupere_taille_du_protectedWorkload(
			$protectedWorkload) {
		return ( int ) $protectedWorkload->usedSourceSize;
	}

	/**
	 * Permet de trouver la liste des protectedWorkloads dans veeamspc et enregistre les donnees des protectedWorkloads dans l'objet
	 * @return protectedWorkloads
	 * @throws Exception
	 */
	public function retrouve_protectedWorkloads(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$protectedWorkloads = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
			$liste_res_VMs = $this->getObjetVeeamWsclientRest ()
				->getMethod ( $this->protectedWorkloads_list_uri (), $params );
			$this->onDebug ( $liste_res_VMs, 2 );
			foreach ( $liste_res_VMs->data as $VM ) {
				$protectedWorkloads [$this->recupere_id_du_protectedWorkload ( $VM )] = $VM;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setId ( "" )
			->setListeProtectedWorkloads ( $protectedWorkloads );
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un protectedWorkload id est rempli/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_protectedWorkloadid(
			$error = true) {
		if (empty ( $this->getProtectedWorkloadId () )) {
			$this->onDebug ( $this->getProtectedWorkloadId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un protectedWorkload id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	public function protectedWorkloads_list_uri() {
		return '/protectedWorkloads';
	}

	public function protectedWorkload_id_uri() {
		if ($this->valide_protectedWorkloadid () == false) {
			return $this->onError ( "Il n'y pas d'id d'protectedWorkload selectionne" );
		}
		return $this->protectedWorkloads_list_uri () . '/' . $this->getProtectedWorkloadId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getProtectedWorkloadId() {
		return $this->protectedWorkload_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProtectedWorkloadId(
			$protectedWorkload_id) {
		$this->protectedWorkload_id = $protectedWorkload_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeProtectedWorkloads() {
		return $this->liste_protectedWorkloads;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeProtectedWorkloads(
			$liste_protectedWorkloads) {
		$this->liste_protectedWorkloads = $liste_protectedWorkloads;
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
		$help [__CLASS__] ["text"] [] .= "protectedWorkloads :";
		return $help;
	}
}
?>
