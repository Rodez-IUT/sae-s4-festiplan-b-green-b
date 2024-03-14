<?php

namespace services;

use PDO;
use PDOStatement;

/**
 * Service de gestion de l'accueil pour les festivals.
 *
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class AccueilService
{

    /**
     * Récupère les informations des festivals à venir.
     *
     * @param PDO $pdo Objet PDO pour la connexion à la base de données.
     * @return array|null Un tableau contenant les détails des festivals à venir, ou null s'il n'y en a pas.
     */
    function getFestivalsPresentation(PDO $pdo): ?array
    {
        // $today = date("Y-m-d");

        $searchStmt = $pdo->prepare("SELECT * FROM festivals
                                    -- WHERE dateDebutFestival > :today 
                                    ORDER BY dateDebutFestival
                                    LIMIT 10");

        // $searchStmt->execute(["today" => $today]);
        $searchStmt->execute();

        $retour = array();

        foreach ($searchStmt as $row) {
            $row["image_name"] = $this->getImageName($pdo, $row["idImage"])->fetch()["nomImage"];
            $row["categories"] = array();
            foreach ($this->getCategories($pdo, $row["idFestival"]) as $cate) {
                $row["categories"][] = $cate["nomCategorie"];
            }
            $retour[] = $row;
        }

        return $retour;
    }

    /**
     * Récupère le nom de l'image à partir de son ID.
     *
     * @param PDO $pdo Objet PDO pour la connexion à la base de données.
     * @param int $id_image L'ID de l'image à récupérer.
     * @return PDOStatement Le nom de l'image ou null s'il n'est pas trouvé.
     */
    function getImageName(PDO $pdo, int $id_image): PDOStatement
    {
        $today = date("Y-m-d");

        $searchStmt = $pdo->prepare("SELECT nomImage 
                                    FROM images
                                    WHERE idImage = ?");

        $searchStmt->execute([$id_image]);
        return $searchStmt;
    }

    /**
     * Récupère les catégories associées à un festival.
     *
     * @param PDO $pdo Objet PDO pour la connexion à la base de données.
     * @param int $id_festival L'ID du festival.
     * @return PDOStatement Les catégories du festival.
     */
    function getCategories(PDO $pdo, int $id_festival): PDOStatement
    {
        $searchStmt = $pdo->prepare("SELECT nomCategorie 
                                    FROM categories
                                    INNER JOIN categorieFestival
                                    ON categories.idCategorie = categorieFestival.idCategorie
                                    WHERE categorieFestival.idFestival = ?");

        $searchStmt->execute([$id_festival]);
        return $searchStmt;
    }
}