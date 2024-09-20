<?php

require_once('conf.inc.php');
require_once('auth.inc.php');

if (!Authentication::HasAccess()) {
    http_response_code(401);
	return;
}

if (!isset($_GET['object']) || !isset($_GET['category'])) {
    http_response_code(405);
    return;
}

$count = 0;
if (isset($_GET['count']) && is_numeric($_GET['count'])) {
    $count = intval($_GET['count']);
}

$open_object = new stdClass;
$open_object->object = htmlspecialchars($_GET['object']);
$open_object->count = $count;
$open_object->category = htmlspecialchars($_GET['category']);
$data->open[] = $open_object;

file_put_contents("ibringit.json", json_encode($data, JSON_PRETTY_PRINT));

?>
