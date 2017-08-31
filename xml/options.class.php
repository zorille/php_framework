<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class options<br>
 *
 * Gere les options passes en argument.
 * @package Lib
 * @subpackage XML
 */
class options extends xml {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_fichier_conf = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_dossier_conf = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_class = array ();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $regexp_conf_dir = "/.*_prod\.xml$/";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type options.
	 * @codeCoverageIgnore
	 * @param int $argc Nombre d'argument.
	 * @param array $argv Liste des arguments.
	 * @param string $limite_basse Nombre d'argument minimum.
	 * @param string $limite_haute Nombre d'argument maximum.
	 * @param string $usage Phrase en cas d'erreur sur le nombre d'argument.
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @param string $rep_framework chemin du framework
	 * @return options
	 */
	static function &creer_options($argc, $argv, $limite_basse = 1, $limite_haute = 50, $usage = "", $rep_framework = "no", $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new options ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $objet 
		) );
		
		try {
			$objet->retrouve_options_param ( $argc, $argv, $limite_basse, $limite_haute, $usage, $rep_framework );
		} catch ( Exception $e ) {
			echo "[Exit]1\n";
			exit ( 1 );
		}
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return options
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * Les options doivent etre du type --option et/ou --option=var
	 *
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @param string $entete chemin du framework
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * 
	 * @param int $argc Nombre d'argument.
	 * @param array $argv Liste des arguments.
	 * @param string $limite_basse Nombre d'argument minimum.
	 * @param string $limite_haute Nombre d'argument maximum.
	 * @param string $usage Phrase en cas d'erreur sur le nombre d'argument.
	 * @param string $rep_framework chemin du framework
	 * @return options
	 * @throws Exception
	 */
	public function retrouve_options_param(&$argc, &$argv, $limite_basse = 1, $limite_haute = 50, $usage = "", $rep_framework = "no") {
		$this->onDebug ( __METHOD__, 1 );
		if ($argc < $limite_basse || $argc > $limite_haute) {
			return $this->onError ( "Le nombre de parametres ne correspond pas pour " . $argv [0] . " " . $usage );
		}
		
		//On cherche un/des fichier(s) de conf
		$this->gestion_des_confs_par_fichier ( $argv )
			->parse_file_option ();
		
		//puis on parse la ligne qui est prioritaire sur le fichier
		$this->parse_ligne_option ( $argc, $argv, $usage );
		
		//utilisable dans tous les programmes
		$this->setOption ( "rep_scripts", dirname ( $argv [0] ) );
		if ($this->verifie_option_existe ( "use_local_dir" )) {
			$this->setOption ( "dossier_tempo", posix_getcwd () );
		}
		$this->setOption ( "netname", php_uname ( "n" ) );
		if ($rep_framework != "no") {
			$this->setOption ( "rep_framework", $rep_framework );
		}
		
		$this->setOption ( "Erreur", false );
		
		return $this;
	}

	/**
	 * Retrouve les parametres conf/conf_regexp/conf_dir dans la ligne de commande
	 * ou --conf=/--conf_regexp=/--conf_dir= et leurs parametres dans la ligne de commande
	 * 
	 * @param string $option
	 * @return string|boolean
	 */
	private function _retrouveParamConf($option) {
		$this->onDebug ( __METHOD__, 2 );
		switch ($option) {
			case "--conf" :
				return "ajoute_fichier_conf";
			case "--conf_regexp" :
				return "setRegexpConfDir";
			case "--conf_dir" :
				return "ajoute_Dossiers_conf";
		}
		
		if (strpos ( $option, "--conf=" ) !== FALSE) {
			//On entre dans la definition des confs avec conf="fic1 fic2 ..."
			$fichier_conf = explode ( " ", substr ( $option, strlen ( "--conf=" ) ) );
			foreach ( $fichier_conf as $conf ) {
				$this->ajoute_fichier_conf ( $conf );
			}
		} elseif (strpos ( $option, "--conf_regexp=" ) !== FALSE) {
			$dossiers_conf = explode ( " ", substr ( $option, strlen ( "--conf_regexp=" ) ) );
			foreach ( $dossiers_conf as $dossier ) {
				$this->setRegexpConfDir ( $dossier );
			}
		} elseif (strpos ( $option, "--conf_dir=" ) !== FALSE) {
			$dossiers_conf = explode ( " ", substr ( $option, strlen ( "--conf_dir=" ) ) );
			foreach ( $dossiers_conf as $dossier ) {
				$this->lit_dossier_conf ( $dossier );
			}
		}
		
		return false;
	}

	/**
	 * 
	 *
	 * @param array $liste_arguments Liste des arguments.
	 * @return options
	 */
	public function &gestion_des_confs_par_fichier($liste_arguments) {
		$this->onDebug ( __METHOD__, 2 );
		//On cherche un parametre de conf
		$fonction = false;
		$flag_fonction = false;
		$fonction_finale = "";
		foreach ( $liste_arguments as $option ) {
			//On entre dans la definition des parametres de conf
			if (strpos ( $option, "--" ) === 0) {
				$flag_fonction = false;
				$fonction_finale = "";
			}
			if ($flag_fonction) {
				$this->$fonction_finale ( $option );
			}
			$fonction = $this->_retrouveParamConf ( $option );
			if ($fonction) {
				$flag_fonction = true;
				$fonction_finale = $fonction;
			}
		}
		
		$this->lit_dossier_conf ();
		
		return $this;
	}

	/**
	 * Trouve l'argument --conf_dir dans la liste des arguments.<br>
	 * Et extrait la liste des fichiers de conf (s'ils existent) du dossier conf_dir.
	 *
	 * @param string $dossier_conf dossier de conf a lire.
	 * @return options
	 */
	public function &lit_dossier_conf() {
		$this->onDebug ( __METHOD__, 2 );
		$dossiers_conf = $this->getListeDossiersConf ();
		foreach ( $dossiers_conf as $dossier_conf => $datas ) {
			$fichier_conf = repertoire::lire_repertoire ( $dossier_conf );
			$regexp = $this->getRegexpConfDir ();
			foreach ( $fichier_conf as $conf ) {
				if (preg_match ( $regexp, $conf ) != FALSE) {
					$this->ajoute_fichier_conf ( $dossier_conf . "/" . $conf );
				}
			}
			$dossiers_conf [$dossier_conf] ["load"] = true;
		}
		$this->setListeDossiersConf ( $dossiers_conf );
		
		return $this;
	}

	/**
	 * Ajoute un fichier de configuration a la liste et charge la configuration qu'il contient
	 *
	 * @param string $dossier_conf dossier de conf a lire.
	 * @return options
	 */
	public function &ajouter_fichier_conf($fichier_conf_sup = false) {
		$this->onDebug ( __METHOD__, 2 );
		if ($fichier_conf_sup !== false && $fichier_conf_sup != "") {
			$this->ajoute_fichier_conf ( $fichier_conf_sup )
				->parse_file_option ();
		}
		return $this;
	}

	/**
	 * Extrait tous les arguments du fichier de configuration.
	 *
	 * @return Bool TRUE si OK, FALSE sinon.
	 * @return options
	 * @throws Exception
	 */
	public function &parse_file_option() {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getListeFichiersConf () as $fichier ) {
			if ($fichier ["load"] === false) {
				$this->open_xml ( $fichier ["nom"] );
				$this->setChampFichierConf ( $fichier ["nom"], "load", true );
			}
		}
		
		return $this;
	}

	/**
	 * Attribut une valeur par defaut au parametre dans $option
	 * @param unknown $option
	 * @param unknown $plusieurs_vars
	 * @return options
	 */
	private function _setOptionParDefaut($option, $plusieurs_vars) {
		$this->onDebug ( __METHOD__, 2 );
		if ($plusieurs_vars === false) {
			if ($option == "verbose") {
				$this->setOption ( $option, 0 );
			} else {
				$this->setOption ( $option, "" );
			}
		}
		
		return $this;
	}

	/**
	 * Extrait tous les arguments de la liste.
	 *
	 * @param int $argc Nombre d'argument.
	 * @param array $argv Liste des arguments.
	 * @param string $usage Phrase en cas d'erreur sur le nombre d'argument.
	 * @return options
	 */
	public function &parse_ligne_option($argc, $argv, $usage) {
		$this->onDebug ( __METHOD__, 1 );
		$plusieurs_vars = false;
		$option = "";
		
		for($i = 1; $i < count ( $argv ); $i ++) {
			$entete = substr ( $argv [$i], 0, 2 );
			
			//Si on a deja le nom de l'option, on cherche la valeur
			if ($entete != "--" && $option != "") {
				$this->setOption ( $option, trim ( $argv [$i] ), true );
				$plusieurs_vars = true;
				continue;
			} elseif ($entete == "--" && $option != "") {
				//Si on est deja sur une autre option et qu'il n'y a pas de valeur a l'option precedente
				//On enregistre une valeur par defaut a l'option precedente
				$this->_setOptionParDefaut ( $option, $plusieurs_vars );
			}
			//On verifie la syntax de l'option
			if ($entete == "--") {
				$option = substr ( $argv [$i], 2 );
				
				if (strpos ( $option, "=" ) != FALSE) {
					//On split le parametre et sa valeur
					$value_option = explode ( "=", $option, 2 );
					//Dans le cas ou un fichier de conf a setter la variable
					$this->supprime_option ( $value_option [0] );
					
					//On creer le tableau
					$this->setOption ( $value_option [0], trim ( $value_option [1] ) );
					$option = "";
				} else {
					//Dans le cas ou un fichier de conf a setter la variable
					$this->supprime_option ( $option );
					$plusieurs_vars = false;
				}
			} else {
				//Enfin sinon on a une erreur de syntax
				return $this->onError ( "Erreur de syntax dans vos options : " . $argv [$i] . " " . $usage );
			}
		}
		
		if ($option != "") {
			$this->_setOptionParDefaut ( $option, $plusieurs_vars );
		}
		
		return $this;
	}

	/**
	 * Verifie si une option existe dans la liste des options.<br>
	 * Le $not_null permet de verifier l'option n'est pas egale a "".
	 *
	 *
	 * @param string|array $nom_option Nom de l'option ou tableau de champ et sous-champ pour trouver l'option.
	 * @param Bool $not_null FALSE si la valeur peut etre egale a "" ou TRUE sinon.
	 * @return Bool TRUE si l'option existe, FALSE sinon.
	 */
	public function verifie_option_existe($nom_option, $not_null = false) {
		$this->onDebug ( __METHOD__, 2 );
		$position = $this->_trouvePosition0ption ( $nom_option );
		
		if ($position === "Z_NOTFOUND") {
			$CODE_RETOUR = false;
		} else {
			if ($not_null && $position === "") {
				$CODE_RETOUR = false;
			} else {
				$CODE_RETOUR = true;
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * ACCESSEURS set
	 * Ajoute une option.<br>
	 * Attention : en XML la valeur ne peut pas etre un tableau.
	 *
	 * @param string|array $champ Chemin complet du fichier XML.
	 * @param string $valeur Valeur du xml.
	 * @return true
	 */
	public function setOption($champ, $valeur, $ajout_valeur = false) {
		$this->onDebug ( __METHOD__, 2 );
		if (is_string ( $valeur )) {
			$valeur = str_replace ( "&", "&amp;", $valeur );
		}
		if ($champ != "" && ! is_array ( $valeur ) && ! is_object ( $valeur )) {
			if ($ajout_valeur === false) {
				$this->supprime_option ( $champ );
			}
			return $this->ajoute_donnee ( $champ, $valeur, $ajout_valeur );
		} elseif ($champ != "" && ! is_array ( $champ ) && (is_object ( $valeur ) || is_array ( $valeur ))) {
			if ($ajout_valeur === false) {
				$this->supprimer_class ( $champ );
			}
			return $this->ajouter_class ( $champ, $valeur );
		}
		
		return $this->onError ( "Le champ est nul.", "" );
	}

	/**
	 * Supprime un element de la liste
	 *
	 * @param string|array $nom Nom de l'option ou tableau de champ et sous-champ pour trouver l'option.
	 * @param Bool $not_null FALSE si la valeur peut etre egale a "" ou TRUE sinon.
	 * @return Bool TRUE si l'option existe, FALSE sinon.
	 */
	public function supprime_option($nom) {
		$this->onDebug ( __METHOD__, 2 );
		$this->supprime_element ( $nom );
		return $this;
	}

	/**
	 * Renvoi la valeur d'une option dans la liste des options.<br>
	 * Le $not_null permet de verifier l'option n'est pas egale a "".
	 *
	 * @param string|array $nom_option Nom de l'option ou tableau de champ et sous-champ pour trouver l'option.
	 * @param Bool $not_null FALSE si la valeur peut etre egale a "" ou TRUE sinon.
	 * @return mixed|false La valeur de l'option, FALSE sinon.
	 */
	public function getOption($nom_option, $not_null = false) {
		$this->onDebug ( __METHOD__, 2 );
		$position = $this->_trouvePosition0ption ( $nom_option );
		
		if ($not_null && $position === "")
			return false;
		elseif ($position === "Z_NOTFOUND")
			return false;
		
		return $position;
	}

	/**
	 * Renvoi la liste des options sous forme de tableau.
	 *
	 * @return array Liste des options.
	 */
	public function getListeOption() {
		$this->onDebug ( __METHOD__, 2 );
		$local_data = $this->renvoi_donnee ();
		$resultat = array_merge ( $local_data, $this->getListeClass () );
		
		return $resultat;
	}

	/**
	 * Renvoi un pointeur sur une option.
	 * @access private
	 *
	 * @param string|array $nom_option Nom de l'option ou tableau de champ et sous-champ pour trouver l'option.
	 * @return mixed Pointeur sur la case de l'option, "Z_NOTFOUND" si l'option n'existe pas.
	 */
	private function &_trouvePosition0ption($nom_option) {
		$this->onDebug ( __METHOD__, 2 );
		//On cherche l'option en priorite sur la ligne de commande
		$data = $this->renvoi_donnee ( $nom_option );
		
		if (is_string ( $data )) {
			return $data;
		} elseif (is_array ( $data )) {
			return $data;
		} elseif ($data === false && ! is_array ( $nom_option )) {
			//Si on a pas trouve dans les xml, on cherche dans les class
			$data = $this->getOneClass ( $nom_option );
			if ($data !== false) {
				return $data;
			}
		}
		
		$CODE_RETOUR = "Z_NOTFOUND";
		return $CODE_RETOUR;
	}

	/**
	 * Extrait tous les arguments du fichier de configuration XML.
	 * @codeCoverageIgnore
	 */
	public function dump_liste_option($fichier) {
		$this->onDebug ( __METHOD__, 1 );
		if ($fichier != "") {
			$this->getDomDatas ()
				->save ( $fichier );
		}
		
		return $this;
	}

	/****************************** Parametres *************************************/
	/**
	 * Prend un parametre au format class@[using='oui']<br>
	 * et renvoi un tableau au format :<br>
	 * $tableau['class']=class<br>
	 * $tableau['param']=using<br>
	 * $tableau['result']=oui<br>
	 *
	 * @param string $parametre_xml parametre au format class@[using='oui'].
	 * @return array|false si le parametre n'est pas au bon format.
	 */
	public function construit_parametre_standard($parametre_xml) {
		$this->onDebug ( __METHOD__, 2 );
		//On prepare la ligne de commande logique
		if (! is_array ( $parametre_xml )) {
			$pos_arobas = strpos ( $parametre_xml, "@" );
			$pos_egal = strpos ( $parametre_xml, "=" );
			if ($pos_arobas !== false && $pos_egal !== false) {
				$retour = array ();
				//on creer le class_parametre a partir de class[@parametre='valeur']
				$retour ['class'] = substr ( $parametre_xml, 0, ($pos_arobas) - 1 );
				$retour ['param'] = substr ( $parametre_xml, ($pos_arobas) + 1, ($pos_egal - $pos_arobas - 1) );
				$retour ['result'] = str_replace ( "]", "", str_replace ( "'", "", substr ( $parametre_xml, $pos_egal + 1 ) ) );
			} else {
				$retour = false;
			}
		} else {
			$retour = false;
		}
		
		return $retour;
	}

	/**
	 * Verifie la presence d'une variable necessaires au traitement.<br>
	 * Retourne la liste d'option mise a jour par la ligne de commande dans le fichier de conf.
	 *
	 * @param string|array $parametre_xml Parametre de l'option en fichier de conf.
	 * @param string $valeur_defaut Valeur par defaut (optionnel).
	 * @return TRUE
	 */
	public function verifie_parametre_standard($parametre_xml) {
		$this->onDebug ( __METHOD__, 2 );
		//On prepare la ligne de commande logique
		$donnees = $this->construit_parametre_standard ( $parametre_xml );
		if ($donnees !== false) {
			$option_ligne_commande = $donnees ['class'] . "_" . $donnees ['param'];
			//On valide la presence en ligne de commande
			if ($this->verifie_option_existe ( $option_ligne_commande, true ) !== false) {
				$valeur = $this->getOption ( $option_ligne_commande, true );
				if ($valeur == $donnees ['result']) {
					$retour = true;
				} else {
					$retour = false;
				}
			} else {
				//On valide la presence dans l'XML
				$retour = $this->verifie_option_existe ( $parametre_xml );
			}
		} else {
			$retour = $this->verifie_option_existe ( $parametre_xml );
		}
		
		$this->onDebug ( "verifie_parametre_standard : ", 2 );
		$this->onDebug ( $parametre_xml, 2 );
		$this->onDebug ( " retour :", 2 );
		$this->onDebug ( $retour, 2 );
		
		return $retour;
	}

	/****************************** Parametres *************************************/
	
	/****************************** Variables *************************************/
	
	/**
	 * Prend un variable au format "fichier de conf" et la transforme au format standard "ligne de commande".
	 *
	 * @param string|array $option_xml variable au format "fichier de conf".
	 * @return string option en ligne de commande.
	 */
	public function construit_variable_ligne_commande_standard($option_xml) {
		$this->onDebug ( __METHOD__, 2 );
		if (! is_array ( $option_xml )) {
			$option_ligne_commande = $option_xml;
		} else {
			$option_ligne_commande = implode ( "_", $option_xml );
		}
		
		return $option_ligne_commande;
	}

	/**
	 * Verifie la presence d'une variable necessaires au traitement.<br>
	 *
	 * @param string|array $option_xml Nom l'option en fichier de conf et/ou en ligne de commande.
	 * @return 1 si la variable existe en ligne de commande, 2 si la variable existe en fichier de conf, FALSE sinon
	 */
	public function verifie_variable_standard($option_xml) {
		$this->onDebug ( __METHOD__, 2 );
		$option_ligne_commande = $this->construit_variable_ligne_commande_standard ( $option_xml );
		
		//Priorite a la ligne de commande
		if ($this->verifie_option_existe ( $option_ligne_commande ) !== false) {
			$retour = 1;
		} elseif ($this->verifie_option_existe ( $option_xml ) !== false) {
			$retour = 2;
		} else {
			$retour = false;
		}
		
		$this->onDebug ( "verifie_variable_standard : " . print_r ( $option_xml, true ), 2 );
		$this->onDebug ( "verifie_variable_standard : code retour : " . ($retour == true ? "TRUE" : "FALSE"), 2 );
		
		return $retour;
	}

	/**
	 * Verifie la présence et/ou ajoute la variable necessaires au traitement au format "fichier de conf".<br>
	 * Retourne la liste d'option mise a jour avec la variable standard.
	 *
	 * @param string|array $option_xml Nom l'option en fichier de conf et/ou en ligne de commande.
	 * @param string $valeur_defaut Valeur par defaut (optionnel).
	 * @return TRUE
	 */
	public function prepare_variable_standard($option_xml, $valeur_defaut = "") {
		$this->onDebug ( __METHOD__, 2 );
		$retour = $this->verifie_variable_standard ( $option_xml );
		
		if ($retour === 1) {
			$valeur = $this->renvoi_variables_standard ( $option_xml );
			$this->onDebug ( "Ajout de  " . $valeur, 2 );
			$this->setOption ( $option_xml, $valeur );
		} elseif ($retour === false) {
			$this->onDebug ( "Ajout de  " . $valeur_defaut, 2 );
			$this->setOption ( $option_xml, $valeur_defaut );
		}
		
		return true;
	}

	/**
	 * Renvoi la valeur d'une variable necessaires au traitement.<br>
	 *
	 * @param string|array $option_xml Nom l'option en fichier de conf et/ou en ligne de commande.
	 * @return mixed|false si la variable n'existe pas.
	 */
	public function renvoi_variables_standard($option_xml, $valeur_defaut = false) {
		$this->onDebug ( __METHOD__, 2 );
		$retour = $this->verifie_variable_standard ( $option_xml );
		switch ($retour) {
			case 1 :
				$option_ligne_commande = $this->construit_variable_ligne_commande_standard ( $option_xml );
				$retour = $this->getOption ( $option_ligne_commande );
				break;
			case 2 :
				$retour = $this->getOption ( $option_xml );
				break;
			default :
				$retour = $valeur_defaut;
		}
		
		$this->onDebug ( $retour, 2 );
		return $retour;
	}

	/****************************** Variables *************************************/
	
	/******************** Accesseurs *****************/
	/**
	 * ACCESSEURS get
	 * @codeCoverageIgnore
	 */
	public function getRegexpConfDir() {
		return $this->regexp_conf_dir;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function &setRegexpConfDir($regexp_conf_dir) {
		$this->regexp_conf_dir = $regexp_conf_dir;
		return $this;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 */
	public function getListeClass() {
		return $this->liste_class;
	}

	/**
	 * ACCESSEURS get
	 * @codeCoverageIgnore
	 */
	public function getListeDossiersConf() {
		return $this->liste_dossier_conf;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function &setListeDossiersConf($liste_dossier_conf) {
		$this->liste_dossier_conf = $liste_dossier_conf;
		return $this;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function &ajoute_Dossiers_conf($dossier) {
		if ($dossier != "" && ! isset ( $this->liste_dossier_conf [$dossier] )) {
			$this->liste_dossier_conf [$dossier] = array (
					"nom" => $dossier,
					"load" => false 
			);
		}
		return $this;
	}

	/**
	 * ACCESSEURS get
	 * @codeCoverageIgnore
	 */
	public function getListeFichiersConf() {
		return $this->liste_fichier_conf;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function &setListeFichiersConf($liste_fichier_conf) {
		$this->liste_fichier_conf = $liste_fichier_conf;
		return $this;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function &ajoute_fichier_conf($fichier) {
		if ($fichier != "" && ! isset ( $this->liste_fichier_conf [$fichier] )) {
			$this->liste_fichier_conf [$fichier] = array (
					"nom" => $fichier,
					"load" => false 
			);
		}
		return $this;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function setChampFichierConf($fichier, $champ, $valeur) {
		if ($fichier != "" && isset ( $this->liste_fichier_conf [$fichier] )) {
			$this->liste_fichier_conf [$fichier] [$champ] = $valeur;
		}
		return $this;
	}

	/**
	 * ACCESSEURS get
	 * @codeCoverageIgnore
	 */
	public function getOneClass($nom) {
		if (isset ( $this->liste_class [$nom] )) {
			return $this->liste_class [$nom];
		}
		
		return false;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function &ajouter_class($nom, $class) {
		if (! isset ( $this->liste_class [$nom] )) {
			$this->liste_class [$nom] = $class;
		}
		return $this;
	}
	
	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function &supprimer_class($nom) {
		if ( isset ( $this->liste_class [$nom] )) {
			unset ($this->liste_class [$nom]);
		}
		return $this;
	}

	/******************** Accesseurs *****************/
	
	/**
	 * Affiche en mode Debug 2
	 * @codeCoverageIgnore
	 * @return options
	 */
	public function debug_options() {
		$this->onDebug ( $this->getListeFichiersConf (), 2 );
		$this->onDebug ( $this->getListeDossiersConf (), 2 );
		$this->onDebug ( $this->getListeOption (), 2 );
		return $this;
	}

	/**
	 * @static
	 * @codeCoverageIgnore
	 *
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des configurations :";
		$help [__CLASS__] ["text"] [] .= "\t--conf /dossier/conf1.xml /dossier2/conf2.xml .... \tListe des fichiers de configuration";
		$help [__CLASS__] ["text"] [] .= "\t--conf_dir /dossier1 /dossier2 .... \t\t\tListe des dossiers contenant des configurations filtrees par conf_regexp";
		$help [__CLASS__] ["text"] [] .= "\t--conf_regexp '/.*\_prod.xml/' \t\t\t\tRegexp de type preg_match pour trouver les fichiers dans conf_dir";
		
		return $help;
	}
} //Fin de la class
?>