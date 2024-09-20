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

for ($i = count($data->open) - 1; $i >= 0; $i--) {
    if ($data->open[$i]->object == $_GET['object'] && $data->open[$i]->category == $_GET['category']) {
        array_splice($data->open, $i, 1);
    }
}

file_put_contents("ibringit.json", json_encode($data, JSON_PRETTY_PRINT));

?>
