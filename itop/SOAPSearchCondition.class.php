<?php
/**
 * @author combodo
 * @package Lib
 *
 */
/**
 * class SOAPSearchCondition<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPSearchCondition {
	public $attcode; // string
	public $value; // mixed

	public function __construct($sAttCode, $value) {
		$this ->setAttcode ( $sAttCode ) 
			->setValue ( $value );
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getAttcode() {
		return $this->attcode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAttcode($attcode) {
		$this->attcode = $attcode;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValue($value) {
		$this->value = $value;
		return $this;
	}
/**
 * *********************** Accesseurs **********************
 */
}
?>