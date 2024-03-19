<?php

use yasmf\DataSource;

class OutilsTests
{
    private $pdo;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        try {
            // Création d'un nouvel objet DataSource avec les paramètres nécessaires
            $datasource = new DataSource(
                'localhost',
                '3306',
                'festiplan',
                'root',
                '',
                'utf8mb4');

            // Obtention de l'objet PDO à partir de l'objet DataSource
            $this->pdo = $datasource->getPdo();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la connexion à la base de données");
        }
    }

    /**
     * Insère un nouvel utilisateur dans la base de données.
     *
     * @param string $nomUser Le nom de l'utilisateur.
     * @param string $prenomUser Le prénom de l'utilisateur.
     * @param string $emailUser L'adresse e-mail de l'utilisateur.
     * @param string $loginUser Le nom d'utilisateur de l'utilisateur.
     * @param string $passwordUser Le mot de passe de l'utilisateur.
     * @return int L'ID de l'utilisateur nouvellement inséré.
     */
    public function insertUser($nomUser, $prenomUser, $emailUser, $loginUser, $passwordUser): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (nomUser, prenomUser, emailUser, loginUser, passwordUser) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nomUser, $prenomUser, $emailUser, $loginUser, $passwordUser]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Insère un nouveau festival dans la base de données.
     *
     * @param string $nomFestival Le nom du festival.
     * @param string $descriptionFestival La description du festival.
     * @param int $idImage L'ID de l'image du festival.
     * @param string $dateDebutFestival La date de début du festival.
     * @param string $dateFinFestival La date de fin du festival.
     * @param int $idGrij L'ID du Grij du festival.
     * @param int $idResponsable L'ID du responsable du festival.
     * @param string $ville La ville du festival.
     * @param int $codePostal Le code postal du festival.
     * @return int L'ID du festival nouvellement inséré.
     */
    public function insertFestival($nomFestival, $descriptionFestival, $idImage, $dateDebutFestival, $dateFinFestival, $idGrij, $idResponsable, $ville, $codePostal): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO festivals (nomFestival, descriptionFestival, idImage, dateDebutFestival, dateFinFestival, idGrij, idResponsable, ville, codePostal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nomFestival, $descriptionFestival, $idImage, $dateDebutFestival, $dateFinFestival, $idGrij, $idResponsable, $ville, $codePostal]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Insère un nouvel organisateur dans la base de données.
     *
     * @param int $idFestival L'ID du festival.
     * @param int $idUser L'ID de l'utilisateur.
     */
    public function insertOrganizer($idFestival, $idUser): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO organiser (idFestival, idUser) VALUES (?, ?)");
        $stmt->execute([$idFestival, $idUser]);
    }

    public function insertScene($nomScene, $tailleScene, $spectateurMax, $coordonneesGPS): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO scenes (nomScene, tailleScene, spectateurMax, coordonneesGPS) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nomScene, $tailleScene, $spectateurMax, $coordonneesGPS]);
        return (int)$this->pdo->lastInsertId();
    }

    public function insertSceneFestival($idFestival, $idScene): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO accueillir (idFestival, idScene) VALUES (?, ?)");
        $stmt->execute([$idFestival, $idScene]);
    }

    public function insertShow($titreSpectacle, $descriptionSpectacle, $idImage, $dureeSpectacle, $surfaceScneRequise, $idResponsableSpectacle): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO spectacles (titreSpectacle, descriptionSpectacle, idImage, dureeSpectacle, surfaceSceneRequise, idResponsableSpectacle) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titreSpectacle, $descriptionSpectacle, $idImage, $dureeSpectacle, $surfaceScneRequise, $idResponsableSpectacle]);
        return (int)$this->pdo->lastInsertId();
    }

    public function insertShowFestival($idFestival, $idSpectacle): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO composer (idFestival, idSpectacle) VALUES (?, ?)");
        $stmt->execute([$idFestival, $idSpectacle]);
    }

}