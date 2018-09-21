<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class splunk_services
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_services extends splunk_ci {

	/**
	 * Remet l'url par defaut
	 * @return splunk_services
	 */
	public function &reset_resource() {
		return parent::reset_resource () ->addResource ( 'service' );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
}
?>
