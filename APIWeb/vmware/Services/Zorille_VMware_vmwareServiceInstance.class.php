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
use \Soapvar as Soapvar;
/**
 * class vmwareServiceInstance<br>
 *
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage VMWare
 */
class vmwareServiceInstance extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var stdClass.
	 */
	private $auth = null;
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $fonction = "RetrieveServiceContent";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareServiceInstance.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareServiceInstance
	 * @throws Exception
	 */
	static function &creer_vmwareServiceInstance(Core\options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__ ): vmwareServiceInstance
	{
		$objet = new vmwareServiceInstance ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareServiceInstance
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
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @throws Exception
	 */
	public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__ ) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass|bool objet contenant le _this charge viewManager.
	 * @throws Exception
	 */
	public function getViewManager(): stdClass|bool
	{
		$this->onDebug ( __METHOD__, 1 );
		if (! isset ( $this->getAuth()->viewManager )) {
			return $this->onError ( "Pas de propriete viewManager dans la liste des ServiceInstances", $this->getAuth() );
		}
		return $this->getAuth ()->viewManager;
	}
	
	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge viewManager.
	 */
	public function creer_entete_viewManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->viewManager;
		return $soap_message;
	}

	/**
	 * @throws Exception
	 */
	public function CreateContainerView($managedObjectType): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		
		$soap_message =  $this->creer_entete_viewManager_this();
		$soap_message->container= $this->getRootFolder();
		$soap_message->recursive=true;
		$soap_message->type=$managedObjectType;
		return	$soap_message;
	}


	/**
	 * renvoi les donnees contenu dans about de l'ESXi ou vcenter
	 * @return array
	 */
	public function about(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		return ( array ) $this->getAuth ()->about;
	}
	
	/***************************** RootFolder ****************************/
	/**
	 * creer l'objet contenant _this
	 * @return stdClass|bool objet contenant le _this charge avec le MOID du rootFolder.
	 * @throws Exception
	 */
	public function getRootFolder(): stdClass|bool
	{
		$this->onDebug ( __METHOD__, 1 );
	
		if (! isset ( $this->getAuth()->rootFolder )) {
			return $this->onError ( "Pas de propriete rootFolder dans la liste des ServiceInstances", $this->getAuth() );
		}
	
		return $this->getAuth()->rootFolder;
	}
	
	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge rootFolder.
	 * @throws Exception
	 */
	public function creer_entete_rootFolder_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
	
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getRootFolder();
		return $soap_message;
	}
	/***************************** RootFolder ****************************/

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge setting (HostAgentSettings).
	 */
	public function creer_entete_setting_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->setting;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge userDirectory.
	 */
	public function creer_entete_userDirectory_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->userDirectory;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge sessionManager.
	 */
	public function creer_entete_sessionManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->sessionManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge authorizationManager.
	 */
	public function creer_entete_authorizationManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->authorizationManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge serviceManager.
	 */
	public function creer_entete_serviceManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->serviceManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge perfManager (PerformanceManager).
	 */
	public function creer_entete_perfManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->perfManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge eventManager.
	 */
	public function creer_entete_eventManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->eventManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge taskManager.
	 */
	public function creer_entete_taskManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->taskManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge accountManager (HostLocalAccountManager).
	 */
	public function creer_entete_accountManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->accountManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge diagnosticManager.
	 */
	public function creer_entete_diagnosticManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->diagnosticManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge licenseManager.
	 */
	public function creer_entete_licenseManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->licenseManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge searchIndex.
	 */
	public function creer_entete_searchIndex_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->searchIndex;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge fileManager.
	 */
	public function creer_entete_fileManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->fileManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge datastoreNamespaceManager.
	 */
	public function creer_entete_datastoreNamespaceManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->datastoreNamespaceManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge virtualDiskManager.
	 */
	public function creer_entete_virtualDiskManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->virtualDiskManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge ovfManager.
	 */
	public function creer_entete_ovfManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->ovfManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge dvSwitchManager (DistributedVirtualSwitchManager).
	 */
	public function creer_entete_dvSwitchManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->dvSwitchManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge localizationManager.
	 */
	public function creer_entete_localizationManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->localizationManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge storageResourceManager.
	 */
	public function creer_entete_storageResourceManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->storageResourceManager;
		return $soap_message;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge guestOperationsManager.
	 */
	public function creer_entete_guestOperationsManager_this(): stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getAuth ()->guestOperationsManager;
		return $soap_message;
	}

	/******************************* vimService ********************************/
	public function prepare_SoapMessage(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( "prepare_SoapMessage", 1 );
		$soap_message = array ();
		$soap_message ["_this"] = new Soapvar ( "ServiceInstance", XSD_STRING, "ServiceInstance" );
		
		return $soap_message;
	}
	/******************************* vimService ********************************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function __clone() {
		// Force la copie de this->xxx, sinon
		// il pointera vers le meme objet.
		if(is_object($this->auth))
			$this->auth = clone $this->getAuth();
	}
	/**
	 * @codeCoverageIgnore
	 * @return stdClass|null
	 */
	public function getAuth(): ?stdClass
	{
		return $this->auth;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuth($auth): static
	{
		$this->auth = $auth;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getNomFonction(): string
	{
		return $this->fonction;
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
