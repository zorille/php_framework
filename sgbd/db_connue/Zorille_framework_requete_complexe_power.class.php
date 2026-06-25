<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class requete_complexe_power<br>

 *
 * Gere la connexion a une base power.
 * @package Lib
 * @subpackage SQL-dbconnue
 */

class requete_complexe_power extends desc_bd_power {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requete_complexe_power.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_complexe_power
	 */
	static function &creer_requete_complexe_power(options &$liste_option, bool|string $sort_en_erreur = true, string $entete = __CLASS__): requete_complexe_power
	{
		$objet = new requete_complexe_power ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_complexe_power
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
}
