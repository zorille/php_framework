<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class CustomizationGuiUnattended<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationGuiUnattended extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $autoLogon = false;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $autoLogonCount = 0;
	/**
	 * var privee
	 * @access private
	 * @var CustomizationPassword
	 */
	private $password = NULL;
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $timeZone = 102;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationGuiUnattended.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationGuiUnattended
	 */
	static function &creer_CustomizationGuiUnattended(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomizationGuiUnattended ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationGuiUnattended
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
		if (empty ( $this->getPassword () )) {
			return $this->onError ( "Il faut un password" );
		}
		$liste_proprietes ["password"] = $this->getPassword ()
		->renvoi_objet_soap ( false );
		$liste_proprietes ["timeZone"] = $this->getTimeZone ();
		$liste_proprietes ["autoLogon"] = $this->getAutoLogon ();
		$liste_proprietes ["autoLogonCount"] = $this->getAutoLogonCount ();
		
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationGuiUnattended" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getAutoLogon() {
		return $this->autoLogon;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setAutoLogon($autoLogon) {
		$this->autoLogon = $autoLogon;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getAutoLogonCount() {
		return $this->autoLogonCount;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAutoLogonCount($autoLogonCount) {
		$this->autoLogonCount = $autoLogonCount;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationPassword
	 */
	public function &getPassword() {
		return $this->password;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword(&$password) {
		$this->password = $password;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTimeZone() {
		return $this->timeZone;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimeZone($timeZone) {
		$this->timeZone = $timeZone;
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
