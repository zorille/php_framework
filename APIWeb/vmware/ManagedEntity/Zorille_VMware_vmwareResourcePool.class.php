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
 * class vmwareResourcePool<br>
 * Methodes valide en 5.5 :
 *  CreateChildVM_Task, CreateResourcePool, CreateVApp, DestroyChildren, ImportVApp, MoveIntoResourcePool, 
 *  QueryResourceConfigOption, RefreshRuntime, RegisterChildVM_Task, UpdateChildResourceConfiguration, UpdateConfig
 * @package Lib
 * @subpackage VMWare
 */
class vmwareResourcePool extends vmwareManagedEntity {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareResourcePool.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareResourcePool
	 */
	static function &creer_vmwareResourcePool(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwareResourcePool ( $sort_en_erreur, $entete );
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
	 * @return vmwareResourcePool
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
	public function &getResourcePool() {
		return $this->getManagedObject();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setResourcePool($ResourcePool) {
		return $this->setManagedObject($ResourcePool);
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
