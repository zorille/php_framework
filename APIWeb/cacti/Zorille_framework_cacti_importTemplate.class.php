<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class cacti_importTemplate<br>
 *
 * Prepare une ligne de commande de generation.
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_importTemplate extends parametresStandard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $template = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var Bool
	 */
	private $with_template_rras = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $with_user_rras = array ();
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_importTemplate.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_importTemplate
	 */
	static function &creer_cacti_importTemplate(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_importTemplate ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_importTemplate
	 */
	public function &_initialise($liste_class) {
		parent::_initialise($liste_class);
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @return true
	 */
	public function __construct($sort_en_erreur = false) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, "cacti_importTemplate" );
		
		
	}
	
	/**
	 * Valide les options with-template-rras et with-user-rras
	 * 
	 * @return boolean
	 * @throws Exception
	 */
	public function gere_rra() {
		if (count($this->getWith_user_rras ()) > 0) {
			if ($this->getWith_template_rras ()) {
				return $this->onError ( "Il faut choisir entre with-template-rras et with-user-rras" );
			} else {
				// Si il n'y a que with-user-rras
				$rra_array = $this->getWith_user_rras ();
				if (sizeof ( $rra_array )) {
					foreach ( $rra_array as $key => $value ) {
						$name = db_fetch_cell ( "SELECT name FROM rra WHERE id=" . intval ( $value ) );
						if (strlen ( $name )) {
							$this->onDebug ( "using RRA $name", 1 );
						} else {
							$this->onWarning ( "RRA id $value not found" );
							unset ( $rra_array [$key] );
						}
					}
					$this->setWith_user_rras ( $rra_array );
				}
			}
		} else {
			$this->setWith_user_rras ( array () );
			if (! $this->getWith_template_rras ()) {
				return $this->onError ( "Il faut choisir entre with-template-rras et with-user-rras" );
			}
		}
		
		return true;
	}
	
	/**
	 * Execute import_xml_data de l'API import.php.
	 *
	 * @return true si OK, False sinon.
	 * @throws Exception
	 */
	public function executecacti_importTemplate() {
		if (fichier::tester_fichier_existe ( $this->getTemplate () )) {
			$xml_data = fichier::Lit_integralite_fichier ( $this->getTemplate () );
			
			if ($xml_data !== false && $this->gere_rra ()) {
				import_xml_data ( $xml_data, $this->getWith_template_rras (), $this->getWith_user_rras () );
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplate() {
		return $this->template;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setTemplate($Template) {
		if ($Template != "") {
			$this->template = $Template;
		} else {
			return $this->onError ( "le template est obligatoire." );
		}
		
		return $this;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function getWith_user_rras() {
		return $this->with_user_rras;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function &setWith_user_rras($With_user_rras) {
		if (is_string ( $With_user_rras )) {
			$With_user_rras = array (
					$With_user_rras 
			);
		}
		$this->with_user_rras = $With_user_rras;
		return $this;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function getWith_template_rras() {
		return $this->with_template_rras;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setWith_template_rras($With_template_rras) {
		if (is_bool ( $With_template_rras )) {
			$this->with_template_rras = $With_template_rras;
		} else {
			return $this->onError ( "Ce parametre doit etre un boolean" );
		}
		
		return $this;
	}
	
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Creer et execute le programme cacti_importTemplate";
		$help [__CLASS__] ["text"] [] .= "NECESSITE au moins un fichier de conf machines/cacti.xml";
		$help [__CLASS__] ["text"] [] .= "\t--cacti_env mut/tlt/dev/perso permet de recuperer l'env dans la conf cacti";
		
		return $help;
	}
}
?>
