<?php

namespace services\api;

use PDO;
use PDOException;
use services\AuthentificationServices;

/**
 * Classe LoginService
 *
 * Cette classe contient une méthode pour gérer la connexion d'un utilisateur.
 */
class LoginService
{
    /**
     * Méthode de connexion
     *
     * Cette méthode vérifie si un utilisateur est valide en utilisant les services d'authentification.
     *
     * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
     * @param string $identifiant L'identifiant de l'utilisateur.
     * @param string $mdp Le mot de passe de l'utilisateur.
     * @return array|bool|PDOException Retourne un tableau si l'utilisateur est valide, false si non valide, ou une exception PDO en cas d'erreur de la base de données.
     */
    public static function login($pdo, string $identifiant, string $mdp): array|bool|PDOException
    {
        return (new \services\AuthentificationServices)->is_user_valid($pdo, $identifiant, $mdp);
    }
}