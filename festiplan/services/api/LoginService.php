<?php

namespace services\api;

use PDO;
use PDOException;
use Random\RandomException;
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
    public static function login(PDO $pdo, string $identifiant, string $mdp): array|bool|PDOException
    {
        $user = (new \services\AuthentificationServices)->is_user_valid($pdo, $identifiant, $mdp);

        // a la connexion, on renvoie l'utilisateur ainsi que sa clé d'authentification
        if (is_array($user)) {
            $user['APIKey'] = self::get_API_key($pdo, $user['idUser']);
        }

        return $user;
    }

    /**
     * Retourne la clé API de l'utilisateur.
     * Si elle n'existe pas, elle la génère
     * @param PDO $pdo PDO pour la requete
     * @param int $idUser l'identifiant de l'utilisateur
     * @param bool $genere si false ne génére pas de clé (si elle n'existe pas)
     * @return string la clé de l'utilisateur
     * @throws RandomException
     */
    public static function get_API_key(PDO $pdo, int $idUser, bool $genere = true): string
    {
        // on verifie si l'utilisateur a déjà une clé
        $stmt = $pdo->prepare("SELECT * FROM api_keys WHERE idUser = :idUser");

        $stmt->bindParam(':idUser', $idUser);
        $stmt->execute();
        $key = $stmt->fetch();

        if ($key) {
            return $key['APIKey'];
        } else if ($genere) {
            return self::generate_key($pdo, $idUser);
        }
        return "";
    }

    /**
     * Génére une clé API pour l'utilisateur
     * @param int $idUser l'identifiant de l'utilisateur qui a besoin d'une clé
     * @return string la clé généré
     * @throws RandomException
     */
    public static function generate_key(PDO $pdo, int $idUser): string
    {
        // on genere
        $key = bin2hex(random_bytes(16));

        // on stocke la clé
        $stmt = $pdo->prepare("INSERT INTO  api_keys (idUser, APIKey) VALUES (:idUser, :key)");
        $stmt->bindParam(':idUser', $idUser);
        $stmt->bindParam(':key', $key);
        $stmt->execute();

        return $key;
    }
}