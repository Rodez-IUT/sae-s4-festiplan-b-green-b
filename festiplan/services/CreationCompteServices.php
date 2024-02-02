<?php

namespace services;

use PDO;
use yasmf\HttpHelper;
use other\classes\User;

/**
 * La classe CreationCompteServices fournit des méthodes
 * pour la création de comptes utilisateurs.
 *
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class CreationCompteServices
{

    /**
     * Vérifie les entrées fournies et retourne un tableau des classes CSS associées à chaque champ.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param array $liste Tableau des données à vérifier.
     * @return array Tableau associatif des classes CSS pour chaque champ.
     */
    private function verif_inputs(PDO $pdo, array $liste): array
    {
        $liste_classes = [];
        foreach ($liste as $key => $value) {
            if ($value != null) {
                $liste_classes[$key] = $this->verif_input($key, $value) ? "ok" : "invalide";
            } else {
                $liste_classes[$key] = "";
            }
        }

        if (HttpHelper::getParam("motDePasse") != HttpHelper::getParam("confirMotDePasse")) {
            $liste_classes["confirMotDePasse"] = "invalide";
        }

        return $liste_classes;
    }

    /**
     * Vérifie une entrée spécifique en fonction de sa clé et de sa valeur.
     *
     * @param string $key Clé de l'entrée à vérifier.
     * @param mixed $value Valeur de l'entrée à vérifier.
     * @return bool Retourne true si l'entrée est valide, sinon false.
     */
    private function verif_input(string $key, mixed $value): bool
    {
        switch ($key) {
        case 'nom':
        case 'prenom':
        case 'identifiant':
        case 'motDePasse':
        case 'confirMotDePasse':
            return !ctype_space($value) && 0 < strlen($value) && strlen($value) < 100;

        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL);

        default:
            return false;
        }
    }

    /**
     * Obtient un tableau des classes CSS associées à chaque champ en fonction des entrées fournies.
     * Si le tableau est vide, renvoie un tableau par défaut.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param array $liste Tableau des données à vérifier.
     * @return array Tableau des classes CSS associées à chaque champ.
     */
    public function getListeClasses(PDO $pdo, array $liste): array
    {
        $liste_classes = $this->verif_inputs($pdo, $liste);

        if (empty($liste_classes)) {
            $liste_classes = [
                "nom" => "",
                "prenom" => "",
                "email" => "",
                "identifiant" => "",
                "motDePasse" => "",
                "confirMotDePasse" => ""
            ];
        }

        return $liste_classes;
    }

    /**
     * Obtient un tableau des valeurs HTML pour chaque champ en fonction des entrées fournies.
     * Si le tableau est vide, renvoie un tableau par défaut.
     *
     * @param array $liste Tableau des données à traiter.
     * @return array Tableau des valeurs HTML pour chaque champ.
     */
    public function getListeValeurs(array $liste): array
    {
        $liste_valeurs = [];
        foreach ($liste as $key => $value) {
            if ($value != null) {
                $liste_valeurs[$key] = htmlspecialchars($value);
            } else {
                $liste_valeurs[$key] = "";
            }
        }

        if ($liste_valeurs == []) {
            $liste_valeurs = [
                "nom" => "",
                "prenom" => "",
                "email" => "",
                "identifiant" => "",
                "motDePasse" => "",
                "confirMotDePasse" => ""
            ];
        }

        return $liste_valeurs;
    }

    /**
     * Crée un objet User à partir des données fournies.
     *
     * @param array $liste Tableau des données de l'utilisateur.
     * @return User|null Retourne l'objet User créé ou null en cas d'échec.
     */
    public function createUser(array $liste): ?User
    {

        foreach ($liste as $key => $value) {
            if ($value != null) {
                $liste[$key] = htmlspecialchars($value);
            }
        }

        try {
            $user = new User($liste["nom"], $liste["prenom"], $liste["email"], $liste["identifiant"], $liste["motDePasse"]);

        } catch (\Exception $e) {
            return null;
        }

        return $user;
    }

    /**
     * Insère un nouvel utilisateur en base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param User $user Objet User à insérer en base de données.
     * @throws \PDOException En cas d'erreur lors de l'insertion en base de données.
     */
    public function insertUser(PDO $pdo, User $user): void
    {

        try {
            $sql = "INSERT INTO users (nomUser, prenomUser, emailUser, loginUser, passwordUser) 
                    VALUES (:nom, :prenom, :email, :identifiant, :motDePasse)";

            $nom = $user->getNom();
            $prenom = $user->getPrenom();
            $email = $user->getEmail();
            $identifiant = $user->getIdentifiant();
            $motDePasse = $user->getPassword();

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":identifiant", $identifiant);
            $stmt->bindParam(":motDePasse", $motDePasse);
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":prenom", $prenom);
            $stmt->bindParam(":email", $email);

            $stmt->execute();

        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }

    }

}