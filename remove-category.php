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
        array_splice($data->categories, $i, 1);
    }
}

file_put_contents("ibringit.json", json_encode($data, JSON_PRETTY_PRINT));

?>
