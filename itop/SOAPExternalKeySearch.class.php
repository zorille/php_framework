<?php
/**
 * @author combodo
 * @package Lib
 *
 */
/**
 * class SOAPExternalKeySearch<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPExternalKeySearch {
	public $conditions; // array of SOAPSearchCondition

	public function __construct($aConditions = null) {
		$this ->setConditions ( $aConditions );
	}

	public function IsVoid() {
		if (is_null ( $this ->getConditions () ))
			return true;
		if (count ( $this ->getConditions () ) == 0)
			return true;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
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
	 * *********************** Accesseurs **********************
	 */
}
?>