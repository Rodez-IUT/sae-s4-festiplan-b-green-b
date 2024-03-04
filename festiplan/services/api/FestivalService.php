<?php

namespace services\api;

use PDOException;

class FestivalService
{
    public static function getAllFestival($pdo): array | PDOException
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM festivals");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $e;
        }
    }

    public static function getOrganizerFestival($pdo, $id): array | PDOException
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users INNER JOIN organiser ON users.idUser = organiser.idUser WHERE organiser.idFestival = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $e;
        }
    }

    public static function getScenesFestival($pdo, $id): array | PDOException
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM scenes INNER JOIN accueillir ON scenes.idScene = accueillir.idScene WHERE accueillir.idFestival = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $e;
        }
    }

    public static function getShowsFestival($pdo, $id): array | PDOException
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM spectacles INNER JOIN composer ON spectacles.idSpectacle = composer.idSpectacle WHERE composer.idFestival = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $e;
        }
    }
}