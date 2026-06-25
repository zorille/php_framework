<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class ServiceFamily
 *
 * @package Lib
 * @subpackage itop
 */
class ServiceFamily extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Service
	 */
	private $Service = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Instancie un objet de type ServiceFamily.
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ServiceFamily
	 */
	static function &creer_ServiceFamily(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): ServiceFamily
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ServiceFamily ( $sort_en_erreur, $entete );
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
	 * @return ServiceFamily|Organization
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'ServiceFamily' )
			->champ_obligatoire_standard ();
		// L'instanciation de Service genere une boucle infini avec ServiceFamily. Donc on lie le ServiceFamily uniquement dans le Service
		// ->setObjetItopService ( Service::creer_Service ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * @return ServiceFamily
	 */
	public function &champ_obligatoire_standard(): static
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false
			) );
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_ServiceFamily(
			$name): ci|bool|ServiceFamily
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
	public function prepare_params_ServiceFamily(
		array $parametres): array
	{
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return ServiceFamily
	 */
	public function creer_oql_ServiceFamily(
		array $fields = array ()): ServiceFamily
	{
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			$filtre [$field] = match ($field) {
				default => $fields [$field],
			};
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Creer une entree ServiceFamily Champs standards : name
	 * @param $parametres
	 * @return ServiceFamily
	 * @throws Exception
	 */
	public function gestion_ServiceFamily(
			$parametres): ServiceFamily
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_ServiceFamily ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_ServiceFamily ( $parametres )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Service|null
	 */
	public function &getObjetItopService(): ?Service
	{
		return $this->Service;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopService(
			&$Service): static
	{
		$this->Service = $Service;
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
		$help [__CLASS__] ["text"] [] .= "ServiceFamily :";
		return $help;
	}
}
