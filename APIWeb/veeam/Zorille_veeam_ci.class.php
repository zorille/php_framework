<?php
/**
 * Gestion de veeam.
 * @author dvargas
 */
namespace Zorille\veeam;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class ci
 *
 * @package Lib
 * @subpackage veeam
 */
abstract class ci extends Core\abstract_log {
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
	 * var privee
	 *
	 * @access private
	 * @var wsclient
	 */
	private $wsclient = null;
	
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
	 * @codeCoverageIgnore
	 * @return wsclient
	 */
	public function &getObjetVeeamWsclientRest() {
		return $this->wsclient;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetVeeamWsclientRest(
			&$wsclient) {
				$this->wsclient = $wsclient;
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
