<?php

class HTTPRouter {
	
	private $routes;
	
	function __construct() {
		$this->routes = array();
	}
	
	function addHandler($pattern, $handler_func) {
		$this->routes[] = array(
			"pattern" => $pattern,
			"handler" => $handler_func
		);
	}
	
	function handle($uri) {
		
	}
}

?>
