<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
/**
 * class zabbix_templates
 *  
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_templates extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var zabbix_template
	 */
	private $zabbix_template_reference = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_templates = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_templates_cli = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_templates.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_templates
	 */
	static function &creer_zabbix_templates(&$liste_option, &$zabbix_ws, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_templates ( $sort_en_erreur, $entete  );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjetTemplateRef ( zabbix_template::creer_zabbix_template ( $liste_class ["options"], $liste_class ["zabbix_wsclient"] ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__ ) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @param boolean $ajoute_template Permet d'ajouter le template a la liste des templates ou non
	 * @return false|zabbix_templates.
	 * @throws Exception
	 */
	public function &retrouve_zabbix_param($ajoute_template = true) {
		$this->onDebug ( __METHOD__, 1 );
		//Gestion des template
		$liste_template = $this->_valideOption ( array (
				"zabbix",
				"templates" 
		) );
		if (! is_array ( $liste_template )) {
			$liste_template = array (
					$liste_template 
			);
		}
		
		if ($ajoute_template) {
			foreach ( $liste_template as $template ) {
				//Le template dans zabbix sont des hosts particulies
				//On range les templates par nom (cle de hash) dans la liste_template
				$this->setAjoutListeTemplates ( $template, "", false );
			}
		}
		
		$this->setListeTemplatesCli($liste_template);
		
		return $this;
	}

	/**
	 * Met le champ exist a TRUE pour tous les templates existant dans Zabbix dont le nom apparait dans la liste d'arguments (CLI)
	 * @return zabbix_templates.
	 * @throws Exception
	 */
	public function &ajoute_template_a_partir_cli() {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getListeTemplatesCli() as $template ) {
			$this->RemplaceValeurListeTemplates ( $template, "exist", true );
		}
		
		return $this;
	}
	
	/**
	 * Met le champ exist a FALSE pour tous les templates issu de Zabbix dont le nom apparait dans la liste d'arguments
	 * @return zabbix_templates.
	 * @throws Exception
	 */
	public function &retire_template_a_partir_cli() {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getListeTemplatesCli() as $template ) {
			$this->RemplaceValeurListeTemplates ( $template, "exist", false );
		}
	
		return $this;
	}

	/**
	 * Ajoute et/ou remplace une valeur pour un template
	 * @param string $champ Le champ a modifier
	 * @param boolean|string $valeur La nouvelle valeur
	 * @param boolean $erreur Affiche une erreur si le template n'existe pas
	 * @return false|zabbix_templates
	 * @throws Exception
	 */
	public function &RemplaceValeurListeTemplates($template_name, $champ, $valeur, $erreur = true) {
		$this->onDebug ( __METHOD__, 2 );
		$liste_template = $this->getListeTemplates ();
		if (isset ( $liste_template [$template_name] )) {
			$liste_template [$template_name] [$champ] = $valeur;
		} elseif ($erreur) {
			return $this->onError ( "le template " . $template_name . " n'existe pas." );
		}
		$this->setListeTemplates ( $liste_template );
		
		return $this;
	}

	/**
	 * Creer tous les templates non existant dans zabbix contenu dans la liste.
	 * @return zabbix_templates
	 * @throws Exception
	 */
	public function &creer_liste_templates() {
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $this->getListeTemplates () as $donnees_templatee ) {
			if ($donnees_templatee ["exist"] === false) {
				$template = clone $this->getObjetTemplateRef ();
				$template->setHost ( $donnees_templatee ["name"] );
				$retour = $template->creer_template ();
				if (! isset ( $retour ["templateids"] )) {
					$this->onWarning ( "Pas de templateids dans la liste" );
				} else {
					foreach ( $retour ["templateids"] as $templateid ) {
						$this->RemplaceValeurListeTemplates ( $donnees_templatee ["name"], "templateid", $templateid )
							->RemplaceValeurListeTemplates ( $donnees_templatee ["name"], "exist", true );
					}
				}
				unset ( $template );
			}
		}
		
		return $this;
	}

	/**
	 * Recupere la liste des Templates defini dans zabbix
	 * @return zabbix_templates
	 */
	public function &recherche_liste_templates() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_templates_zabbix = $this->getObjetZabbixWsclient ()
			->templateGet ( array (
				"output" => "extend" 
		) );
		foreach ( $liste_templates_zabbix as $template_zabbix ) {
			if ($template_zabbix ['host'] != "") {
				$this->onDebug ( "Ajout de " . $template_zabbix ['host'], 2 );
				//Le template dans zabbix sont des hosts particulies
				//On range les templates par nom (cle de hash) dans la liste_template
				$this->setAjoutListeTemplates ( $template_zabbix ['host'], $template_zabbix ["templateid"], true );
			}
		}
		
		return $this;
	}

	/**
	 * Valide que chaque template de la liste ($this) existe dans zabbix
	 * @return zabbix_templates
	 * @throws Exception
	 */
	public function &valide_liste_templates() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_templates_zabbix = $this->getObjetZabbixWsclient ()
			->templateGet ( array (
				"output" => "extend" 
		) );
		
		foreach ( $liste_templates_zabbix as $template_zabbix ) {
			if ($template_zabbix ['host'] != "") {
				$this->onDebug ( "Remplacement de " . $template_zabbix ['host'], 2 );
				$this->RemplaceValeurListeTemplates ( $template_zabbix ['host'], "templateid", $template_zabbix ["templateid"], false );
				$this->RemplaceValeurListeTemplates ( $template_zabbix ['host'], "exist", true, false );
			}
		}
		
		return $this;
	}

	/**
	 * Valide les templates zabbix qui correspondent au templateid dans la liste en argument
	 * @return zabbix_templates
	 * @throws Exception
	 */
	public function &ajoute_liste_templates_a_partir_de_tableau($liste_templateids) {
		$this->onDebug ( __METHOD__, 1 );
		
		foreach ( $this->getListeTemplates () as $template ) {
			if (in_array ( $template ["templateid"], $liste_templateids )) {
				$this->RemplaceValeurListeTemplates ( $template ['name'], "exist", true, true );
			}
		}
		$this->onDebug ( $this->getListeTemplates (), 1 );
		return $this;
	}

	/**
	 * Valide les templates zabbix qui correspondent au templateid dans la liste en argument et invalide les autres
	 * @return zabbix_templates
	 * @throws Exception
	 */
	public function &valide_liste_templates_a_partir_de_tableau($liste_templateids) {
		$this->onDebug ( __METHOD__, 1 );
		
		foreach ( $this->getListeTemplates () as $template ) {
			if (in_array ( $template ["templateid"], $liste_templateids )) {
				$this->RemplaceValeurListeTemplates ( $template ['name'], "exist", true, true );
			} else {
				$this->RemplaceValeurListeTemplates ( $template ['name'], "exist", false, true );
			}
		}
		$this->onDebug ( $this->getListeTemplates (), 2 );
		return $this;
	}

	/**
	 * InValide les templates zabbix qui correspondent au templateid dans la liste en argument
	 * @return zabbix_templates
	 * @throws Exception
	 */
	public function &invalide_liste_templates_a_partir_de_tableau($liste_templateids) {
		$this->onDebug ( __METHOD__, 1 );
		
		foreach ( $this->getListeTemplates () as $template ) {
			if ($template ["exist"] === true && in_array ( $template ["templateid"], $liste_templateids )) {
				$this->RemplaceValeurListeTemplates ( $template ['name'], "exist", false, true );
			}
		}
		$this->onDebug ( $this->getListeTemplates (), 2 );
		return $this;
	}

	/**
	 * Creer un tableau de templateids
	 * @return array
	 */
	public function creer_definition_templatesids_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this->getListeTemplates () as $template ) {
			if ($template ["exist"] === true) {
				$liste_id [count ( $liste_id )] ["templateid"] = $template ["templateid"];
			}
		}
		
		return $liste_id;
	}
	
	/**
	 * Creer un tableau de templateids
	 * @return array
	 */
	public function creer_definition_templatesids_sans_champ_templateid_ws() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_id = array ();
		foreach ( $this->getListeTemplates () as $template ) {
			if ($template ["exist"] === true) {
				$liste_id [count ( $liste_id )] = $template ["templateid"];
			}
		}
	
		return $liste_id;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetTemplateRef() {
		return $this->zabbix_template_reference;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetTemplateRef(&$zabbix_template_reference) {
		$this->zabbix_template_reference = $zabbix_template_reference;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjoutListeTemplates($template_name, $template_id, $exist = true) {
		$this->liste_templates [$template_name] = array (
				"templateid" => $template_id,
				"name" => $template_name,
				"exist" => $exist 
		);
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeTemplates() {
		return $this->liste_templates;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeTemplates($liste_templates) {
		$this->liste_templates = $liste_templates;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeTemplatesCli() {
		return $this->liste_templates_cli;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeTemplatesCli($liste_templates_cli) {
		$this->liste_templates_cli = $liste_templates_cli;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Templates :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_templates 'template 1' 'template 2' ... liste des templates du host";
		
		return $help;
	}
}
?>
