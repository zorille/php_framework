<?php
/**
 * @author combodo
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class SOAPKeyValue<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPKeyValue {
	public $key; // string
	public $value; // string

	public function __construct($sKey, $sValue) {
		$this ->setKey ( $sKey ) 
			->setValue ( $sValue );
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setKey($key) {
		$this->key = $key;
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