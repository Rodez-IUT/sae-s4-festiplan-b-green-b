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
                API::send_error($url[0] . " inexistant", 404);
            }
            break;
        case 'DELETE':
            // si le contenu de $url[0] existe dans le dossier routes/PUT
            if (file_exists("routes/PUT/{$url[0]}.php")) {
                // on inclut le fichier correspondant
                // TODO: mettre un clé ???
                include_once "routes/PUT/{$url[0]}.php";
            } else {
                API::send_error($url[0] . " inexistant", 404);
            }
            break;
        case 'POST':
            // si le contenu de $url[0] existe dans le dossier routes/POST
            if (file_exists("routes/POST/{$url[0]}.php")) {
                // on inclut le fichier correspondant
                include_once "routes/POST/{$url[0]}.php";
            } else {
                API::send_error($url[0] . " inexistant", 404);
            }
            break;
        default:
            API::send_error("Appel non valide", 404);
    }
} else {
    API::send_error("URL non valide", 404);
}