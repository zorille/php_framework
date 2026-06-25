<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use SimpleXMLElement;
use Zorille\framework as Core;
use Exception as Exception;

/**
 * class virtualMachines
 *
 * @package Lib
 * @subpackage Veeam
 */
class virtualMachines extends protectedWorkloads {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $virtualMachine_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_virtualMachines = null;
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
	 * InstanvirtualMachinese un objet de type virtualMachines. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return virtualMachines
	 */
	static function &creer_veeamspc_virtualMachines(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): virtualMachines
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new virtualMachines ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return virtualMachines
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
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de virtualMachines
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere l'id du virtualMachine, l'ajoute à l'objet et renvoi l'Id
	 * @param $virtualMachine
	 * @return string|null
	 * @throws Exception
	 */
	public function recupere_id_du_virtualMachine(
			$virtualMachine): ?string
	{
		$this->setVirtualMachineId ( $this->recupere_instanceUid ( $virtualMachine ) );
		return $this->getVirtualMachineId ();
	}

	/**
	 * Recupere le nom du virtualMachine
	 * @param $virtualMachine
	 * @return string
	 */
	public function recupere_nom_du_virtualMachine(
			$virtualMachine): string
	{
		return ( string ) $virtualMachine->name;
	}

	/**
	 * Recupere le nom du virtualMachine
	 * @param $virtualMachine
	 * @return string
	 */
	public function recupere_OrganizationUID_du_virtualMachine(
			$virtualMachine): string
	{
		return ( string ) $virtualMachine->organizationUid;
	}

	/**
	 * Recupere le nom du virtualMachine
	 * @param $virtualMachine
	 * @return string
	 */
	public function recupere_latestRestorePointDate_du_virtualMachine(
			$virtualMachine): string
	{
		return ( string ) $virtualMachine->latestRestorePointDate;
	}

	/**
	 * Recupere le nom du virtualMachine
	 * @param $virtualMachine
	 * @return int|string
	 */
	public function recupere_nb_restorePoints_du_virtualMachine(
			$virtualMachine): int|string
	{
		return ( int ) $virtualMachine->restorePoints;
	}

	/**
	 * Recupere le nom du virtualMachine
	 * @param $virtualMachine
	 * @return int|string
	 */
	public function recupere_taille_du_virtualMachine(
			$virtualMachine): int|string
	{
				return ( int ) $virtualMachine->usedSourceSize;
	}

	/**
	 * Permet de trouver la liste des virtualMachines dans veeamspc et enregistre les donnees des virtualMachines dans l'objet
	 * @param array $params
	 * @return virtualMachines
	 * @throws Exception
	 */
	public function retrouve_virtualMachines(
		array $params = array ()): virtualMachines
	{
		$this->onDebug ( __METHOD__, 1 );
		$virtualMachines = array ();
		while ( ! $this->getObjetVeeamWsclientRest ()
			->getDernierePage () ) {
				$liste_res_VMs = $this->getObjetVeeamWsclientRest ()
				->getMethod ( $this->virtualMachines_list_uri (), $params );
			$this->onDebug ( $liste_res_VMs, 2 );
			foreach ( $liste_res_VMs->data as $VM ) {
				$virtualMachines [$this->recupere_id_du_virtualMachine ( $VM )] = $VM;
			}
		}
		$this->getObjetVeeamWsclientRest ()
			->reset_pages ();
		return $this->setId ( "" )
			->setListeVirtualMachines ( $virtualMachines );
	}

	/**
	 * ******************************* ORGANIZATION URI ******************************
	 */
	/**
	 * Verifie qu'un virtualMachine id est rempli/existe
	 * @param bool $error
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_virtualMachineid(
		bool $error = true): bool
	{
		if (empty ( $this->getVirtualMachineId() )) {
			$this->onDebug ( $this->getVirtualMachineId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un virtualMachine id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	public function virtualMachines_list_uri(): string
	{
		return $this->protectedWorkloads_list_uri().'/virtualMachines';
	}

	/**
	 * @throws Exception
	 */
	public function virtualMachine_id_uri(): bool|string
	{
		if (!$this->valide_virtualMachineid()) {
			return $this->onError ( "Il n'y pas d'id virtualMachine selectionne" );
		}
		return $this->protectedWorkload_id_uri() () . '/virtualMachines/' . $this->getVirtualMachineId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getVirtualMachineId(): ?string
	{
		return $this->virtualMachine_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setVirtualMachineId(
			$virtualMachine_id): static
	{
		$this->virtualMachine_id = $virtualMachine_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeVirtualMachines(): SimpleXMLElement|null|array
	{
		return $this->liste_virtualMachines;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeVirtualMachines(
			$liste_virtualMachines): static
	{
		$this->liste_virtualMachines = $liste_virtualMachines;
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
		$help [__CLASS__] ["text"] [] .= "virtualMachines :";
		return $help;
	}
}
