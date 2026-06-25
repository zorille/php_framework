<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use Exception;

/**
 * class fonctions_standard_webservices<br>
 * @package Lib
 * @subpackage WebService
 */
class fonctions_standard_webservices extends abstract_log {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fonctions_standard_webservices.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standard_webservices
	 * @throws Exception
	 */
	static function &creer_fonctions_standard_webservices(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): fonctions_standard_webservices
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new fonctions_standard_webservices ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
	
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standard_webservices
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise($liste_class);
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	/**
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur
	 * @param string $entete
	 */
	public function __construct($sort_en_erreur=true, string $entete=__CLASS__){
		//Gestion de abstract_log
		parent::__construct($entete,$sort_en_erreur);
	}

}
