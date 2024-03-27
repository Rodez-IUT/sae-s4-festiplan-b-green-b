<?php

use api\API;


if (isset($url[1]) && is_numeric($url[1])) {
    $result = $API->getAllFavorites($url[1]);
    if (is_array($result)) {
        if (count($result) == 0) {
            API::send_error("Aucun favoris lié à cet utilisateur", 400);
        }

        API::send_json($result, 200);
    } else {
        API::send_error("Erreur lors de la récupération des favoris : " . $result, 500);
    }
} else {
    API::send_error("L'id de l'utilisateur est manquant", 400);
}
