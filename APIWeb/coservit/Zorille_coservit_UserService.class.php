<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;

/**
 * class UserService
 *
 * @package Lib
 * @subpackage coservit
 */
class UserService extends UserServices {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type UserService. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webuserService_rest Reference sur un objet webuserService_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return UserService
	 */
	static function &creer_UserService(
			&$liste_option,
			&$webuserService_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new UserService ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webuserService_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return UserService
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->champ_obligatoire_standard ()
			->setFormat ( 'UserService' );
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
	 * { "userService_alias": "UserService 123", "userService_mode": "box", "userService_address": "127.1.2.3", "company": 2, "collector": 3, "userService_category": 0, "userService_templates": [ 123, 456, 789 ], "business_impact": 0, "tags": [ 14, 25, 96 ], "instructions": "Lorem Ipsum instructionum.", "description": "Lorem Ipsum descriptum.", "documentation": "Lorem Ipsum documentatum.", "availability_rate": 95.5, "availability_time_period": 1, "check_time_period": 1, "normal_check_interval": 4, "max_check_attempts": 3, "retry_check_interval": 5, "check_template": 12, "check_command_arguments": [ { "value": "8", "name": "Seuil d'alerte (ms)" }, { "value": "10", "name": "Seuil d'alerte (%)" }, { "value": "20", "name": "Seuil critique (%)" }, { "value": "5", "name": "Nombre" }, { "value": "56", "name": "Taille" } ], "active_checks_enabled": false, "passive_checks_enabled": false, "notifications": { "enabled": true, "interval": 3, "time_period": 4, "options": [ "d", "r" ], "contacts": [ 23, 45 ], "contact_groups": [ 23, 45 ], "low_flap_threshold": 90, "high_flap_threshold": 95 }, "escalations": [ { "level": 2, "first_notification": 5, "notification_interval": 4, "contacts": [ 23, 45 ], "contact_groups": [ 23, 45 ] } ], "monitoring_account_overloaded": true, "parent_userServices": [ 56, 756 ], "children_userServices": [ 42, 777 ], "action_template": 19, "action_command_arguments": [ { "value": "supervision", "name": "Nom Application" }, { "value": "%CISTATE%;%HOSTALIAS%", "name": "Format Syslog" }, { "value": "", "name": "Maintenance" }, { "value": "", "name": "Etat de déclenchement" }, { "value": "", "name": "Type d'état de déclenchement" } ], "auto_handle_userServices": true, "additional_data": "Lorem Ipsum additionnal data.", "itsm_id": "19" }
	 */
	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return UserService
	 */
	public function &champ_obligatoire_standard() {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
				// 'host' => false,
			) );
		}
		return $this;
	}

	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format coservit
	 */
	public function prepare_params_UserService(
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
	 * ******************************* UserService URI ******************************
	 */
	public function userService_id_uri() {
		if ($this->valide_item_id () == false) {
			return $this->onError ( "Il n'y pas d'id de UserService selectionne" );
		}
		return $this->userServices_uri () . '/' . $this->getId ();
	}

	/**
	 * ******************************* Coservit UserService *********************************
	 */
	/**
	 * Retrouve tous les user-Services la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande userService. (parametres obligatoires) : 'userService_alias',"userService_address","company","collector"
	 * @return $this
	 */
	public function retrouve_UserServicesList(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setMandatory ( array (
				"companies" => false
		) );
		$this->onDebug ( $parametres, 1 );
		$params = $this->prepare_params_UserService ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->postMethod ( $this->userServices_lists_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Creer un userService la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande userService. (parametres obligatoires) : 'userService_alias',"userService_address","company","collector"
	 * @return $this
	 */
	public function creerUserService(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
// 		name*	string	example: User Service
// 		description	string	example: Lorem Ipsum descriptionum.
// 		company*	integer		example: 2
// 		business_impact	integer	example: 0		pattern: (\d+) 		0: low 		1: medium 		2: high
// 		tags	[...]
// 		availability_rate*	float		example: 95.5		maximum: 100
// 		Availability rate float
// 		availability_time_period*	integer 		example: 1
// 		shared	boolean		example: false 		Set if the user service is shared
// 		displayed	boolean 		example: false 		Set if the user service is displayed
// 		notifications	Notifications{...}
// 		blocking_hosts	[...]
// 		degrading_hosts	[...]
// 		blocking_unit_services	[...]
// 		degrading_unit_services	[...]
// 		blocking_user_services	[...]
// 		degrading_user_services	[...]
		$this->setMandatory ( array (
				"company" => false,
				"name" => false,
				"availability_rate" => false,
				"availability_time_period" =>false
		) );
		$params = $this->prepare_params_UserService ( $parametres );
		$this->onDebug ( $params, 1 );
		$this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->postMethod ( $this->userServices_uri (), $params );
		return $this;
	}

	/**
	 * Creer un userService la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande userService. (parametres obligatoires) : 'userService_alias',"userService_address","company","collector"
	 * @return $this
	 */
	public function updateUserService(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_UserService ( $parametres );
		$this->onDebug ( $params, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->putMethod ( $this->userService_id_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Delete un userService
	 * @return $this
	 */
	public function deleteUserService() {
		$this->onDebug ( __METHOD__, 1 );
		// $resultat = $this->getObjetCoservitWsclient ()
		// ->deleteMethod ( $this->userService_id_uri (), array () );
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
		$help [__CLASS__] ["text"] [] .= "UserService :";
		return $help;
	}
}
?>
