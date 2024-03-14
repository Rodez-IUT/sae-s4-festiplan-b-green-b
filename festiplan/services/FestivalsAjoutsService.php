<?php

namespace services;

use PDO;

/**
 * Service pour la gestion des ajouts liés aux festivals (spectacles, membres, scènes).
 *
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class FestivalsAjoutsService
{

    /**
     * Récupère les scènes avec leur taille pour un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $idFestival Identifiant du festival.
     * @return array Tableau des scènes avec leur taille pour le festival.
     */
    private function getScenesTaille(PDO $pdo, int $idFestival): array
    {
        $requete = "SELECT idScene, tailleScene from scenes WHERE idScene IN (
                        SELECT idScene FROM accueillir WHERE idFestival = :idFestival
                    )";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Récupère les scènes possibles pour un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $idFestival Identifiant du festival.
     * @return array Tableau des scènes possibles pour le festival.
     */
    public function getScenesPossibles(PDO $pdo, int $idFestival): array
    {
        $requete = "SELECT idScene, nomScene from scenes WHERE idScene IN (
                        SELECT idScene FROM accueillir WHERE idFestival = :idFestival
                    )";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Récupère toutes les scènes enregistrées.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $idFestival Identifiant du festival.
     * @return array Tableau de toutes les scènes enregistrées.
     */
    public function getScenes(PDO $pdo, int $idFestival): array
    {
        $requete = "SELECT idScene, nomScene from scenes";

        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Récupère les spectacles possibles pour un festival en fonction des scènes disponibles.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $idFestival Identifiant du festival.
     * @return array Tableau des spectacles possibles pour le festival.
     */
    public function getSpectaclesPossibles(PDO $pdo, int $idFestival): array
    {
        $idFestival = htmlspecialchars($idFestival);

        $scenes = $this->getScenesTaille($pdo, $idFestival);

        $tailles = array();

        foreach ($scenes as $scene) {
            $tailles[] = $scene["tailleScene"];
        }

        $tailles = array_unique($tailles);

        if (empty($tailles)) {
            return array();
        }
        $taille_max = max($tailles);

        // on ajoute les tailles inférieures au max, car elles sont aussi possibles
        for ($i = $taille_max; $i >= 0; $i--) {
            if (!in_array($i, $tailles)) {
                $tailles[] = $i;
            }
        }

        // on récupère tous les spectacles qui sont compatibles avec les scenes du festival
        // et qui ne sont pas déjà programmés dans le festival

        $requete = "SELECT idSpectacle, titreSpectacle FROM spectacles WHERE surfaceSceneRequise
                                                             IN (" . implode(",", $tailles) . ")
                                                             AND idSpectacle IN (
                                                                SELECT idSpectacle FROM composer WHERE idFestival = :idFestival
                                                             )";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les spectacles enregistrés.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $idFestival Identifiant du festival.
     * @return array Tableau de tous les spectacles enregistrés.
     */
    public function getSpectacles(PDO $pdo, int $idFestival): array
    {
        $idFestival = htmlspecialchars($idFestival);

        $scenes = $this->getScenesTaille($pdo, $idFestival);

        $tailles = array();

        foreach ($scenes as $scene) {
            $tailles[] = $scene["tailleScene"];
        }

        $tailles = array_unique($tailles);

        if (empty($tailles)) {
            return array();
        }
        $taille_max = max($tailles);

        // on ajoute les tailles inférieures au max, car elles sont aussi possibles
        for ($i = $taille_max; $i > 0; $i--) {
            if (!in_array($i, $tailles)) {
                $tailles[] = $i;
            }
        }

        $requete = "SELECT idSpectacle, titreSpectacle FROM spectacles  WHERE surfaceSceneRequise
                                                             IN (" . implode(",", $tailles) . ")";

        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les membres possibles pour un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param int $idFestival Identifiant du festival.
     * @return array Tableau des membres possibles pour le festival.
     */
    public function getMembresPossibles(PDO $pdo, int $idFestival): array
    {
        $idFestival = htmlspecialchars($idFestival);

        $requete = "SELECT idUser, nomUser, prenomUser FROM users WHERE idUser IN (
                        SELECT idUser FROM organiser WHERE idFestival = :idFestival
                    )";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les membres enregistrés.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Tableau de tous les membres enregistrés.
     */
    public function getMembres(PDO $pdo): array
    {
        $requete = "SELECT idUser, nomUser, prenomUser FROM users";

        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute un spectacle à un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string|null $idFestival Identifiant du festival.
     * @param string|null $idSpectacle Identifiant du spectacle.
     * @return void
     */
    public function ajouterSpectacle(PDO $pdo, ?string $idFestival, ?string $idSpectacle): void
    {
        $idFestival = htmlspecialchars($idFestival);
        $idSpectacle = htmlspecialchars($idSpectacle);

        $requete = "INSERT INTO composer (idFestival, idSpectacle) VALUES (:idFestival, :idSpectacle)";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->bindValue(":idSpectacle", $idSpectacle);
        $stmt->execute();
    }

    /**
     * Ajoute un membre à un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string $idFestival Identifiant du festival.
     * @param string $idUser Identifiant du membre.
     * @return void
     */
    public function ajouterMembre(PDO $pdo, string $idFestival, string $idUser): void
    {
        $idFestival = htmlspecialchars($idFestival);
        $idUser = htmlspecialchars($idUser);

        $requete = "INSERT INTO organiser (idFestival, idUser) VALUES (:idFestival, :idUser)";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->execute();
    }

    /**
     * Ajoute une scène à un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string|null $idFestival Identifiant du festival.
     * @param string|null $idScene Identifiant de la scène.
     * @return void
     */
    public function ajouterScene(PDO $pdo, ?string $idFestival, ?string $idScene): void
    {
        $idFestival = htmlspecialchars($idFestival);
        $idScene = htmlspecialchars($idScene);

        $requete = "INSERT INTO accueillir (idFestival, idScene) VALUES (:idFestival, :idScene)";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->bindValue(":idScene", $idScene);
        $stmt->execute();
    }

    /**
     * Retire un spectacle d'un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string|null $idFestival Identifiant du festival.
     * @param string|null $idSpectacle Identifiant du spectacle.
     * @return void
     */
    public function retirerSpectacle(PDO $pdo, ?string $idFestival, ?string $idSpectacle): void
    {
        $idFestival = htmlspecialchars($idFestival);
        $idSpectacle = htmlspecialchars($idSpectacle);

        $requete = "DELETE FROM composer WHERE idFestival = :idFestival AND idSpectacle = :idSpectacle";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->bindValue(":idSpectacle", $idSpectacle);
        $stmt->execute();
    }

    /**
     * Retire un membre d'un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string|null $idFestival Identifiant du festival.
     * @param string|null $idUser Identifiant du membre.
     * @return void
     */
    public function retirerMembre(PDO $pdo, ?string $idFestival, ?string $idUser): void
    {
        $idFestival = htmlspecialchars($idFestival);
        $idUser = htmlspecialchars($idUser);

        $requete = "DELETE FROM organiser WHERE idFestival = :idFestival AND idUser = :idUser";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->execute();
    }

    /**
     * Retire une scène d'un festival.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param string|null $idFestival Identifiant du festival.
     * @param string|null $idScene Identifiant de la scène.
     * @return void
     */
    public function retirerScene(PDO $pdo, ?string $idFestival, ?string $idScene): void
    {
        $idFestival = htmlspecialchars($idFestival);
        $idScene = htmlspecialchars($idScene);

        $requete = "DELETE FROM accueillir WHERE idFestival = :idFestival AND idScene = :idScene";

        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(":idFestival", $idFestival);
        $stmt->bindValue(":idScene", $idScene);
        $stmt->execute();
    }

}