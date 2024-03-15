<?php

namespace services;

use PDO;
use PDOException;

/**
 * La classe AuthentificationServices fournit des méthodes
 * pour gérer l'authentification des utilisateurs
 *
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class AuthentificationServices
{

    /**
     * Vérifie si les informations d'identification de l'utilisateur sont valides.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $identifiant Identifiant de l'utilisateur.
     * @param string $password Mot de passe de l'utilisateur.
     * @return array|bool Retourne un tableau contenant les informations de l'utilisateur si valide, sinon false.
     */
    function is_user_valid (PDO $pdo, string $identifiant, string $password): array|bool
    {
        $sql = "SELECT idUser, nomUser, prenomUser, loginUser, passwordUser
                FROM users 
                WHERE loginUser = :identifiant";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":identifiant", $identifiant);

        $stmt->execute();

        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user["passwordUser"])) {
            return false;
        } else {
            return $user;
        }

    }

    /**
     * Obtient les identifiants des festivals associés à un utilisateur.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return array Retourne un tableau contenant les identifiants des festivals associés à l'utilisateur.
     */
    private function get_user_festivals_id(PDO $pdo, string $id): array
    {
        $sql = "SELECT idFestival
                FROM festivals
                WHERE idResponsable = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $festivals = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $festivals[] = $row;
        }

        return $festivals;
    }

    /**
     * Obtient les identifiants des spectacles associés à un utilisateur.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return array Retourne un tableau contenant les identifiants des spectacles associés à l'utilisateur.
     */
    private function get_user_spectacles_id(PDO $pdo, string $id): array
    {
        $sql = "SELECT idSpectacle
                FROM spectacles
                WHERE idResponsableSpectacle = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $spectacles = array();
        while ($spectacle = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $spectacles[] = $spectacle;
        }

        return $spectacles;
    }

    /**
     * Supprime le compte de l'utilisateur avec ses festivals et spectacles associés.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Retourne true si la suppression est réussie, sinon false.
     */
    function delete_account(PDO $pdo, string $id): bool
    {
        $requetes_festivals = array(
            "DELETE FROM categorieFestival WHERE idFestival = :id;",
            "DELETE FROM composer WHERE idFestival = :id;",
            "DELETE FROM organiser WHERE idFestival = :id;",
            "DELETE FROM accueillir WHERE idFestival = :id;",
            "DELETE FROM festivals WHERE idFestival = :id;",
        );

        $requetes_user = array(
            "DELETE FROM intervenir WHERE idSpectacle = :id;",
            "DELETE FROM categorieSpectacle WHERE idSpectacle = :id;",
            "DELETE FROM spectacles WHERE idSpectacle = :id;",
        );

        $liste_id_festivals = $this->get_user_festivals_id($pdo, $id);
        $liste_id_spectacles = $this->get_user_spectacles_id($pdo, $id);

        $reussite = true;

        try {
            foreach ($liste_id_festivals as $id_festival) {
                foreach ($requetes_festivals as $requete) {
                    $stmt = $pdo->prepare($requete);
                    $stmt->bindParam(":id", $id_festival["idFestival"]);
                    $reussite &= $stmt->execute();
                }

            }

            foreach ($liste_id_spectacles as $id_spectacle) {
                foreach ($requetes_user as $requete) {
                    $stmt = $pdo->prepare($requete);
                    $stmt->bindParam(":id", $id_spectacle["idSpectacle"]);
                    $reussite &= $stmt->execute();
                }
            }

            $sql = "DELETE FROM users WHERE idUser = :id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":id", $id);

            $reussite &= $stmt->execute();

        } catch (PDOException $e) {
            echo $e;
        }

        return $reussite;
    }

    /**
     * Vérifie si l'utilisateur est responsable d'un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Retourne true si l'utilisateur est responsable d'un festival, sinon false.
     */
    function is_responsable(PDO $pdo, string $id): bool
    {
        $sql = "SELECT idResponsable
                FROM festivals
                WHERE idResponsable = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $result = $stmt->fetch();

        return (bool)$result;
    }

    /**
     * Vérifie si l'utilisateur est un organisateur.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Retourne true si l'utilisateur est un organisateur, sinon false.
     */
    function is_organisateur(PDO $pdo, string $id): bool
    {
        $sql = "SELECT idUser
                FROM organiser
                WHERE idUser = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $result = $stmt->fetch();

        return (bool)$result;
    }

}