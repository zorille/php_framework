<?php
/**
 * @author combodo
 * @package Lib
 *
 */
/**
 * class SOAPSimpleResult<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPSimpleResult {
	public $status; // boolean
	public $message; // string

	public function __construct($bStatus, $sMessage) {
		$this ->setStatus ( $bStatus ) 
			->setMessage ( $sMessage );
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setStatus($status) {
		$this->status = $status;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMessage($message) {
		$this->message = $message;
		return $this;
	}
/**
 * *********************** Accesseurs **********************
 */
}
?>