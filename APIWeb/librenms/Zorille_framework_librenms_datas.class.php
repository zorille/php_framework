<?php
/**
 * Gestion de librenms.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class librenms_datas
 *
 * @package Lib
 * @subpackage librenms
 */
class librenms_datas extends serveur_datas {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type librenms_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return librenms_datas
	 */
	static function &creer_librenms_datas(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new librenms_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return librenms_datas
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_librenms_param ();
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
	public function retrouve_librenms_param() {
		$this->onDebug ( __METHOD__, 1 );
		$donnee_librenms = $this->_valideOption ( array (
				"librenms_machines",
				"serveur" 
		) );
		
		return $this->setServeurData ( $donnee_librenms );
	}

	/**
	 * Valide la presence de la definition d'un librenms nomme : $nom
	 *
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_librenms_data($nom) {
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
		$help [__CLASS__] ["text"] [] .= "librenms_datas :";
		$help [__CLASS__] ["text"] [] .= "\t--librenms_machines_serveur {Donnees du/des serveur/s} Donnees contenus dans le fichier de configuration";
		
		return $help;
	}
}
?>
