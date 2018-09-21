<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class splunk_datamodel_pivot
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_datamodel_pivot extends splunk_datamodel {

	/**
	 * Remet l'url par defaut
	 * @return splunk_datamodel_pivot
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'pivot' );
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
		$help [__CLASS__] ["text"] [] .= "splunk_datamodel_pivot :";
		
		return $help;
	}
}
?>
