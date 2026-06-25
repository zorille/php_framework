<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

use Exception;

/**
 * class Services
 *
 * @package Lib
 * @subpackage evobserve
 */
abstract class Services extends item {

	/**
	 * ********************* Creation de l'objet ********************
	 */

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Services
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
	 * ******************************* Services URI ******************************
	 */
	public function services_list_uri(): string {
		return $this->globalapi_uri().'/services';
	}
	/**
	 * ******************************* Evobserve Services *********************************
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
		$help [__CLASS__] ["text"] = [
			'Services :'
		];
		return $help;
	}
}
