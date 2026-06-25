<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Location
 *
 * @package Lib
 * @subpackage itop
 */
class Location extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization
	 */
	private $Organization = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Location. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Location
	 * @throws Exception
	 */
	static function &creer_Location(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): Location
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Location ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Location
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Location' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * @return Location
	 */
	public function &champ_obligatoire_standard(): static
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'org_id' => false
			) );
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_Location(
			$name,
			$org_name): ci|bool|Location
	{
				if (is_array ( $name )) {
					return $this->creer_oql ( $name )
					->retrouve_ci ();
				}
				return $this->creer_oql ( array (
						'name' => $name,
						'org_name' => $org_name
				) )
				->retrouve_ci ();
	}
	

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_Location(
		array $parametres): array
	{
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Location
	 */
	public function creer_oql_Location(
		array $fields = array ()): Location
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
	 * name, org_name, status, postal_code,city,country,address
	 * @param array $parametres
	 * @return Location
	 * @throws Exception
	 */
	public function gestion_Location(
		array $parametres): Location
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Location( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_Location ( $parametres )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopOrganization(): ?Organization
	{
		return $this->Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization(
			&$Organization): static
	{
		$this->Organization = $Organization;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Location :";
		return $help;
	}
}
