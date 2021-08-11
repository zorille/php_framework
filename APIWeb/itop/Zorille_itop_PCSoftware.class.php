<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Zorille\framework as Core;

/**
 * class PCSoftware
 *
 * @package Lib
 * @subpackage itop
 */
class PCSoftware extends FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Software
	 */
	private $Software = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type PCSoftware. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return PCSoftware
	 */
	static function &creer_PCSoftware(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new PCSoftware ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return PCSoftware
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'PCSoftware' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopSoftware ( Software::creer_Software ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	public function &champ_obligatoire_standard() {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'org_id' => false,
					'system_id' => false,
					'software_id' => false
			) );
		}
		return $this;
	}

	public function retrouve_PCSoftware(
			$name,
			$server_name) {
		return $this->creer_oql ( array (
				'friendlyname' => $name,
				'server_name' => $server_name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_PCSoftware(
			$parametres) {
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'system_name' :
					$params ['system_id'] = $this->creer_oql_FunctionalCI ( array (
							'Server',
							'VirtualMachine',
							'PC'
					), $valeur )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'system_id', $params ['system_id'] );
					if (isset ( $params ['system_name'] )) {
						unset ( $params ['system_name'] );
					}
					break;
				case 'software_name' :
					$params ['software_id'] = $this->getObjetItopSoftware ()
						->creer_oql ( array (
							'name' => $valeur,
							'vendor' => $params ['software_vendor'],
							'version' => $params ['software_version']
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'software_id', $params ['software_id'] );
					if (isset ( $params ['software_name'] )) {
						unset ( $params ['software_name'] );
					}
					if (isset ( $params ['software_vendor'] )) {
						unset ( $params ['software_vendor'] );
					}
					if (isset ( $params ['software_version'] )) {
						unset ( $params ['software_version'] );
					}
					break;
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return PCSoftware
	 */
	public function creer_oql_PCSoftware(
			$fields = array ()) {
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'org_id' :
					$filtre ['org_name'] = $fields ['org_name'];
					break;
				case 'system_id' :
					$filtre ['system_name'] = $fields ['system_name'];
					break;
				case 'software_id' :
					$filtre ['software_name'] = $fields ['software_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs standards : name, org_name, status, business_criticity, path, move2production, system_name, software_name, software_vendor, software_version
	 * @return DBServer
	 */
	public function gestion_PCSoftware(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_PCSoftware ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_PCSoftware ( $parametres )
			->creer_ci ( $params ['name'] . " " . $params ['system_name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Software
	 */
	public function &getObjetItopSoftware() {
		return $this->Software;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopSoftware(
			&$Software) {
		$this->Software = $Software;
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
		$help [__CLASS__] ["text"] [] .= "PCSoftware :";
		return $help;
	}
}
?>
