<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception;

/**
 * class CustomerUsers
 *
 * @package Lib
 * @subpackage otrs
 */
abstract class CustomerUsers extends item {

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomerUsers
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	public function customer_user_create_uri(): string {
		return $this->operation_uri ( 'CustomerUserCreate' );
	}

	public function customer_user_update_uri(): string {
		return $this->operation_uri ( 'CustomerUserUpdate' );
	}

	public function customer_user_get_uri(): string {
		return $this->operation_uri ( 'CustomerUserGet' );
	}

	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "CustomerUsers :";
		return $help;
	}
}
