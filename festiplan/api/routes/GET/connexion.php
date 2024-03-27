<?php

use api\API;

$login = $_GET['login'] ?? null;
$mdp = $_GET['mdp'] ?? null;


if (!empty($login) && !empty($mdp)) {
    $result = $API->login($login, $mdp);
    if (is_array($result)) {
        API::send_json($result, 200);
    } if ($result === false) {
        API::send_error("Login ou mot de passe incorrect", 400);
    } else {
        API::send_error("erreur de connexion a la BD", 500);
    }
} else {
    API::send_error("Login ou mot de passe manquant", 400);
}