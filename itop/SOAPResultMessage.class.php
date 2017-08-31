<?php
/**
 * @author combodo
 * @package Lib
 *
 */
/**
 * class SOAPResultMessage<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPResultMessage {
	public $label; // string
	public $values; // array of SOAPKeyValue

	public function __construct($sLabel, $aValues) {
		$this ->setLabel ( $sLabel ) 
			->setValues ( $aValues );
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLabel($label) {
		$this->label = $label;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getValues() {
		return $this->values;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setValues($values) {
		$this->values = $values;
		return $this;
	}
/**
 * *********************** Accesseurs **********************
 */
}
?>