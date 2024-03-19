<?php

use api\API;


if (isset($url[1])) {
    $result = $API->getDetailsFestival($url[1]);
    if (is_array($result)) {
        API::send_json($result, 200);
    } else {
        API::send_error("Erreur lors de la récupération des details du festival : " . $result, 500);
    }
} else {
    API::send_error("ID manquant", 400);
}
