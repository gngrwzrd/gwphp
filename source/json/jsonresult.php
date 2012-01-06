<?php

/**
 * Use this as a wrapper for json responses. You can use if
 * for success and failure payloads.
 * 
 * Failure:
 * $json = new JSONResult();
 * $json->setStatus(0);
 * $json->setFault("An error happened.");
 * print $json->encode();
 * 
 * Success:
 * $json = new JSONResult();
 * $json->setStatus(1);
 * $json->setResult(array("test" => "1"));
 * print $json->encode();
 */

class JSONResult {
	public $fault;
	public $result;
	public $response;
	
	function __construct() {
		$this->response = array();
	}
	
	function setStatus($status) {
		$this->response["status"] = $status;
	}
	
	function setFault($message) {
		$this->response["fault"] = $message;
	}
	
	function setResult($result) {
		$this->response["result"] = $result;
	}
	
	function raw() {
		return $this->response;
	}
	
	function encode() {
		return json_encode($this->response);
	}
}

?>
