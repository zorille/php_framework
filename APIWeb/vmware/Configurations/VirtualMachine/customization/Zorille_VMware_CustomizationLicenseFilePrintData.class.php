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
 * class CustomizationLicenseFilePrintData<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationLicenseFilePrintData extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $autoMode = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $autoUsers = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationLicenseFilePrintData.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationLicenseFilePrintData
	 */
	static function &creer_CustomizationLicenseFilePrintData(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new CustomizationLicenseFilePrintData ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationLicenseFilePrintData
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
		if (empty ( $this->getAutoMode () )) {
			return $this->onError ( "Il faut un autoMode" );
		}
		$liste_proprietes ["autoMode"] = $this->getAutoMode ();
		if ( $this->getAutoUsers () ) {
			$liste_proprietes ["autoUsers"] = $this->getAutoUsers ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationLicenseFilePrintData" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getAutoMode() {
		return $this->autoMode;
	}

	/**
	 * perSeat/perServer
	 * @codeCoverageIgnore
	 */
	public function &setAutoMode($autoMode) {
		switch ($autoMode) {
			case 'perSeat' :
			case 'perServer' :
				$this->autoMode = $autoMode;
				break;
			default :
				$this->autoMode = "";
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAutoUsers() {
		return $this->autoUsers;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAutoUsers($autoUsers) {
		$this->autoUsers = $autoUsers;
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
