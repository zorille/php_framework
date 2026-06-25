<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class Service
 *
 * @package Lib
 * @subpackage itop
 */
class Service extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization
	 */
	private $Organization = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var ServiceFamily
	 */
	private $ServiceFamily = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Instancie un objet de type Service.
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Service
	 */
	static function &creer_Service(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): Service
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Service ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * @codeCoverageIgnore
	 * Initialisation de l'objet
	 * @param array $liste_class
	 * @return Service
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Service' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopServiceFamily ( ServiceFamily::creer_ServiceFamily ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * @return Service
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
	public function retrouve_Service(
			$name): ci|bool|Service
	{
		return $this->creer_oql ( array (
				'name' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_Service(
		array $parametres): array
	{
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			if ($champ == 'serviceFamily_name') {
				$params ['servicefamily_id'] = $this->getObjetItopServiceFamily()
					->creer_oql(array(
						'name' => $valeur
					))
					->getOqlCi();
				$this->valide_mandatory_field_filled('servicefamily_id', $params ['servicefamily_id']);
				if (isset ($params ['serviceFamily_name'])) {
					unset ($params ['serviceFamily_name']);
				}
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Service
	 */
	public function creer_oql_Service(
		array $fields = array ()): Service
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
	 * Creer une entree Service Champs standards : name, org_name, status, description, serviceFamily_name
	 * @return Service
	 * @throws Exception
	 */
	public function gestion_Service(
			$parametres): Service
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Service ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_Service ( $parametres )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization|null
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
	 * @codeCoverageIgnore
	 * @return ServiceFamily|null
	 */
	public function &getObjetItopServiceFamily(): ?ServiceFamily
	{
		return $this->ServiceFamily;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopServiceFamily(
			&$ServiceFamily): static
	{
		$this->ServiceFamily = $ServiceFamily;
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
		$help [__CLASS__] ["text"] [] .= "Service :";
		return $help;
	}
}
