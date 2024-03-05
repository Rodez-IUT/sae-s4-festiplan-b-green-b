<?php

use api\API;

$result =  $API->getAllFestival();
if (is_array($result)) {
    API::send_json($result, 200);
} else {
    API::send_error("Erreur lors de la récupération des festivals : " . $result->getMessage(), 500);
}