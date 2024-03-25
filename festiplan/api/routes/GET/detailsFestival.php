<?php

use api\API;


if (isset($url[1])) {
    $result = $API->getDetailsFestival($url[1]);
    if (is_array($result)) {
        if (count($result) == 0) {
            API::send_error("Festival introuvable", 400);
        }
        // on modifi le chemin pour accéder à l'image
        $result[0]['imagePath'] = $_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/stockage/images/" . $result[0]['nomImage'];
        API::send_json($result, 200);
    } else {
        API::send_error("Erreur lors de la récupération des details du festival : " . $result, 500);
    }
} else {
    API::send_error("ID manquant", 400);
}
