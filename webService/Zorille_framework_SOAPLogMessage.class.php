<?php
/**
 * @author combodo
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class SOAPLogMessage<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPLogMessage
{
	public $text; // string

	public function __construct($sText)
	{
		$this->setText($sText);
	}
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setText($text) {
		$this->text = $text;
		return $this;
	}
	/**
	 * *********************** Accesseurs **********************
	 */
}
?>