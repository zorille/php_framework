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
 * class vmwareCustomFieldsManager<br>
 *
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage VMWare
 */
class vmwareCustomFieldsManager extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $CustomFieldsManager = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareWsclient
	 */
	private $objetVmwareWsclient = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareCustomFieldsManager.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param vmwareServiceInstance $ObjectServiceInstance Reference sur un objet vmwareServiceInstance
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareCustomFieldsManager
	 */
	static function &creer_vmwareCustomFieldsManager(&$liste_option, &$ObjectVmwareWsclient, &$ObjectServiceInstance, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwareCustomFieldsManager ( $sort_en_erreur, $entete );
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
	 * @return vmwareCustomFieldsManager
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjectVmwareWsclient ( $liste_class ['vmwareWsclient'] )
			->retrouve_customFieldsManager ( $liste_class ['vmwareServiceInstance']->getAuth () );
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
	public function retrouve_customFieldsManager($auth) {
		$this->onDebug ( __METHOD__, 1 );
		
		if (! isset ( $auth->customFieldsManager )) {
			return $this->onError ( "Pas de propriete customFieldsManager dans la liste des ServiceInstances", $auth );
		}
		
		$this->setCustomFieldsManager ( $auth->customFieldsManager );
		return $this;
	}

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge customFieldsManager.
	 */
	public function creer_entete_customFieldsManager_this() {
		$this->onDebug ( __METHOD__, 1 );
		
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getCustomFieldsManager ();
		return $soap_message;
	}

	/**
	 *
	 * @param string $name Nom du CustomField
	 * @param string $moType Peut etre Global/VirtualMachine/HostSystem
	 * @return array|false
	 */
	public function AddCustomFieldDef($name, $moType = "") {
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_customFieldsManager_this ();
		$request->name = $name;
		if (! empty ( $moType )) {
			$request->moType = $moType;
		}
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "AddCustomFieldDef", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}
	
	/**
	 *
	 * @param string $key The unique key for the field definition.
	 * @return array|false
	 */
	public function RemoveCustomFieldDef($key) {
		$this->onDebug ( __METHOD__, 1 );
	
		$request = $this->creer_entete_customFieldsManager_this ();
		$request->key = $key;
	
		$resultat = $this->getObjectVmwareWsclient ()
		->applique_requete_soap ( "RemoveCustomFieldDef", array (
				$request
		) );
	
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}
	
	/**
	 *
	 * @param string $key The unique key for the field definition.
	 * @param string $name New name of the custom field. 
	 * @return array|false
	 */
	public function RenameCustomFieldDef($key, $name) {
		$this->onDebug ( __METHOD__, 1 );
	
		$request = $this->creer_entete_customFieldsManager_this ();
		$request->key = $key;
		$request->name = $name;
	
		$resultat = $this->getObjectVmwareWsclient ()
		->applique_requete_soap ( "RenameCustomFieldDef", array (
				$request
		) );
	
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}
	
	/**
	 *
	 * @param vmwareVim25ManagedObject $obj_ref A reference to the ManagedObject used to make the method call.
	 * @param string $key The unique key for the field definition.
	 * @param string $value Value to be assigned to the custom field.
	 * @return array|false
	 */
	public function SetField($obj_ref, $key, $value) {
		$this->onDebug ( __METHOD__, 1 );
	
		$request = $this->creer_entete_customFieldsManager_this ();
		$request->entity = $obj_ref;
		$request->key = $key;
		$request->value = $value;
	
		$resultat = $this->getObjectVmwareWsclient ()
		->applique_requete_soap ( "SetField", array (
				$request
		) );
	
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return stdClass
	 */
	public function &getCustomFieldsManager() {
		return $this->CustomFieldsManager;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCustomFieldsManager($CustomFieldsManager) {
		$this->CustomFieldsManager = $CustomFieldsManager;
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
