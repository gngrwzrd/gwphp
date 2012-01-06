<?php

/**
 * THIS ISN'T FINISHED!
 */

error_reporting(E_ALL);

require_once("../source/http/http.php");
require_once("../source/http/router.php");
echo "word";

function handler() {
	HTTPRequest::ProcessRequest(
		array("action" => new MyAction() ),
		array()
	);
}

$router = new HTTPRouter();
$router->addHandler("/index.php?",handler);
$router->handle("index.php");

?>
