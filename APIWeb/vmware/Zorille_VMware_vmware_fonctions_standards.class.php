<?php
/**
 * Gestion de Vmware.
 * @author dvargas
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class vmware_fonctions_standards
 *
 * @package Lib
 * @subpackage Vmware
 */
class vmware_fonctions_standards extends Core\abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_connexions = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareWsclient
	 */
	private $ObjetVmwareWsclient = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmware_fonctions_standards.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return vmware_fonctions_standards
	 */
	static function &creer_vmware_fonctions_standards(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmware_fonctions_standards ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmware_fonctions_standards
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjetVmwareSoapConfigurationRef ( vmwareWsclient::creer_vmwareWsclient ( $liste_class["options"], vmwareDatas::creer_vmwareDatas ( $liste_class["options"] ) ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param options &$liste_option pointeur sur liste_option
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * ******************* Gestion des connexions multi-vmware **************************
	 */
	/**
	 * Connecte plusieurs vmwares en parallele.<br/>
	 * Supprime de la liste 'liste_noms_vmware' les vmwares non connectes.
	 *
	 * @param array &$liste_noms_vmware
	 */
	public function connexion_soap_configuration_de_tous_les_vmwares(&$liste_noms_vmware) {
		if (! is_array ( $liste_noms_vmware )) {
			return $this->onError ( "Il faut un tableau de nom de vmware", "", 5109 );
		}
		
		foreach ( $liste_noms_vmware as $id => $vmware ) {
			// On prepare les variables Vmware WebService
			$vmware_webservice = clone $this->getObjetVmwareSoapConfigurationRef ();
			// On connecte le vmware
			try {
				$vmware_webservice->prepare_connexion ( $vmware );
			} catch ( Exception $e ) {
				$this->nettoie_vmware_non_connecte ( $liste_noms_vmware, $id );
				continue;
			}
			
			$liste_noms_vmware[$id]=$vmware_webservice;
			$this->setOneListeConnexion ( $vmware, $vmware_webservice );
		}
		
		return true;
	}

	/**
	 * Supprime le vmware non connecte de la liste des vmwares
	 * @param array $liste_noms_vmware
	 * @param string $vmware
	 * @param int $id id du vmware dans la liste
	 * @return vmware_fonctions_standards
	 */
	public function nettoie_vmware_non_connecte(&$liste_noms_vmware, $id) {
		unset ( $liste_noms_vmware [$id] );
		
		return $this;
	}

	/********************* Gestion des connexions multi-vmware ***************************/
	
	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeConnexion() {
		return $this->liste_connexions;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneListeConnexion($nom_vmware) {
		if (isset ( $this->liste_connexions [$nom_vmware] )) {
			return $this->liste_connexions [$nom_vmware];
		}
		
		return false;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeConnexion($liste_connexions) {
		$this->liste_connexions = $liste_connexions;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOneListeConnexion($nom_vmware, $connexion) {
		$this->liste_connexions [$nom_vmware] = $connexion;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareWsclient
	 */
	public function &getObjetVmwareSoapConfigurationRef() {
		return $this->ObjetVmwareWsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetVmwareSoapConfigurationRef(&$vmware_soap_configuration_ref) {
		$this->ObjetVmwareWsclient = $vmware_soap_configuration_ref;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>
