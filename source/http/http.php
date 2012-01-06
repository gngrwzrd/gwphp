<?php

/**
 * Generic action for request/response actions.
 */
class Action {
	public $runner;
	public $request;
	public $response;
	public $finished;
	
	function __construct() {
		$this->finished = false;
	}
	
	function setRequest($request) {
		$this->request = $request;
	}
	
	function setResponse($response) {
		$this->response = $response;
	}
	
	function setActionRunner($runner) {
		$this->runner = $runner;
	}
	
	function getRequestAction($name) {
		$action = $this->runner->request_actions[$name];
		$action->setRequest($this->request);
		$action->setResponse($this->response);
		return $action;
	}
	
	function getResponseAction($name) {
		$action = $this->runner->response_actions[$name];
		$action->setRequest($this->request);
		$action->setResponse($this->response);
		return $action;
	}
	
	function setFinished() {
		$this->finished = true;
	}
	
	function isFinished() {
		return $finished;
	}
	
	function setup(){}
	function teardown(){}
	function before(){}
	function after(){}
	function update(){}
	function process(){}
}

/**
 * Generic action runner.
 */
class ActionRunner {
	public $request;
	public $response;
	public $request_actions;
	public $response_actions;
	
	function setRequest($request) {
		$this->request = $request;
	}
	
	function setResponse($response) {
		$this->response = $response;
	}
	
	function setRequestActions($actions) {
		$this->request_actions = $actions;
	}
	
	function setResponseActions($actions) {
		$this->response_actions = $actions;
	}
	
	function runRequestActions() {
		foreach($this->request_actions as $key => $action) {
			$action->setActionRunner($this);
			$action->setRequest($this->request);
			$action->setResponse($this->response);
			$action->setup();
			$action->before();
			$action->process();
			$action->setFinished();
			$action->after();
			$action->teardown();
		}
	}
	
	function runResponseActions() {
		foreach($this->response_actions as $key => $action) {
			$action->setActionRunner($this);
			$action->setRequest($this->request);
			$action->setResponse($this->response);
			$action->setup();
			$action->before();
			$action->update();
			$action->setFinished();
			$action->after();
			$action->teardown();
		}
	}
	
	function errors() {
		error_reporting(E_ALL ^ E_NOTICE);
	}
}

/**
 * Generic response action.
 */
class ResponseAction extends Action {}

/**
 * Generic request action.
 */
class RequestAction extends Action {}

/**
 * Generic Request
 */
class Request {
	function prepare(){}
}

/**
 * Generic HTTPRequest.
 */
class HTTPRequest extends Request {
	public $method;
	public $arguments;
	public $files;
	public $cookies;
	
	static function ProcessRequest($request_actions,$response_actions) {
		$runner = new ActionRunner();
		$request = new HTTPRequest();
		$response = new HTTPResponse();
		$request->prepare();
		$runner->errors();
		$runner->setRequest($request);
		$runner->setResponse($response);
		$runner->setRequestActions($request_actions);
		$runner->setResponseActions($response_actions);
		$runner->runRequestActions();
		$runner->runResponseActions();
		$response->write();
	}
	
	function getArgument($name, $defaultValue) {
		if($this->arguments[$name]) return $this->arguments[$name];
		return $defaultValue;
	}
	
	function prepare() {
		$this->arguments = $_REQUEST;
		$this->files = $_FILES;
		$this->cookies = $_COOKIE;
		$method = $_SERVER["REQUEST_METHOD"];
		if($method == "POST" || $method == "post") $this->method = "post";
		else if($method == "GET" || $method == "get") $this->method = "get";
		else $this->method = "cmd";
	}
}

/**
 * Generic Response.
 */
class Response {
	public $out;
	
	function __construct() {
		$this->out = "";
	}
	
	function write() {
		print($this->out);
	}
	
	function set($out) {
		$this->out = $out;
	}
	
	function append($out) {
		$this->out .= $out;
	}
}

/**
 * Generic HTTP response.
 */
class HTTPResponse extends Response {
	private $headers;
	
	function __construct() {
		parent::__construct();
		$this->headers = array();
	}
}

/**
 * Generic request handler.
 */
class HTTPRequestAction extends RequestAction {
	function process() {
		if($this->request->method == "post") $this->post();
		else if($this->request->method == "get") $this->get();
		else if($this->request->method == "cmd") $this->cmd();
	}
	function get(){}
	function post(){}
}

?>
