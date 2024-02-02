<?php

namespace services;

use PDO;

/**
 * Classe fournissant des services pour récupérer la liste de tous les spectacles.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ListeTousSpectaclesServices
{

    /**
     * Récupère la liste de tous les spectacles.
     *
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @return array Liste de tous les spectacles.
     */
    public function getListeSpectacles(PDO $pdo): array
    {
        $liste_spectacles = array();

        $stmt = $pdo->query("SELECT * FROM spectacles");
        while ($row = $stmt->fetch()) {
            $liste_spectacles[] = $row;
        }

        return $liste_spectacles;
    }

}
