<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
/**
 * class splunk_saved
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_saved extends splunk_ci {
	/**
	 * Remet l'url par defaut
	 * @return splunk_saved
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'saved' );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
}
?>
