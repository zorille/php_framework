<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_ci
 *
 * @package Lib
 * @subpackage itop
 */
class itop_ci extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $format = '';
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $id = '';
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $donnees = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_wsclient_rest
	 */
	private $itop_wsclient_rest = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $oql_ci = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_ci. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_wsclient_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_ci
	 */
	static function &creer_itop_ci(&$liste_option, &$itop_webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_ci ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"itop_wsclient_rest" => $itop_webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_ci
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setObjetItopWsclientRest ( $liste_class ["itop_wsclient_rest"] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}
	
	/**
	 * Cree un ligne type ' AND champ1='valeur1' AND champ2='valeur2' ... etc
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return string
	 */
	public function prepare_oql_fields (
			$fields) {
				$liste_fields = "";
				foreach ( $fields as $champ => $valeur ) {
					$liste_fields .= " AND " . $champ . "='" . $valeur . "'";
				}
				return $liste_fields;
	}
	
	/**
	 * Enregistre les donnees class, key et fields du premier objet de la reponse REST
	 * @param array $ci Retour d'un requete REST sur itop
	 * @return itop_ci
	 */
	public function enregistre_ci_a_partir_rest($ci) {
		foreach ( $ci ['objects'] as $donnees ) {
			$this ->setFormat ( $donnees ['class'] ) 
				->setId ( $donnees ['key'] ) 
				->setDonnees ( $donnees ['fields'] );
			break;
		}
		
		return $this;
	}

	/**
	 * Permet de trouver un CI dans itop a partir d'une requete OQL 
	 * @return itop_ci|false False en cas d'erreur sans leve d'Exception ($error=false)
	 * @throws Exception
	 */
	public function recupere_ci_dans_itop() {
		//Sinon, on requete iTop
		return $this ->getObjetItopWsclientRest () 
			->core_get ( $this ->getFormat (), $this ->getOqlCi () );
	}

	/**
	 * Permet de trouver un CI dans itop a partir d'une requete OQL et enregistre les donnees du CI dans l'objet
	 * @return itop_ci
	 * @throws Exception
	 */
	public function retrouve_ci() {
		//Si il y a deja un objet itop_ci, alors le ci existe
		if ( $this ->getDonnees () ) {
			return $this;
		}
		//Sinon, on requete iTop
		$ci = $this ->recupere_ci_dans_itop ();
		if ($ci ['message'] != 'Found: 1') {
			//Le ci n'existe pas donc on emet une exception
			return $this ->onError ( "Probleme avec la requete : " . $this ->getOqlCi () . " : " . $ci ['message'] );
		}
		
		return $this ->enregistre_ci_a_partir_rest ( $ci );
	}

	/**
	 * Valide que le CI existe et est unique dans itop et enregistre les donnees du CI dans l'objet s'il est trouve
	 * @return itop_ci|null
	 */
	public function valide_ci_existe() {
		//Si il y a deja un objet itop_ci, alors le ci existe
		if ( $this ->getDonnees () ) {
			return $this;
		}
		//Sinon, on requete iTop
		$ci = $this ->recupere_ci_dans_itop ();
		if ($ci ['message'] != 'Found: 1') {
			//Le ci n'existe pas
			$this ->onDebug ( "Probleme avec la requete : " . $this ->getOqlCi () . " : " . $ci ['message'], 1 );
			return null;
		}
		
		return $this ->enregistre_ci_a_partir_rest ( $ci );
	}

	/**
	 * Creer un CI dans itop du format de l'objet
	 * @param string $name
	 * @param array $params
	 * @return itop_ci
	 * @throws Exception
	 */
	public function creer_ci($name, $params) {
		$this ->onDebug ( __METHOD__, 1 );
		
		if (! $this ->valide_ci_existe ()) {
			$this ->onInfo ( "Ajout de : " . $name );
			$ci = $this ->getObjetItopWsclientRest () 
				->core_create ( $this ->getFormat (), '', $params );
			$this ->enregistre_ci_a_partir_rest ( $ci );
		}
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFormat($format) {
		$this->format = $format;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setId($id) {
		$this->id = $id;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDonnees() {
		return $this->donnees;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDonnees($donnees) {
		if (is_array ( $donnees )) {
			$this->donnees = $donnees;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return itop_wsclient_rest
	 */
	public function &getObjetItopWsclientRest() {
		return $this->itop_wsclient_rest;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopWsclientRest(&$itop_wsclient_rest) {
		$this->itop_wsclient_rest = $itop_wsclient_rest;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOqlCi() {
		return $this->oql_ci;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOqlCi($oql_ci) {
		$this->oql_ci = $oql_ci;
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "itop_ci :";
		
		return $help;
	}
}
?>
