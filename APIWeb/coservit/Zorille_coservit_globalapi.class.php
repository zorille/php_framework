<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class globalapi
 *
 * @package Lib
 * @subpackage coservit
 */
abstract class globalapi extends Core\abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $lang = 'fr_FR';
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $donnees = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var wsclient
	 */
	private $wsclient = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return globalapi
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetCoservitWsclient ( $liste_class ["wsclient"] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Extrait des parametres d'un liste d'option
	 * @codeCoverageIgnore
	 * @param string|array $chemin_option
	 * @return boolean string array
	 */
	protected function _valideOption(
			$chemin_option) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->getListeOptions ()
			->verifie_variable_standard ( $chemin_option ) === false) {
			if (is_array ( $chemin_option )) {
				$chemin_option = implode ( "_", $chemin_option );
			}
			return $this->onError ( "Il manque le parametre : " . $chemin_option );
		}
		$datas = $this->getListeOptions ()
			->renvoi_variables_standard ( $chemin_option );
		if (is_array ( $datas ) && isset ( $datas ["#comment"] )) {
			unset ( $datas ["#comment"] );
		}
		return $datas;
	}

	/**
	 * ******************************* Global URI ******************************
	 */
	public function globalapi_uri() {
		return 'servicenav/' . $this->getLang ();
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getLang() {
		return $this->lang;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLang(
			$lang) {
		$this->lang = $lang;
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
	public function &getObjetCoservitWsclient() {
		return $this->wsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetCoservitWsclient(
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
		$help [__CLASS__] ["text"] [] .= "globalapi :";
		return $help;
	}
}
?>
