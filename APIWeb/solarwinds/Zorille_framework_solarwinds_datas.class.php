<?php
/**
 * Gestion de solarwinds.
 * @author dvargas
 */
namespace Zorille\framework;
/**
 * class solarwinds_datas
 *
 * @package Lib
 * @subpackage solarwinds
 */
class solarwinds_datas extends serveur_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $wsdl_data = false;
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type solarwinds_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return solarwinds_datas
	 */
	static function &creer_solarwinds_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new solarwinds_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return solarwinds_datas
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_solarwinds_param ();
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
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return boolean True est OK, False sinon.
	 */
	public function retrouve_solarwinds_param() {
		$this->onDebug ( __METHOD__, 1 );
		$donnee_solarwinds = $this->_valideOption ( array (
				"solarwinds_machines",
				"serveur" 
		) );
		
		$this->setServeurData ( $donnee_solarwinds );
		
		//Gestion des WSDL
		$wsdl_solarwinds = $this->_valideOption ( array (
				"solarwinds_machines",
				"wsdl"
		) );
		
		$this->setWsdlData ( $wsdl_solarwinds );
		
		return $this;
	}

	/**
	 * Valide la presence de la definition d'un solarwinds nomme : $nom
	 *
	 * @param string $nom
	 * @param string $protocole rest|soap|both par defaut 'both'
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_solarwinds_data($nom, $protocole='both') {
		$this->onDebug ( __METHOD__, 1 );
		return $this->valide_presence_serveur_data ( $nom, $protocole );
	}

	/**
	 * Valide la presence de la definition d'un solarwinds nomme : $nom
	 *
	 * @param string $nom
	 * @return array false informations de configuration, false sinon.
	 */
	public function retrouve_wsdl($wsdl) {
		$this->onDebug ( __METHOD__, 1 );
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
	 * Connexion au soap preferences de solarwinds
	 *
	 * @param string $nom nom du solarwinds a connecter
	 * @return bool TRUE si connexion ok, FALSE sinon
	 */
	public function recupere_donnees_solarwinds_serveur($nom = "", $wsdl = "") {
		$this->onDebug ( __METHOD__, 1 );
		if ($nom == "") {
			return $this->onError ( "Il faut un nom de solarwinds pour se connecter.", "", 5103 );
		}
		if ($wsdl == "") {
			return $this->onError ( "Il faut un wsdl de solarwinds pour se connecter.", "", 5104 );
		}
		$serveur_data = $this->valide_presence_solarwinds_data ( $nom, 'soap' );
		if ($serveur_data === false) {
			$this->onWarning ( "Pas de configuration de solarwinds pour se connecter." );
			return false;
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
		$help [__CLASS__] ["text"] [] .= "solarwinds Datas :";
		$help [__CLASS__] ["text"] [] .= "\t--solarwinds_machines_serveur {Donnees du/des serveur/s} Donnees contenus dans le fichier de configuration";
		
		return $help;
	}
}
?>
