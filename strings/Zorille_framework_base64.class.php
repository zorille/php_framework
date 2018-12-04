<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class base64<br>

 *
 * Encode/Decode au format base64.
 * @package Lib
 * @subpackage Strings
 */
class base64 extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_remplacement = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type base64.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return base64
	 */
	static function &creer_base64(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new base64 ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return base64
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		return true;
	}

	/**
	 * Verifie l'encodage
	 * @param string $string
	 * @return boolean
	 */
	public function is_encoded($string) {
		if (base64_decode ( $string,true ) !== false) {
			$retour = true;
		} else {
			$retour = false;
		}
		
		return $retour;
	}

	/**
	 * Encode une ligne
	 * @param string $string
	 * @return string
	 */
	public function encode($string) {
		return base64_encode ( $string );
	}

	/**
	 * Decode une ligne
	 * @param string $string
	 * @return string
	 */
	public function decode($string) {
		return base64_decode ( $string,true );
	}

	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "\t--base64_encoded";
		
		return $help;
	}
}

?>
