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
 * class CustomizationIdentification<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationIdentification extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $domainAdmin = "";
	/**
	 * var privee
	 * @access private
	 * @var CustomizationPassword
	 */
	private $domainAdminPassword = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $joinDomain = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $joinWorkgroup = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationIdentification.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationIdentification
	 */
	static function &creer_CustomizationIdentification(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomizationIdentification ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationIdentification
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
		if ( $this->getDomainAdmin () ) {
			$liste_proprietes ["domainAdmin"] = $this->getDomainAdmin ();
		}
		if ( $this->getDomainAdminPassword () ) {
			$liste_proprietes ["domainAdminPassword"] = $this->getDomainAdminPassword ()
				->renvoi_donnees_soap ( false );
		}
		if ( $this->getJoinDomain () ) {
			$liste_proprietes ["joinDomain"] = $this->getJoinDomain ();
		}
		if ( $this->getJoinWorkgroup () ) {
			$liste_proprietes ["joinWorkgroup"] = $this->getJoinWorkgroup ();
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationIdentification" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getDomainAdmin() {
		return $this->domainAdmin;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDomainAdmin($domainAdmin) {
		$this->domainAdmin = $domainAdmin;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomizationName
	 */
	public function &getDomainAdminPassword() {
		return $this->domainAdminPassword;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDomainAdminPassword(&$domainAdminPassword) {
		$this->domainAdminPassword = $domainAdminPassword;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getJoinDomain() {
		return $this->joinDomain;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setJoinDomain($joinDomain) {
		$this->joinDomain = $joinDomain;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getJoinWorkgroup() {
		return $this->joinWorkgroup;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setJoinWorkgroup($joinWorkgroup) {
		$this->joinWorkgroup = $joinWorkgroup;
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
