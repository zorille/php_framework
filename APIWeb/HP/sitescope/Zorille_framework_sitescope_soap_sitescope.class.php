<?php
/**
 * Gestion de SiteScope.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class sitescope_soap_sitescope
 *
 * @package Lib
 * @subpackage SiteScope
 */
class sitescope_soap_sitescope extends sitescope_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_prefs = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $wsdl = "APISiteScope";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type sitescope_soap_sitescope.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return sitescope_soap_sitescope
	 */
	static function &creer_sitescope_soap_sitescope(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new sitescope_soap_sitescope ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return sitescope_soap_sitescope
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
		// Gestion de sitescope_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Connexion au soap sitescope de sitescope (APIPreference)
	 *
	 * @param string $nom
	 *        	nom du sitescope a connecter
	 * @return bool TRUE si connexion ok, FALSE sinon
	 */
	public function connect($nom = "") {
		return $this->connexion ( $nom, $this->getWsdlNom () );
	}

	/**
	 * Retrouve toutes les sitescope d'un sitescope, hors creds.
	 *
	 * @return boolean array
	 */
	public function retrouve_toutes_les_preferences() {
		$liste_prefs = array ();
		$liste_infos = $this->applique_requete_soap ( "getSiteScopeInfo", array () );
		$this->onDebug ( $liste_infos, 2 );
		
		$liste_datas_status = $this->applique_requete_soap ( "getSiteScopeStatusInfo", array () );
		$this->onDebug ( $liste_datas_status, 2 );
		
		if (is_array ( $liste_infos )) {
			$this->onDebug ( "Ajout des infos globales du SiS", 1 );
			foreach ( $liste_infos as $key => $valeur ) {
				$liste_prefs [$key] = $valeur;
			}
		}
		if (is_array ( $liste_datas_status )) {
			$this->onDebug ( "Ajout des status du SiS.", 1 );
			foreach ( $liste_datas_status as $key => $valeur ) {
				$liste_prefs [$key] = $valeur;
			}
		}
		
		$this->onDebug ( $liste_prefs, 2 );
		$this->setListePrefs ( $liste_prefs );
		return true;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListePrefs() {
		return $this->liste_prefs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListePrefs($liste_prefs) {
		$this->liste_prefs = $liste_prefs;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlNom() {
		return $this->wsdl;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
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
