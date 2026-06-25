<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use SimpleXMLElement;
use stdClass;
use Zorille\framework as Core;
use Exception as Exception;

/**
 * class People
 *
 * @package Lib
 * @subpackage o365
 */
class People extends User {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $people_content = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $people_o365_ref = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type People. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return People
	 */
	static function &creer_People(
		Core\options &$liste_option,
		wsclient     &$webservice,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): People
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new People ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return People
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
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * ******************************* PEOPLE *********************************
	 */
	/**
	 * ******************************* O365 PEOPLE *********************************
	 */
	/**
	 * @param array $params
	 * @return array|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function get_people_data(
		array $params = array ()): SimpleXMLElement|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjetO365Wsclient ()
			->getMethod ( $this->user_id_uri () . '/people', $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getPeopleId() {
		return $this->people_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPeopleId(
			&$people_id): static
	{
		$this->people_id = $people_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getEmailContent(): array
	{
		return $this->people_content;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEmailContent(
			&$people_content): static
	{
		$this->people_content = $people_content;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getO356PeopleRef(): array
	{
		return $this->people_o365_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setO356PeopleRef(
			&$people_o365_ref): static
	{
		$this->people_o365_ref = $people_o365_ref;
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
		$help [__CLASS__] ["text"] [] .= "People :";
		return $help;
	}
}
