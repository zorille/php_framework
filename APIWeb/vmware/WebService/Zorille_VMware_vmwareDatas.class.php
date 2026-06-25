<?php
/**
 * Serveur de vmware.
 * @author dvargas
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class vmwareDatas
 *
 * @package Lib
 * @subpackage VMWare
 */
class vmwareDatas extends Core\serveur_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $wsdl_data = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareDatas.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet serveur_connexion_url
	 * @return vmwareDatas
	 * @throws Exception
	 */
	static function &creer_vmwareDatas(Core\options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__ ): vmwareDatas
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new vmwareDatas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareDatas
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_vmware_param ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__ ) {
		// Serveur de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 *
	 * @return vmwareDatas True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_vmware_param(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		$donnee_vmware = $this->_valideOption ( array (
				"vmware_machines",
				"serveur" 
		) );
		
		$this->setServeurData ( $donnee_vmware );
		
		//Gestion des WSDL
		$wsdl_vmware = $this->_valideOption ( array (
				"vmware_machines",
				"wsdl" 
		) );
		
		$this->setWsdlData ( $wsdl_vmware );
		
		return $this;
	}

	/**
	 * Valide la presence de la definition d'un vmware nomme : $nom
	 *
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_vmware_data(string $nom): array
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->valide_presence_serveur_data ( $nom );
	}

	/**
	 * Valide la presence de la definition d'un vmware nomme : $nom
	 *
	 * @param $wsdl
	 * @return array|bool false informations de configuration, false sinon.
	 * @throws Exception
	 */
	public function retrouve_wsdl($wsdl): array|bool
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_wsdl = $this->getWsdlDatas ();
		if (! isset ( $liste_wsdl [$wsdl] )) {
			return $this->onError ( "Ce wsdl " . $wsdl . " n'existe pas.", "", 5105 );
		}
		if (is_array ( $liste_wsdl [$wsdl] )) {
			return $liste_wsdl [$wsdl] [0];
		}
		return $liste_wsdl [$wsdl];
	}

	/**
	 * Connexion au soap preferences de vmware
	 *
	 * @param string $nom nom du vmware a connecter
	 * @param string $wsdl
	 * @return bool|array TRUE si connexion ok, FALSE sinon
	 * @throws Exception
	 */
	public function recupere_donnees_vmware_serveur(string $nom = "", string $wsdl = ""): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($nom == "") {
			return $this->onError ( "Il faut un nom de vmware pour se connecter.", "", 5103 );
		}
		if ($wsdl == "") {
			return $this->onError ( "Il faut un wsdl de vmware pour se connecter.", "", 5104 );
		}
		$serveur_data = $this->valide_presence_vmware_data ( $nom );
		if ($serveur_data === false) {
			$this->onWarning ( "Pas de configuration de vmware pour se connecter." );
			return false;
		}
		
		$serveur_data ["wsdl"] = $this->retrouve_wsdl ( $wsdl );
		return $serveur_data;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlDatas(): bool|array
	{
		return $this->wsdl_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWsdlData($wsdl_data): static
	{
		if (is_array ( $wsdl_data )) {
			$this->wsdl_data = $wsdl_data;
		}
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
