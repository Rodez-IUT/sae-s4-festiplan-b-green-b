<?php

namespace services;

use PDO;

/**
 * La classe InfoSpectacleService fournit des méthodes 
 * pour obtenir des informations sur un spectacle.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class InfoSpectacleService
{

    /**
     * Récupère la présentation d'un spectacle en fonction de son identifiant.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param mixed $idSpectacle Identifiant du spectacle dont les informations sont recherchées.
     * @return array|null Tableau contenant les informations sur le spectacle, ou null s'il n'existe pas.
     */
    function getSpectaclePresentation(PDO $pdo, $idSpectacle): ?array
    {

        $searchStmt = $pdo->prepare("SELECT * 
                                    FROM spectacles
                                    WHERE idSpectacle = :idSpectacle");
        $searchStmt->bindParam(":idSpectacle", $idSpectacle);
        $searchStmt->execute();


        $retour = $searchStmt->fetch();

        $retour["image_name"] = $this->getImageName($pdo, $retour["idImage"])->fetch()["nomImage"];
        $retour["categories"] = array();
        foreach ($this->getCategories($pdo, $retour["idSpectacle"]) as $cate) {
            $retour["categories"][] = $cate["nomCategorie"];
        }
        foreach($this->getIntervenantScene($pdo, $retour["idSpectacle"]) as $intervenant) {
            $retour["intervenantScene"]["nomPrenom"][] = $intervenant;
        }

        foreach($this->getIntervenantHors($pdo, $retour["idSpectacle"]) as $intervenant) {
            $retour["intervenantHors"]["nomPrenom"][] = $intervenant;
        }
        $retour["tailleScene"] = $this->getTailleScene($retour["surfaceSceneRequise"]);

        $retour["responsable"] = $this->getResponsable($pdo, $retour["idSpectacle"]);

        return $retour;
    }

    /**
     * Récupère le nom de l'image en fonction de l'identifiant de l'image.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $id_image Identifiant de l'image.
     * @return \PDOStatement Résultat de la requête PDOStatement pour récupérer le nom de l'image.
     */
    function getImageName(PDO $pdo, int $id_image): \PDOStatement
    {

        $searchStmt = $pdo->prepare("SELECT nomImage 
                                    FROM images
                                    WHERE idImage = ?");

        $searchStmt->execute([$id_image]);
        return $searchStmt;
    }

    /**
     * Récupère les catégories associées à un spectacle en fonction de son identifiant.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $id_spectacle Identifiant du spectacle.
     * @return \PDOStatement Résultat de la requête PDOStatement pour récupérer les catégories du spectacle.
     */
    function getCategories(PDO $pdo, int $id_spectacle): \PDOStatement
    {
        $searchStmt = $pdo->prepare("SELECT nomCategorie 
                                    FROM categories
                                    INNER JOIN categorieSpectacle
                                    ON categories.idCategorie = categorieSpectacle.idCategorie
                                    WHERE categorieSpectacle.idSpectacle = ?");

        $searchStmt->execute([$id_spectacle]);
        return $searchStmt;
    }

    /**
     * Récupère les intervenants sur scène associés à un spectacle en fonction de son identifiant.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $id_spectacle Identifiant du spectacle.
     * @return \PDOStatement Résultat de la requête PDOStatement pour récupérer les intervenants sur scène du spectacle.
     */
    function getIntervenantScene(PDO $pdo, int $id_spectacle): \PDOStatement
    {
        $searchStmt = $pdo->prepare("SELECT nomIntervenant, prenomIntervenant 
                                    FROM intervenants
                                    INNER JOIN intervenir
                                    ON intervenants.idIntervenant = intervenir.idIntervenant
                                    WHERE intervenir.idSpectacle = ?
                                    AND intervenants.estSurScene = 1");

        $searchStmt->execute([$id_spectacle]);
        return $searchStmt;
    }

    /**
     * Récupère les intervenants hors scène associés à un spectacle en fonction de son identifiant.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $id_spectacle Identifiant du spectacle.
     * @return \PDOStatement Résultat de la requête PDOStatement pour récupérer les intervenants hors scène du spectacle.
     */
    function getIntervenantHors(PDO $pdo, int $id_spectacle): \PDOStatement
    {
        $searchStmt = $pdo->prepare("SELECT nomIntervenant, prenomIntervenant
                                    FROM intervenants
                                    INNER JOIN intervenir
                                    ON intervenants.idIntervenant = intervenir.idIntervenant
                                    WHERE intervenir.idSpectacle = ?
                                    AND intervenants.estSurScene = 0");

        $searchStmt->execute([$id_spectacle]);
        return $searchStmt;
    }

    /**
     * Convertit l'identifiant de taille de scène en une description lisible.
     *
     * @param int $idTaille Identifiant de la taille de scène.
     * @return string Description de la taille de scène (petite, moyenne, grande).
     */
    function getTailleScene(int $idTaille): string
    {
        if ($idTaille == 1){
            $taille = "petite";
        } else if($idTaille == 2){
            $taille = "moyenne";
        } else {
            $taille = "grande";
        }
        return $taille;
    }

    /**
     * Récupère les informations sur le responsable d'un spectacle en fonction de son identifiant.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $id_spectacle Identifiant du spectacle.
     * @return array Tableau contenant les informations sur le responsable du spectacle.
     */
    function getResponsable(PDO $pdo, int $id_spectacle): array
    {
        $searchStmt = $pdo->prepare("SELECT nomUser, prenomUser
                                    FROM users
                                    INNER JOIN spectacles
                                    ON users.idUser = spectacles.idResponsableSpectacle
                                    WHERE spectacles.idSpectacle = ?");

        $searchStmt->execute([$id_spectacle]);

        return $searchStmt->fetch();
    }
}