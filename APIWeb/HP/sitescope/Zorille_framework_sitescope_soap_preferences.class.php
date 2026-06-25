<?php
/**
 * Gestion de SiteScope.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
use \stdClass as stdClass;
/**
 * class sitescope_soap_preferences
 *
 * @package Lib
 * @subpackage SiteScope
 */
class sitescope_soap_preferences extends sitescope_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $arbre_machines = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $arbre_credentials = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $wsdl = "APIPreference";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type sitescope_soap_preferences.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return sitescope_soap_preferences
	 */
	static function &creer_sitescope_soap_preferences(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new sitescope_soap_preferences ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return sitescope_soap_preferences
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet 
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de sitescope_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Connexion au soap preferences de sitescope (APIPreference)
	 *
	 * @param string $nom nom du sitescope a connecter
	 * @return bool TRUE si connexion ok, FALSE sinon
	 * @throws Exception
	 */
	public function connect(string $nom = ""): bool
	{
		return $this->connexion ( $nom, $this->getWsdlNom () );
	}

	/**
	 * 
	 * @param stdClass $liste_OS
	 * @param string $OS peut prendre les valeurs NT/Unix
	 * @param boolean $flag_credentials precise si les credentials sont bien recuperes
	 * @param array $arbre_machines tableau a mettre a jour avec les informations recoltees
	 * @return sitescope_soap_preferences
	 */
	public function retrouve_donnees_machine(stdClass &$liste_OS, string $OS, bool $flag_credentials, array &$arbre_machines): static
	{
		$liste_creds = $this->getArbreCredentials ();
		
		if (is_object ( $liste_OS ) && isset ( $liste_OS->item )) {
			if (isset ( $liste_OS->item ["_id"] )) {
				$liste_OS->item = array (
						$liste_OS->item 
				);
			}
			foreach ( $liste_OS->item as $machine_data ) {
				// Gestion des credentials
				if (isset ( $machine_data ["_credentials"] ) && $machine_data ["_credentials"] != "" && $flag_credentials && isset ( $liste_creds [$machine_data ["_credentials"]] )) {
					$machine_data ["_credentials"] = $liste_creds [$machine_data ["_credentials"]] ["_name"];
				}
				
				$arbre_machines ["Remote" . $OS . "InstancePreferences_" . $machine_data ["_id"]] = $machine_data;
				$arbre_machines ["machines"] [$machine_data ["_host"]] = "Remote" . $OS . "InstancePreferences_" . $machine_data ["_id"];
			}
		}
		
		return $this;
	}

	/**
	 * Permet de recuperer la liste des machines dans sitescope
	 *
	 * @return sitescope_soap_preferences en cas d'erreur
	 * @throws Exception
	 */
	public function retrouve_arbre_machines(): static
	{
		$credentials = $this->retrouve_arbre_credentials ();
		$arbre_machines = array ();
		
		$liste_NT = $this->applique_requete_soap ( "getInstances", array (
				"RemoteNTInstancePreferences",
				"",
				"",
				"",
				1 
		) );
		$this->retrouve_donnees_machine ( $liste_NT, "NT", $credentials, $arbre_machines );
		
		$liste_UNIX = $this->applique_requete_soap ( "getInstances", array (
				"RemoteUnixInstancePreferences",
				"",
				"",
				"",
				1 
		) );
		$this->retrouve_donnees_machine ( $liste_UNIX, "Unix", $credentials, $arbre_machines );
		
		$this->setArbreMachines ( $arbre_machines );
		
		$this->onDebug ( $this->getArbreMachines (), 2 );
		return $this;
	}

	/**
	 *
	 * @param stdClass $liste_credentials
	 * @param $arbre_credentials
	 * @return sitescope_soap_preferences
	 */
	public function retrouve_donnees_credentials(stdClass &$liste_credentials, &$arbre_credentials): static
	{
		// Pour chaque machine, on retrouve le detail des informations
		if (is_object ( $liste_credentials ) && isset ( $liste_credentials->item )) {
			if (isset ( $liste_credentials->item ["_id"] )) {
				$arbre_credentials [$liste_credentials->item ["_id"]] = $liste_credentials->item;
			} else {
				foreach ( $liste_credentials->item as $credential_data ) {
					$arbre_credentials [$credential_data ["_id"]] = $credential_data;
				}
			}
		}
		
		return $this;
	}

	/**
	 * Permet de recuperer la liste des credentials dans sitescope
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function retrouve_arbre_credentials(): bool
	{
		$liste_credential = $this->applique_requete_soap ( "getInstances", array (
				"CredentialsInstancePreferences",
				"",
				"",
				"",
				1 
		) );
		
		$arbre_credentials = array ();
		$this->retrouve_donnees_credentials ( $liste_credential, $arbre_credentials );
		$this->setArbreCredentials ( $arbre_credentials );
		
		$this->onDebug ( $this->getArbreCredentials (), 2 );
		return true;
	}

	/**
	 * Permet de recuperer la liste des preferences dans sitescope
	 *
	 * @return false|array
	 */
	public function retrouve_liste_preferences(): bool|array
	{
		$liste_Preferences = $this->applique_requete_soap ( "getPreferenceTypes", array () );
		
		$arbre_Preferences = array ();
		// Pour chaque machine, on retrouve le detail des informations
		if (is_object ( $liste_Preferences ) && isset ( $liste_Preferences->item [0] )) {
			$arbre_Preferences = $liste_Preferences->item [0];
		}
		
		$this->onDebug ( $arbre_Preferences, 2 );
		return $arbre_Preferences;
	}

	/**
	 *
	 * @param stdClass $liste_donnees
	 * @param array $arbre_de_sortie tableau a mettre a jour avec les informations recoltees
	 * @return sitescope_soap_preferences
	 */
	public function retrouve_donnees_instances(stdClass &$liste_donnees, array &$arbre_de_sortie): static
	{
		// Pour chaque machine, on retrouve le detail des informations
		if (is_object ( $liste_donnees ) && isset ( $liste_donnees->item )) {
			if (! isset ( $liste_donnees->item [0] )) {
				$liste_donnees->item = array (
						$liste_donnees->item 
				);
			}
			
			foreach ( $liste_donnees->item as $liste_props ) {
				foreach ( $liste_props as $key => $valeur ) {
					if (! is_array ( $valeur )) {
						$valeur = trim ( $valeur );
					}
					$arbre_de_sortie [$key] = $valeur;
				}
			}
		}
		
		return $this;
	}

	/**
	 * Retrouve toutes les preferences d'un sitescope, hors creds.
	 * 
	 * @return boolean|array
	 * @throws Exception
	 */
	public function retrouve_toutes_les_preferences(): bool|array
	{
		$liste_preferences = $this->retrouve_liste_preferences ();
		
		$liste_prefs = array ();
		foreach ( $liste_preferences as $pref_type ) {
			if (trim ( $pref_type ) == "") {
				continue;
			}
			switch ($pref_type) {
				//La liste des machines est sur le getFullConf
				case "RemoteNTInstancePreferences" :
				case "RemoteUnixInstancePreferences" :
					continue 2;
			}
			$this->onInfo ( $pref_type );
			$resultat = $this->applique_requete_soap ( "getInstances", array (
					$pref_type,
					"",
					"",
					"",
					1 
			) );
			
			$liste_prefs [$pref_type] = array ();
			$this->retrouve_donnees_instances ( $resultat, $liste_prefs [$pref_type] );
		}
		
		return $liste_prefs;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getArbreMachines(): array
	{
		return $this->arbre_machines;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setArbreMachines($arbre_machines): static
	{
		$this->arbre_machines = $arbre_machines;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getArbreCredentials(): array
	{
		return $this->arbre_credentials;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setArbreCredentials($arbre_credentials): static
	{
		$this->arbre_credentials = $arbre_credentials;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlNom(): string
	{
		return $this->wsdl;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

