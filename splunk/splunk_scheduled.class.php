<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
/**
 * class splunk_scheduled
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_scheduled extends splunk_ci {

	/**
	 * Remet l'url par defaut
	 * @return splunk_scheduled
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'scheduled' );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
}
?>
