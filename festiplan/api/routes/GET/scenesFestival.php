<?php

use api\API;

$url = explode('/', filter_var($_GET['demande'], FILTER_SANITIZE_URL));

if (isset($url[1])) {
    $result = $API->getScenesFestival($url[1]);
    if (is_array($result)) {
        API::send_json($result, 200);
    } else {
        API::send_error("Erreur lors de la récupération des scènes : " . $result->getMessage(), 500);
    }
} else {
    API::send_error("L'id du festival est manquant", 400);
}
