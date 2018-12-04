<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class CustomizationWinOptions<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationWinOptions extends CustomizationOptions {
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $changeSID = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $deleteAccounts = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $reboot = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationWinOptions.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationWinOptions
	 */
	static function &creer_CustomizationWinOptions(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomizationWinOptions ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationWinOptions
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
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		$liste_proprietes ["changeSID"] = $this->getChangeSID ();
		$liste_proprietes ["deleteAccounts"] = $this->getDeleteAccounts ();
		if ( $this->getReboot () ) {
			$liste_proprietes ["reboot"] = $this->getReboot ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationWinOptions" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getChangeSID() {
		return $this->changeSID;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setChangeSID($changeSID) {
		$this->changeSID = $changeSID;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getDeleteAccounts() {
		return $this->deleteAccounts;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeleteAccounts($deleteAccounts) {
		$this->deleteAccounts = $deleteAccounts;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getReboot() {
		return $this->reboot;
	}

	/**
	 * noreboot/reboot/shutdown
	 * @codeCoverageIgnore
	 */
	public function &setReboot($reboot) {
		switch ($reboot) {
			case 'noreboot' :
			case 'reboot' :
			case 'shutdown' :
				$this->reboot = $reboot;
				break;
			default :
				$this->reboot = "";
		}
		
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
