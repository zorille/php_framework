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
 * class vmwareClusterComputeResource<br>
 * Methodes valide en 5.5 :
 *  AddHost_Task, ApplyRecommendation, CancelRecommendation, ClusterEnterMaintenanceMode, MoveHostInto_Task, 
 *  MoveInto_Task, RecommendHostsForVm, ReconfigureCluster_Task, RefreshRecommendation, RetrieveDasAdvancedRuntimeInfo
 * Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage VMWare
 */
class vmwareClusterComputeResource extends vmwareComputeResource {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $ClusterComputeResource = "";


	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareClusterComputeResource.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareClusterComputeResource
	 */
	static function &creer_vmwareClusterComputeResource(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwareClusterComputeResource ( $sort_en_erreur, $entete );
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
	 * @return vmwareClusterComputeResource
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
	public function &getClusterComputeResource() {
		return $this->getManagedObject();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setClusterComputeResource($ClusterComputeResource) {
		return $this->setManagedObject($ClusterComputeResource);
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
