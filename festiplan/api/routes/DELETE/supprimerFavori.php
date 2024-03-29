<?php

use api\API;

$idU = $_GET['idUser'] ?? null;
$idF = $_GET['idFestival'] ?? null;

if (!empty($idU) && !empty($idF)) {
    $result = $API->supprimerFavori($idU, $idF);
    // si le résultat est une string, c'est qu'il y a une erreur
    if (is_string($result)) {
        API::send_error($result, 500);
    } else {
        if ($result) {
            API::send_json(["status" => "OK", "message" => "Favori supprimé"], 200);
        } else {
            API::send_error("Favori non supprimé", 500);
        }
    }
} else {
    API::send_error("identifiant d'utilsateur ou identifiant de festival manquant", 400);
}