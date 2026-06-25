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
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param vmwareServiceInstance $ObjectServiceInstance Reference sur un objet vmwareServiceInstance
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwarePropertyCollector
	 * @throws Exception
	 */
	static function &creer_vmwarePropertyCollector(Core\options &$liste_option, vmwareWsclient &$ObjectVmwareWsclient, vmwareServiceInstance &$ObjectServiceInstance, bool|string $sort_en_erreur = false, string $entete = __CLASS__): vmwarePropertyCollector
	{
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
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		$this->setObjectVmwareWsclient ( $liste_class ['vmwareWsclient'] )
			->retrouve_propertyCollector ( $liste_class ['vmwareServiceInstance']->getAuth () );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @throws Exception
	 */
	public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * creer l'objet contenant _this
	 * @param stdClass $auth Reponse contenant la liste des ServiceInstances
	 * @return bool|vmwarePropertyCollector objet contenant le _this charge customFieldsManager.
	 * @throws Exception
	 */
	public function retrouve_propertyCollector(stdClass $auth): bool|vmwarePropertyCollector|static
	{
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
	public function creer_entete_propertyCollector_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getPropertyCollector ();
		return $soap_message;
	}

	/**
	 * Recupere la liste des proprietes par ManagedObjectReference
	 */
	public function retrouve_donnees_par_ManagedObject($ManagedObjectReference, $all = true, $pathSet = array(), $options = ""): bool|array
	{
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
	public function retrouve_propset($ManagedObjectReference, $all = true, $pathSet = array(), $options = ""): bool|array
	{
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
	 * @param $PropertyFilterSpec_type
	 * @param bool $PropertyFilterSpec_all
	 * @param array $PropertyFilterSpec_pathSet
	 * @param string $options
	 * @return bool
	 */
	public function RetrievePropertiesEx($PropertyFilterSpec_type, bool $PropertyFilterSpec_all = true, array $PropertyFilterSpec_pathSet = array(), string $options = ""): bool
	{
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
	 * @return bool
	 */
	public function ContinueRetrievePropertiesEx(string $token): bool
	{
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
	public function PropertyFilterSpec(string $type, bool $all, array $pathSet): array
	{
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
	 * @param array $selectSet
	 * @return vmwarePropertyCollector
	 */
	public function ObjectSpec(stdClass $ManagedObjectReference, bool $skip = false, array $selectSet = array()): static
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->setObjectSpec ( array (
				'obj' => $ManagedObjectReference->_this,
				'skip' => $skip,
				'selectSet' => $selectSet 
		) );
	}

	/**
	 * Vreer une session de type containerView
	 * @param $PropertyContainerView
	 * @return bool
	 */
	public function CreateContainerView($PropertyContainerView): bool
	{
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
	public function &getPropertyCollector(): ?stdClass
	{
		return $this->propertyCollector;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPropertyCollector($propertyCollector): static
	{
		$this->propertyCollector = $propertyCollector;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return array|string
	 */
	public function getObjectSpec(): array|string
	{
		return $this->ObjectSpec;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectSpec($ObjectSpec): static
	{
		$this->ObjectSpec = $ObjectSpec;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareWsclient|null
	 */
	public function &getObjectVmwareWsclient(): ?vmwareWsclient
	{
		return $this->objetVmwareWsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareWsclient($objetVmwareWsclient): static
	{
		$this->objetVmwareWsclient = $objetVmwareWsclient;
		return $this;
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
