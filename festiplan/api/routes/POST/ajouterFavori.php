<?php

use api\API;

// on récupère le contenu de la requête
$json_str = file_get_contents('php://input');

// on le transforme en objet
$json_obj = json_decode($json_str);

$idU = $json_obj->idUser ?? null;
$idF = $json_obj->idFestival ?? null;


if (!empty($idU) && !empty($idF)) {
    $result = $API->ajouterFavori($idU, $idF);
    // si le résultat est une string, c'est qu'il y a une erreur
    if (is_string($result)) {
        API::send_error($result, 500);
    } else {
        if ($result) {
            API::send_json(["status" => "OK", "message" => "Favori ajouté"], 200);
        } else {
            API::send_error("Favori non ajouté", 500);
        }
    }
} else {
    API::send_error("identifiant d'utilsateur ou identifiant de festival manquant", 400);
}