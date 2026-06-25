<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use Exception;
use Zorille\framework as Core;

/**
 * class restapiAbstract
 * @package Lib
 * @subpackage Veeam
 */
abstract class restapi extends Core\abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var wsclient
	 */
	private $wsclient = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $ws_reponse = null;

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return $this
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setObjetVeeamWsclientRest( $liste_class ['wsclient'] );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return wsclient|null
	 */
	public function &getObjetVeeamWsclientRest(): ?wsclient
	{
		return $this->wsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetVeeamWsclientRest(
			&$wsclient): static
	{
		$this->wsclient = $wsclient;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getWsReponse(): ?string
	{
		return $this->ws_reponse;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWsReponse(
			$ws_reponse): static
	{
		$this->ws_reponse = $ws_reponse;
		return $this;
	}
/**
 * ***************************** ACCESSEURS *******************************
 */
}
