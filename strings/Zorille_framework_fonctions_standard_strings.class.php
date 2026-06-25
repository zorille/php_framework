<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use Exception;

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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standard_strings
	 * @throws Exception
	 */
	static function &creer_fonctions_standard_strings(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): fonctions_standard_strings
	{
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
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
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
	 * @return base64|null $base64,NULL en cas d'erreur
	 * @throws Exception
	 */
	static public function creer_base64(options &$liste_option): ?base64
	{
		if ($liste_option->verifie_option_existe ( "base64_encode" ) !== false) {
			$base64 = base64::creer_base64 ( $liste_option, false, "base64" );
		} else {
			$base64 = NULL;
		}
		
		return $base64;
	}

/************************* GESTION de BASE64 *******************************/
}
