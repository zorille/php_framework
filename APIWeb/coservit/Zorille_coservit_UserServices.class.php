<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Exception;

/**
 * class UserServices
 *
 * @package Lib
 * @subpackage coservit
 */
abstract class UserServices extends item {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return UserServices
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
	 * ******************************* UserServices URI ******************************
	 */
	public function userServices_uri(): string {
		return $this->globalapi_uri () . '/user_services';
	}

	public function userServices_lists_uri(): string {
		return $this->userServices_uri () . '/lists';
	}

	/**
	 * ******************************* Coservit UserServices *********************************
	 */
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "UserServices :";
		return $help;
	}
}
