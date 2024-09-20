<?php

require_once('conf.inc.php');
require_once('auth.inc.php');

if (!Authentication::HasAccess()) {
    http_response_code(401);
	return;
}

if (!isset($_GET['key']) || !preg_match("#^[a-zA-Z0-9]+$#", $_GET['key']) || !isset($_GET['title'])) {
    http_response_code(405);
    return;
}

$category = new stdClass;
$category->key = $_GET['key'];
$category->title = htmlspecialchars($_GET['title']);
$data->categories[] = $category;

file_put_contents("ibringit.json", json_encode($data, JSON_PRETTY_PRINT));

?>
