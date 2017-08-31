<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
/**
 * class splunk_search
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_search extends splunk_ci {

	/**
	 * Remet l'url par defaut
	 * @return splunk_search
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'search' );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
}
?>
