<?php

use api\API;


if (!empty($_GET["demande"])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $url = explode('/', filter_var($_GET['demande'], FILTER_SANITIZE_URL));

            switch ($url[0]) {
                case 'allFestivals':
                    // TODO: à compléter
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

