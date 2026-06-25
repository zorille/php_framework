<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class CrmAssets
 *
 * @package Lib
 * @subpackage itop
 */
class CrmAssets extends FunctionalCI {
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type CrmAssets. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return self
	 */
	static function &creer_CrmAssets(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
					  $sort_en_erreur = false,
		string        $entete = __CLASS__
	): self {
		Core\abstract_log::onDebug_standard( __METHOD__, 1 );
		$objet = new static($sort_en_erreur, $entete);
		$objet->_initialise([
			"options" => $liste_option,
			"wsclient_rest" => $webservice_rest
		]);
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return self
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise( $liste_class );

		return $this->setFormat('CrmAssets')
			->champ_obligatoire_standard()
			->setObjetItopOrganization(
				Organization::creer_Organization(
					$liste_class['options'],
					$liste_class ['wsclient_rest']
				)
			);
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
		$entete = __CLASS__
	)
	{
		// Gestion de serveur_datas
		parent::__construct( $sort_en_erreur, $entete );
	}

	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return self
	 */
	public function &champ_obligatoire_standard(): self
	{
		if (empty($this->getMandatory())) {
			$this->setMandatory([
				'name' => false,
				'org_id' => false
			]);
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_CrmAssets(string $name): self
	{
		return $this->creer_oql(['friendlyname' => $name])->retrouve_ci();
	}

	/**
	 * @throws Exception
	 */
	public function get_CrmAssets_from_model(data_models\CrmAsset $crmAsset): self
	{
		return $this->setDonnees($crmAsset->toArray())
			->setFormat($crmAsset->getFinalclass())
			->setId($crmAsset->getId())
			->retrouve_ci();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parameters
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_CrmAssets(array $parameters): array
	{
		return $this->prepare_standard_params($parameters);
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return self
	 */
	public function creer_oql_CrmAssets(array $fields = []): self
	{
		$filtre = [];
		foreach ($this->getMandatory() as $field => $inutile) {
			switch ($field) {
				case 'org_id' :
					$filtre['org_name'] = $fields['org_name'];
					break;
				default :
					$filtre[$field] = $fields[$field];
			}
		}
		return parent::creer_oql($filtre);
	}

	/**
	 * Champs Standards : name, org_name, status, move2production
	 * @return CrmAssets
	 * @throws Exception
	 */
	public function gestion_CrmAssets(array $parameters) {
		$this->onDebug(__METHOD__, 1);
		$params = $this->prepare_params_CrmAssets($parameters);
		$this->onDebug($params, 1);
		return $this->valide_mandatory_fields()
			->creer_oql_CrmAssets($parameters)
			->creer_ci($params ['name'], $params);
	}

	public function creer_lnkFunctionalCIToTicket(string $ticket_id = ''): array
	{
		$lnkFunctionalCIToTicket = $this->creer_lnkToFunctionalCI();
		if (!empty($ticket_id)) {
			$lnkFunctionalCIToTicket['ticket_id'] = $ticket_id;
		}
		$lnkFunctionalCIToTicket['functionalci_id'] = !empty($this->getDonnees()['id']) ? $this->getDonnees()['id'] : $this->getId();
		$lnkFunctionalCIToTicket['impact_code'] = 'computed';

		return $lnkFunctionalCIToTicket;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help();
		$help [__CLASS__] ["text"] = [];
		$help [__CLASS__] ["text"] [] .= "CrmAssets :";
		return $help;
	}
}
