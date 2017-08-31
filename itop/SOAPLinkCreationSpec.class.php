<?php
/**
 * @author combodo
 * @package Lib
 *
 */
/**
 * class SOAPLinkCreationSpec<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPLinkCreationSpec {
	public $class;
	public $conditions; // array of SOAPSearchCondition
	public $attributes; // array of SOAPAttributeValue

	public function __construct($sClass, $aConditions, $aAttributes) {
		$this ->setClass ( $sClass ) 
			->setConditions ( $aConditions ) 
			->setAttributes ( $aAttributes );
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setClass($class) {
		$this->class = $class;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getConditions() {
		return $this->conditions;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConditions($conditions) {
		$this->conditions = $conditions;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAttributes($attributes) {
		$this->attributes = $attributes;
		return $this;
	}
/**
 * *********************** Accesseurs **********************
 */
}
?>