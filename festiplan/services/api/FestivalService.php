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
            $stmt = $pdo->prepare("SELECT * FROM users INNER JOIN organiser ON users.id = organiser.id_user WHERE organiser.id_festival = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $e;
        }
    }

    public static function getScenesFestival()
    {

    }

    public static function getShowsFestival()
    {

    }
}