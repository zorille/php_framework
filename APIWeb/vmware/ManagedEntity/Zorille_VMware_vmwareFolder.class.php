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
 * class vmwareFolder<br>
 * Methodes valide en 5.5 :
 *  AddStandaloneHost_Task, CreateCluster, CreateClusterEx, CreateDatacenter, CreateDVS_Task, CreateFolder,
 *  CreateStoragePod, CreateVM_Task, MoveIntoFolder_Task, RegisterVM_Task, UnregisterAndDestroy_Task
 * Renvoi des informations via un webservice.
 * @package Lib
 * @subpackage VMWare
 */
class vmwareFolder extends vmwareManagedEntity {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $Folder = array();
	/**
	 * var privee
	 * Liste de ManagedObjectReference to a ManagedEntity
	 * @access private
	 * @var array
	 */
	private $childEntity = array();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $childType = "";
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareFolder.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareFolder
	 * @throws Exception
	 */
	static function &creer_vmwareFolder(options &$liste_option, vmwareWsclient &$ObjectVmwareWsclient, bool|string $sort_en_erreur = false, string $entete = __CLASS__): vmwareFolder
	{
		$objet = new vmwareFolder ( $sort_en_erreur, $entete );
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
	 * @return vmwareFolder
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge folder.
	 */
	public function creer_entete_folder_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getMoIDFolder ();
		return $soap_message;
	}

	/************************* Methodes VMWare ***********************/
	/**
	 * Fait un CreateDatacenter
	 * Necessite le rootFolder de reference
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function CreateDatacenter(string $name): bool
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_folder_this ();
		$request->name = $name;
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "CreateDatacenter", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * Fait un CreateVM_Task
	 * @return bool (Task)|false
	 */
	public function CreateVM_Task($VirtualMachineConfigSpec, $ResourcePool, $host=""): bool
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_folder_this ();
		$request->config = $VirtualMachineConfigSpec;
		$request->pool = $ResourcePool;
		$request->host = $host;
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "CreateVM_Task", array (
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
	public function &getMoIDFolder(): stdClass
	{
		return $this->getManagedObject();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMoIDFolder($Folder): vmwareFolder
	{
		return $this->setManagedObject($Folder);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getChildEntity(): array
	{
		return $this->childEntity;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setChildEntity($childEntity): static
	{
		$this->childEntity=$childEntity;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getChildType(): string
	{
		return $this->childType;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setChildType($childType): static
	{
		$this->childType=$childType;
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
