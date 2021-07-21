<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class CustomizationAdapterMapping<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationAdapterMapping extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var CustomizationIPSettings
	 */
	private $adapter = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $macAddress = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationAdapterMapping.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationAdapterMapping
	 */
	static function &creer_CustomizationAdapterMapping(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new CustomizationAdapterMapping ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationAdapterMapping
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

	/*********************** Creation de l'objet *********************/
	
	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if (empty ( $this->getAdapter () )) {
			return $this->onError ( "Il faut un adapter" );
		}
		$liste_proprietes ["adapter"] = $this->getAdapter ()
			->renvoi_donnees_soap ( false );
		if ( $this->getMacAddress () ) {
			$liste_proprietes ["macAddress"] = $this->getMacAddress ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject = false) {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationAdapterMapping" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return CustomizationIPSettings
	 */
	public function &getAdapter() {
		return $this->adapter;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAdapter(&$adapter) {
		$this->adapter = $adapter;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMacAddress() {
		return $this->macAddress;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMacAddress($macAddress) {
		$this->macAddress = $macAddress;
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
