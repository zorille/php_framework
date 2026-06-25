<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

use Exception;

/**
 * class Maintenances
 *
 * @package Lib
 * @subpackage evobserve
 */
abstract class Maintenances extends item {

	/**
	 * ********************* Creation de l'objet ********************
	 */

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Maintenances
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
	 * ******************************* Maintenances URI ******************************
	 */
	public function maintenances_uri(): string {
		return $this->globalapi_uri () . '/maintenances';
	}

	public function maintenances_lists_uri(
		array $query_params = array ()): string {
		$uri = $this->maintenances_uri () . '/lists';
		if (! empty ( $query_params )) {
			$uri .= '?' . http_build_query ( $query_params );
		}
		return $uri;
	}

	public function maintenances_create_uri(): string {
		return $this->maintenances_uri () . '/create';
	}

	public function host_maintenances_uri(
		int|string $host,
		array      $query_params = array ()): string {
		$uri = $this->globalapi_uri () . '/hosts/' . $host . '/maintenances';
		if (! empty ( $query_params )) {
			$uri .= '?' . http_build_query ( $query_params );
		}
		return $uri;
	}

	public function host_maintenance_id_uri(
		int|string $host,
		int|string $maintenance): string {
		return $this->globalapi_uri () . '/hosts/' . $host . '/maintenances/' . $maintenance;
	}

	public function service_maintenances_uri(
		int|string $service,
		array      $query_params = array ()): string {
		$uri = $this->globalapi_uri () . '/services/' . $service . '/maintenances';
		if (! empty ( $query_params )) {
			$uri .= '?' . http_build_query ( $query_params );
		}
		return $uri;
	}

	public function service_maintenance_id_uri(
		int|string $service,
		int|string $maintenance): string {
		return $this->globalapi_uri () . '/services/' . $service . '/maintenances/' . $maintenance;
	}

	/**
	 * ******************************* Evobserve Maintenances *********************************
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
		$help [__CLASS__] ["text"] [] .= "Maintenances :";
		return $help;
	}
}
