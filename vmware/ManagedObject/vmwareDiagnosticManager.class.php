<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class vmwareDiagnosticManager<br>
 * Methodes valide en 5.5 :
 *  BrowseDiagnosticLog, GenerateLogBundles_Task, QueryDescriptions
 * @package Lib
 * @subpackage VMWare
 */
class vmwareDiagnosticManager extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareWsclient
	 */
	private $objetVmwareWsclient = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareDiagnosticManager.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $ObjectVmwareWsclient Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareDiagnosticManager
	 */
	static function &creer_vmwareDiagnosticManager(&$liste_option, &$ObjectVmwareWsclient, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new vmwareDiagnosticManager ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareWsclient" => $ObjectVmwareWsclient 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareDiagnosticManager
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjectVmwareWsclient ( $liste_class ['vmwareWsclient'] );
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

	/**
	 * creer l'objet contenant _this
	 * @return stdClass objet contenant le _this charge diagnosticManager.
	 */
	public function creer_entete_diagnosticManager_this() {
		$this->onDebug ( __METHOD__, 1 );
		$soap_message = new stdClass ();
		$soap_message->_this = $this->getObjectVmwareWsclient ()
			->getObjectServiceInstance ()
			->getAuth ()->diagnosticManager;
		return $soap_message;
	}

	/************************* Methodes VMWare ***********************/
	/**
	 *
	 * @param hostSystem $host_ref
	 * @return array|false
	 */
	public function QueryDescriptions($host_ref) {
		$this->onDebug ( __METHOD__, 1 );
		
		$request = $this->creer_entete_diagnosticManager_this ();
		$request->host = $host_ref;
		
		$resultat = $this->getObjectVmwareWsclient ()
			->applique_requete_soap ( "QueryDescriptions", array (
				$request 
		) );
		
		$this->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return vmwareWsclient
	 */
	public function &getObjectVmwareWsclient() {
		return $this->objetVmwareWsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareWsclient(&$objetVmwareWsclient) {
		$this->objetVmwareWsclient = $objetVmwareWsclient;
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
