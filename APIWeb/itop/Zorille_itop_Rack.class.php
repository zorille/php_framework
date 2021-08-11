<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Zorille\framework as Core;

/**
 * class Rack
 *
 * @package Lib
 * @subpackage itop
 */
class Rack extends FunctionalCI {
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
	 * Instancie un objet de type Rack. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Rack
	 */
	static function &creer_Rack(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Rack ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Rack
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Rack' )
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
	 * @return Organization
	 */
	public function champ_obligatoire_standard() {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'ord_id' => false
			) );
		}
		return $this;
	}

	public function retrouve_Rack(
			$name,
			$org_name) {
		return $this->creer_oql ( array (
				'friendlyname' => $name,
				'org_name' => $org_name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_Rack(
			$parametres) {
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'location_name' :
					$params ['location_id'] = $this->getObjetItopLocation ()
						->creer_oql ( array (
							'fiendlyname' => $valeur,
							'org_id' => $params ['org_id']
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'location_id', $params ['location_id'] );
					if (isset ( $params ['location_name'] )) {
						unset ( $params ['location_name'] );
					}
					break;
				case 'contacts_list' :
					$params ['contacts_list'] = array ();
					foreach ( $valeur as $contact ) {
						$params ['contacts_list'] [count ( $params ['contacts_list'] )] = $this->creer_lnkContactToFunctionalCI ( $contact ["id"] );
					}
					break;
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Rack
	 */
	public function creer_oql_Rack(
			$fields = array ()) {
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'org_id' :
					if (isset ( $fields ['organization_name'] )) {
						$filtre ['organization_name'] = $fields ['organization_name'];
					} else {
						$filtre ['organization_name'] = $fields ['org_name'];
					}
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
	 * Champs standards : name, organization_name/org_name, location_name, contacts_list
	 * @param array $parametres
	 * @return \Zorille\itop\Rack
	 */
	public function gestion_Rack(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Rack ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_Rack ( $parametres )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Location
	 */
	public function &getObjetItopLocation() {
		return $this->Location;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopLocation(
			&$Location) {
		$this->Location = $Location;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Rack :";
		return $help;
	}
}
?>
