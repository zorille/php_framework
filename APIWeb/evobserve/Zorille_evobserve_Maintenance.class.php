<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

use Exception;
use Zorille\framework as Core;

/**
 * class Maintenance
 *
 * @package Lib
 * @subpackage evobserve
 */
class Maintenance extends Maintenances {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Maintenance. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Maintenance
	 * @throws Exception
	 */
	static function &creer_Maintenance(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): Maintenance|static {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Maintenance ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return $this
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->champ_obligatoire_standard ()
			->setFormat ( 'Maintenance' );
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
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return $this
	 */
	public function &champ_obligatoire_standard(): static {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'company' => false,
					'hosts' => false,
					'requester' => false,
					'start' => false,
					'end' => false
			) );
		}
		return $this;
	}

	/**
	 * Prepare les parametres standards d'un objet Maintenance.
	 * @param array $parametres
	 * @return array liste des parametres au format evobserve
	 */
	public function prepare_params_Maintenance(
		array $parametres): array {
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * Separe les parametres d'URL et le body pour l'endpoint /maintenances/lists.
	 * Query params supportes : dateStart, dateEnd, status, status[], limit, page, sort, sort[]
	 * Body attendu : companyIds
	 * @param array $parametres
	 * @return array{query: array, body: array}
	 */
	protected function prepare_params_MaintenancesList(
		array $parametres): array {
		$query = array ();
		$body = array ();
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'dateStart' :
				case 'dateEnd' :
				case 'limit' :
				case 'page' :
				case 'status' :
				case 'status[]' :
				case 'sort' :
				case 'sort[]' :
					$query [$champ] = $valeur;
					$this->valide_mandatory_field_filled ( $champ, $valeur );
					break;
				default :
					$body [$champ] = $valeur;
					$this->valide_mandatory_field_filled ( $champ, $valeur );
			}
		}
		return array (
				'query' => $query,
				'body' => $body
		);
	}

	/**
	 * ******************************* Maintenance URI ******************************
	 */

	/**
	 * @throws Exception
	 */
	public function maintenance_id_uri(): bool|string {
		if (!$this->valide_item_id()) {
			return $this->onError ( "Il n'y pas d'id de Maintenance selectionne" );
		}
		return $this->maintenances_uri () . '/' . $this->getId ();
	}

	/**
	 * ******************************* Evobserve Maintenance *********************************
	 */

	/**
	 * Retrouve une liste de maintenances.
	 * Parametres obligatoires dans la query : dateStart, dateEnd, status[]. Body : companyIds.
	 * @param array $parametres
	 * @return $this
	 * @throws Exception
	 */
	public function retrouve_MaintenancesList(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$this->setMandatory ( array (
				'dateStart' => false,
				'dateEnd' => false,
				'status[]' => false,
				'companyIds' => false
		) );
		$params = $this->prepare_params_MaintenancesList ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetEvobserveWsclient ()
			->postMethod ( $this->maintenances_lists_uri ( $params ['query'] ), $params ['body'] );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Creer une maintenance.
	 * @param array $parametres Parametres de creation : company, hosts, unit_services, requester, start, end, etc.
	 * @return $this
	 * @throws Exception
	 */
	public function creerMaintenance(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Maintenance ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetEvobserveWsclient ()
			->postMethod ( $this->maintenances_create_uri (), $params );
		if (isset ( $resultat->id )) {
			$this->setId ( $resultat->id );
		}
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Supprime une maintenance.
	 * @return $this
	 * @throws Exception
	 */
	public function deleteMaintenance(): static {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetEvobserveWsclient ()
			->deleteMethod ( $this->maintenance_id_uri (), array () );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Retrouve les maintenances d'un host.
	 * @param int|string $host Identifiant du host
	 * @param array $parametres Query params optionnels : status[]
	 * @return $this
	 * @throws Exception
	 */
	public function retrouve_HostMaintenances(
		int|string $host,
		array      $parametres = array ()): static {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetEvobserveWsclient ()
			->getMethod ( $this->host_maintenances_uri ( $host, $parametres ) );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Supprime un host d'une maintenance.
	 * @param int|string $host Identifiant du host
	 * @param int|string|null $maintenance Identifiant de la maintenance, utilise getId() si null
	 * @return $this
	 * @throws Exception
	 */
	public function deleteHostMaintenance(
		int|string|null $host,
		int|string|null $maintenance = null): static {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $maintenance )) {
			if (!$this->valide_item_id()) {
				return $this->onError ( "Il faut un ID de Maintenance pour supprimer le lien host/maintenance" );
			}
			$maintenance = $this->getId ();
		}
		$resultat = $this->getObjetEvobserveWsclient ()
			->deleteMethod ( $this->host_maintenance_id_uri ( $host, $maintenance ), array () );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Retrouve les maintenances d'un service.
	 * @param int|string $service Identifiant du service
	 * @param array $parametres Query params optionnels : status[]
	 * @return $this
	 * @throws Exception
	 */
	public function retrouve_ServiceMaintenances(
		int|string $service,
		array      $parametres = array ()): static {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetEvobserveWsclient ()
			->getMethod ( $this->service_maintenances_uri ( $service, $parametres ) );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Supprime un service d'une maintenance.
	 * @param int|string $service Identifiant du service
	 * @param int|string|null $maintenance Identifiant de la maintenance, utilise getId() si null
	 * @return $this
	 * @throws Exception
	 */
	public function deleteServiceMaintenance(
		int|string|null $service,
		int|string|null $maintenance = null): static {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $maintenance )) {
			if (!$this->valide_item_id()) {
				return $this->onError ( "Il faut un ID de Maintenance pour supprimer le lien service/maintenance" );
			}
			$maintenance = $this->getId ();
		}
		$resultat = $this->getObjetEvobserveWsclient ()
			->deleteMethod ( $this->service_maintenance_id_uri ( $service, $maintenance ), array () );
		return $this->setDonnees ( $resultat );
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
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Maintenance :";
		return $help;
	}
}
