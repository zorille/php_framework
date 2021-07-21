<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\o365;

use Zorille\framework as Core;

/**
 * class VirtualMachineCommun<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class Graph extends Core\abstract_log {
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
	 * @return Item
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetO365Wsclient ( $liste_class ['wsclient'] );
	}

	public function valide_champ_value(
			$reponse) {
		if (! isset ( $reponse->value )) {
			$this->onWarning ( "Il n'y a pas de donnees dans la response" );
			return false;
		}
		return true;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return wsclient
	 */
	public function &getObjetO365Wsclient() {
		return $this->wsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetO365Wsclient(
			&$wsclient) {
		$this->wsclient = $wsclient;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getWsReponse() {
		return $this->ws_reponse;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWsReponse(
			$ws_reponse) {
		$this->ws_reponse = $ws_reponse;
		return $this;
	}
/**
 * ***************************** ACCESSEURS *******************************
 */
}
?>
