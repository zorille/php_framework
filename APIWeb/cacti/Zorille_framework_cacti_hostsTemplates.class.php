<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use Exception;

/**
 * class cacti_hostsTemplates<br>
 *
 * Prepare une ligne de commande de generation.
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_hostsTemplates extends parametresStandard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $templates = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_hostsTemplates.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_hostsTemplates
	 */
	static function &creer_cacti_hostsTemplates(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): cacti_hostsTemplates
	{
		$objet = new cacti_hostsTemplates ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_hostsTemplates
	 */
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise ( $liste_class );
		$this->charge_templates ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de cacti_globals
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Charge la liste des hosts via l'API Cacti
	 * @throws Exception
	 */
	public function charge_templates(): static
	{
		$this->onDebug ( "On charge la liste des templates.", 1 );
		// fonction de l'API cacti : lib/database.php via global.php
		$this->setTemplates ( getHostTemplates () );
		
		return $this;
	}

	/**
	 * Valide qu'un tree existe par son id.
	 *
	 * @return boolean True le tree existe, false le tree n'existe pas.
	 */
	public function valide_template_by_id($template_id): bool
	{
		$templates = $this->getTemplates ();
		if (isset ( $templates [$template_id] )) {
			return true;
		}
		return false;
	}

	/**
	 * Retrouve l'id d'un template cacti
	 * @param requete_complexe_cacti $db_cacti
	 * @param string $template Terme recherche dans le nom du template
	 * @param string $preg_match RegExpr permetant de filtrer les templates recupere par le terme $template
	 * @param boolean $error True pour afficher une erreur si le template n'est pas trouve,false passe en warning
	 * @return boolean
	 * @throws Exception
	 */
	public function retrouve_templateid_par_nom(requete_complexe_cacti &$db_cacti, string $template, string $preg_match = "/^IMSL - /", bool $error = true): bool
	{
		// On retrouve l'id du template host
		$resultat_host = $db_cacti->requete_select_standard ( 'host_template', array (
				"name" => "%" . $template . "%" 
		), "id ASC" );
		if (!$resultat_host) {
			return $this->onError ( "Erreur durant la requete sur les host_template" );
		}
		
		// On trouve le template ID
		$template_id = false;
		foreach ( $resultat_host as $row ) {
			$this->onDebug ( $row, 2 );
			if (preg_match ( $preg_match, $row ["name"] )) {
				$template_id = $row ['id'];
				break;
			}
		}
		if (!$template_id) {
			if ($error) {
				return $this->onError ( "Pas de template pour ce type de template : " . $template . " et preg_match : " . $preg_match );
			}
			$this->onWarning ( "Pas de template pour ce type de template : " . $template . " et preg_match : " . $preg_match );
			return false;
		}
		
		$this->onDebug ( "Template selectionne : " . $template_id, 1 );
		return $template_id;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplates(): array
	{
		return $this->templates;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplate($template_id) {
		if (isset ( $this->templates [$template_id] )) {
			return $this->templates [$template_id];
		}
		return false;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function setTemplates($templates): bool
	{
		if (is_array ( $templates )) {
			$this->templates = $templates;
		} else {
			return $this->onError ( "Il faut un tableau de templates." );
		}
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function ajouteTemplates($nom, $tree_id): bool
	{
		if ($nom != "" && $tree_id != "") {
			$this->templates [$nom] = $tree_id;
		} else {
			return $this->onError ( "Il faut un nom et/ou un tree_id." );
		}
		return true;
	}

/**
 * ***************************** ACCESSEURS *******************************
 */
}
