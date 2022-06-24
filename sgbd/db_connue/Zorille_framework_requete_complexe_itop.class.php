<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class requete_complexe_itop<br>

 *
 * Gere la connexion a une base itop.
 * @package Lib
 * @subpackage SQL-dbconnue
 */

class requete_complexe_itop extends desc_bd_itop {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requete_complexe_itop.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_complexe_itop
	 */
	static function &creer_requete_complexe_itop(&$liste_option,$sort_en_erreur = true, $entete = __CLASS__) {
		$objet = new requete_complexe_itop ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_complexe_itop
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
}
?>