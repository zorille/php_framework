<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class vmwareComputeResource<br>
 * Methodes valide en 5.5 :
 *  ReconfigureComputeResource_Task
 * Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage VMWare
 */
class vmwareComputeResource extends vmwareManagedEntity {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareComputeResource.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareComputeResource
	 */
	static function &creer_vmwareComputeResource(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new vmwareComputeResource ( $sort_en_erreur, $entete );
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
	 * @return vmwareComputeResource
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
	 * Change the compute resource configuration. 
	 * @param vmwareComputeResourceConfigSpec $ComputeResourceConfigSpec A set of configuration changes to apply to the compute resource
	 * @param boolean $modify Flag to specify whether the specification ("spec") should be applied incrementally
	 * @return vmwareTask
	 * @throws Exception
	 */
	public function ReconfigureComputeResource_Task($ComputeResourceConfigSpec, $modify = false) {
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this();
		$request->spec = $ComputeResourceConfigSpec;
		$request->modify = $modify;
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "ReconfigureComputeResource_Task", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return stdClass
	 */
	public function &getComputeResource() {
		return $this->getManagedObject();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setComputeResource($ComputeResource) {
		return $this->setManagedObject($ComputeResource);
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
