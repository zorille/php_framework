<?php
/**
 * Serveur de bladelogic.
 * @author dvargas
 */
namespace Zorille\framework;
/**
 * class bladelogic_datas
 *
 * @package Lib
 * @subpackage bladelogic
 */
class bladelogic_datas extends serveur_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $wsdl_data = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type bladelogic_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet serveur_connexion_url
	 * @return bladelogic_datas
	 */
	static function &creer_bladelogic_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new bladelogic_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return bladelogic_datas
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_bladelogic_param ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Serveur de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 *
	 * @return boolean True est OK, False sinon.
	 */
	public function retrouve_bladelogic_param() {
		$donnee_bladelogic = $this->_valideOption ( array (
				"bladelogic",
				"serveur" 
		) );
		
		$this->setServeurData ( $donnee_bladelogic );
		
		//Gestion des WSDL
		$wsdl_bladelogic = $this->_valideOption ( array (
				"bladelogic",
				"wsdl" 
		) );
		
		$this->setWsdlData ( $wsdl_bladelogic );
		return $this;
	}

	/**
	 * Valide la presence de la definition d'un bladelogic nomme : $nom
	 *
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_bladelogic_data($nom) {
		return $this->valide_presence_serveur_data ( $nom );
	}

	/**
	 * Valide la presence de la definition d'un bladelogic nomme : $nom
	 *
	 * @param string $nom
	 * @return array false informations de configuration, false sinon.
	 */
	public function retrouve_wsdl($wsdl) {
		$liste_wsdl = $this->getWsdlDatas ();
		if (! isset ( $liste_wsdl [$wsdl] )) {
			return $this->onError ( "Ce wsdl " . $wsdl . " n'existe pas.", "", 5105 );
		}
		if (is_array ( $liste_wsdl [$wsdl] )) {
			return $liste_wsdl [$wsdl] [0];
		}
		return $liste_wsdl [$wsdl];
	}

	/**
	 * Connexion au soap preferences de bladelogic
	 *
	 * @param string $nom nom du bladelogic a connecter
	 * @return soap|bool TRUE si connexion ok, FALSE sinon
	 */
	public function recupere_donnees_bladelogic_serveur($nom = "", $wsdl = "") {
		if ($nom == "") {
			return $this->onError ( "Il faut un nom de bladelogic pour se connecter.", "", 5103 );
		}
		if ($wsdl == "") {
			return $this->onError ( "Il faut un wsdl de bladelogic pour se connecter.", "", 5104 );
		}
		$serveur_data = $this->valide_presence_bladelogic_data ( $nom );
		if ($serveur_data === false) {
			return $this->onWarning ( "Pas de configuration de bladelogic pour se connecter." );
		}
		
		$serveur_data ["wsdl"] = $this->retrouve_wsdl ( $wsdl );
		return $serveur_data;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlDatas() {
		return $this->wsdl_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWsdlData($wsdl_data) {
		if (is_array ( $wsdl_data )) {
			$this->wsdl_data = $wsdl_data;
		}
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}

	/**
	 * (non-PHPdoc)
	 * @codeCoverageIgnore
	 * @see lib/fork/message#__destruct()
	 */
	public function __destruct() {
	}
}
?>
