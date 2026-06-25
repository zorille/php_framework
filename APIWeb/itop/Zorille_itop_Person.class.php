<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class Person
 *
 * @package Lib
 * @subpackage itop
 */
class Person extends Contact {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Team
	 */
	private $Team = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Person. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Person
	 */
	static function &creer_Person(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): Person
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Person ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Person
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Person' )
			->champ_obligatoire_standard ()
			->setObjetItopTeam ( Team::creer_Team ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return Person|UserLocal|Team
	 */
	public function &champ_obligatoire_standard(): Person|UserLocal|Team|static
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'first_name' => false,
					'email' => false,
					'org_id' => false
			) );
		}
		return $this;
	}

	/**
	 * @param string $name
	 * @param string $firstname
	 * @return Person
	 * @throws Exception
	 */
	public function retrouve_Person(
		string $name,
		string $firstname): Person
	{
		return $this->creer_oql ( array (
				'name' => $name,
				'first_name' => $firstname
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_Person(
		array $parametres): array
	{
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'team_name' :
					$params ['team_id'] = $this->getObjetItopTeam ()
						->creer_oql ( array (
							'name' => $valeur,
							'org_id' => $params ['org_id']
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'team_id', $params ['team_id'] );
					if (isset ( $params ['team_name'] )) {
						unset ( $params ['team_name'] );
					}
					break;
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Person
	 */
	public function creer_oql_Person(
		array $fields = array ()): Person
	{
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'org_id' :
					$filtre ['org_name'] = $fields ['org_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Récupère une personne existante suivant les critères données ou créer cette personne si elle n'existe pas 'org_name', 'team_name
	 * Champs standards : name, org_name,first_name, email, team_name
	 * @param array $parametres Liste des critères. Le nom de la case= le nom du champ itop, la valeur de la case est la valeur dans itop.
	 * @return Person
	 * @throws Exception
	 */
	public function gestion_Person(
		array $parametres): Person
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Person ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_Person ( $parametres )
			->creer_ci ( $params ['first_name'] . " " . $params ['name'] . " " . $params ['email'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Team|null
	 */
	public function &getObjetItopTeam(): ?Team
	{
		return $this->Team;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopTeam(
			&$Team): static
	{
		$this->Team = $Team;
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
		$help [__CLASS__] ["text"] [] .= "Person :";
		return $help;
	}
}
