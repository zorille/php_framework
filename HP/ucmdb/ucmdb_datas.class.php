<?php
/**
 * Serveur de ucmdb.
 * @author dvargas
 */
/**
 * class ucmdb_datas
 *
 * @package Lib
 * @subpackage ucmdb
 */
class ucmdb_datas extends serveur_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $wsdl_data = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type ucmdb_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet serveur_connexion_url
	 * @return ucmdb_datas
	 */
	static function &creer_ucmdb_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new ucmdb_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ucmdb_datas
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setSoapConnection ( soap::creer_soap ( $this->getListeOptions () ) )
			->retrouve_ucmdb_param ();
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
	public function retrouve_ucmdb_param() {
		$donnee_ucmdb = $this->_valideOption ( array (
				"ucmdb",
				"serveur" 
		) );
		
		$this->setServeurData ( $donnee_ucmdb );
		
		//Gestion des WSDL
		$wsdl_ucmdb = $this->_valideOption ( array (
				"ucmdb",
				"wsdl" 
		) );
		

		$this->setWsdlData ( $wsdl_ucmdb );
		return $this;
	}

	/**
	 * Valide la presence de la definition d'un ucmdb nomme : $nom
	 *
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_ucmdb_data($nom) {
		return $this->valide_presence_serveur_data ( $nom );
	}
	
	/**
	 * Valide la presence de la definition d'un ucmdb nomme : $nom
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
	 * Connexion au soap preferences de ucmdb
	 *
	 * @param string $nom nom du ucmdb a connecter
	 * @return soap|bool TRUE si connexion ok, FALSE sinon
	 */
	public function recupere_donnees_ucmdb_serveur($nom = "", $wsdl = "") {
		if ($nom == "") {
			return $this->onError ( "Il faut un nom de ucmdb pour se connecter.", "", 5103 );
		}
		if ($wsdl == "") {
			return $this->onError ( "Il faut un wsdl de ucmdb pour se connecter.", "", 5104 );
		}
		$serveur_data = $this->valide_presence_ucmdb_data ( $nom );
		if ($serveur_data === false) {
			return $this->onWarning ( "Pas de configuration de ucmdb pour se connecter." );
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
}
?>
