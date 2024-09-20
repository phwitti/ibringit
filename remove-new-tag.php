<?php

require_once('conf.inc.php');
require_once('auth.inc.php');

if (!Authentication::HasAccess()) {
    http_response_code(401);
	return;
}

if (!isset($_GET['object']) || !isset($_GET['name'])) {
    http_response_code(405);
    return;
}

for ($i = count($data->brought) - 1; $i >= 0; $i--) {
    if ($data->brought[$i]->object == $_GET['object'] && $data->brought[$i]->name == $_GET['name']) {
        $data->brought[$i]->new = false;
    }
}

file_put_contents("ibringit.json", json_encode($data, JSON_PRETTY_PRINT));

?>
