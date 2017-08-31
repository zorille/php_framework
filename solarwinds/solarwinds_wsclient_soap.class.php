<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class solarwinds_wsclient_soap<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage solarwinds
 */
class solarwinds_wsclient_soap extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var solarwinds_datas
	 */
	private $solarwinds_datas = null;
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
	 * Instancie un objet de type solarwinds_wsclient_soap. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param solarwinds_datas &$solarwinds_datas Reference sur un objet solarwinds_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return solarwinds_wsclient_soap
	 */
	static function &creer_solarwinds_wsclient_soap(&$liste_option, &$solarwinds_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new solarwinds_wsclient_soap ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"solarwinds_datas" => $solarwinds_datas ) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return solarwinds_wsclient_soap
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["solarwinds_datas"] )) {
			$this ->onError ( "il faut un objet de type solarwinds_datas" );
			return false;
		}
		$this ->setObjetSolarwindsDatas ( $liste_class ["solarwinds_datas"] ) 
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
	 * Prepare l'url de connexion au solarwinds nomme $nom
	 * @param string $nom
	 * @return boolean solarwinds_wsclient_soap
	 */
	public function prepare_connexion($nom) {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_data_solarwinds = $this ->getObjetSolarwindsDatas () 
			->valide_presence_solarwinds_data ( $nom, 'soap' );
		if ($liste_data_solarwinds === false) {
			return $this ->onError ( "Aucune definition de solarwinds pour " . $nom );
		}
		$this ->setNomServeur ( $nom );
		
		if (! isset ( $liste_data_solarwinds ["username"] )) {
			return $this ->onError ( "Il faut un username dans la liste des parametres solarwinds" );
		}
		if (! isset ( $liste_data_solarwinds ["password"] )) {
			return $this ->onError ( "Il faut un password dans la liste des parametres solarwinds" );
		}
		if (! isset ( $liste_data_solarwinds ["url"] )) {
			return $this ->onError ( "Il faut une url dans la liste des parametres solarwinds" );
		}
		
		//On gere la partie Soap webService
		$this ->getObjetSoap () 
			->setCacheWsdl ( WSDL_CACHE_NONE ) 
			->setLogin ( $liste_data_solarwinds ["username"] ) 
			->setPassword ( $liste_data_solarwinds ["password"] ) 
			->retrouve_variables_tableau ( $this ->getObjetSolarwindsDatas () 
			->recupere_donnees_solarwinds_serveur ( $nom, "solarwindsService" ) ) 
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
				->getOption ( "dry-run" ) !== false && preg_match ( "/^Create$|^Update$|^BulkUpdate$|^Delete$|^BulkDelete$/", $fonction ) === 1) {
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
	 * Read
	 * @return array
	 * @throws Exception
	 */
	public function Read() {
		$this ->onDebug ( __METHOD__, 1 );

		$result = $this ->applique_requete_soap ( "Read");
		
		$resultat = $this ->convertit_donnees ( $result, "array" );
		if (isset ( $resultat ['result'] )) {
			return $resultat ['result'];
		}
		return $resultat;
	}

	/**
	 * Gestion des erreur solarwinds
	 * @param SOAPResult $resultat_soap
	 * @return solarwinds_wsclient_soap
	 * @throws Exception
	 */
	public function gestion_erreur($resultat_soap) {
		if (! $resultat_soap instanceof SOAPResult) {
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
		if (is_object ( $this->solarwinds_datas ))
			$this->solarwinds_datas = clone $this ->getObjetSolarwindsDatas ();
	}

	/**
	 * @codeCoverageIgnore
	 * @return solarwinds_datas
	 */
	public function &getObjetSolarwindsDatas() {
		return $this->solarwinds_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSolarwindsDatas(&$solarwinds_datas) {
		$this->solarwinds_datas = $solarwinds_datas;
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
