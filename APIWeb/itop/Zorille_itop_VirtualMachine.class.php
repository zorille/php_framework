<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class VirtualMachine
 *
 * @package Lib
 * @subpackage itop
 */
class VirtualMachine extends FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Hypervisor
	 */
	private $Hypervisor = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var OSFamily
	 */
	private $OSFamily = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var OSVersion
	 */
	private $OSVersion = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type VirtualMachine. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachine
	 */
	static function &creer_VirtualMachine(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): VirtualMachine
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualMachine ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachine
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'VirtualMachine' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItophypervisor ( Hypervisor::creer_Hypervisor ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopOSFamily ( OSFamily::creer_OSFamily ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopOSVersion ( OSVersion::creer_OSVersion ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return VirtualMachine
	 */
	public function &champ_obligatoire_standard(): VirtualMachine
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'org_id' => false,
					'virtualhost_id' => false
			) );
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_VirtualMachine(
			$name): ci|bool|VirtualMachine
	{
		return $this->creer_oql ( array (
				'friendlyname' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_VirtualMachine(
		array $parametres): array
	{
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'hypervisor_name' :
					$params ['virtualhost_id'] = $this->getObjetItopHypervisor ()
						->creer_oql ( array (
							'fiendlyname' => $valeur,
							'org_id' => $params ['org_id']
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'virtualhost_id', $params ['virtualhost_id'] );
					if (isset ( $params ['hypervisor_name'] )) {
						unset ( $params ['hypervisor_name'] );
					}
					break;
				case 'osfamily_name' :
					$params ['osfamily_id'] = $this->getObjetItopOSFamily ()
						->creer_oql ( array (
							'name' => $valeur
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'osfamily_id', $params ['osfamily_id'] );
					if (isset ( $params ['osfamily_name'] )) {
						unset ( $params ['osfamily_name'] );
					}
					break;
				case 'osversion_name' :
					$params ['osversion_id'] = $this->getObjetItopOSVersion ()
						->creer_oql ( array (
							'name' => $valeur,
							'osfamily_id' => $params ['osfamily_id']
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'osversion_id', $params ['osversion_id'] );
					if (isset ( $params ['osversion_name'] )) {
						unset ( $params ['osversion_name'] );
					}
					break;
				case 'fqdn' :
					if (isset ( $params ['description'] )) {
						$params ['description'] = "FQDN: " . $valeur . $params ['description'];
					} else {
						$params ['description'] = "FQDN: " . $valeur;
					}
					if (isset ( $params ['fqdn'] )) {
						unset ( $params ['fqdn'] );
					}
					break;
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return VirtualMachine
	 */
	public function creer_oql_VirtualMachine(
		array $fields = array ()): VirtualMachine
	{
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'org_id' :
					$filtre ['org_name'] = $fields ['org_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champ Obligatoire : name, org_name, hypervisor_name, osfamily_name, osversion_name, status, business_criticity, managementip, cpu, mem, move2production, fqdn
	 * @param $parametres
	 * @return VirtualMachine
	 * @throws Exception
	 */
	public function gestion_VirtualMachine(
			$parametres): VirtualMachine
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_VirtualMachine ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_VirtualMachine ( $parametres )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return hypervisor
	 */
	public function &getObjetItophypervisor(): hypervisor
	{
		return $this->hypervisor;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItophypervisor(
			&$hypervisor): static
	{
		$this->hypervisor = $hypervisor;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return OSFamily|null
	 */
	public function &getObjetItopOSFamily(): ?OSFamily
	{
		return $this->OSFamily;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOSFamily(
			&$OSFamily): static
	{
		$this->OSFamily = $OSFamily;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return OSVersion|null
	 */
	public function &getObjetItopOSVersion(): ?OSVersion
	{
		return $this->OSVersion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOSVersion(
			&$OSVersion): static
	{
		$this->OSVersion = $OSVersion;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "VirtualMachine :";
		return $help;
	}
}
