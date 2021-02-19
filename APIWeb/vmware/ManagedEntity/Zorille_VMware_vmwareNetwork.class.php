<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \Exception as Exception;
use \stdClass as stdClass;
/**
 * class vmwareNetwork<br>
 * Methodes valide en 5.5 :
 *  NetworkEnterMaintenanceMode, NetworkExitMaintenanceMode_Task, DestroyNetwork, RefreshNetwork, 
 *  RefreshNetworkStorageInfo, RenameNetwork, UpdateVirtualMachineFiles_Task
 * @package Lib
 * @subpackage VMWare
 */
class vmwareNetwork extends vmwareManagedEntity {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareNetwork.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareNetwork
	 */
	static function &creer_vmwareNetwork(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwareNetwork ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareWsclient" => $ObjectVmwareWsclient 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareNetwork
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/************************* Methodes VMWare ***********************/
	/**
	 * To Delete des qu'une autre methode apparait
	 * @return string
	 */
	public function affiche_les_Tests() {
		return "affiche";
	}
	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return stdClass
	 */
	public function &getNetwork() {
		return $this->getManagedObject();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNetwork($Network) {
		return $this->setManagedObject($Network);
	}

	/************************* Accesseurs ***********************/
	
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
