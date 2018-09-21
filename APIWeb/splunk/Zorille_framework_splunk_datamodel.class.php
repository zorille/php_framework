<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class splunk_datamodel
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_datamodel extends splunk_ci {
	
	/**
	 * Remet l'url par defaut
	 * @return splunk_datamodel
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'datamodel' );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
}
?>
