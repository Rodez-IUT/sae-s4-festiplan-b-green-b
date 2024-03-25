<?php

// Déclaration de l'espace de noms
namespace services\api;

// Importation de la classe PDOException
use PDOException;

/**
 * Classe FestivalService
 *
 * Cette classe fournit des méthodes pour interagir avec la base de données des festivals.
 */
class FestivalService
{
    /**
     * Récupère tous les festivals de la base de données.
     *
     * @param $pdo L'objet PDO pour la connexion à la base de données.
     * @return array|PDOException Les festivals récupérés ou une exception PDO en cas d'erreur.
     */
    public static function getAllFestival($pdo): array|PDOException
    {
        $stmt = $pdo->prepare("SELECT * FROM festivals ORDER BY dateDebutFestival");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère l'organisateur d'un festival spécifique.
     *
     * @param $pdo L'objet PDO pour la connexion à la base de données.
     * @param $id L'ID du festival.
     * @return array|PDOException L'organisateur du festival ou une exception PDO en cas d'erreur.
     */
    public static function getOrganizerFestival($pdo, $id): array|PDOException
    {
        $stmt = $pdo->prepare("SELECT * FROM users INNER JOIN organiser ON users.idUser = organiser.idUser WHERE organiser.idFestival = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les scènes d'un festival spécifique.
     *
     * @param $pdo L'objet PDO pour la connexion à la base de données.
     * @param $id L'ID du festival.
     * @return array|PDOException Les scènes du festival ou une exception PDO en cas d'erreur.
     */
    public static function getScenesFestival($pdo, $id): array|PDOException
    {
        $stmt = $pdo->prepare("SELECT * FROM scenes INNER JOIN accueillir ON scenes.idScene = accueillir.idScene WHERE accueillir.idFestival = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les spectacles d'un festival spécifique.
     *
     * @param $pdo L'objet PDO pour la connexion à la base de données.
     * @param $id L'ID du festival.
     * @return array|PDOException Les spectacles du festival ou une exception PDO en cas d'erreur.
     */
    public static function getShowsFestival($pdo, $id): array|PDOException
    {
        $stmt = $pdo->prepare("SELECT * FROM spectacles INNER JOIN composer ON spectacles.idSpectacle = composer.idSpectacle WHERE composer.idFestival = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les détails d'un festival spécifique.
     *
     * @param $pdo L'objet PDO pour la connexion à la base de données.
     * @param $id L'ID du festival.
     * @return array|PDOException Les détails du festival ou une exception PDO en cas d'erreur.
     */
    public static function getDetailsFestival($pdo, $id): array|PDOException
    {
        $stmt = $pdo->prepare("SELECT festivals.*, images.nomImage FROM festivals 
                           INNER JOIN images ON festivals.idImage = images.idImage 
                           WHERE idFestival = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result =  $stmt->fetchAll();

        return $result;
    }
}