<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualDiskConfigSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDiskConfigSpec extends VirtualDeviceConfigSpec {
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $migrateCache = false;
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDiskConfigSpec.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDiskConfigSpec
	 */
	static function &creer_VirtualDiskConfigSpec(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new VirtualDiskConfigSpec ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDiskConfigSpec
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
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = parent::renvoi_donnees_soap(true);
		$liste_proprietes ["migrateCache"] = $this->getMigrateCache ();
	
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}
	
	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap($arrayObject = false) {
		$soap_var= new soapvar ( $this->renvoi_donnees_soap(true), SOAP_ENC_OBJECT, 'VirtualDiskConfigSpec' );
		return $soap_var;
	}
	/************************* Methodes VMWare ***********************/
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getMigrateCache() {
		return $this->migrateCache;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setMigrateCache($migrateCache) {
		$this->migrateCache = $migrateCache;
		return $this;
	}
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
