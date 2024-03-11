<?php

use api\API;

$login = $_GET['login'] ?? null;
$mdp = $_GET['mdp'] ?? null;


if (!empty($login) && !empty($mdp)) {
    $result = $API->login($login, $mdp);
    if ($result instanceof PDOException) {
        API::send_error($result->getMessage(), 500);
    } else {
        API::send_json($result, 200);
    }
} else {
    API::send_error("Login ou mot de passe manquant", 400);
}