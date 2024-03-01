<?php

require_once "../autoload.php";
use api\API;

$API = new API();

if (!empty($_GET["demande"])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $url = explode('/', filter_var($_GET['demande'], FILTER_SANITIZE_URL));

            switch ($url[0]) {
                case 'allFestivals':
                    $result =  $API->getAllFestival();
                    if (is_array($result)) {
                        API::send_json($result, 200);
                    } else {
                        API::send_json([
                            "status" => "KO",
                            "message" => "Erreur lors de la récupération des festivals"
                        ], 500);
                    }
                    break;
                default:
                    $info['status'] = "KO";
                    $info['message'] = $url[0] . "inexistant";
                    API::send_json($info, 404);
            }
            break;
        case 'PUT':
            $url = explode('/', filter_var($_GET['demande'], FILTER_SANITIZE_URL));

            if ($url[0] === '' && isset($url[1])) {
                // TODO: à compléter
            } else {
                $info['status'] = "KO";
                $info['message'] = "URL non valide";
                API::send_json($info, 404);
            }
            break;
        default:
            $info['status'] = "KO";
            $info['message'] = "URL non valide";
            API::send_json($info, 404);
    }
}

