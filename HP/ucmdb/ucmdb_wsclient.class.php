<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

/**
 * class ucmdb_wsclient<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage ucmdb
 */
class ucmdb_wsclient extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var ucmdb_datas
	 */
	private $ucmdb_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $auth = '';
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
	private $soap = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type ucmdb_wsclient. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param gestion_connexion_url &$gestion_connexion_url Reference sur un objet gestion_connexion_url
	 * @param ucmdb_datas &$ucmdb_datas Reference sur un objet ucmdb_datas
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ucmdb_wsclient
	 */
	static function &creer_ucmdb_wsclient(&$liste_option, &$ucmdb_datas, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new ucmdb_wsclient ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"ucmdb_datas" => $ucmdb_datas ) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ucmdb_wsclient
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["ucmdb_datas"] )) {
			$this ->onError ( "il faut un objet de type ucmdb_datas" );
			return false;
		}
		$this ->setObjetUcmdbDatas ( $liste_class ["ucmdb_datas"] ) 
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
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au ucmdb nomme $nom
	 * @param string $nom
	 * @return boolean ucmdb_wsclient
	 */
	public function prepare_connexion($nom,$service="LoginService") {
		$liste_data_ucmdb = $this ->getObjetUcmdbDatas () 
			->valide_presence_ucmdb_data ( $nom );
		if ($liste_data_ucmdb === false) {
			return $this ->onError ( "Aucune definition de ucmdb pour " . $nom );
		}
		$this ->setNomServeur ( $nom );
		
		if (! isset ( $liste_data_ucmdb ["username"] )) {
			return $this ->onError ( "Il faut un username dans la liste des parametres ucmdb" );
		}
		if (! isset ( $liste_data_ucmdb ["password"] )) {
			return $this ->onError ( "Il faut un password dans la liste des parametres ucmdb" );
		}
		if (! isset ( $liste_data_ucmdb ["url"] )) {
			return $this ->onError ( "Il faut une url dans la liste des parametres ucmdb" );
		}
		
		//On gere la partie Soap webService
		$this ->getObjetSoap () 
			->setCacheWsdl ( WSDL_CACHE_NONE ) 
			->retrouve_variables_tableau ( $this ->getObjetUcmdbDatas () 
			->recupere_donnees_ucmdb_serveur ( $nom, $service ) ) 
			->connect ();
		
		return $this;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return ucmdb_datas
	 */
	public function &getObjetUcmdbDatas() {
		return $this->ucmdb_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetUcmdbDatas(&$ucmdb_datas) {
		$this->ucmdb_datas = $ucmdb_datas;
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
