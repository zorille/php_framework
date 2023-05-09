<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class infrastructures
 *
 * @package Lib
 * @subpackage veeamspc
 */
abstract class infrastructures extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $infrastructure_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_infrastructures = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_includes = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */

	/**
	 * ******************************* Infrastructure URI ******************************
	 */
	/**
	 * Verifie qu'un infrastructure id est rempli/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_infrastructureid(
			$error = true) {
		if (empty ( $this->getInfrastructureId () )) {
			$this->onDebug ( $this->getInfrastructureId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un infrastructure id renvoye par VeeamSPC pour travailler" );
			}
			return false;
		}
		return true;
	}

	public function infrastructures_list_uri() {
		return '/infrastructure';
	}

	public function infrastructure_id_uri() {
		if ($this->valide_infrastructureid () == false) {
			return $this->onError ( "Il n'y pas d'id d'infrastructure selectionne" );
		}
		return $this->infrastructures_list_uri () . '/' . $this->getInfrastructureId ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getInfrastructureId() {
		return $this->infrastructure_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInfrastructureId(
			$infrastructure_id) {
		$this->infrastructure_id = $infrastructure_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeInfrastructures() {
		return $this->liste_infrastructures;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeInfrastructures(
			$liste_infrastructures) {
		$this->liste_infrastructures = $liste_infrastructures;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "infrastructures :";
		return $help;
	}
}
?>
