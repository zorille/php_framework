<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework\options as options;
use \Exception as Exception;
use \stdClass as stdClass;
/**
 * class vmwareManagedEntity<br>
 * Methodes valide en 5.5 :
 *  Destroy_Task, Reload, Rename_Task
 * @package Lib
 * @subpackage VMWare
 */
class vmwareManagedEntity extends vmwareExtensibleManagedObject {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareManagedEntity.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareManagedEntity
	 */
	static function &creer_vmwareManagedEntity(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwareManagedEntity ( $sort_en_erreur, $entete );
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
	 * @return vmwareManagedEntity
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
	 * Supprimme l'objet defini dans la request
	 *
	 * @param stdClass $request A reference to the ExtensibleManagedObject used to make the method call.
	 * @return array|false
	 */
	public function Destroy_Task() {
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this ();
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "Destroy_Task", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	
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
