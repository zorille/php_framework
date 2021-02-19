<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \Exception as Exception;
use \stdClass as stdClass;
/**
 * class vmwarePropertyCollector<br>
 *
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage VMWare
 */
class vmwarePropertyCollector extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var stdClass
	 */
	private $propertyCollector = NULL;
	/**
	 * var privee
	 * @access private
	 * @var array.
	 */
	private $ObjectSpec = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareWsclient
	 */
	private $objetVmwareWsclient = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwarePropertyCollector.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param vmwareServiceInstance $ObjectServiceInstance Reference sur un objet vmwareServiceInstance
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwarePropertyCollector
	 */
	static function &creer_vmwarePropertyCollector(&$liste_option, &$ObjectVmwareWsclient, &$ObjectServiceInstance, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwarePropertyCollector ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareWsclient" => $ObjectVmwareWsclient,
				"vmwareServiceInstance" => $ObjectServiceInstance 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwarePropertyCollector
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjectVmwareWsclient ( $liste_class ['vmwareWsclient'] )
			->retrouve_propertyCollector ( $liste_class ['vmwareServiceInstance']->getAuth () );
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

	/**
	 * creer l'objet contenant _this
	 * @param stdClass $auth Reponse contenant la liste des ServiceInstances
	 * @return stdClass objet contenant le _this charge customFieldsManager.
	 * @throws Exception
	 */
	public function retrouve_propertyCollector($auth) {
		$this->onDebug ( __METHOD__, 1 );
		
		if (! isset ( $auth->propertyCollector )) {
			return $this->onError ( "Pas de propriete propertyCollector dans la liste des ServiceInstances", $auth );
		}
		
		$this->setPropertyCollector ( $auth->propertyCollector );
		return $this;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge propertyCollector.
	 */
	public function creer_entete_propertyCollector_this() {
		$this->onDebug ( __METHOD__, 1 );
		
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getPropertyCollector ();
		return $soap_message;
	}

	/**
	 * Recupere la liste des proprietes par ManagedObjectReference
	 */
	public function retrouve_donnees_par_ManagedObject($ManagedObjectReference, $all = true, $pathSet = array(), $options = "") {
		$this->onDebug ( __METHOD__, 1 );
		$this->setObjectSpec ( array (
				'obj' => $ManagedObjectReference,
				'skip' => false,
				'selectSet' => array () 
		) );
		
		if(is_object($ManagedObjectReference)){
			$type=$ManagedObjectReference->type;
		} else {
			$type=$ManagedObjectReference ["type"];
		}
		
		return $this->RetrievePropertiesEx ( $type, $all, $pathSet, $options );
	}

	/**
	 * Recupere la liste des proprietes dans un resultat de recherche
	 * @return array|false
	 */
	public function retrouve_propset($ManagedObjectReference, $all = true, $pathSet = array(), $options = "") {
		$this->onDebug ( __METHOD__, 1 );
		$resultat_recherche_soap = $this->retrouve_donnees_par_ManagedObject ( $ManagedObjectReference, $all, $pathSet, $options );
		
		$datas_xml = $this->getObjectVmwareWsclient ()
			->convertit_donnees ( $resultat_recherche_soap, "xml" );
		$resultat_recherche = $datas_xml->renvoi_donnee ( array (
				"objects",
				"propSet" 
		) );
		if ($resultat_recherche !== false) {
			$this->onDebug ( $resultat_recherche, 2 );
			return $resultat_recherche;
		}
		
		$this->onDebug ( array (), 2 );
		return array ();
	}

	/**
	 * Recupere la liste des DataCenters et leurs donnees
	 * @return array|false
	 */
	public function RetrievePropertiesEx($PropertyFilterSpec_type, $PropertyFilterSpec_all = true, $PropertyFilterSpec_pathSet = array(), $options = "") {
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_propertyCollector_this ();
		$request->specSet = array (
				'propSet' => $this->PropertyFilterSpec ( $PropertyFilterSpec_type, $PropertyFilterSpec_all, $PropertyFilterSpec_pathSet ),
				'objectSet' => $this->getObjectSpec (),
				'reportMissingObjectsInResults' => true 
		);
		$request->options = $options;
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "RetrievePropertiesEx", array (
				$request 
		) );

		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * 
	 * @param string $token
	 * @return array|false
	 */
	public function ContinueRetrievePropertiesEx($token) {
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_propertyCollector_this ();
		$request->token = $token;
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "ContinueRetrievePropertiesEx", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Renvoi le tableau propSet de la fonction RetrievePropertiesEx
	 * @param string $type
	 * @param boolean $all
	 * @param array $pathSet
	 * @return array
	 */
	public function PropertyFilterSpec($type, $all, $pathSet) {
		$this->onDebug ( __METHOD__, 1 );
		return array (
				//array (
				//'type' => 'VirtualMachine', 
				//'all' => 0, 
				//'pathSet' => array ('name', 'guest.ipAddress', 'guest.guestState', 'runtime.powerState', 'config.hardware.numCPU', 'config.hardware.memoryMB')
				//)
				array (
						'type' => $type,
						'all' => $all,
						'pathSet' => $pathSet 
				) 
		);
	}

	/**
	 * @param stdClass $ManagedObjectReference	Objet contenant une variable _this.
	 * @param Boolean $skip
	 * @param Array $selectSet
	 * @return vmwarePropertyCollector
	 */
	public function ObjectSpec($ManagedObjectReference, $skip = false, $selectSet = array()) {
		$this->onDebug ( __METHOD__, 1 );
		return $this->setObjectSpec ( array (
				'obj' => $ManagedObjectReference->_this,
				'skip' => $skip,
				'selectSet' => $selectSet 
		) );
	}
	
	/**
	 * Vreer une session de type containerView
	 * @return array|false
	 */
	public function CreateContainerView($PropertyContainerView) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "CreateContainerView", array($PropertyContainerView));

		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return stdClass
	 */
	public function &getPropertyCollector() {
		return $this->propertyCollector;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPropertyCollector($propertyCollector) {
		$this->propertyCollector = $propertyCollector;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getObjectSpec() {
		return $this->ObjectSpec;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectSpec($ObjectSpec) {
		$this->ObjectSpec = $ObjectSpec;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareWsclient
	 */
	public function &getObjectVmwareWsclient() {
		return $this->objetVmwareWsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareWsclient($objetVmwareWsclient) {
		$this->objetVmwareWsclient = $objetVmwareWsclient;
		return $this;
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
