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
 * class vmwareExtensibleManagedObject<br>
 * Methodes valide en 5.5 :
 *  setCustomValue
 * @package Lib
 * @subpackage VMWare
 */
class vmwareExtensibleManagedObject extends Core\abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareWsclient
	 */
	private $objetVmwareWsclient = null;
	/**
	 * var privee
	 * @access private
	 * @var stdClass|array|string
	 */
	private $ExtensibleManagedObject = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareExtensibleManagedObject.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareExtensibleManagedObject
	 */
	static function &creer_vmwareExtensibleManagedObject(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwareExtensibleManagedObject ( $sort_en_erreur, $entete );
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
	 * @return vmwareExtensibleManagedObject
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjectVmwareWsclient ( $liste_class ['vmwareWsclient'] );
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
	 * @return stdClass objet contenant le _this charge la ManagedEntity.
	 */
	public function creer_entete_ManagedObject_this() {
		$this->onDebug ( __METHOD__, 1 );
	
		if ($this->getManagedObject () == "") {
			return $this->onError ( "Aucune ManagedEntity de la class : ".__CLASS__." de defini." );
		}
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getManagedObject ();
		return $soap_message;
	}
	

	/************************* Methodes VMWare ***********************/
	/**
	 * Assigns a value to a custom field
	 * @param string $name The name of the field whose value is to be updated.
	 * @param string $value Value to be assigned to the custom field.
	 * @return array|false
	 */
	public function setCustomValue($name, $value) {
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_ManagedObject_this();
		$request->key = $name;
		$request->value = $value;
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "setCustomValue", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
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
	public function &setObjectVmwareWsclient(&$objetVmwareWsclient) {
		$this->objetVmwareWsclient = $objetVmwareWsclient;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return stdClass|array|string
	 */
	public function &getManagedObject() {
		return $this->ExtensibleManagedObject;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setManagedObject($ManagedEntity) {
		$this->ExtensibleManagedObject = $ManagedEntity;
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
