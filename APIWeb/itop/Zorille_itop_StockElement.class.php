<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class StockElement
 *
 * @package Lib
 * @subpackage itop
 */
class StockElement extends FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Location
	 */
	private $Location = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type StockElement. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return StockElement
	 */
	static function &creer_StockElement(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): StockElement
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new StockElement ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return StockElement
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'StockElement' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopLocation ( Location::creer_Location ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return StockElement
	 */
	public function &champ_obligatoire_standard(): static
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'org_id' => false,
					'location_id' => false
			) );
		}
		return $this;
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_StockElement(
		array $parametres): array
	{
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			if ($champ == 'location_name') {
				$params ['location_id'] = $this->getObjetItopLocation()
					->prepare_params_obligatoire(array(
						'name' => $parametres ['location_name'],
						'org_id' => $params ['org_id']
					));
				$this->valide_mandatory_field_filled('location_id', $params ['location_id']);
				if (isset ($params ['location_name'])) {
					unset ($params ['location_name']);
				}
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return StockElement
	 */
	public function creer_oql_StockElement(
		array $fields = array ()): StockElement
	{
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'org_id' :
					$filtre ['org_name'] = $fields ['org_name'];
					break;
				case 'location_id' :
					$filtre ['location_name'] = $fields ['location_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_StockElement(
			$name): ci|StockElement|bool
	{
		if (is_array ( $name )) {
			return $this->creer_oql ( $name )
				->retrouve_ci ();
		}
		return $this->creer_oql ( array (
				'name' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * name, description, org_name, location_name, picture, current_quantity, threshold
	 * @param array $parametres
	 * @return StockElement
	 * @throws Exception
	 */
	public function gestion_StockElement(
		array $parametres): StockElement
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_StockElement ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_StockElement ( $parametres )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Location|null
	 */
	public function &getObjetItopLocation(): ?Location
	{
		return $this->Location;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopLocation(
			&$Location): static
	{
		$this->Location = $Location;
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
		$help [__CLASS__] ["text"] [] .= "StockElement :";
		return $help;
	}
}
