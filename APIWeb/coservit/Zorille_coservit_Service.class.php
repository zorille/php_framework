<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;

/**
 * class Service
 *
 * @package Lib
 * @subpackage coservit
 */
class Service extends Services {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Service. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Service
	 */
	static function &creer_Service(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Service ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Service
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->champ_obligatoire_standard ()
			->setFormat ( 'Service' );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * { "service_alias": "Service 123", "service_mode": "box", "service_address": "127.1.2.3", "company": 2, "collector": 3, "service_category": 0, "service_templates": [ 123, 456, 789 ], "business_impact": 0, "tags": [ 14, 25, 96 ], "instructions": "Lorem Ipsum instructionum.", "description": "Lorem Ipsum descriptum.", "documentation": "Lorem Ipsum documentatum.", "availability_rate": 95.5, "availability_time_period": 1, "check_time_period": 1, "normal_check_interval": 4, "max_check_attempts": 3, "retry_check_interval": 5, "check_template": 12, "check_command_arguments": [ { "value": "8", "name": "Seuil d'alerte (ms)" }, { "value": "10", "name": "Seuil d'alerte (%)" }, { "value": "20", "name": "Seuil critique (%)" }, { "value": "5", "name": "Nombre" }, { "value": "56", "name": "Taille" } ], "active_checks_enabled": false, "passive_checks_enabled": false, "notifications": { "enabled": true, "interval": 3, "time_period": 4, "options": [ "d", "r" ], "contacts": [ 23, 45 ], "contact_groups": [ 23, 45 ], "low_flap_threshold": 90, "high_flap_threshold": 95 }, "escalations": [ { "level": 2, "first_notification": 5, "notification_interval": 4, "contacts": [ 23, 45 ], "contact_groups": [ 23, 45 ] } ], "monitoring_account_overloaded": true, "parent_services": [ 56, 756 ], "children_services": [ 42, 777 ], "action_template": 19, "action_command_arguments": [ { "value": "supervision", "name": "Nom Application" }, { "value": "%CISTATE%;%HOSTALIAS%", "name": "Format Syslog" }, { "value": "", "name": "Maintenance" }, { "value": "", "name": "Etat de déclenchement" }, { "value": "", "name": "Type d'état de déclenchement" } ], "auto_handle_services": true, "additional_data": "Lorem Ipsum additionnal data.", "itsm_id": "19" }
	 */
	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return Service
	 */
	public function &champ_obligatoire_standard() {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'host' => false,
					"name" => false,
					"service_template" => false,
					"check_command_arguments" => false
			) );
		}
		return $this;
	}

	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format coservit
	 */
	public function prepare_params_Service(
			$parametres) {
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				default :
			}
		}
		return $params;
	}

	/**
	 * ******************************* Service URI ******************************
	 */
	public function service_id_uri() {
		if ($this->valide_item_id () == false) {
			return $this->onError ( "Il n'y pas d'id de Service selectionne" );
		}
		return $this->services_list_uri () . '/' . $this->getId ();
	}

	/**
	 * ******************************* Coservit Service *********************************
	 */
	public function retrouve_service(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getId () )) {
			return $this->onError ( "Il faut un ID pour recuperer les donnees d'un service", "", 1 );
		}
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->service_id_uri (), $params );
		if (isset ( $resultat->id )) {
			$this->setId ( $resultat->id );
		}
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Creer un service la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande service. (parametres obligatoires) : 'service_alias',"service_address","company","collector"
	 * @return $this
	 */
	public function creerService(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Service ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->postMethod ( $this->services_list_uri (), $params );
		if (isset ( $resultat->service->id )) {
			$this->setId ( $resultat->service->id );
		}
		return $this;
	}

	/**
	 * Creer un service la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande service. (parametres obligatoires) : 'service_alias',"service_address","company","collector"
	 * @return $this
	 */
	public function updateService(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Service ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->putMethod ( $this->service_id_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Delete un service
	 * @return $this
	 */
	public function deleteService() {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->deleteMethod ( $this->service_id_uri (), array () );
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
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Service :";
		return $help;
	}
}
?>
