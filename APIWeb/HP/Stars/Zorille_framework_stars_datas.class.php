<?php
/**
 * Gestion de stars.
 * @author dvargas
 */
namespace Zorille\framework;
/*
* Code erreur stars
* 5100 Il manque les parametres clients stars
* 5101 Il manque les parametres WSDL pour stars
* 5102 La connexion par tunnel est en erreur
* 5103 Il faut un nom de stars pour se connecter
* 5104 Il faut un wsdl de stars pour se connecter
* 5105 Le wsdl demande n'existe pas
* 5106 Le tunnel demande n'existe pas
* 5107 Le proxy demande n'existe pas
* 5108 Le type n'est pas supporte, il doit etre MONITOR ou ALERT
* 5109 Il faut un tableau de nom de stars
* 5110 Pas de connexion active au stars
*
* Plusieurs codes erreurs de type SOAP
*/
/**
 * class CommandLine
 *
 * @package Lib
 * @subpackage stars
 */
class stars_datas extends serveur_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $wsdl_data = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var soap
	 */
	private $soapClient_connexion = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type stars_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return stars_datas
	 */
	static function &creer_stars_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new stars_datas ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return stars_datas
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setSoapConnection ( soap::creer_soap ( $liste_class ["options"] ) )
			->retrouve_stars_param ();
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string $entete Entete des logs de l'objet
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Recupere les parametres dans les confs XML
	 * @return stars_datas|boolean stars_datas si OK, False sinon.
	 */
	public function retrouve_stars_param() {
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"stars",
				"serveur" 
		) ) === false) {
			return $this->onError ( "Il manque les parametres clients stars.", "", 5100 );
		}
		
		$donnee_sis = $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"stars",
				"serveur" 
		) );
		
		if (isset ( $donnee_sis ["#comment"] )) {
			unset ( $donnee_sis ["#comment"] );
		}
		$this->setServeurData ( $donnee_sis );
		
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"stars",
				"wsdl" 
		) ) === false) {
			return $this->onError ( "Il manque les parametres WSDL pour stars.", "", 5101 );
		}
		
		$wsdl_data = $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"stars",
				"wsdl" 
		) );
		
		if (isset ( $wsdl_data ["#comment"] )) {
			unset ( $wsdl_data ["#comment"] );
		}
		
		$this->setWsdlData ( $wsdl_data );
		
		return $this;
	}

	/**
	 * Valide la presence de la definition d'un stars nomme : $nom
	 * 
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_stars_data($nom) {
		return $this->valide_presence_serveur_data ( $nom );
	}

	/**
	 * Connexion au soap preferences de stars
	 *
	 * @param string $nom nom du stars a connecter
	 * @return bool TRUE si connexion ok, FALSE sinon
	 */
	public function connexion($nom = "", $wsdl = "") {
		if ($nom == "") {
			return $this->onError ( "Il faut un nom de stars pour se connecter.", "", 5103 );
		}
		if ($wsdl == "") {
			return $this->onError ( "Il faut un wsdl de stars pour se connecter.", "", 5104 );
		}
		$serveur_data = $this->valide_presence_stars_data ( $nom );
		if ($serveur_data === false) {
			return $this->onWarning ( "Pas de configuration de stars pour se connecter." );
		}
		
		$serveur_data ["wsdl"] = $this->getWsdlData ( $wsdl ) . "?wsdl";
		$this->getSoapConnection ()
			->setCacheWsdl ( WSDL_CACHE_NONE )
			->retrouve_variables_tableau ( $serveur_data );
		
		return $this->getSoapConnection ()
			->connect ();
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
	public function getWsdlData($wsdl) {
		if (! isset ( $this->wsdl_data [$wsdl] )) {
			return $this->onError ( "Ce wsdl " . $wsdl . " n'existe pas.", "", 5105 );
		}
		if (is_array ( $this->wsdl_data [$wsdl] )) {
			return $this->wsdl_data [$wsdl] [0];
		}
		return $this->wsdl_data [$wsdl];
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

	/**
	 * @codeCoverageIgnore
	 */
	public function &getSoapConnection() {
		return $this->soapClient_connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSoapConnection(&$soapClient_connexion) {
		$this->soapClient_connexion = $soapClient_connexion;
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
