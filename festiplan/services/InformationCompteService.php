<?php

namespace services;

use PDO;

/**
 * La classe InformationCompteService contient des méthodes 
 * pour récupérer les informations d'un compte utilisateur.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class InformationCompteService 
{
    
    /**
     * Récupère les informations d'un compte utilisateur à partir de son identifiant.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $user_id Identifiant de l'utilisateur dont les informations sont recherchées.
     * @return mixed Tableau contenant les informations de l'utilisateur (nom, prénom, email, login), ou false si aucun résultat.
     * @throws \Exception Si l'objet PDO est null.
     */
    function GetInfoFromAccount(PDO $pdo, string $user_id) {

        if (is_null($pdo)) {
            throw new \Exception("InformationCompteService Exception : PDO object is null");
        }

        $requete = "SELECT nomUser, prenomUser, emailUser, loginUser 
                    FROM users WHERE idUser = ?";
        
        $stmt = $pdo->prepare($requete);
        $stmt->execute([$user_id]);

        return $stmt->fetch();

    }

}
