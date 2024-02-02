<?php

namespace services;

/**
 * GestionSpectaclesService - Service de gestion des spectacles.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class GestionSpectaclesService
{
    /**
     * Supprime un spectacle de la base de données.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string $id Identifiant du spectacle à supprimer.
     */
    public function supprimerSpectacle(\PDO $pdo, string $id)
    {
        // Requête SQL pour supprimer un spectacle en utilisant un paramètre nommé.
        $sql = "DELETE FROM spectacles WHERE idSpectacle = :id";

        // Préparation et exécution de la requête SQL avec liaison du paramètre.
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
}
