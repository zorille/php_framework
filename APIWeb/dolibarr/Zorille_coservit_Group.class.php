<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

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
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return $this
	 */
	static function &creer_Group(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
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
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->champ_obligatoire_standard ()
			->setFormat ( 'Group' );
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
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return $this
	 */
	public function &champ_obligatoire_standard() {
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
	 * ******************************* Group URI ******************************
	 */
	public function group_id_uri() {
		if ($this->valide_item_id () == false) {
			return $this->onError ( "Il n'y pas d'id de Group selectionne" );
		}
		return $this->groups_list_uri () . '/' . $this->getId ();
	}

	public function group_metrics_uri() {
		return $this->group_id_uri () . '/metrics';
	}

	public function group_measurement_uri(
			$metricUuid) {
		if (empty ( $metricUuid )) {
			return $this->onError ( "Il faut un Metric-Uuid pour recuperer les donnees de mesure", "", 1 );
		}
		return $this->group_metrics_uri () . '/' . $metricUuid . "/measurements";
	}

	/**
	 * ******************************* Coservit Group *********************************
	 */
	public function retrouve_group(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->group_id_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Creer un group en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande group. (parametres obligatoires) : 'group_alias',"group_address","company","collector"
	 * @return $this
	 */
	public function creerGroup(
			$parametres) {
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
	 */
	public function updateGroup(
			$parametres) {
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
	public function retrouve_group_metrics(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->group_metrics_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	public function retrouve_group_measurement(
			$metricUuid,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setMandatory ( array (
				'date_start' => false,
				'date_stop' => false,
		) );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->group_measurement_uri ( $metricUuid ), $params );
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
		$help [__CLASS__] ["text"] [] .= "Group :";
		return $help;
	}
}
?>
