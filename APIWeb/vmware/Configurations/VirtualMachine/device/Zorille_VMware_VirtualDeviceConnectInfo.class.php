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
 * class VirtualDeviceConnectInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDeviceConnectInfo extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $allowGuestControl = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $connected = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $startConnected = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $status = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDeviceConnectInfo.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDeviceConnectInfo
	 */
	static function &creer_VirtualDeviceConnectInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualDeviceConnectInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDeviceConnectInfo
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
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		$liste_proprietes ["startConnected"] = $this->getStartConnected ();
		$liste_proprietes ["allowGuestControl"] = $this->getAllowGuestControl ();
		$liste_proprietes ["connected"] = $this->getConnected ();
		
		if ( $this->getStatus () ) {
			$liste_proprietes ["status"] = $this->getStatus ();
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject = false) {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, 'VirtualDeviceConnectInfo' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getAllowGuestControl() {
		return $this->allowGuestControl;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAllowGuestControl($allowGuestControl) {
		$this->allowGuestControl = $allowGuestControl;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getConnected() {
		return $this->connected;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnected($connected) {
		$this->connected = $connected;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getStartConnected() {
		return $this->startConnected;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setStartConnected($startConnected) {
		$this->startConnected = $startConnected;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setStatus($status) {
		$this->status = $status;
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
