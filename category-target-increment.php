<?php

require_once('conf.inc.php');
require_once('auth.inc.php');

if (!Authentication::HasAccess()) {
    http_response_code(401);
	return;
}

if (!isset($_GET['key'])) {
    http_response_code(405);
    return;
}

for ($i = count($data->categories) - 1; $i >= 0; $i--) {
    if ($data->categories[$i]->key == $_GET['key']) {
        $data->categories[$i]->target = isset($data->categories[$i]->target) ? ($data->categories[$i]->target + 1) : 0;
    }
}

file_put_contents("ibringit.json", json_encode($data, JSON_PRETTY_PRINT));

?>
