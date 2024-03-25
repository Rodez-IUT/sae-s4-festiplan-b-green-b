<?php

use api\API;


if (isset($url[1])) {
    $result = $API->getScenesFestival($url[1]);
    if (is_array($result)) {
        if (count($result) == 0) {
            API::send_error("Festival introuvable", 404);
        }
        API::send_json($result, 200);
    } else {
        API::send_error("Erreur lors de la récupération des scènes : " . $result, 500);
    }
} else {
    API::send_error("L'id du festival est manquant", 400);
}
