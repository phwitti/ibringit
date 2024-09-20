<?php

require_once('conf.inc.php');

if (!isset($_GET['object']) || !isset($_GET['name'])) {
    http_response_code(405);
    return;
}

for ($i = count($data->open) - 1; $i >= 0; $i--) {
    if ($data->open[$i]->object == $_GET['object']) {
        $brought_object = new stdClass;
        $brought_object->object = $data->open[$i]->object;
        $brought_object->name = htmlspecialchars($_GET['name']);
        $brought_object->new = true;
        $brought_object->category = $data->open[$i]->category;
        $data->brought[] = $brought_object;

        if ($data->open[$i]->count == 0 || $data->open[$i]->count == 1) {
            array_splice($data->open, $i, 1);
        } elseif ($data->open[$i]->count > 1) {
            $data->open[$i]->count--;
        }

        file_put_contents("ibringit.json", json_encode($data, JSON_PRETTY_PRINT));
        return;
    }
}

if (isset($_GET['category'])) {
    $brought_object = new stdClass;
    $brought_object->object = htmlspecialchars($_GET['object']);
    $brought_object->name = htmlspecialchars($_GET['name']);
    $brought_object->new = true;
    $brought_object->category = htmlspecialchars($_GET['category']);
    $data->brought[] = $brought_object;

    file_put_contents("ibringit.json", json_encode($data, JSON_PRETTY_PRINT));
    return;
}

?>
