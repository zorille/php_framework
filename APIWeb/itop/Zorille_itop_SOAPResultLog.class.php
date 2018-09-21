<?php
/**
 * @author combodo
 * @package Lib
 *
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class SOAPResultLog<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPResultLog {
	public $messages; // array of SOAPLogMessage

	public function __construct($aMessages) {
		$this ->setMessages ( $aMessages );
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMessages($messages) {
		$this->messages = $messages;
		return $this;
	}
/**
 * *********************** Accesseurs **********************
 */
}
?>