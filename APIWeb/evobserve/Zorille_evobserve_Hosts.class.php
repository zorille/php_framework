<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

use Exception;

/**
 * class Hosts
 *
 * @package Lib
 * @subpackage evobserve
 */
abstract class Hosts extends item {

	/**
	 * ********************* Creation de l'objet ********************
	 */

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Hosts
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
	 * ******************************* Hosts URI ******************************
	 */
	public function hosts_list_uri(): string {
		return $this->globalapi_uri().'/hosts';
	}
	/**
	 * ******************************* Evobserve Hosts *********************************
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
		$help [__CLASS__] ["text"] [] .= "Hosts :";
		return $help;
	}
}
