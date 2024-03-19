<?php

namespace services;

use PDO;
use yasmf\HttpHelper;

/**
 * ModifInfoPersoService - Service de modification des informations personnelles de l'utilisateur.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ModifInfoPersoService
{

    /**
     * Récupère les informations actuelles de l'utilisateur.
     *
     * @param PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param int $idUtilisateur Identifiant de l'utilisateur.
     * @return array Tableau contenant les informations de l'utilisateur.
     */
    function getListeValeurs(PDO $pdo, int $idUtilisateur): array
    {
        $sql= "SELECT nomUser, prenomUser, emailUser, loginUser FROM users WHERE idUser = :idUser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idUser', $idUtilisateur);
        $stmt->execute();

        return $stmt->fetch();

    }

    /**
     * Vérifie les changements dans les informations de l'utilisateur.
     *
     * @param PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param array $liste Tableau contenant les nouvelles informations de l'utilisateur.
     * @param int $idUtilisateur Identifiant de l'utilisateur.
     * @param array $liste_information Tableau contenant les anciennes informations de l'utilisateur.
     * @return array Tableau associatif indiquant l'état de validation de chaque champ.
     */
    public function verif_changes(PDO $pdo, array $liste, int $idUtilisateur, array $liste_information): array
    {
        $sql= "SELECT passwordUser FROM users WHERE idUser = :idUser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idUser', $idUtilisateur);
        $stmt->execute();

        $mdp = $stmt->fetch()['passwordUser'];

        $liste_classes = [];
        foreach ($liste as $key => $value) {
            if ($key == "nouveauMotDePasse" || $key == "confirMotDePasse" || $key == "ancienMotDePasse"){
                if ($value == null){
                    $liste_classes[$key] = "ok";
                } else {
                    $liste_classes[$key] = $this->verif_input($key, $value) ? "ok" : "invalide";
                }
            } else if ($value != null && $value != $liste_information[$key] && $key != "ancienMotDePasses") {
                $liste_classes[$key] = $this->verif_input($key, $value) ? "ok" : "invalide";
            } else {
                $liste_classes[$key] = "";
            }
        }

        if (HttpHelper::getParam("nouveauMotDePasse") != HttpHelper::getParam("confirMotDePasse") && HttpHelper::getParam("nouveauMotDePasse") != null) {
            $liste_classes["confirMotDePasse"] = "invalide";
        } else if ( password_verify($mdp ,HttpHelper::getParam("ancienMotDePasse")) && HttpHelper::getParam("ancienMotDePasse") != null) {
            $liste_classes["ancienMotDePasse"] = "invalide";
        }

        return $liste_classes;
    }

    /**
     * Vérifie la validité d'une valeur d'entrée.
     *
     * @param string $key Clé correspondant au champ.
     * @param mixed $value Valeur à vérifier.
     * @return bool True si la valeur est valide, false sinon.
     */
    private function verif_input(string $key, mixed $value): bool
    {
        switch ($key) {
        case 'nom':
        case 'prenom':
        case 'identifiant':
        case 'nouveauMotDePasse':
        case 'confirMotDePasse':
        case 'ancienMotDePasse':
            return !ctype_space($value) && 0 < strlen($value) && strlen($value) < 100;

        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL);

        default:
            return false;

        }
    }

    /**
     * Met à jour les informations de l'utilisateur dans la base de données.
     *
     * @param PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param array $liste Tableau contenant les nouvelles informations de l'utilisateur.
     * @param int $idUtilisateur Identifiant de l'utilisateur.
     * @return void
     */
    public function updateUser(PDO $pdo, array $liste, int $idUtilisateur): void
    {
        $password = $liste['nouveauMotDePasse'] != null ? password_hash(htmlspecialchars($liste['nouveauMotDePasse'] ), PASSWORD_DEFAULT): password_hash(htmlspecialchars($liste['ancienMotDePasse'] ), PASSWORD_DEFAULT);
        $sql= "UPDATE users SET nomUser = :nomUser, prenomUser = :prenomUser, emailUser = :emailUser, loginUser = :loginUser, passwordUser = :passwordUser WHERE idUser = :idUser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idUser', $idUtilisateur);
        $stmt->bindParam(':nomUser', $liste['nom']);
        $stmt->bindParam(':prenomUser', $liste['prenom']);
        $stmt->bindParam(':emailUser', $liste['email']);
        $stmt->bindParam(':loginUser', $liste['identifiant']);
        $stmt->bindParam(':passwordUser', $password);
        $stmt->execute();
    }
}