<?php
/**
 * Gestion de Cacti.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class cacti_datas
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_datas extends serveur_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $includes_data = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $path_data = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return cacti_datas
	 */
	static function &creer_cacti_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_datas
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_cacti_param ();
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
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur,$entete );
		
	}

	/**
	 *
	 * @return cacti_datas|boolean cacti_datas si OK, False sinon.
	 */
	public function retrouve_cacti_param() {
		$donnee_cacti = $this->_valideOption ( array (
				"cacti_machines",
				"serveur" 
		) );
		
		// Gestion des proxys
		$includes = array ();
		$path = array ();
		foreach ( $donnee_cacti as $serveur_data ) {
			// Les includes
			if (isset ( $serveur_data ["includes"] )) {
				$includes [$serveur_data ["cacti_env"]] = $serveur_data ["includes"];
			}
			
			// Les includes
			if (isset ( $serveur_data ["path"] )) {
				$path [$serveur_data ["cacti_env"]] = $serveur_data ["path"];
			}
		}
		
		return $this->setServeurData ( $donnee_cacti )
			->setIncludesData ( $includes )
			->setPathData ( $path );
	}

	/**
	 * Valide la presence de la definition d'un cacti nomme : $nom
	 *
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_cacti_data($nom) {
		return $this->valide_presence_serveur_data ( $nom );
	}

	/**
	 * Met au format standard Cacti les noms de CI
	 * @param string $code_client Tri-gramme du client (cacti_env)
	 * @param string $ci_name Nom du CI
	 * @return string
	 */
	public function prepare_nom_ci_version_cacti($code_client, $ci_name) {
		return $code_client . "/" . $ci_name;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getIncludesDatas() {
		return $this->includes_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIncludesData($nom_client) {
		if (! isset ( $this->includes_data [$nom_client] )) {
			return $this->onError ( "Ce client " . $nom_client . " n'existe pas dans includes." );
		}
		
		return $this->includes_data [$nom_client];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setIncludesData($includes_data) {
		if (is_array ( $includes_data )) {
			$this->includes_data = $includes_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPathDatas() {
		return $this->path_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPathData($nom_client) {
		if (! isset ( $this->path_data [$nom_client] )) {
			return $this->onError ( "Ce client " . $nom_client . " n'existe pas dans path." );
		}
		
		return $this->path_data [$nom_client];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setPathData($path_data) {
		if (is_array ( $path_data )) {
			$this->path_data = $path_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setPath($path) {
		$this->path = $path;
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

	/**
	 * (non-PHPdoc)
	 * @codeCoverageIgnore
	 * @see lib/fork/message#__destruct()
	 */
	public function __destruct() {
	}
}
?>
