<?php

use api\API;


if (isset($url[1])) {
    $result = $API->getOrganizerFestival($url[1]);
    if (is_array($result)) {
        if (count($result) == 0) {
            API::send_error("Festival introuvable", 400);
        }
        API::send_json($result, 200);
    } else {
        API::send_error("Erreur lors de la récupération des festivals : " . $result, 500);
    }
} else {
    API::send_error("ID manquant", 400);
}
