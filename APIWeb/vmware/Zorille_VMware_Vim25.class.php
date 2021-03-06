<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class Vim25<br>
 * Class qui permet d'instancie toutes les methodes vim25
 * 
 * @package Lib
 * @subpackage VMWare
 */
class Vim25 extends vmwareVim25ManagedObject {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type Vim25.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $vmware_webservice Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Vim25
	 */
	static function &creer_Vim25(&$liste_option, &$vmware_webservice, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Vim25 ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareWsclient" => $vmware_webservice 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Vim25
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
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
	 * To Delete des qu'une autre methode apparait
	 * @return string
	 */
	public function affiche_les_Tests() {
		return "affiche";
	}
	/************************* Accesseurs ***********************/
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
