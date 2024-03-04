<?php

require_once "../autoload.php";

use api\API;

$API = new API();

// on vérifie que la demande est bien présente
if (!empty($_GET["demande"])) {
    $url = explode('/', filter_var($_GET['demande'], FILTER_SANITIZE_URL));
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            // si le contenu de $url[0] existe dans le dossier routes/GET
            if (file_exists("routes/GET/{$url[0]}.php")) {
                // on inclut le fichier correspondant
                include_once "routes/GET/{$url[0]}.php";
            } else {
                $info['status'] = "KO";
                $info['message'] = $url[0] . " inexistant";
                API::send_json($info, 404);
            }
            break;
        case 'PUT':
            // si le contenu de $url[0] existe dans le dossier routes/PUT
            if (file_exists("routes/PUT/{$url[0]}.php")) {
                // on inclut le fichier correspondant
                include_once "routes/PUT/{$url[0]}.php";
            } else {
                $info['status'] = "KO";
                $info['message'] = $url[0] . " inexistant";
                API::send_json($info, 404);
            }
            break;
        default:
            $info['status'] = "KO";
            $info['message'] = "URL non valide";
            API::send_json($info, 404);
    }
} else {
    $info['status'] = "KO";
    $info['message'] = "URL non valide";
    API::send_json($info, 404);
}