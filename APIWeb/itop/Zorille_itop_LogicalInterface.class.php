<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Zorille\framework as Core;

/**
 * class LogicalInterface
 *
 * @package Lib
 * @subpackage itop
 */
class LogicalInterface extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var VirtualMachine
	 */
	private $VirtualMachine = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type LogicalInterface. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return LogicalInterface
	 */
	static function &creer_LogicalInterface(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new LogicalInterface ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return LogicalInterface
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'LogicalInterface' )
			->champ_obligatoire_standard ()
			->setObjetItopVirtualMachine ( VirtualMachine::creer_VirtualMachine ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
					'virtualmachine_id' => false
			) );
		}
		return $this;
	}

	public function retrouve_LogicalInterface(
			$name,
			$server_name) {
		return $this->creer_oql ( array (
				'name' => $name,
				'virtualmachine_name' => $server_name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_LogicalInterface(
			$parametres) {
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'virtualmachine_name' :
					$params ['virtualmachine_id'] = $this->getObjetItopVirtualMachine ()
						->creer_oql ( array (
							'fiendlyname' => $valeur,
							'org_id' => $params ['org_id']
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'virtualmachine_id', $params ['virtualmachine_id'] );
					if (isset ( $params ['virtualmachine_name'] )) {
						unset ( $params ['virtualmachine_name'] );
					}
					break;
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return LogicalInterface
	 */
	public function creer_oql_LogicalInterface(
			$fields = array ()) {
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'virtualmachine_id' :
					$filtre ['virtualmachine_name'] = $fields ['virtualmachine_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs standards : name, virtualmachine_name, ipaddress, macaddress, ipgateway, ipmask
	 * @return LogicalInterface
	 */
	public function gestion_LogicalInterface(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_LogicalInterface ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_LogicalInterface ( $parametres )
			->creer_ci ( $params ['name'] . " " . $params ['virtualmachine_name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return VirtualMachine
	 */
	public function &getObjetItopVirtualMachine() {
		return $this->VirtualMachine;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopVirtualMachine(
			&$VirtualMachine) {
		$this->VirtualMachine = $VirtualMachine;
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
		$help [__CLASS__] ["text"] [] .= "LogicalInterface :";
		return $help;
	}
}
?>
