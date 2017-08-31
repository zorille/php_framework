<?php
/**
 * Gestion de stars.
 * @author dvargas
 */
/**
 * class hpom_soap_Incidentsvc
 *
 * @package Lib
 * @subpackage stars
 */
class hpom_soap_Incidentsvc extends hpom_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $wsdl = "IncidentService";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type hpom_soap_Incidentsvc.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet 
	 * @return hpom_soap_Incidentsvc
	 */
	static function &creer_hpom_soap_Incidentsvc(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new hpom_soap_Incidentsvc ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return hpom_soap_Incidentsvc
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
	 * @param string $entete Entete des logs de l'objet
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de hpom_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Connexion au soap IncidentManagement de stars (IncidentManagement)
	 *
	 * @param string $nom
	 *        	nom du stars a connecter
	 * @return bool TRUE si connexion ok, FALSE sinon
	 */
	public function connect($nom = "") {
		return $this->connexion ( $nom, $this->getWsdlNom () );
	}

	/**
	 * Execute la demande soap
	 * @param string $fonction Fonction SOAP demandee
	 * @param array $params Parametres de la fonction
	 * @return Ambigous <false, boolean>|boolean
	 */
	public function applique_requete_soap($fonction, $params = array()) {
		$this->onDebug ( "applique_requete_soap", 1 );
	
		try {
			if ($this->getListeOptions ()
			->getOption ( "dry-run" ) !== false) {
				$this->onWarning ( "DRY RUN : " . $fonction . " NON EXECUTE" );
				$resultat = false;
			} else {
				$resultat = $this->getSoapConnection ()
				->getSoapClient ()
				->__call ( $fonction, $params );
	
				$this->onDebug ( $this->getSoapConnection ()
						->getSoapClient ()
						->__getLastRequest (), 2 );
			}
		} catch ( Exception $e ) {
			return $this->onError ( $e->getMessage (), $this->getSoapConnection ()
					->getSoapClient ()
					->__getLastRequest (), $e->getCode () );
		}
	
		return $resultat;
	}
	/******************************* Incidents ********************************/
	


	/******************************* Incidents ********************************/
	
	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlNom() {
		return $this->wsdl;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "\t--dry-run Affiche les appels sans les executer";
		
		return $help;
	}
}
?>
