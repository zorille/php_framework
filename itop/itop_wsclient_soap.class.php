<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class itop_wsclient_soap<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage itop
 */
class itop_wsclient_soap extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var itop_datas
	 */
	private $itop_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $nom_serveur = '';
	/**
	 * var privee
	 * @access private
	 * @var array.
	 */
	private $defaultParams = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var soap
	 */
	private $objet_soap = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_wsclient_soap. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param itop_datas &$itop_datas Reference sur un objet itop_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_wsclient_soap
	 */
	static function &creer_itop_wsclient_soap(&$liste_option, &$itop_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_wsclient_soap ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"itop_datas" => $itop_datas ) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_wsclient_soap
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["itop_datas"] )) {
			$this ->onError ( "il faut un objet de type itop_datas" );
			return false;
		}
		$this ->setObjetItopdatas ( $liste_class ["itop_datas"] ) 
			->setObjetSoap ( soap::creer_soap ( $liste_class ["options"] ) );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
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
	 * Prepare l'url de connexion au itop nomme $nom
	 * @param string $nom
	 * @return boolean itop_wsclient_soap
	 */
	public function prepare_connexion($nom) {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_data_itop = $this ->getObjetItopdatas () 
			->valide_presence_itop_data ( $nom, 'soap' );
		if ($liste_data_itop === false) {
			return $this ->onError ( "Aucune definition de itop pour " . $nom );
		}
		$this ->setNomServeur ( $nom );
		
		if (! isset ( $liste_data_itop ["username"] )) {
			return $this ->onError ( "Il faut un username dans la liste des parametres itop" );
		}
		if (! isset ( $liste_data_itop ["password"] )) {
			return $this ->onError ( "Il faut un password dans la liste des parametres itop" );
		}
		if (! isset ( $liste_data_itop ["url"] )) {
			return $this ->onError ( "Il faut une url dans la liste des parametres itop" );
		}
		
		//On gere la partie Soap webService
		$this ->getObjetSoap () 
			->setCacheWsdl ( WSDL_CACHE_NONE ) 
			->retrouve_variables_tableau ( $this ->getObjetItopdatas () 
			->recupere_donnees_itop_serveur ( $nom, "itopService" ) ) 
			->connect ();
		
		return $this;
	}

	/**
	 * ***************************** vimService *******************************
	 */
	/**
	 * Execute la demande soap
	 * @param string $fonction Fonction SOAP demandee
	 * @param array $params Parametres de la fonction
	 * @return Ambigous <false, boolean>|boolean
	 * @throws Exception
	 */
	public function applique_requete_soap($fonction, $params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		try {
			if ($this ->getListeOptions () 
				->getOption ( "dry-run" ) !== false  && preg_match ( "/^CreateRequestTicket$|^CreateIncidentTicket$/", $fonction ) === 1) {
				$this ->onWarning ( "DRY RUN : " . $fonction . " NON EXECUTE" );
				$resultat = false;
			} else {
				$resultat = $this ->getObjetSoap () 
					->getSoapClient () 
					->__call ( $fonction, $params );
				
				$this ->onDebug ( $this ->getObjetSoap () 
					->getSoapClient () 
					->__getLastRequest (), 2 );
				$this ->onDebug ( $resultat, 2 );
			}
		} catch ( Exception $e ) {
			return $this ->onError ( $e ->getMessage (), $this ->getObjetSoap () 
				->getSoapClient () 
				->__getLastRequest (), $e ->getCode () );
		}
		
		$this ->gestion_erreur ( $resultat );
		return $resultat;
	}

	/**
	 * Creer le ticket de type user request CreateRequestTicket(string $login, string $password, string $title, string $description, ExternalKeySearch $caller, ExternalKeySearch $customer, ExternalKeySearch $service, ExternalKeySearch $service_subcategory, string $product, ExternalKeySearch $workgroup, ArrayOfLinkCreationSpec $impacted_cis, string $impact, string $urgency)
	 * @param string $titre
	 * @param string $description
	 * @param string $customer
	 * @param array $ci tableau au format [type de ci]=>nom du ci Le type de CI doit etre un type de iTop (VirtualMachine, Middleware, ... )
	 * @param string $impact 1 = department, 2 = service, 3 = person
	 * @param string $urgency 1 = high, 2 = medium, 3 = low
	 * @param string $caller
	 * @param string $workgroup
	 * @param string $service
	 * @param string $service_subcategory
	 * @param string $product
	 * @return array
	 * @throws Exception
	 */
	public function CreateRequestTicket($titre, $description, $customer, $ci, $impact, $urgency, $caller = '', $workgroup = '', $service = '', $service_subcategory = '', $product = '') {
		$this ->onDebug ( __METHOD__, 1 );
		
		$liste_cis = array ();
		if (is_array ( $ci )) {
			foreach ( $ci as $ci_type => $ci_name ) {
				$liste_cis [count ( $liste_cis )] = new SOAPLinkCreationSpec ( $ci_type, array (
						new SOAPSearchCondition ( 'name', $ci_name ) ), array () );
			}
		} else {
			$this ->onError ( "Le champ ci doit etre un tableau", $ci );
		}
		
		$result = $this ->applique_requete_soap ( 
				"CreateRequestTicket", 
					array (
							$this ->getObjetSoap () 
								->getLogin (),
							$this ->getObjetSoap () 
								->getPassword (),
							$titre,
							$description,
							$this ->creer_SOAPExternalKeySearch_par_name ( $caller ),
							$this ->creer_SOAPExternalKeySearch_par_name ( $customer ),
							$this ->creer_SOAPExternalKeySearch_par_name ( $service ),
							$this ->creer_SOAPExternalKeySearch_par_name ( $service_subcategory ),
							$product,
							$this ->creer_SOAPExternalKeySearch_par_name ( $workgroup ),
							$liste_cis,
							$impact,
							$urgency ) );
		
		$resultat = $this ->convertit_donnees ( $result, "array" );
		if (isset ( $resultat ['result'] )) {
			return $resultat ['result'];
		}
		return $resultat;
	}

	/**
	 * Creer le ticket d'incident CreateIncidentTicket(string $login, string $password, string $title, string $description, ExternalKeySearch $caller, ExternalKeySearch $customer, ExternalKeySearch $service, ExternalKeySearch $service_subcategory, string $product, ExternalKeySearch $workgroup, ArrayOfLinkCreationSpec $impacted_cis, string $impact, string $urgency)
	 * @param string $titre
	 * @param string $description
	 * @param string $customer
	 * @param array $ci tableau au format [type de ci]=>nom du ci Le type de CI doit etre un type de iTop (VirtualMachine, Middleware, ... )
	 * @param string $impact 1 = department, 2 = service, 3 = person
	 * @param string $urgency 1 = high, 2 = medium, 3 = low
	 * @param string $caller
	 * @param string $workgroup
	 * @param string $service
	 * @param string $service_subcategory
	 * @param string $product
	 * @return array
	 * @throws Exception
	 */
	public function CreateIncidentTicket($titre, $description, $customer, $ci, $impact, $urgency, $caller = '', $workgroup = '', $service = '', $service_subcategory = '', $product = '') {
		$this ->onDebug ( __METHOD__, 1 );
		
		$liste_cis = array ();
		if (is_array ( $ci )) {
			foreach ( $ci as $ci_type => $ci_name ) {
				$liste_cis [count ( $liste_cis )] = new SOAPLinkCreationSpec ( $ci_type, array (
						new SOAPSearchCondition ( 'name', $ci_name ) ), array () );
			}
		} else {
			$this ->onError ( "Le champ ci doit etre un tableau", $ci );
		}
		
		$result = $this ->applique_requete_soap ( 
				"CreateIncidentTicket", 
					array (
							$this ->getObjetSoap () 
								->getLogin (),
							$this ->getObjetSoap () 
								->getPassword (),
							$titre,
							$description,
							$this ->creer_SOAPExternalKeySearch_par_name ( $caller ),
							$this ->creer_SOAPExternalKeySearch_par_name ( $customer ),
							$this ->creer_SOAPExternalKeySearch_par_name ( $service ),
							$this ->creer_SOAPExternalKeySearch_par_name ( $service_subcategory ),
							$product,
							$this ->creer_SOAPExternalKeySearch_par_name ( $workgroup ),
							$liste_cis,
							$impact,
							$urgency ) );
		
		$resultat = $this ->convertit_donnees ( $result, "array" );
		if (isset ( $resultat ['result'] )) {
			return $resultat ['result'];
		}
		return $resultat;
	}

	/**
	 * Applique une recherche sur iTop SearchObjects(string $login, string $password, string $oql)
	 * @param string $titre requete OQL
	 * @return array
	 * @throws Exception
	 */
	public function SearchObjects($oql) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$result = $this ->applique_requete_soap ( "SearchObjects", array (
				$this ->getObjetSoap () 
					->getLogin (),
				$this ->getObjetSoap () 
					->getPassword (),
				$oql ) );
		
		$resultat = $this ->convertit_donnees ( $result, "array" );
		if (isset ( $resultat ['result'] )) {
			return $resultat ['result'];
		}
		return $resultat;
	}

	/**
	 * Gestion des erreur iTop
	 * @param SOAPResult $resultat_soap
	 * @return itop_wsclient_soap
	 * @throws Exception
	 */
	public function gestion_erreur($resultat_soap) {
		if(!$resultat_soap instanceof SOAPResult){
			return $this;
		}
		//Gestion des erreurs
		if ($resultat_soap ->getStatus () === false) {
			$erreurs = $resultat_soap ->getErrors () 
				->getMessages ();
			if (is_array ( $erreurs ) && count ( $erreurs ) > 0) {
				return $this ->onError ( $erreurs [0] ->getText () );
			} else {
				return $this ->onError ( "Erreur durant la requete SOAP", $resultat_soap );
			}
		}
		
		//Gestion des warnings
		$warnings = $resultat_soap ->getWarnings () 
			->getMessages ();
		if (is_array ( $warnings ) && count ( $warnings ) > 0) {
			foreach ( $warnings as $message ) {
				$this ->onWarning ( $message ->getText () );
			}
		}
		
		return $this;
	}

	/**
	 * Creer un objet de type recherche avec 'name'=$valeur
	 * @param string $valeur
	 * @return string SOAPExternalKeySearch
	 */
	public function creer_SOAPExternalKeySearch_par_name($valeur) {
		if ($valeur != '') {
			$valeur_search = new SOAPExternalKeySearch ( array (
					new SOAPSearchCondition ( 'name', $valeur ) ) );
		} else {
			$valeur_search = '';
		}
		
		return $valeur_search;
	}

	/**
	 * Convertit les donnees renvoyees par le soap en array ou xml
	 * @param stdClass $donnees
	 * @param string $type array|xml
	 * @return array xml
	 */
	public function convertit_donnees($donnees, $type = "xml") {
		$xml_vm_info = new SimpleXMLElement ( "<?xml version=\"1.0\" encoding=\"UTF-8\"?><objects/>" );
		$this ->renvoi_donnees_xml ( $donnees, $xml_vm_info );
		$xml = xml::creer_xml ( $this ->getListeOptions () );
		$xml ->import_dom_a_partir_de_simpleXML ( $xml_vm_info, "objects" );
		
		switch ($type) {
			case "array" :
				return $xml ->renvoi_donnee ( "/objects" );
			case "xml" :
			default :
			//retour standard de la fonction
		}
		
		return $xml;
	}

	/**
	 * Transforme un soap VMWare en SimpleXMLElement
	 * @param stdClass|array $object_src
	 * @param SimpleXMLElement $xml_output
	 * @return vmwareWsclient
	 */
	public function renvoi_donnees_xml($object_src, &$xml_output) {
		if (is_array ( $object_src )) {
			foreach ( $object_src as $key => $value ) {
				$this ->traite_valeur_xml ( $key, $value, $xml_output );
			}
		} elseif (is_object ( $object_src )) {
			if (isset ( $object_src->key ) && isset ( $object_src->value )) {
				$this ->traite_valeur_xml ( $object_src->key, $object_src->value, $xml_output );
			} else {
				foreach ( $object_src as $key => $value ) {
					$this ->traite_valeur_xml ( $key, $value, $xml_output );
				}
			}
		}
		
		return $this;
	}

	/**
	 * Choix du traitement en fonction de la valeur. Traitement particulier pour propSet
	 * @param string $key
	 * @param stdClass|array|string $value
	 * @param SimpleXMLElement $xml_output
	 */
	public function traite_valeur_xml($key, $value, &$xml_output) {
		if (is_numeric ( $key )) {
			$this ->renvoi_donnees_xml ( $value, $xml_output );
		} elseif (is_array ( $value )) {
			//Le propSet a une exection
			if ($key != "values" && isset ( $value [0] )) {
				foreach ( $value as $key_MOR => $value_MOR ) {
					$subnode = $xml_output ->addChild ( $key );
					$this ->traite_valeur_xml ( $key_MOR, $value_MOR, $subnode );
				}
			} else {
				$subnode = $xml_output ->addChild ( $key );
				$this ->renvoi_donnees_xml ( $value, $subnode );
			}
		} elseif (is_object ( $value )) {
			$subnode = $xml_output ->addChild ( $key );
			$this ->renvoi_donnees_xml ( $value, $subnode );
		} else {
			$xml_output ->addChild ( $key, str_replace ( "&", "&amp;", $value ) );
		}
		
		return $this;
	}

	/**
	 * ***************************** vimService *******************************
	 */
	
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function __clone() {
		// Force la copie de this->xxx, sinon
		// il pointera vers le meme objet.
		if (is_object ( $this->objet_soap ))
			$this->objet_soap = clone $this ->getObjetSoap ();
		if (is_object ( $this->itop_datas ))
			$this->itop_datas = clone $this ->getObjetItopdatas ();
	}

	/**
	 * @codeCoverageIgnore
	 * @return itop_datas
	 */
	public function &getObjetItopdatas() {
		return $this->itop_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopdatas(&$itop_datas) {
		$this->itop_datas = $itop_datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNomServeur() {
		return $this->nom_serveur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNomServeur($nom_serveur) {
		$this->nom_serveur = $nom_serveur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return soap
	 */
	public function &getObjetSoap() {
		return $this->objet_soap;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSoap(&$objet_soap) {
		$this->objet_soap = $objet_soap;
		return $this;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

?>
