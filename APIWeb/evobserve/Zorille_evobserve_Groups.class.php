<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

/**
 * class Groups
 *
 * @package Lib
 * @subpackage evobserve
 */
abstract class Groups extends item {

	/**
	 * ********************* Creation de l'objet ********************
	 */

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Groups
	 * @throws \Exception
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
	 * ******************************* Groups URI ******************************
	 */
	public function groups_list_uri(): string {
		return '/bigdata/groups';
	}
	/**
	 * ******************************* Evobserve Groups *********************************
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
		$help [__CLASS__] ["text"] [] .= "Groups :";
		return $help;
	}
}
