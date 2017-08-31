<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_host_interfaces
 * Gere une liste d'interface
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_host_interfaces extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_interface = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_interface_cli = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_host_interface
	 */
	private $zabbix_interface_reference = NULL;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_host_interfaces.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_host_interfaces
	 */
	static function &creer_zabbix_host_interfaces(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_host_interfaces ( $sort_en_erreur, $entete );
		return $objet ->_initialise ( array ( 
				"options" => $liste_option ) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->setObjetHostInterfaceRef ( zabbix_host_interface::creer_zabbix_host_interface ( $liste_class ["options"] ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return false|zabbix_host_interfaces
	 * @throws Exception
	 */
	public function retrouve_zabbix_param() {
		$this ->onDebug ( __METHOD__, 1 );
		//Gestion des interfaces
		$liste_interfaces = $this ->_valideOption ( array ( 
				"zabbix", 
				"interfaces" ) );
		if (! is_array ( $liste_interfaces )) {
			$liste_interfaces = array ( 
					$liste_interfaces );
		}
		
		return $this ->ajoute_interfaces_par_ligne ( $liste_interfaces );
	}

	/**
	 * Valide la presence d'un objet interface similaire dans la liste de interfaces
	 * @param zabbix_host_interface $objet_interface Objet interface a comparer a la liste existante
	 * @return zabbix_host_interface|False zabbix_host_interface si l'objet existe dans la liste, false sinon
	 */
	public function verifie_interface_existe(&$objet_interface) {
		//On valide que l'interface n'existe pas deja
		foreach ( $this ->getListeInterface () as $interface_local ) {
			if ($interface_local ->compare_interface ( $objet_interface )) {
				return $interface_local;
			}
		}
		
		return false;
	}

	/**
	 * Ajoute a l'objet en cours toutes les interfaces de $liste_interfaces non existante. Liste d'interfaces au format 'Type|Main|Port' ('agent|oui|10050').
	 * @param array $liste_interfaces Liste de ligne au format 'Type|Main|Port' ('agent|oui|10050').
	 * @return zabbix_host_interfaces
	 * @throws Exception
	 */
	public function ajoute_interfaces_par_ligne($liste_interfaces) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$liste = array ();
		foreach ( $liste_interfaces as $interface ) {
			//On creer un objet interface
			$objet_interface = clone $this ->getObjetHostInterfaceRef ();
			$objet_interface ->retrouve_zabbix_param ( $interface );
			
			//On valide que l'interface n'existe pas deja
			if (! $this ->verifie_interface_existe ( $objet_interface )) {
				$liste [count ( $liste )] = $objet_interface;
				$this ->setAjoutInterface ( $objet_interface );
			}
		}
		
		$this ->setListeInterfaceCli ( $liste );
		
		return $this;
	}

	/**
	 * Ajoute a l'objet en cours toutes les interfaces de $liste_interfaces non existante. Liste d'interfaces recuperees dans zabbix. 
	 * @param array $liste_interfaces
	 * @return zabbix_host_interfaces
	 */
	public function ajoute_interfaces($liste_interfaces) {
		$this ->onDebug ( __METHOD__, 1 );
		foreach ( $liste_interfaces as $interface ) {
			$objet_interface = clone $this ->getObjetHostInterfaceRef ();
			$objet_interface ->setType ( $interface ["type"] );
			$objet_interface ->setMain ( $interface ["main"] );
			$objet_interface ->setPort ( $interface ["port"] );
			$objet_interface ->creer_une_interface ( $interface ["useip"], $interface ["ip"], $interface ["dns"] );
			
			$obj_interface = $this ->verifie_interface_existe ( $objet_interface );
			if ($obj_interface !== false) {
				$obj_interface ->setInterfaceId ( $interface ["interfaceid"] );
				$obj_interface ->setHostId ( $interface ["hostid"] );
			} else {
				//on ajoute l'interface
				$objet_interface ->setInterfaceId ( $interface ["interfaceid"] );
				$objet_interface ->setHostId ( $interface ["hostid"] );
				$this ->setAjoutInterface ( $objet_interface );
			}
		}
		
		return $this;
	}

	/**
	 * Redefinie la liste d'interface du host en retirant les interfaces contenuent dans l'ojbet courant.
	 * @param array $liste_interfaces Liste d'interface du host dans zabbix
	 * @return zabbix_host_interfaces
	 */
	public function supprime_interfaces($liste_interfaces) {
		$this ->onDebug ( __METHOD__, 1 );
		$liste_interface_finale = array ();
		
		foreach ( $liste_interfaces as $position => $interface ) {
			$objet_interface = clone $this ->getObjetHostInterfaceRef ();
			$objet_interface ->setType ( $interface ["type"] );
			$objet_interface ->setMain ( $interface ["main"] );
			$objet_interface ->setPort ( $interface ["port"] );
			$objet_interface ->creer_une_interface ( $interface ["useip"], $interface ["ip"], $interface ["dns"] );
			
			if ($this ->verifie_interface_existe ( $objet_interface )) {
				continue;
			}
			$objet_interface ->setInterfaceId ( $interface ["interfaceid"] );
			$objet_interface ->setHostId ( $interface ["hostid"] );
			$liste_interface_finale [count ( $liste_interface_finale )] = $objet_interface;
		}
		$this ->setListeInterface ( $liste_interface_finale );
		
		return $this;
	}

	/**
	 * Creer un definition de toutes les interfaces listees dans la class
	 * @return array;
	 */
	public function creer_definition_host_interfaces_ws() {
		$this ->onDebug ( __METHOD__, 1 );
		$donnees_interfaces = array ();
		
		foreach ( $this ->getListeInterface () as $interface ) {
			$donnees_interfaces [count ( $donnees_interfaces )] = $interface ->creer_definition_host_interface_ws ();
		}
		
		return $donnees_interfaces;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeInterface() {
		return $this->liste_interface;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeInterface($liste_interface) {
		$this->liste_interface = $liste_interface;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutInterface(&$interface) {
		$this->liste_interface [count ( $this->liste_interface )] = $interface;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeInterfaceCli() {
		return $this->liste_interface_cli;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeInterfaceCli($liste_interface_cli) {
		$this->liste_interface_cli = $liste_interface_cli;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetHostInterfaceRef() {
		return $this->zabbix_interface_reference;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetHostInterfaceRef(&$zabbix_interface_reference) {
		$this->zabbix_interface_reference = $zabbix_interface_reference;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Host Interfaces :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_interfaces 'agent/snmp|main: oui/non|port' 'agent/snmp|main: oui/non|port' ... liste des groupes du CI";
		$help = array_merge ( $help, zabbix_host_interface::help () );
		
		return $help;
	}
}
?>
