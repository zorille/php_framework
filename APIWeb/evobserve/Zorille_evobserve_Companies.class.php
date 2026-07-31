<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;
use stdClass;
use Exception;

/**
 * class Companies
 *
 * @package Lib
 * @subpackage evobserve
 */
abstract class Companies extends item {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $Companies = array();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $Customers = array();

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return $this
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * ******************************* Companies URI ******************************
	 */
	public function companies_list_uri(): string {
		return $this->globalapi_uri().'/companies';
	}

	/**
	 * @throws Exception
	 */
	public function all_companies_list_uri(): string {
		return $this->companies_list_uri () . '/list';
	}
	/**
	 * ******************************* Evobserve Companies *********************************
	 */
	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format evobserve
	 */
	public function prepare_params_Companies(
		array $parametres): array
	{
		return $this->prepare_standard_params ( $parametres );
	}
	
	/**
	 * Recupere la liste des companies et des clients sous la companie en parametre (cf: id)
	 * @param array $parametres Liste des parametres de la commande tree. ("id"=> x est un parametre obligatoire)
	 * @return stdClass | NULL
	 * @throws Exception
	 */
	public function recupere_companies_list(
		array $parametres): stdClass|NULL {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Companies ( $parametres );
		$this->onDebug ( $params, 1 );
		$liste_companies = $this->getObjetEvobserveWsclient ()
			->getMethod ( $this->all_companies_list_uri (), $params );
		return $liste_companies;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getCompanies(): array {
		return $this->Companies;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCompanies(
			$ListeCompanies): static {
				$this->Companies = $ListeCompanies;
				return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getCustomers(): array {
		return $this->Customers;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCustomers(
			$ListeCustomers): static {
				$this->Customers = $ListeCustomers;
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
		$help [__CLASS__] ["text"] = [
			'Companies :'
		];
		return $help;
	}
}
