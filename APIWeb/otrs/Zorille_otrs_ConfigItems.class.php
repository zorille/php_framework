<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception;

/**
 * class ConfigItems
 *
 * @package Lib
 * @subpackage otrs
 */
abstract class ConfigItems extends item {

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ConfigItems
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	public function config_item_create_uri(): string {
		return $this->operation_uri ( 'ConfigItemCreate' );
	}

	public function config_item_update_uri(): string {
		return $this->operation_uri ( 'ConfigItemUpdate' );
	}

	public function config_item_delete_uri(): string {
		return $this->operation_uri ( 'ConfigItemDelete' );
	}

	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "ConfigItems :";
		return $help;
	}
}
