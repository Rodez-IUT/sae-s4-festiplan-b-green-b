<?php

namespace services\api;

use PDOException;

/**
 * Classe FavorisService
 *
 * Cette classe contient des méthodes pour gérer les favoris d'un utilisateur.
 */
class FavorisService
{
    /**
     * Méthode pour obtenir les favoris d'un utilisateur
     *
     * Cette méthode récupère tous les favoris d'un utilisateur spécifique.
     *
     * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
     * @param int $id L'identifiant de l'utilisateur.
     * @return array Retourne un tableau contenant tous les favoris de l'utilisateur.
     */
    public static function getFavoris($pdo, $id): array
    {
        $stmt = $pdo->prepare("SELECT * FROM favoris WHERE idUser = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Méthode pour ajouter un favori
     *
     * Cette méthode ajoute un nouveau favori pour un utilisateur spécifique.
     *
     * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
     * @param int $isUser L'identifiant de l'utilisateur.
     * @param int $idFestival L'identifiant du festival.
     * @return bool|string Retourne true si le favori a été ajouté avec succès, false sinon.
     */
    public static function addFavori($pdo, $isUser, $idFestival): bool|string
    {
        // on verifie que la favori n'existe pas déjà
        $stmt = $pdo->prepare("SELECT * FROM favoris WHERE idUser = :idUser AND idFestival = :idFestival");
        $stmt->bindParam(':idUser', $isUser);
        $stmt->bindParam(':idFestival', $idFestival);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Favori déjà existant";
        }

        $stmt = $pdo->prepare("INSERT INTO favoris VALUES (:idUser, :idFestival)");
        $stmt->bindParam(':idUser', $isUser);
        $stmt->bindParam(':idFestival', $idFestival);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Méthode pour supprimer un favori
     *
     * Cette méthode supprime un favori spécifique d'un utilisateur spécifique.
     *
     * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
     * @param int $isUser L'identifiant de l'utilisateur.
     * @param int $idFestival L'identifiant du festival.
     * @return bool|PDOException Retourne true si le favori a été supprimé avec succès, false sinon.
     */
    public static function deleteFavori($pdo, $isUser, $idFestival): bool|PDOException
    {
        $stmt = $pdo->prepare("DELETE FROM favoris WHERE idUser = :idUser AND idFestival = :idFestival");
        $stmt->bindParam(':idUser', $isUser);
        $stmt->bindParam(':idFestival', $idFestival);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

}