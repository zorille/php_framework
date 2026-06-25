<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Exception;
use Zorille\framework as Core;

/**
 * class Group
 *
 * @package Lib
 * @subpackage coservit
 */
class Group extends Groups {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Group. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return $this
	 */
	static function &creer_Group(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): Group|static {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Group ( $sort_en_erreur, $entete );
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
	 */
	public function &_initialise(
		array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->champ_obligatoire_standard ()
			->setFormat ( 'Group' );
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
	 * @return $this
	 */
	public function &champ_obligatoire_standard(): static {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
				// 'group_alias' => false,
			) );
		}
		return $this;
	}

	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format coservit
	 */
	public function prepare_params_Group(
		array $parametres): array {
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * ******************************* Group URI ******************************
	 */
	/**
	 * @throws Exception
	 */
	public function group_id_uri(): bool|string {
		if (!$this->valide_item_id()) {
			return $this->onError ( "Il n'y pas d'id de Group selectionne" );
		}
		return $this->groups_list_uri () . '/' . $this->getId ();
	}

	/**
	 * @throws Exception
	 */
	public function group_metrics_uri(): string {
		return $this->group_id_uri () . '/metrics';
	}

	/**
	 * @throws Exception
	 */
	public function group_measurement_uri(
			$metricUuid): bool|string {
		if (empty ( $metricUuid )) {
			return $this->onError ( "Il faut un Metric-Uuid pour recuperer les donnees de mesure" );
		}
		return $this->group_metrics_uri () . '/' . $metricUuid . "/measurements";
	}

	/**
	 * ******************************* Coservit Group *********************************
	 */
	/**
	 * @throws Exception
	 */
	public function retrouve_group(
			$params = array ()): Group {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->group_id_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Creer un group en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande group. (parametres obligatoires) : 'group_alias',"group_address","company","collector"
	 * @return $this
	 * @throws Exception
	 */
	public function creerGroup(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Group ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->postMethod ( $this->groups_list_uri (), $params );
		if (isset ( $resultat->id )) {
			$this->setId ( $resultat->id );
		}
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Update un group de la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande group. (parametres obligatoires) : 'group_alias',"group_address","company","collector"
	 * @return $this
	 * @throws Exception
	 */
	public function updateGroup(
			$parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Group ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->putMethod ( $this->group_id_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * ******************************* Metrics *********************************
	 */

	/**
	 * @throws Exception
	 */
	public function retrouve_group_metrics(
			$params = array ()): Group {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->group_metrics_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_group_measurement(
			$metricUuid,
			$params = array ()): Group {
		$this->onDebug ( __METHOD__, 1 );
		$this->setMandatory ( array (
				'date_start' => false,
				'date_stop' => false
		) );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->group_measurement_uri ( $metricUuid ), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Group :";
		return $help;
	}
}
