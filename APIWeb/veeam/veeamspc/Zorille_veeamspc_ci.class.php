<?php

/**
 * Gestion de veeamspc.
 * @author dvargas
 */
namespace Zorille\veeamspc;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class ci
 *
 * @package Lib
 * @subpackage veeamspc
 */
abstract class ci extends restapi {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $id = '';
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $donnees = null;

	/**
	 * Valide qu'un objet est instancie dans les donnees
	 * @return boolean
	 */
	public function valide_donnees_existe() {
		if (is_object ( $this->getDonnees () )) {
			return true;
		}
		return false;
	}

	/**
	 * Recupere l'id du companie, l'ajoute à l'objet et renvoi l'Id
	 * @return string
	 * @throws Exception
	 */
	public function recupere_instanceUid(
			$objet) {
		if (isset ( $objet->instanceUid )) {
			$this->setId ( $objet->instanceUid );
			return $objet->instanceUid;
		}
		$this->setId ( "" );
		return "";
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setId(
			$id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDonnees() {
		return $this->donnees;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDonnees(
			$donnees) {
		$this->donnees = $donnees;
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
		$help [__CLASS__] ["text"] [] .= "ci :";
		return $help;
	}
}
?>
