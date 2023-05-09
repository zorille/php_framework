<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;

/**
 * class Companies
 *
 * @package Lib
 * @subpackage coservit
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
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * ******************************* Companies URI ******************************
	 */
	public function companies_list_uri() {
		return $this->globalapi_uri().'/companies';
	}
	/**
	 * ******************************* Coservit Companies *********************************
	 */
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getCompanies() {
		return $this->Companies;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCompanies(
			$ListeCompanies) {
				$this->Companies = $ListeCompanies;
				return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getCustomers() {
		return $this->Customers;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setCustomers(
			$ListeCustomers) {
				$this->Customers = $ListeCustomers;
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
		$help [__CLASS__] ["text"] [] .= "Companies :";
		return $help;
	}
}
?>
