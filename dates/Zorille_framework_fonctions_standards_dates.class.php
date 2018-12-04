<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
/**
 * class fonctions_standards_dates.
 * @package Lib
 * @subpackage Dates
 */
class fonctions_standards_dates extends abstract_log {

	/**
	 * @deprecated
	 * Parse les options passees en ligne de commande ou par xml et creer un objet dates.<br>
	 * Include : $INCLUDE_FONCTIONS<br>
	 * Arguments reconnus :<br>
	 *  --date=YYYYMMDD<br>
	 *  --date_debut=YYYYMMDD <br>
	 *  --date_fin=YYYYMMDD<br>
	 *  --ajouter_week_extreme<br>
	 *  --ajouter_month_extreme<br>
	 *
	 *Si aucune date n'est precisee alors on prend la date du jour en cours.<br>
	 *Si date_fin n'est pas precisee alors on prend la date du jour en cours.
	 *
	 * @param options &$liste_option Pointeur sur les arguments
	 * @return dates|false Renvoi un objet DATES ou FALSE en cas d'erreur.
	 */
	static public function &creer_liste_dates(&$liste_option) {
		//voir help date
		return dates::creer_dates ( $liste_option );
	}
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}
}
?>