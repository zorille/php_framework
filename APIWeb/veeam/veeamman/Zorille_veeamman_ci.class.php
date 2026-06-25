<?php
/**
 * Gestion de veeamman.
 * @author dvargas
 */
namespace Zorille\veeamman;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class ci
 *
 * @package Lib
 * @subpackage veeamman
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
	public function valide_donnees_existe(): bool
	{
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
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setId(
			$id): static
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDonnees(): ?\SimpleXMLElement
	{
		return $this->donnees;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDonnees(
			$donnees): static
	{
		$this->donnees = $donnees;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return wsclient
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
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "ci :";
		return $help;
	}
}
