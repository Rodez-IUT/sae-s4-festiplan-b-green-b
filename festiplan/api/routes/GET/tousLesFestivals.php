<?php

use api\API;

$result =  $API->getAllFestival();
if (is_array($result)) {
    API::send_json($result, 200);
} else {
    API::send_json([
        "status" => "KO",
        "message" => "Erreur lors de la récupération des festivals"
    ], 500);
}