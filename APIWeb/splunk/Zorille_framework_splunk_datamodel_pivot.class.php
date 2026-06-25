<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
namespace Zorille\framework;
/**
 * class splunk_datamodel_pivot
 *
 * @package Lib
 * @subpackage splunk
 */
abstract class splunk_datamodel_pivot extends splunk_datamodel {

	/**
	 * Remet l'url par defaut
	 * @return splunk_ci|splunk_datamodel
	 */
	public function &reset_resource(): splunk_ci|splunk_datamodel
	{
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
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "splunk_datamodel_pivot :";
		
		return $help;
	}
}

