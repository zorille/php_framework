<?php
/**
 * Gestion de SiteScope.
 * @author dvargas
 */
/**
 * class sitescope_fonctions_standards
 *
 * @package Lib
 * @subpackage SiteScope
 */
class sitescope_fonctions_standards extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $arbre_moniteurs = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $arbre_groupes = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $groupes_id = array ();
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
	private $dependance = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var int
	 */
	private $serveur_id = 0;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $full_path_separator = "!";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_connexions = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var sitescope_soap_configuration
	 */
	private $sitescope_soap_configuration_ref = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type sitescope_fonctions_standards.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param int $serveur_id ID de serveur sitescope
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return sitescope_fonctions_standards
	 */
	static function &creer_sitescope_fonctions_standards(&$liste_option, $serveur_id = 0, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new sitescope_fonctions_standards ( $serveur_id, $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return sitescope_fonctions_standards
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjetSisSoapConfigurationRef ( sitescope_soap_configuration::creer_sitescope_soap_configuration ( $this->getListeOptions (), false ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param options &$liste_option pointeur sur liste_option
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($serveur_id = 0, $sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->setServeurId ( $serveur_id );
	}

	/**
	 * ******************* Gestion des données de sitescope **************************
	 */
	
	/**
	 * Creer un arbre de moniteur a partir d'une extraction FullConfiguration
	 *
	 * @param array $full_confs   
	 * @return sitescope_fonctions_standards     	
	 */
	public function retrouve_arbre_moniteurs_from_FullConf(&$full_confs) {
		if (isset ( $full_confs ["snapshot_groupSnapshotChildren"] )) {
			$this->retrouve_arbre_moniteurs_total ( $full_confs ["snapshot_groupSnapshotChildren"] );
		}
		
		return $this->onDebug ( $this->getArbreMoniteurs (), 2 );
	}

	/**
	 * Creer un arbre de moniteur a partir d'une extraction FullConfiguration
	 *
	 * @param array $full_confs Resultat d'un getFullConfiguration
	 * @return sitescope_fonctions_standards     	
	 */
	public function retrouve_arbre_moniteurs_total(&$full_confs, $chemin_groupe = "SiteScopeRoot") {
		// Pour chaque entree on extrait les donnees
		foreach ( $full_confs as $nom => $groupe_data ) {
			if ($nom == "Health") {
				continue;
			}
			// on prepare les donnees du groupe
			$this->ajoute_groupe_data ( $nom, $groupe_data ["entitySnapshot_properties"], $chemin_groupe );
			if ($chemin_groupe == "SiteScopeRoot") {
				$full_chemin_groupe = $nom;
			} else {
				$full_chemin_groupe = $chemin_groupe . $this->full_path_separator . $nom;
			}
			
			// si le groupe a des sous-groupes
			if (isset ( $groupe_data ["snapshot_groupSnapshotChildren"] ) && $groupe_data ["snapshot_groupSnapshotChildren"] != "NULL") {
				// On cherche le dernier element du tableau
				$this->retrouve_arbre_moniteurs_total ( $groupe_data ["snapshot_groupSnapshotChildren"], $full_chemin_groupe );
			}
			$this->onDebug ( "Groupe en cours : " . $groupe_data ["entitySnapshot_name"], 1 );
			$this->onDebug ( $groupe_data, 2 );
			// Si des moniteurs sont declares
			if (isset ( $groupe_data ["snapshot_monitorSnapshotChildren"] ) && is_array ( $groupe_data ["snapshot_monitorSnapshotChildren"] )) {
				$this->onDebug ( "On retrouve les moniteurs de : " . $groupe_data ["entitySnapshot_name"], 1 );
				
				foreach ( $groupe_data ["snapshot_monitorSnapshotChildren"] as $moniteur ) {
					$moniteur ["group_id"] = $this->getGroupeNumber ( $full_chemin_groupe );
					$moniteur ["fullpathname"] = $full_chemin_groupe . $this->full_path_separator . $moniteur ["entitySnapshot_name"];
					$this->AddArbreMoniteurs ( $moniteur, $groupe_data ["entitySnapshot_name"] );
				}
			}
		}
		
		return $this;
	}

	/**
	 * Creer un tableau de dependance a partir de champ entitySnapshot
	 * @return sitescope_fonctions_standards     
	 */
	public function creer_arbre_dependance_from_FullConf() {
		$dependance = array ();
		foreach ( $this->getArbreMoniteurs () as $groupe => $liste_moniteurs_machine ) {
			foreach ( $liste_moniteurs_machine as $moniteur ) {
				if (isset ( $moniteur ["entitySnapshot_properties"] ["_ownerID"] )) {
					$dependance [$moniteur ["entitySnapshot_properties"] ["_ownerID"] . " " . $moniteur ["entitySnapshot_properties"] ["_id"]] = $moniteur ["entitySnapshot_properties"] ["_name"];
				}
			}
		}
		
		$this->setDependance ( $dependance );
		
		return $this->onDebug ( $this->getDependance (), 2 );
	}

	/**
	 * Permet de recuperer la liste des machines dans sitescope a prtir d'un getFullConfig
	 *
	 * @param array $full_confs Resultat d'un getFullConfiguration
	 * @return sitescope_fonctions_standards
	 */
	public function retrouve_arbre_machines_from_FullConf(&$full_confs) {
		$arbre_machines = array ();
		
		// Pour chaque machine, on retrouve le detail des informations
		if (isset ( $full_confs ["snapshot_preferenceSnapShot"] ["snapshot_remoteNTSnapshotChildren"] )) {
			$this->onDebug ( "snapshot_remoteNTSnapshotChildren", 1 );
			foreach ( $full_confs ["snapshot_preferenceSnapShot"] ["snapshot_remoteNTSnapshotChildren"] as $name => $machine_data ) {
				$arbre_machines ["RemoteNTInstancePreferences_" . $machine_data ["entitySnapshot_properties"] ["_id"]] = $machine_data ["entitySnapshot_properties"];
				$arbre_machines ["RemoteNTInstancePreferences_" . $machine_data ["entitySnapshot_properties"] ["_id"]] ["type_os"] = 1;
				$arbre_machines ["machines"] [$machine_data ["entitySnapshot_properties"] ["_host"]] = "RemoteNTInstancePreferences_" . $machine_data ["entitySnapshot_properties"] ["_id"];
			}
		}
		if (isset ( $full_confs ["snapshot_preferenceSnapShot"] ["snapshot_remoteUNIXSnapshotChildren"] )) {
			$this->onDebug ( "snapshot_remoteUNIXSnapshotChildren", 1 );
			foreach ( $full_confs ["snapshot_preferenceSnapShot"] ["snapshot_remoteUNIXSnapshotChildren"] as $name => $machine_data ) {
				$arbre_machines ["RemoteUnixInstancePreferences_" . $machine_data ["entitySnapshot_properties"] ["_id"]] = $machine_data ["entitySnapshot_properties"];
				$arbre_machines ["RemoteUnixInstancePreferences_" . $machine_data ["entitySnapshot_properties"] ["_id"]] ["type_os"] = 2;
				$arbre_machines ["machines"] [$machine_data ["entitySnapshot_properties"] ["_host"]] = "RemoteUnixInstancePreferences_" . $machine_data ["entitySnapshot_properties"] ["_id"];
			}
		}
		
		$this->setArbreMachines ( $arbre_machines );
		
		return $this->onDebug ( $this->getArbreMachines (), 2 );
	}

	/**
	 * Ajoute les proprietes par groupe
	 *
	 * @param string $nom        	
	 * @param array $proprietes    
	 * @return sitescope_fonctions_standards    	
	 */
	public function ajoute_groupe_data($nom, $proprietes, $fullpathname) {
		if (isset ( $proprietes ["_externalId"] )) {
			$externalId = $proprietes ["_externalId"];
		} else {
			$externalId = 0;
		}
		
		if ($fullpathname == "SiteScopeRoot") {
			$this->setOneGroupeNumber ( $nom, $externalId );
			$full_path = $nom;
		} else {
			$this->setOneGroupeNumber ( $fullpathname . $this->full_path_separator . $nom, $externalId );
			$full_path = $fullpathname . $this->full_path_separator . $nom;
		}
		
		// Ajout du groupe a la liste
		$groupe_data = $proprietes;
		$groupe_data ["id"] = $this->getGroupeNumber ( $full_path );
		$groupe_data ["fullpathname"] = $full_path;
		$groupe_data ["id_parent"] = $this->getGroupeNumber ( $fullpathname );
		return $this->AddArbreGroupes ( $groupe_data, $full_path );
	}

	/**
	 * Reset les tableaux de l'objet
	 */
	public function reset_tableaux() {
		return $this->setArbreMoniteurs ( array () )
			->setArbreGroupes ( array () )
			->setArbreMachines ( array () )
			->setDependance ( array () )
			->setGroupeNumbers ( array () );
	}

	/**
	 * ******************* Gestion des données de sitescope **************************
	 */
	
	/**
	 * ******************* appel au webservice de sitescope **************************
	 */
	
	/**
	 * Fait un delete du type demande.
	 *
	 * @param sitescope_soap_configuration &$soapClient_configuration
	 * @param int $isgroup 0 = non, 1 = oui
	 * @param array $path Chemin jusqu'au moniteur/groupe a desactiver
	 *
	 * @return Boolean String si appel OK,false en cas d'erreur.
	 */
	public function transmet_delete_sitescope(&$soapClient_configuration, $isgroup, $path, $temps_desactivation_en_sec) {
		$fullpathname = implode ( $this->full_path_separator, $path );
		
		if ($isgroup == "1") {
			$this->onDebug ( "On supprime le groupe : " . $fullpathname . " pour : " . $temps_desactivation_en_sec . " s", 1 );
			$retour_sans_erreur = $soapClient_configuration->deleteGroupEx ( $path );
		} else {
			$this->onDebug ( "On supprime le moniteur : " . $fullpathname . " pour : " . $temps_desactivation_en_sec . " s", 1 );
			$retour_sans_erreur = $soapClient_configuration->deleteMonitorEx ( $path );
		}
		
		return $retour_sans_erreur;
	}

	/**
	 * Fait un disable du type demande.
	 *
	 * @param sitescope_soap_configuration &$soapClient_configuration
	 * @param string $type MONITOR ou ALERT
	 * @param int $temps_desactivation_en_sec        	
	 * @param int $isgroup 0 = non, 1 = oui
	 * @param array $path Chemin jusqu'au moniteur/groupe a desactiver
	 * @param string $reason Message de desactivation
	 *        	
	 * @return Boolean String si appel OK,false en cas d'erreur.
	 * @throws Exception
	 */
	public function transmet_disable_sitescope(&$soapClient_configuration, $type, $temps_desactivation_en_sec, $isgroup, $path, $reason, $from_en_sec = 0) {
		$retour_sans_erreur = true;
		$fullpathname = implode ( $this->full_path_separator, $path );
		switch ($type) {
			case "MONITOR" :
				if ($isgroup == "1") {
					$this->onDebug ( "On desactive le groupe : " . $fullpathname . " pour : " . $temps_desactivation_en_sec . " s", 1 );
					$retour_sans_erreur = $soapClient_configuration->disableGroupWithDescription ( $path, $from_en_sec * 1000, $temps_desactivation_en_sec * 1000, $reason );
				} else {
					$this->onDebug ( "On desactive le moniteur : " . $fullpathname . " pour : " . $temps_desactivation_en_sec . " s", 1 );
					$retour_sans_erreur = $soapClient_configuration->disableMonitorWithDescription ( $path, $from_en_sec * 1000, $temps_desactivation_en_sec * 1000, $reason );
				}
				break;
			case "ALERT" :
				$this->onDebug ( "On desactive les alertes du groupe : " . $fullpathname . " pour : " . $temps_desactivation_en_sec . " s", 1 );
				$retour_sans_erreur = $soapClient_configuration->disableAssociatedAlerts ( $path, $from_en_sec * 1000, $temps_desactivation_en_sec * 1000, $reason );
				break;
			default :
				return $this->onError ( "Le type " . $type . " n'est pas supporte.", "", 5108 );
		}
		
		return $retour_sans_erreur;
	}

	/**
	 * Fait un disable du type demande.
	 *
	 * @param sitescope_soap_configuration &$soapClient_configuration
	 * @param string $type MONITOR ou ALERT
	 * @param int $isgroup 0 = non, 1 = oui
	 * @param array $path Chemin jusqu'au moniteur/groupe a desactiver
	 * @param string $reason Message de desactivation
	 *        	
	 * @return Boolean true si appel OK,false en cas d'erreur.
	 * @throws Exception
	 */
	public function transmet_enable_sitescope(&$soapClient_configuration, $type, $isgroup, $path, $reason) {
		$retour_sans_erreur = true;
		$fullpathname = implode ( $this->full_path_separator, $path );
		switch ($type) {
			case "MONITOR" :
				if ($isgroup == "1") {
					$this->onDebug ( "On active le groupe : " . $fullpathname, 1 );
					$retour_sans_erreur = $soapClient_configuration->enableGroupEx ( $path, $reason );
				} else {
					$this->onDebug ( "On active le moniteur : " . $fullpathname, 1 );
					$retour_sans_erreur = $soapClient_configuration->enableMonitorWithDescription ( $path, $reason );
				}
				break;
			case "ALERT" :
				$this->onDebug ( "On active les alertes du groupe : " . $fullpathname, 1 );
				$retour_sans_erreur = $soapClient_configuration->enableAssociatedAlerts ( $path, $reason );
				break;
			default :
				return $this->onError ( "Le type " . $type . " n'est pas supporte.", "", 5108 );
		}
		
		return $retour_sans_erreur;
	}

	/**
	 * ******************* appel au webservice de sitescope **************************
	 */
	
	/**
	 * ******************* Gestion des connexions multi-sitescope **************************
	 */
	/**
	 * Connecte plusieurs sitescopes en parallele.<br/>
	 * Supprime de la liste 'liste_noms_sis' les sitescopes non connectes.
	 *
	 * @param array &$liste_noms_sis
	 */
	public function connexion_soap_configuration_de_tous_les_sitescopes(&$liste_noms_sis) {
		if (! is_array ( $liste_noms_sis )) {
			return $this->onError ( "Il faut un tableau de nom de sitescope", "", 5109 );
		}

		foreach ( $liste_noms_sis as $id => $sis ) {
			// On prepare les variables SiteScope WebService
			$soapClient_configuration = clone $this->getObjetSisSoapConfigurationRef ();
			
			// On valide d'avoir une config pour ce sitescope
			if ($soapClient_configuration->valide_presence_sitescope_data ( $sis ) === false) {
				$this->onWarning ( "Pas de configuration pour le serveur : " . $sis );
				unset ( $liste_noms_sis [$id] );
				continue;
			}
			
			// On connecte le sitescope
			try {
				if($soapClient_configuration->connect ( $sis )===false){
					//Si le connect est juste a false sans exception
					$this->nettoie_sitescope_non_connecte($liste_noms_sis, $sis, $id);
					continue;
				}
			} catch ( Exception $e ) {
				//Si le connect emet une exception
				$this->nettoie_sitescope_non_connecte($liste_noms_sis, $sis, $id);
				continue;
			}
			
			$this->setOneListeConnexion ( $sis, $soapClient_configuration );
		}
		
		return true;
	}
	
	/**
	 * Supprime le sitescope non connecte de la liste des sitescopes
	 * @param array $liste_noms_sis
	 * @param string $sis
	 * @param int $id id du sitescope dans la liste
	 * @return sitescope_fonctions_standards
	 */
	public function nettoie_sitescope_non_connecte(&$liste_noms_sis,$sis,$id){
		try {
			$this->onError ( "Pas de connexion au sitescope : " . $sis, "", 5110 );
		} catch ( Exception $e ) {
			//pas de traitement de l'exception
		}
		unset ( $liste_noms_sis [$id] );
		
		return $this;
	}

	/**
	 * applique le disable MONITOR ou ALERT sur un sitescope
	 *
	 * @param string $nom_sis nom du sitescope
	 * @param string $type MONITOR ou ALERT
	 * @param int $temps_desactivation temps en seconde de desactivation
	 * @param int $isgroup 0 = non, 1 = oui
	 * @param array $path Chemin jusqu'au moniteur/groupe a desactiver
	 * @param string $raison Message de desactivation
	 * @param number $from temps en seconde avant desactivation
	 * @return Boolean String si appel OK,false en cas d'erreur.
	 */
	public function applique_disable_sur_un_sitescope($nom_sis, $type, $temps_desactivation, $isgroup, $path, $raison, $from = 0) {
		$soap_con = $this->getOneListeConnexion ( $nom_sis );
		if ($soap_con !== false) {
			return $this->transmet_disable_sitescope ( $soap_con, $type, $temps_desactivation, $isgroup, $path, $raison, $from );
		}
		$this->onWarning ( "Pas de connexion trouvee pour : " . $nom_sis );
		$this->setMessage ( "Pas de connexion trouvee pour : " . $nom_sis );
		return false;
	}

	/**
	 * applique le enable MONITOR ou ALERT sur un sitescope
	 *
	 * @param string $nom_sis nom du sitescope
	 * @param string $type MONITOR ou ALERT
	 * @param int $isgroup 0 = non, 1 = oui
	 * @param array $path Chemin jusqu'au moniteur/groupe a desactiver
	 * @param string $raison Message de desactivation
	 * @return Boolean String si appel OK,false en cas d'erreur.
	 */
	public function applique_enable_sur_un_sitescope($nom_sis, $type, $isgroup, $path, $raison) {
		$soap_con = $this->getOneListeConnexion ( $nom_sis );
		if ($soap_con !== false) {
			return $this->transmet_enable_sitescope ( $soap_con, $type, $isgroup, $path, $raison );
		}
		$this->onWarning ( "Pas de connexion trouvee pour : " . $nom_sis );
		$this->setMessage ( "Pas de connexion trouvee pour : " . $nom_sis );
		return false;
	}

	/********************* Gestion des connexions multi-sitescope ***************************/
	
	/******************************* ACCESSEURS ********************************/
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getArbreMoniteurs() {
		return $this->arbre_moniteurs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setArbreMoniteurs($moniteur) {
		$this->arbre_moniteurs = $moniteur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &AddArbreMoniteurs($moniteur, $champs) {
		if (! isset ( $this->arbre_moniteurs [$champs] )) {
			$this->arbre_moniteurs [$champs] = array ();
		}
		array_push ( $this->arbre_moniteurs [$champs], $moniteur );
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGroupeNumbers() {
		return $this->groupes_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGroupeNumbers($groupes_id) {
		$this->groupes_id = $groupes_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getGroupeNumber($groupe_name) {
		$entree = $groupe_name . $this->getServeurId ();
		if (isset ( $this->groupes_id [$entree] )) {
			return $this->groupes_id [$entree];
		}
		
		return - 1;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOneGroupeNumber($groupe_name, $externalID) {
		$entree = $groupe_name . $this->getServeurId ();
		if (! isset ( $this->groupes_id [$entree] )) {
			if ($externalID === 0) {
				$md5 = md5 ( $entree );
				$externalID = substr ( $md5, 0, 8 ) . '-' . substr ( $md5, 8, 4 ) . '-' . substr ( $md5, 12, 4 ) . '-' . substr ( $md5, 16, 4 ) . '-' . substr ( $md5, 20, 12 );
			}
			$this->groupes_id [$entree] = $externalID;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &AddArbreGroupes($groupe_data, $champs) {
		if (! isset ( $this->arbre_groupes [$champs] )) {
			$this->arbre_groupes [$champs] = $groupe_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getArbreGroupes() {
		return $this->arbre_groupes;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setArbreGroupes($arbre_groupes) {
		$this->arbre_groupes = $arbre_groupes;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getArbreMachines() {
		return $this->arbre_machines;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setArbreMachines($arbre_machines) {
		$this->arbre_machines = $arbre_machines;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDependance() {
		return $this->dependance;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDependance($dependance) {
		$this->dependance = $dependance;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getServeurId() {
		return $this->serveur_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setServeurId($serveur_id) {
		$this->serveur_id = $serveur_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeConnexion() {
		return $this->liste_connexions;
	}

	/**
	 * @codeCoverageIgnore
	 * @return sitescope_soap_configuration
	 */
	public function getOneListeConnexion($nom_sis) {
		if (isset ( $this->liste_connexions [$nom_sis] )) {
			return $this->liste_connexions [$nom_sis];
		}
		
		return false;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeConnexion($liste_connexions) {
		$this->liste_connexions = $liste_connexions;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOneListeConnexion($nom_sis, $connexion) {
		$this->liste_connexions [$nom_sis] = $connexion;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return sitescope_soap_configuration
	 */
	public function &getObjetSisSoapConfigurationRef() {
		return $this->sitescope_soap_configuration_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSisSoapConfigurationRef(&$sitescope_soap_configuration_ref) {
		$this->sitescope_soap_configuration_ref = $sitescope_soap_configuration_ref;
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
}
?>
