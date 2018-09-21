<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class CustomizationUserData<br>
 * @package Lib
 * @subpackage VMWare
 */
class CustomizationUserData extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var CustomizationName
	 */
	private $computerName = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $fullName = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $orgName = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $productId = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CustomizationUserData.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomizationUserData
	 */
	static function &creer_CustomizationUserData(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomizationUserData ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomizationUserData
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
		$liste_proprietes = new ArrayObject ();
		if (empty ( $this->getFullName () )) {
			return $this->onError ( "Il faut un fullName" );
		}
		$liste_proprietes ["fullName"] = $this->getFullName ();
		if (empty ( $this->getOrgName () )) {
			return $this->onError ( "Il faut un orgName" );
		}
		$liste_proprietes ["orgName"] = $this->getOrgName ();
		if (empty ( $this->getComputerName () )) {
			return $this->onError ( "Il faut un computerName" );
		}
		$liste_proprietes ["computerName"] = $this->getComputerName ()
			->renvoi_objet_soap ( false );
		if (empty ( $this->getProductId () )) {
			return $this->onError ( "Il faut un productId" );
		}
		$liste_proprietes ["productId"] = $this->getProductId ();
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "CustomizationUserData" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return CustomizationName
	 */
	public function &getComputerName() {
		return $this->computerName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setComputerName(&$computerName) {
		$this->computerName = $computerName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFullName() {
		return $this->fullName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFullName($fullName) {
		$this->fullName = $fullName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOrgName() {
		return $this->orgName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOrgName($orgName) {
		$this->orgName = $orgName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getProductId() {
		return $this->productId;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProductId($productId) {
		$this->productId = $productId;
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
