<?php

namespace services\api;

use PDOException;
use services\AuthentificationServices;


class LoginService
{
    public static function login($pdo, string $identifiant, string $mdp): array|bool|PDOException
    {
        try {
            return (new \services\AuthentificationServices)->is_user_valid($pdo, $identifiant, $mdp);
        } catch (PDOException $e) {
            return $e;
        }
    }
}