<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;

/**
 * class Host
 *
 * @package Lib
 * @subpackage coservit
 */
class Host extends Hosts {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Host. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return $this
	 */
	static function &creer_Host(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Host ( $sort_en_erreur, $entete );
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
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->champ_obligatoire_standard ()
			->setFormat ( 'Host' );
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
	 * { "host_alias": "Host 123", "host_mode": "box", "host_address": "127.1.2.3", "company": 2, "collector": 3, "host_category": 0, "host_templates": [ 123, 456, 789 ], "business_impact": 0, "tags": [ 14, 25, 96 ], "instructions": "Lorem Ipsum instructionum.", "description": "Lorem Ipsum descriptum.", "documentation": "Lorem Ipsum documentatum.", "availability_rate": 95.5, "availability_time_period": 1, "check_time_period": 1, "normal_check_interval": 4, "max_check_attempts": 3, "retry_check_interval": 5, "check_template": 12, "check_command_arguments": [ { "value": "8", "name": "Seuil d'alerte (ms)" }, { "value": "10", "name": "Seuil d'alerte (%)" }, { "value": "20", "name": "Seuil critique (%)" }, { "value": "5", "name": "Nombre" }, { "value": "56", "name": "Taille" } ], "active_checks_enabled": false, "passive_checks_enabled": false, "notifications": { "enabled": true, "interval": 3, "time_period": 4, "options": [ "d", "r" ], "contacts": [ 23, 45 ], "contact_groups": [ 23, 45 ], "low_flap_threshold": 90, "high_flap_threshold": 95 }, "escalations": [ { "level": 2, "first_notification": 5, "notification_interval": 4, "contacts": [ 23, 45 ], "contact_groups": [ 23, 45 ] } ], "monitoring_account_overloaded": true, "parent_hosts": [ 56, 756 ], "children_hosts": [ 42, 777 ], "action_template": 19, "action_command_arguments": [ { "value": "supervision", "name": "Nom Application" }, { "value": "%CISTATE%;%HOSTALIAS%", "name": "Format Syslog" }, { "value": "", "name": "Maintenance" }, { "value": "", "name": "Etat de déclenchement" }, { "value": "", "name": "Type d'état de déclenchement" } ], "auto_handle_services": true, "additional_data": "Lorem Ipsum additionnal data.", "itsm_id": "19" }
	 */
	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return $this
	 */
	public function &champ_obligatoire_standard() {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'host_alias' => false,
					"host_address" => false,
					"company" => false,
					"collector" => false,
					'host_mode' => false,
					'host_category' => false,
					'availability_rate' => false,
					'normal_check_interval' => false,
					'check_template' => false
			) );
		}
		return $this;
	}

	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format coservit
	 */
	public function prepare_params_Host(
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
	 * ******************************* Host URI ******************************
	 */
	public function host_id_uri() {
		if ($this->valide_item_id () == false) {
			return $this->onError ( "Il n'y pas d'id de Host selectionne" );
		}
		return $this->hosts_list_uri () . '/' . $this->getId ();
	}

	/**
	 * ******************************* Coservit Host *********************************
	 */
	public function retrouve_host(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getId () )) {
			return $this->onError ( "Il faut un ID pour recuperer les donnees d'un host", "", 1 );
		}
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->host_id_uri (), $params );
		if (isset ( $resultat->id )) {
			$this->setId ( $resultat->id );
		}
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return $this
	 */
	public function creerHost(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Host ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->postMethod ( $this->hosts_list_uri (), $params );
		if (isset ( $resultat->id )) {
			$this->setId ( $resultat->id );
		}
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Update un host de la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return $this
	 */
	public function updateHost(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Host ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->putMethod ( $this->host_id_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Delete un host
	 * @return $this
	 */
	public function deleteHost() {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->deleteMethod ( $this->host_id_uri (), array () );
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
		$help [__CLASS__] ["text"] [] .= "Host :";
		return $help;
	}
}
?>
