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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_importTemplate
	 */
	static function &creer_cacti_importTemplate(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): cacti_importTemplate
	{
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
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise($liste_class);
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
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
	public function gere_rra(): bool
	{
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

 si OK, False sinon.
	 * @throws Exception
	 */
	public function executecacti_importTemplate(): bool
	{
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
	public function getTemplate(): string
	{
		return $this->template;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setTemplate($Template): bool|static
	{
		if ($Template != "") {
			$this->template = $Template;
		} else {
			$r = $this->onError ( "le template est obligatoire." );
			return $r;
		}
		
		return $this;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function getWith_user_rras(): array
	{
		return $this->with_user_rras;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function &setWith_user_rras($With_user_rras): static
	{
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
	public function getWith_template_rras(): bool
	{
		return $this->with_template_rras;
	}
	/**
	 * @codeCoverageIgnore
	 * @throws Exception
	 */
	public function &setWith_template_rras($With_template_rras): bool|static
	{
		if (is_bool ( $With_template_rras )) {
			$this->with_template_rras = $With_template_rras;
		} else {
			$r = $this->onError ( "Ce parametre doit etre un boolean" );
			return $r;
		}
		
		return $this;
	}
	
	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Creer et execute le programme cacti_importTemplate";
		$help [__CLASS__] ["text"] [] .= "NECESSITE au moins un fichier de conf machines/cacti.xml";
		$help [__CLASS__] ["text"] [] .= "\t--cacti_env mut/tlt/dev/perso permet de recuperer l'env dans la conf cacti";
		
		return $help;
	}
}
