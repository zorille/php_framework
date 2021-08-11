<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Zorille\framework as Core;

/**
 * class NetworkSocket
 *
 * @package Lib
 * @subpackage itop
 */
class NetworkSocket extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type NetworkSocket. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return NetworkSocket
	 */
	static function &creer_NetworkSocket(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new NetworkSocket ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return NetworkSocket
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'NetworkSocket' )
			->champ_obligatoire_standard ();
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
					'application_id' => false
			) );
		}
		return $this;
	}

	public function retrouve_NetworkSocket(
			$name) {
		return $this->creer_oql ( array (
				'fiendlyname' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_NetworkSocket(
			$parametres) {
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'software_friendlyname' :
					$params ['application_id'] = "SELECT FunctionalCI WHERE friendlyname = \"" . $valeur . '"';
					$this->valide_mandatory_field_filled ( 'application_id', $params ['application_id'] );
					if (isset ( $params ['software_friendlyname'] )) {
						unset ( $params ['software_friendlyname'] );
					}
					break;
				case 'logicalinterface_friendlyname' :
					$params ['networkinterface_id'] = 'SELECT IPInterface WHERE IPInterface.friendlyname = "' . $valeur . '"';
					$this->valide_mandatory_field_filled ( 'networkinterface_id', $params ['networkinterface_id'] );
					if (isset ( $params ['logicalinterface_friendlyname'] )) {
						unset ( $params ['logicalinterface_friendlyname'] );
					}
					break;
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return NetworkSocket
	 */
	public function creer_oql_NetworkSocket(
			$fields = array ()) {
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'application_id' :
					$filtre ['application_name'] = $fields ['application_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs standards : name, socketvalue, socketprotocol, software_friendlyname, logicalinterface_friendlyname
	 * @return NetworkSocket
	 */
	public function gestion_NetworkSocket(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_NetworkSocket ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_NetworkSocket ( $parametres )
			->creer_ci ( $params ['name'], $params );
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
		$help [__CLASS__] ["text"] [] .= "NetworkSocket :";
		return $help;
	}
}
?>
