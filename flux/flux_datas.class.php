<?php
/**
 * Serveur de serveur.
 * @author dvargas
 */
/**
 * class flux_datas
 * 
 * @package Lib
 * @subpackage serveur
 */
class flux_datas extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var utilisateurs
	 */
	private $class_utilisateurs = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $flux_data = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type flux_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet serveur_connexion_url
	 * @return flux_datas
	 */
	static function &creer_flux_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new flux_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return flux_datas
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjetUtilisateurs ( utilisateurs::creer_utilisateurs ( $liste_class ["options"] ) );
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
		// Serveur de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Extrait des parametres d'un liste d'option
	 * @codeCoverageIgnore
	 * @param string|array $chemin_option        	
	 * @return boolean string array
	 * @throws Exception
	 */
	protected function _valideOption($chemin_option) {
		if ($this->getListeOptions ()
			->verifie_variable_standard ( $chemin_option ) === false) {
			if (is_array ( $chemin_option )) {
				$chemin_option = implode ( "_", $chemin_option );
			}
			return $this->onError ( "Il manque le parametre : " . $chemin_option );
		}
		
		$datas = $this->getListeOptions ()
			->renvoi_variables_standard ( $chemin_option );
		
		if (is_array ( $datas ) && isset ( $datas ["#comment"] )) {
			unset ( $datas ["#comment"] );
		}
		
		return $datas;
	}
	
	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return boolean True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_flux_param($tag_liste_flux) {
		$donnee_flux = $this->_valideOption ( array (
				$tag_liste_flux,
				"serveur"
		) );
	
		return $this->setFluxData ( $donnee_flux );
	}

	/**
	 * Valide la presence de la definition d'un username nomme : $nom
	 *
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_flux_data($nom) {
		$this->onDebug ("valide_presence_flux_data",1);
		foreach ( $this->getFluxDatas() as $flux_data ) {
			if (strtolower ( $nom ) != strtolower ( $flux_data ["nom"] )) {
				$this->onDebug ( strtolower ( $nom ) . " != " . strtolower ( $flux_data ["nom"] ), 2 );
				continue;
			}
			
			$this->getObjetUtilisateurs ()
				->retrouve_utilisateurs_array ( $flux_data );
			$flux_data ["username"] = $this->getObjetUtilisateurs ()
				->getUsername ();
			$flux_data ["password"] = $this->getObjetUtilisateurs ()
				->getPassword ();
			$this->onDebug ( $flux_data, 2 );
			return $flux_data;
		}
		
		return false;
	}

	/******************************* ACCESSEURS ********************************/

	/**
	 * @codeCoverageIgnore
	 */
	public function &getFluxDatas() {
		return $this->flux_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFluxData($flux_data) {
		if (is_array ( $flux_data )) {
			$this->flux_data = $flux_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetUtilisateurs() {
		return $this->class_utilisateurs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetUtilisateurs(&$class_utilisateurs) {
		$this->class_utilisateurs = $class_utilisateurs;
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
