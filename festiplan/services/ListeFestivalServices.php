<?php

namespace services;

use PDO;

/**
 * Classe fournissant des services liés à la liste des festivals.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ListeFestivalServices
{

    /**
     * Récupère la liste des festivals pour un utilisateur donné.
     *
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @param int $idUtilisateur Identifiant de l'utilisateur.
     * @return array|null Tableau contenant les ID et noms des festivals pour l'utilisateur, ou null s'il n'y en a aucun.
     */
    public function getFestivals(PDO $pdo, int $idUtilisateur): ?array
    {
        $requete = "SELECT idFestival, nomFestival FROM festivals WHERE idResponsable = :idUtilisateur";

        $stmt = $pdo->prepare($requete);
        $stmt->bindParam(":idUtilisateur", $idUtilisateur);
        $stmt->execute();

        $festivals = array();
        while ($row = $stmt->fetch()) {
            $festivals[] = $row;
        }

        return $festivals;
    }

    public function getListeSpectaclesFestivals($pdo, $festival): array
    {
        foreach($festival as $i=> $liste_festival) {
            $idFestival = $liste_festival["idFestival"];

            $sql = "SELECT idSpectacle FROM festivals
                    INNER JOIN composer 
                    ON composer.idFestival = festivals.idFestival
                    WHERE festivals.idFestival = :idFestival";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("idFestival", $idFestival);
            if($stmt->execute()) {
                foreach($stmt as $row) {
                    $festival[$i]["spectacles"] = $row;
                }
            }
        }
        return $festival;
    }

    /**
     * Vérifie si un utilisateur est responsable d'un festival.
     *
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Indique si l'utilisateur est responsable d'un festival.
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
     * Vérifie si un utilisateur est organisateur d'un festival.
     *
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Indique si l'utilisateur est organisateur d'un festival.
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