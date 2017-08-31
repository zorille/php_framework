<?php
/**
 * @author combodo
 * @package Lib
 *
 */
/**
 * class SOAPResult<br>
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage iTop
 */
class SOAPResult {
	public $status; // boolean
	public $result; // array of SOAPResultMessage
	public $errors; // array of SOAPResultLog
	public $warnings; // array of SOAPResultLog
	public $infos; // array of SOAPResultLog

	public function __construct($bStatus, $aResult, $aErrors, $aWarnings, $aInfos) {
		$this ->setStatus ( $bStatus ) 
			->setResult ( $aResult ) 
			->setErrors ( $aErrors ) 
			->setWarnings ( $aWarnings ) 
			->setInfos ( $aInfos );
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
	public function getResult() {
		return $this->result;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setResult($result) {
		$this->result = $result;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return SOAPResultLog
	 */
	public function &getErrors() {
		return $this->errors;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setErrors($errors) {
		$this->errors = $errors;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return SOAPResultLog
	 */
	public function &getWarnings() {
		return $this->warnings;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWarnings($warnings) {
		$this->warnings = $warnings;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return SOAPResultLog
	 */
	public function &getInfos() {
		return $this->infos;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInfos($infos) {
		$this->infos = $infos;
		return $this;
	}
/**
 * *********************** Accesseurs **********************
 */
}

?>