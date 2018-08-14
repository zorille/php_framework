<?php
/**
 * Gestion de dolibarr.
 * @author dvargas
 */
/**
 * class dolibarr_datas
 *
 * @package Lib
 * @subpackage dolibarr
 */
class dolibarr_datas extends serveur_datas {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type dolibarr_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return dolibarr_datas
	 */
	static function &creer_dolibarr_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new dolibarr_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return dolibarr_datas
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_dolibarr_param ();
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
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return boolean True est OK, False sinon.
	 */
	public function retrouve_dolibarr_param() {
		$this->onDebug ( __METHOD__, 1 );
		$donnee_dolibarr = $this->_valideOption ( array (
				"dolibarr_machines",
				"serveur" 
		) );
		
		return $this->setServeurData ( $donnee_dolibarr );
	}

	/**
	 * Valide la presence de la definition d'un dolibarr nomme : $nom
	 *
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_dolibarr_data($nom) {
		$this->onDebug ( __METHOD__, 1 );
		return $this->valide_presence_serveur_data ( $nom );
	}

	/******************************* ACCESSEURS ********************************/
	
	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "dolibarr Datas :";
		$help [__CLASS__] ["text"] [] .= "\t--dolibarr_machines_serveur {Donnees du/des serveur/s} Donnees contenus dans le fichier de configuration";
		
		return $help;
	}
}
?>
