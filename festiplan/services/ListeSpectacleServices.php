<?php

namespace services;

use PDO;

/**
 * Classe fournissant des services liés à la gestion de la liste des spectacles.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ListeSpectacleServices
{

    /**
     * Récupère la liste des spectacles pour un utilisateur donné.
     *
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @param string $idUser Identifiant de l'utilisateur.
     * @return array Liste des spectacles pour l'utilisateur.
     */
    public function getListeSpectacles(PDO $pdo, string $idUser): array
    {
        $liste_spectacles = array();

        $stmt = $pdo->prepare("SELECT * FROM spectacles WHERE idResponsableSpectacle = :idUtilisateur");

        $stmt->bindParam(":idUtilisateur", $idUser);

        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $liste_spectacles[] = $row;
        }

        return $liste_spectacles;
    }

    /**
     * Vérifie si un utilisateur est responsable d'un spectacle.
     *
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Indique si l'utilisateur est responsable d'un spectacle.
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
     * Vérifie si un utilisateur est organisateur d'un spectacle.
     *
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @param string $id Identifiant de l'utilisateur.
     * @return bool Indique si l'utilisateur est organisateur d'un spectacle.
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