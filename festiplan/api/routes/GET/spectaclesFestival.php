<?php

use api\API;

$url = explode('/', filter_var($_GET['demande'], FILTER_SANITIZE_URL));

if (isset($url[1])) {
    $result = $API->getShowsFestival($url[1]);
    if (is_array($result)) {
        API::send_json($result, 200);
    } else {
        API::send_json([
            "status" => "KO",
            "message" => "Erreur lors de la récupération des festivals"
        ], 500);
    }
} else {
    API::send_json([
        "status" => "KO",
        "message" => "URL non valide, id manquant"
    ], 400);
}