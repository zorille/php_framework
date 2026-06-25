<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

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
	 * ******************************* Evobserve Companies *********************************
	 */
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
