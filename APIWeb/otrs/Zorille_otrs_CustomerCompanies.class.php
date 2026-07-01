<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception;

/**
 * class CustomerCompanies
 *
 * @package Lib
 * @subpackage otrs
 */
abstract class CustomerCompanies extends item {

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomerCompanies
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	public function customer_company_create_uri(): string {
		return $this->operation_uri ( 'CustomerCompanyCreate' );
	}

	public function customer_company_update_uri(): string {
		return $this->operation_uri ( 'CustomerCompanyUpdate' );
	}

	public function customer_company_get_uri(): string {
		return $this->operation_uri ( 'CustomerCompanyGet' );
	}

	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "CustomerCompanies :";
		return $help;
	}
}
