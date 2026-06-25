<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class requete_complexe_fmanager<br>

 *
 * Gere la connexion a une base fmanager.
 * @package Lib
 * @subpackage SQL-dbconnue
 */

class requete_complexe_fmanager extends desc_bd_fmanager {
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type requete_complexe_fmanager.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return requete_complexe_fmanager
	 */
	static function &creer_requete_complexe_fmanager(options &$liste_option, bool|string $sort_en_erreur = true, string $entete = __CLASS__): requete_complexe_fmanager
	{
		$objet = new requete_complexe_fmanager ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return requete_complexe_fmanager
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
}
