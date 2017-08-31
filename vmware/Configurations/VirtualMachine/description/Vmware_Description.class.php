<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class Vmware_Description<br>
 * @package Lib
 * @subpackage VMWare
 */
class Vmware_Description extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $label = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $summary = "";
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type Vmware_Description.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Vmware_Description
	 */
	static function &creer_Description(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Vmware_Description ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Vmware_Description
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
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if (empty ( $this->getLabel () )) {
			return $this->onError("Il faut un label");
		}
		$liste_proprietes ["label"] = $this->getLabel ();
		if (empty ( $this->getSummary () )) {
			return $this->onError("Il faut un summary");
		}
		$liste_proprietes ["summary"] = $this->getSummary ();
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, "Description" );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLabel($label) {
		$this->label = $label;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSummary($summary) {
		$this->summary = $summary;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
