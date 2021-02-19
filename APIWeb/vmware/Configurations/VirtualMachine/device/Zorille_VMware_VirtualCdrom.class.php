<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \Exception as Exception;
use \soapvar as soapvar;
/**
 * class VirtualCdrom<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualCdrom extends VirtualDevice {
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $migrateCache = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualCdrom.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualCdrom
	 */
	static function &creer_VirtualCdrom(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new VirtualCdrom ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualCdrom
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

	/*********************** Creation de l'objet *********************/
	/************************* Methodes VMWare ***********************/
	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @return soapvar
	 */
	public function &renvoi_objet_soap() {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'VirtualCdrom' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
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
