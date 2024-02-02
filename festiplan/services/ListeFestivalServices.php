<?php

namespace services;

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
     * @param mixed $pdo Instance PDO pour la connexion à la base de données.
     * @param mixed $idUtilisateur Identifiant de l'utilisateur.
     * @return array|null Tableau contenant les ID et noms des festivals pour l'utilisateur, ou null s'il n'y en a aucun.
     */
    public function getFestivals($pdo, $idUtilisateur): ?array
    {
        $stmt = $pdo->prepare("SELECT idFestival, nomFestival FROM festivals WHERE idResponsable = :idUtilisateur");

        $stmt->bindParam(":idUtilisateur", $idUtilisateur);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getListeSpectaclesFestivals($pdo, $festival) {
        foreach($festival as $i=> $liste_festival) {
            $idFestival = $festival[$i]["idFestival"];
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
     * @param mixed $pdo Instance PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Indique si l'utilisateur est responsable d'un festival.
     */
    function is_responsable($pdo, string $id): bool
    {
        $sql = "SELECT idResponsable
                FROM festivals
                WHERE idResponsable = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $result = $stmt->fetch();

        return $result ? true : false;
    }

    /**
     * Vérifie si un utilisateur est organisateur d'un festival.
     *
     * @param mixed $pdo Instance PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Indique si l'utilisateur est organisateur d'un festival.
     */
    function is_organisateur($pdo, string $id): bool
    {
        $sql = "SELECT idUser
                FROM organiser
                WHERE idUser = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $result = $stmt->fetch();

        return $result ? true : false;
    }

}