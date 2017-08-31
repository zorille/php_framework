<?php
/**
 * Serveur de serveur.
 * @author dvargas
 */
/**
 * class serveur_datas
 *
 * @package Lib
 * @subpackage WebService
 */
class serveur_datas extends abstract_log {
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
	private $serveur_data = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type serveur_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet serveur_connexion_url
	 * @return serveur_datas
	 */
	static function &creer_serveur_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new serveur_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return serveur_datas
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
	 */
	protected function _valideOption($chemin_option) {
		$this->onDebug ( __METHOD__, 1 );
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
	 * Valide la presence de la definition d'un serveur nomme : $nom
	 *
	 * @param string $nom  
	 * @param string $protocole rest|soap|both par defaut 'both'
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_serveur_data($nom, $protocole='both') {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getserveurDatas () as $serveur_data ) {
			if (strtolower ( $nom ) != strtolower ( $serveur_data ["nom"] )) {
				$this->onDebug ( strtolower ( $nom ) . " != " . strtolower ( $serveur_data ["nom"] ), 2 );
				continue;
			}
			
			$this->getObjetUtilisateurs ()
				->retrouve_utilisateurs_array ( $serveur_data );
			
			if($protocole!='both' && isset($serveur_data['protocole']) && $serveur_data['protocole']!=$protocole){
				$this->onDebug ( $protocole . " != " .  $serveur_data ["protocole"] , 2 );
				continue;
			}
			$serveur_data ["username"] = $this->getObjetUtilisateurs ()
				->getUsername ();
			$serveur_data ["password"] = $this->getObjetUtilisateurs ()
				->getPassword ();
			$this->onDebug ( $serveur_data, 2 );
			return $serveur_data;
		}
		
		return false;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function __clone() {
		// Force la copie de this->xxx, sinon
		// il pointera vers le meme objet.
		if(is_object($this->class_utilisateurs))
			$this->class_utilisateurs = clone $this->getObjetUtilisateurs();
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &getServeurDatas() {
		return $this->serveur_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setServeurData($serveur_data) {
		if (is_array ( $serveur_data )) {
			$this->serveur_data = $serveur_data;
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
		$help = array_merge ( $help, utilisateurs::help () );
		
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
