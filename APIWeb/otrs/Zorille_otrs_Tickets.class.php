<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception;

/**
 * class Tickets
 *
 * @package Lib
 * @subpackage otrs
 */
abstract class Tickets extends item {

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Tickets
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ******************************* Tickets URI ******************************
	 */
	public function ticket_create_uri(): string {
		return $this->operation_uri ( 'TicketCreate' );
	}

	public function ticket_update_uri(): string {
		return $this->operation_uri ( 'TicketUpdate' );
	}

	public function ticket_search_uri(): string {
		return $this->operation_uri ( 'TicketSearch' );
	}

	public function ticket_get_uri(): string {
		return $this->operation_uri ( 'TicketGet' );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Tickets :";
		return $help;
	}
}
