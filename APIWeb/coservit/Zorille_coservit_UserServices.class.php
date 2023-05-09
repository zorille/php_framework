<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;

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
	 * ******************************* UserServices URI ******************************
	 */
	public function userServices_uri() {
		return $this->globalapi_uri () . '/user_services';
	}

	public function userServices_lists_uri() {
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
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "UserServices :";
		return $help;
	}
}
?>
