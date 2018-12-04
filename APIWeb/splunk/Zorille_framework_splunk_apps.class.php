<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
/**
 * class splunk_apps
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_apps extends splunk_ci {
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}
	
	/**
	 * Remet l'url par defaut
	 * @return splunk_apps
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'apps' );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "splunk_apps :";
		
		return $help;
	}
}
?>
