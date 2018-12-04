<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class fonctions_standard_strings<br>

 * @package Lib
 * @subpackage Generation
 */
class fonctions_standard_strings extends abstract_log {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fonctions_standard_strings.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standard_strings
	 */
	static function &creer_fonctions_standard_strings(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fonctions_standard_strings ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standard_strings
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et set la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/*********************** GESTION de BASE64 ****************************/
	
	/**
     * Creer un objet generation.
     *
     * @param options &$liste_option Pointeur sur les arguments.
     * @return $base64,NULL en cas d'erreur
     */
	static public function creer_base64(&$liste_option) {
		if ($liste_option->verifie_option_existe ( "base64_encode" ) !== false) {
			$base64 = base64::creer_base64 ( $liste_option, false, "base64" );
		} else {
			$base64 = NULL;
		}
		
		return $base64;
	}

/************************* GESTION de BASE64 *******************************/
}
?>
