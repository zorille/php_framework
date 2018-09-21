<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class requete_complexe_cacti<br>
 *
 * Gere la connexion a une base Cacti.
 * @package Lib
 * @subpackage SQL-dbconnue
 */

class requete_complexe_cacti extends desc_bd_cacti {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requete_complexe_cacti.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_complexe_cacti
	 */
	static function &creer_requete_complexe_cacti(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new requete_complexe_cacti ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_complexe_cacti
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
}
?>