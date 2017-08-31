<?php
/**
 * @author dvargas
 */
/**
 * class fonctions_standards_sgbd<br> Gere la connexion a une base SQL.
 *
 * @package Lib
 * @subpackage SQL
 */
class fonctions_standards_sgbd extends abstract_log {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type fonctions_standards_sgbd. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standards_sgbd
	 */
	static function &creer_fonctions_standards_sgbd(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fonctions_standards_sgbd ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standards_sgbd
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Parse les options passees en ligne de commande et set toutes les variables necessaires aux differentes connexions SGBD. Sous-fonction de creer_connexion_liste_option.
	 *
	 * @param options &$liste_option Pointeur sur les arguments
	 * @return array Renvoi un tableau avec toutes les variables pre-charge.
	 */
	static public function parse_option_sgbd(&$liste_option) {
		$sgbd_options = array ();
		
		$sql = $liste_option ->getOption ( "sql" );
		
		if (is_array ( $sql )) {
			$liste_bases = $liste_option ->getOption ( array (
					"sql",
					"liste_bases" ) );
			
			if (is_array ( $liste_bases )) {
				foreach ( $liste_bases as $nom_base => $sgbd ) {
					$sgbd_options ["liste_bases"] [$nom_base] = fonctions_standards_sgbd::verifie_variable_sgbd ( $sgbd );
				}
			} else {
				$sgbd_options = fonctions_standards_sgbd::verifie_variable_sgbd ( $sql );
			}
		} elseif ($sql == "oui") {
			$sgbd_options = fonctions_standards_sgbd::verifie_variable_sgbd ( $liste_option ->getListeOption () );
		} elseif ($sql == "multi") {
			$nom_sgbd = $liste_option ->getOption ( "noms_sgbd" );
			
			if (! is_array ( $nom_sgbd )) {
				$nom_sgbd = explode ( " ", $nom_sgbd );
			}
			
			foreach ( $nom_sgbd as $sgbd ) {
				$sgbd_options ["liste_bases"] [$sgbd] = fonctions_standards_sgbd::verifie_variable_sgbd ( $liste_option ->getListeOption (), $sgbd );
			}
		}
		
		return $sgbd_options;
	}

	/**
	 * Verifie et set par defaut les variables necessaires a une connexion SGBD.<br> Sous-fonction de creer_connexion_liste_option.
	 *
	 * @param array $liste_donnees_db
	 * @return array Renvoi un tableau de valeur.
	 */
	static public function verifie_variable_sgbd($liste_donnees_db, $nom_base = "") {
		$CODE_RETOUR = array ();
		if ($nom_base !== "")
			$nom_base .= "_";
		
		if (isset ( $liste_donnees_db [$nom_base . "SQL_using"] ) && $liste_donnees_db [$nom_base . "SQL_using"] != "")
			$CODE_RETOUR ["using"] = $liste_donnees_db [$nom_base . "SQL_using"];
		elseif (isset ( $liste_donnees_db [$nom_base . "using"] ) && $liste_donnees_db [$nom_base . "using"] != "")
			$CODE_RETOUR ["using"] = $liste_donnees_db [$nom_base . "using"];
		else
			$CODE_RETOUR ["using"] = "oui";
		
		if (isset ( $liste_donnees_db [$nom_base . "dbhost"] ) && $liste_donnees_db [$nom_base . "dbhost"] != "")
			$CODE_RETOUR ["dbhost"] = $liste_donnees_db [$nom_base . "dbhost"];
		else
			$CODE_RETOUR ["dbhost"] = "localhost";
		
		if (isset ( $liste_donnees_db [$nom_base . "username"] ) && $liste_donnees_db [$nom_base . "username"] != "")
			$CODE_RETOUR ["username"] = $liste_donnees_db [$nom_base . "username"];
		else
			$CODE_RETOUR ["username"] = "nobody";
		
		if (isset ( $liste_donnees_db [$nom_base . "password"] ) && $liste_donnees_db [$nom_base . "password"] != "")
			$CODE_RETOUR ["password"] = $liste_donnees_db [$nom_base . "password"];
		else
			$CODE_RETOUR ["password"] = "";
		
		if (isset ( $liste_donnees_db [$nom_base . "crypt_password"] ) && $liste_donnees_db [$nom_base . "crypt_password"] != "")
			$CODE_RETOUR ["crypt_password"] = $liste_donnees_db [$nom_base . "crypt_password"];
		
		if (isset ( $liste_donnees_db [$nom_base . "database"] ) && $liste_donnees_db [$nom_base . "database"] != "")
			$CODE_RETOUR ["database"] = $liste_donnees_db [$nom_base . "database"];
		else
			$CODE_RETOUR ["database"] = "aucuneBD";
		
		if (isset ( $liste_donnees_db [$nom_base . "encode"] ) && $liste_donnees_db [$nom_base . "encode"] != "")
			$CODE_RETOUR ["encode"] = $liste_donnees_db [$nom_base . "encode"];
		else
			$CODE_RETOUR ["encode"] = "utf8";
		
		if (isset ( $liste_donnees_db [$nom_base . "maj_db"] ) && $liste_donnees_db [$nom_base . "maj_db"] == "non")
			$CODE_RETOUR ["maj_db"] = "non";
		else
			$CODE_RETOUR ["maj_db"] = "oui";
		
		if (isset ( $liste_donnees_db [$nom_base . "port"] ) && $liste_donnees_db [$nom_base . "port"] != "")
			$CODE_RETOUR ["port"] = $liste_donnees_db [$nom_base . "port"];
		else
			$CODE_RETOUR ["port"] = 3306;
		
		if (isset ( $liste_donnees_db [$nom_base . "socket"] ) && $liste_donnees_db [$nom_base . "socket"] != "")
			$CODE_RETOUR ["socket"] = $liste_donnees_db [$nom_base . "socket"];
		else
			$CODE_RETOUR ["socket"] = ""; // /var/lib/mysql/mysql.sock
		

		if (isset ( $liste_donnees_db [$nom_base . "options"] ) && $liste_donnees_db [$nom_base . "options"] != "")
			$CODE_RETOUR ["options"] = $liste_donnees_db [$nom_base . "options"];
		else
			$CODE_RETOUR ["options"] = "";
		
		if (isset ( $liste_donnees_db [$nom_base . "salvosize"] ) && $liste_donnees_db [$nom_base . "salvosize"] != "")
			$CODE_RETOUR ["salvosize"] = $liste_donnees_db [$nom_base . "salvosize"];
		else
			$CODE_RETOUR ["salvosize"] = "";
		
		if (isset ( $liste_donnees_db [$nom_base . "SQL_type"] ) && $liste_donnees_db [$nom_base . "SQL_type"] != "")
			$CODE_RETOUR ["type"] = $liste_donnees_db [$nom_base . "SQL_type"];
		elseif (isset ( $liste_donnees_db [$nom_base . "type"] ) && $liste_donnees_db [$nom_base . "type"] != "")
			$CODE_RETOUR ["type"] = $liste_donnees_db [$nom_base . "type"];
		else
			$CODE_RETOUR ["type"] = "mysql";
		
		if (isset ( $liste_donnees_db [$nom_base . "SQL_sort_en_erreur"] ) && $liste_donnees_db [$nom_base . "SQL_sort_en_erreur"] == "non")
			$CODE_RETOUR ["sort_en_erreur"] = "non";
		elseif (isset ( $liste_donnees_db [$nom_base . "sort_en_erreur"] ) && $liste_donnees_db [$nom_base . "sort_en_erreur"] == "non")
			$CODE_RETOUR ["sort_en_erreur"] = "non";
		else
			$CODE_RETOUR ["sort_en_erreur"] = "oui";
		
		return $CODE_RETOUR;
	}

	/**
	 * Permet de renvoyer uniquement les variables en parametre
	 *
	 * @param options $liste_option
	 * @param string $db_recherche nom de la base recherche
	 * @return array boolean des variales, False sinon
	 */
	static function renvoi_parametres_database(&$liste_option, $db_recherche) {
		$liste_sgbd = fonctions_standards_sgbd::parse_option_sgbd ( $liste_option );
		// Pour chaque base on recupere la liste des donnees et on cree la connexion
		if (isset ( $liste_sgbd ["liste_bases"] ) && is_array ( $liste_sgbd ["liste_bases"] )) {
			foreach ( $liste_sgbd ["liste_bases"] as $nom_base => $liste_variables ) {
				// On se base sur le nom du champ XML
				if ($liste_variables ["using"] == "oui") {
					switch ($nom_base) {
						case $db_recherche :
						case $db_recherche . "_prod" :
						case $db_recherche . "_preprod" :
						case $db_recherche . "_dev" :
							abstract_log::onDebug_standard ( $liste_variables, 1 );
							return $liste_variables;
						default :
					}
				}
			}
		}
		
		return false;
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml et creer un objet db.<br> Include : $INCLUDE_FONCTIONS<br> Arguments reconnus :<br> Pour une base :<br> --sql=oui <br> --dbhost=xx <br> --username=xx <br> --password=xx <br> --database=xx <br> --SQL_type=mysql <br> --SQL_sort_en_erreur=oui<br>
	 * Pour les multi-bases :<br> --sql=multi <br> --noms_sgbd=\"NOM_BD1 NOM_BD2 ...\"<br> --NOM_BD1_dbhost=xx <br> --NOM_BD1_username=xx <br> --NOM_BD1_password=xx <br> --NOM_BD1_database=xx <br> --NOM_BD1_SQL_type=mysql //par defaut mysql<br> --NOM_BD1_SQL_sort_en_erreur=oui //par defaut oui<br> --NOM_BD1_using=oui/non //par defaut oui<br> --NOM_BD2_dbhost=xx <br> .<br> .<br> .
	 *
	 * @param options &$liste_option Pointeur sur les arguments
	 * @return db array false un objet DB, soit un tableau d'objet DB, soit FALSE en cas d'erreur.
	 */
	static public function creer_connexion_liste_option(&$liste_option) {
		// voir help SQL
		// Si on a une base dans les options
		abstract_log::onDebug_standard ( "creer_connexion_liste_option", 1 );
		
		$utilisateurs = utilisateurs::creer_utilisateurs ( $liste_option );
		if ($liste_option ->verifie_option_existe ( "sql", true ) !== false) {
			// @codeCoverageIgnoreStart
			$liste_sgbd = fonctions_standards_sgbd::parse_option_sgbd ( $liste_option );
			abstract_log::onDebug_standard ( $liste_sgbd, 2 );
			
			// Pour chaque base on recupere la liste des donnees et on cree la connexion
			foreach ( $liste_sgbd ["liste_bases"] as $nom_base => $liste_variables ) {
				$utilisateurs ->retrouve_utilisateurs_array ( $liste_variables );
				$liste_variables ["username"] = $utilisateurs ->getUsername ();
				$liste_variables ["password"] = $utilisateurs ->getPassword ();
				
				// le dbnom est obligatoire dans ce cas
				if ($liste_variables ["using"] == "oui") {
					switch ($nom_base) {
						case "cacti_prod" :
						case "cacti_preprod" :
						case "cacti_dev" :
							$CODE_RETOUR [$nom_base] = requete_complexe_cacti::creer_requete_complexe_cacti ( $liste_option, $liste_variables ["sort_en_erreur"] );
							break;
						case "gestion_cacti_prod" :
						case "gestion_cacti_preprod" :
						case "gestion_cacti_dev" :
							$CODE_RETOUR [$nom_base] = requete_complexe_gestion_cacti::creer_requete_complexe_gestion_cacti ( $liste_option, $liste_variables ["sort_en_erreur"] );
							break;
						case "gestion_idat_prod" :
						case "gestion_idat_preprod" :
						case "gestion_idat_dev" :
							$CODE_RETOUR [$nom_base] = requete_complexe_gestion_idat::creer_requete_complexe_gestion_idat ( $liste_option, $liste_variables ["sort_en_erreur"] );
							break;
						case "gestion_sam_prod" :
						case "gestion_sam_preprod" :
						case "gestion_sam_dev" :
							$CODE_RETOUR [$nom_base] = requete_complexe_gestion_sam::creer_requete_complexe_gestion_sam ( $liste_option, $liste_variables ["sort_en_erreur"] );
							break;
						case "gestion_zabbix_prod" :
						case "gestion_zabbix_preprod" :
						case "gestion_zabbix_dev" :
							$CODE_RETOUR [$nom_base] = requete_complexe_gestion_zabbix::creer_requete_complexe_gestion_zabbix ( $liste_option, $liste_variables ["sort_en_erreur"] );
							break;
						case "sitescope_prod" :
						case "sitescope_preprod" :
						case "sitescope_dev" :
							$CODE_RETOUR [$nom_base] = requete_complexe_sitescope::creer_requete_complexe_sitescope ( $liste_option, $liste_variables ["sort_en_erreur"] );
							break;
						case "tools_prod" :
						case "tools_preprod" :
						case "tools_dev" :
							$CODE_RETOUR [$nom_base] = requete_complexe_tools::creer_requete_complexe_tools ( $liste_option, $liste_variables ["sort_en_erreur"] );
							break;
						default :
							$CODE_RETOUR [$nom_base] = requete::creer_requete ( $liste_option, $liste_variables ["sort_en_erreur"] );
					}
					
					$CODE_RETOUR [$nom_base] ->setDbServeur ( $liste_variables ["dbhost"] ) 
						->setDatabase ( $liste_variables ["database"] ) 
						->setDbUsername ( $liste_variables ["username"] ) 
						->setDbPassword ( $liste_variables ["password"] ) 
						->setDbType ( $liste_variables ["type"] ) 
						->setDbEncodage ( $liste_variables ["encode"] ) 
						->setDbSocket ( $liste_variables ["socket"] ) 
						->setDbPort ( $liste_variables ["port"] ) 
						->setDbMaj ( $liste_variables ["maj_db"] ) 
						->prepare_connexion ();
					// En cas de dry-run, on desactive la MAJ de la base de donnees
					if ($liste_option ->verifie_option_existe ( "dry-run" ) !== false) {
						$CODE_RETOUR [$nom_base] ->desactive_maj_db ();
					}
				} else {
					$CODE_RETOUR [$nom_base] = 0;
				}
			}
		} else {
			// @codeCoverageIgnoreEnd
			$CODE_RETOUR = false;
		}
		
		abstract_log::onDebug_standard ( $CODE_RETOUR, 2 );
		return $CODE_RETOUR;
	}

	/**
	 * Applique une requete sur la base defini dans les options.<br> Si la connexion n'existe pas, la fonction cree la connexion et la referme.<br> Include : $INCLUDE_FONCTIONS<br> @codeCoverageIgnore
	 * @param options &$liste_option Liste d'options.
	 * @param string $requete Requete a appliquer.
	 * @param db $connexion_local Connexion des type bd pour appliquer la requete.
	 * @return array false un tableau de resultat, FALSE sinon.
	 */
	static public function requete_sql(&$liste_option, $requete, $connexion_local = "non") {
		$flag_connexion = false;
		
		// si on a pas de connexion alors on en creer une et on la ferme a la fin
		// On creer la connexion a la base de donnees
		if ($connexion_local === "non") {
			$flag_connexion = true;
			$connexion_local = fonctions_standards_sgbd::creer_connexion_liste_option ( $liste_option );
		}
		
		if ($connexion_local) {
			if (is_array ( $connexion_local )) {
				foreach ( $connexion_local as $local ) {
					$connexion = $local;
				}
			} else {
				$connexion = $connexion_local;
			}
			// On creer la liste complete des resultat de la base
			try {
				$resultat = $connexion ->faire_requete ( $requete );
			} catch ( Exception $e ) {
				abstract_log::onError_standard ( $e ->getMessage (), "", $e ->getCode () );
			}
			$i = 0;
			if ($resultat !== false && $resultat ->rowCount () > 0) {
				foreach ( $resultat as $row ) {
					$liste_resultat [$i] = $row;
					$i ++;
				}
			} else {
				$liste_resultat = array ();
			}
		} else {
			$liste_resultat = false;
			abstract_log::onError_standard ( "(SGBD) il faut une connexion pour travailler", "", 3004 );
		}
		
		if ($flag_connexion)
			$connexion ->close ();
		
		abstract_log::onDebug_standard ( $liste_resultat, 2 );
		return $liste_resultat;
	}

	/**
	 * Set le $db_cacti avec la base cacti standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_cacti false renvoi l'objet requete_complexe_cacti, false en cas d'erreur.
	 */
	static public function recupere_db(&$connexion, $db_name, $sort_en_erreur = false) {
		if ($connexion && isset ( $connexion [$db_name . "_prod"] )) {
			$db = $connexion [$db_name . "_prod"];
		} elseif ($connexion && isset ( $connexion [$db_name . "_preprod"] )) {
			$db = $connexion [$db_name . "_preprod"];
		} elseif ($connexion && isset ( $connexion [$db_name . "_dev"] )) {
			$db = $connexion [$db_name . "_dev"];
		} elseif ($sort_en_erreur) {
			return abstract_log::onError_standard ( "Il n'y a pas de connexion a la base " . $db_name . ".", "", 3004 );
		} else {
			$db = false;
		}
		
		return $db;
	}

	/**
	 * Set le $db_cacti avec la base cacti standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_cacti false renvoi l'objet requete_complexe_cacti, false en cas d'erreur.
	 */
	static public function recupere_db_cacti(&$connexion, $sort_en_erreur = false) {
		return fonctions_standards_sgbd::recupere_db ( $connexion, "cacti", $sort_en_erreur );
	}

	/**
	 * Set le $db_cacti avec la base cacti standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_gestion_cacti false renvoi l'objet requete_complexe_cacti, false en cas d'erreur.
	 */
	static public function recupere_db_gestion_cacti(&$connexion, $sort_en_erreur = false) {
		return fonctions_standards_sgbd::recupere_db ( $connexion, "gestion_cacti", $sort_en_erreur );
	}

	/**
	 * Set le $db_idat avec la base idat standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_gestion_idat false renvoi l'objet requete_complexe_idat, false en cas d'erreur.
	 */
	static public function recupere_db_gestion_idat(&$connexion, $sort_en_erreur = false) {
		return fonctions_standards_sgbd::recupere_db ( $connexion, "gestion_idat", $sort_en_erreur );
	}
	
	/**
	 * Set le $db_sam avec la base sam standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_gestion_sam false renvoi l'objet requete_complexe_sam, false en cas d'erreur.
	 */
	static public function recupere_db_gestion_sam(&$connexion, $sort_en_erreur = false) {
		return fonctions_standards_sgbd::recupere_db ( $connexion, "gestion_sam", $sort_en_erreur );
	}

	/**
	 * Set le $db_zabbix avec la base zabbix standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_gestion_zabbix false renvoi l'objet requete_complexe_zabbix, false en cas d'erreur.
	 */
	static public function recupere_db_gestion_zabbix(&$connexion, $sort_en_erreur = false) {
		return fonctions_standards_sgbd::recupere_db ( $connexion, "gestion_zabbix", $sort_en_erreur );
	}

	/**
	 * Set le $db_sitescope avec la base sitescope standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_sitescope false renvoi l'objet requete_complexe_sitescope, false en cas d'erreur.
	 */
	static public function recupere_db_sitescope(&$connexion, $sort_en_erreur = false) {
		return fonctions_standards_sgbd::recupere_db ( $connexion, "sitescope", $sort_en_erreur );
	}

	/**
	 * Set le $db_bo avec la base bo standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_bo false renvoi l'objet requete_complexe_bo, false en cas d'erreur.
	 */
	static public function recupere_db_bo(&$connexion, $sort_en_erreur = false) {
		return fonctions_standards_sgbd::recupere_db ( $connexion, "bo", $sort_en_erreur );
	}

	/**
	 * Set le $db_cmdb_vodafone avec la base cmdb_vodafone standard.
	 *
	 * @param array $connexion
	 * @param bool $sort_en_erreur
	 * @return requete_complexe_cmdb_vodafone false renvoi l'objet requete_complexe_cmdb_vodafone, false en cas d'erreur.
	 */
	static public function recupere_db_cmdb_vodafone(&$connexion, $sort_en_erreur = false) {
		return fonctions_standards_sgbd::recupere_db ( $connexion, "cmdb_vodafone", $sort_en_erreur );
	}

	/**
	 *
	 * @static @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Permet de se connecter a des bases connues";
		$help [__CLASS__] ["text"] [] .= "La tag xml doit porter un nom specifique :";
		$help [__CLASS__] ["text"] [] .= " <sql using=\"oui\">";
		$help [__CLASS__] ["text"] [] .= "  <liste_bases>";
		$help [__CLASS__] ["text"] [] .= "   <cacti using=\"oui\" sort_en_erreur=\"oui\">";
		$help [__CLASS__] ["text"] [] .= "    Pour les bases type cacti";
		$help [__CLASS__] ["text"] [] .= "   </cacti>";
		$help [__CLASS__] ["text"] [] .= "  </liste_bases>";
		$help [__CLASS__] ["text"] [] .= " </sql>";
		$help [__CLASS__] ["text"] [] .= "\t--dry-run permet de ne pas modifier les bases de donnees.";
		
		return $help;
	}
}
?>
