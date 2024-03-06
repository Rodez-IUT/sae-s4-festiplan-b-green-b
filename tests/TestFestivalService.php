<?php

require_once "../festiplan/autoload.php";

use services\api\FestivalService;
use yasmf\DataSource;

/**
 * Classe TestFestivalService
 *
 * Cette classe est utilisée pour tester la classe FestivalService. Elle étend la classe PHPUnit\Framework\TestCase
 * qui fournit le cadre pour créer des tests unitaires pour le code PHP.
 */
class TestFestivalService extends \PHPUnit\Framework\TestCase
{
    private PDO $pdo;
    private FestivalService $usersService;

    /**
     * Fonction setUp
     *
     * Cette fonction est appelée avant chaque méthode de test. Elle prépare l'environnement pour les tests.
     * Elle initialise un objet PDO et un objet FestivalService.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Donné un PDO pour les tests
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

        // Et un service utilisateur
        // Création d'un nouvel objet FestivalService
        $this->festivalService = new FestivalService();
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
    private function insertUser($nomUser, $prenomUser, $emailUser, $loginUser, $passwordUser): int
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
    private function insertFestival($nomFestival, $descriptionFestival, $idImage, $dateDebutFestival, $dateFinFestival, $idGrij, $idResponsable, $ville, $codePostal): int
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
    private function insertOrganizer($idFestival, $idUser): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO organiser (idFestival, idUser) VALUES (?, ?)");
        $stmt->execute([$idFestival, $idUser]);
    }

    private function insertScene($nomScene, $tailleScene, $spectateurMax, $coordonneesGPS): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO scenes (nomScene, tailleScene, spectateurMax, coordonneesGPS) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nomScene, $tailleScene, $spectateurMax, $coordonneesGPS]);
        return (int)$this->pdo->lastInsertId();
    }

    private function insertSceneFestival($idFestival, $idScene): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO accueillir (idFestival, idScene) VALUES (?, ?)");
        $stmt->execute([$idFestival, $idScene]);
    }

    private function insertShow($titreSpectacle, $descriptionSpectacle, $idImage, $dureeSpectacle, $surfaceScneRequise, $idResponsableSpectacle): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO spectacles (titreSpectacle, descriptionSpectacle, idImage, dureeSpectacle, surfaceSceneRequise, idResponsableSpectacle) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titreSpectacle, $descriptionSpectacle, $idImage, $dureeSpectacle, $surfaceScneRequise, $idResponsableSpectacle]);
        return (int)$this->pdo->lastInsertId();
    }

    private function insertShowFestival($idFestival, $idSpectacle): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO composer (idFestival, idSpectacle) VALUES (?, ?)");
        $stmt->execute([$idFestival, $idSpectacle]);
    }

    public function testGetAllFestivalsCorrectNumber()
    {
        try {
            $this->pdo->beginTransaction();
            // GIVEN: la base de données qui contient $nb festivals
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM festivals");
            $stmt->execute();
            $nb = $stmt->fetchColumn();

            // WHEN: on récupère tous les festivals
            $festivals = $this->festivalService->getAllFestival($this->pdo);

            // THEN: on obtient un tableau de festivals de taille $nb
            $this->assertCount($nb, $festivals);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    public function testGetAllFestivalsOrderedByDate()
    {
        try {
            $this->pdo->beginTransaction();
            // GIVEN: la base de données qui contient $nb festivals
            $stmt = $this->pdo->prepare("SELECT * FROM festivals ORDER BY dateDebutFestival");
            $stmt->execute();
            $festivals = $stmt->fetchAll();

            // WHEN: on récupère tous les festivals
            $festivalsFromService = $this->festivalService->getAllFestival($this->pdo);

            // THEN: on obtient un tableau de festivals trié par date
            $this->assertEquals($festivals, $festivalsFromService);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    public function testGetOrganizerFestival()
    {
        try {
            $this->pdo->beginTransaction();
            // GIVEN: un festival avec 2 organisateurs
            $idOrganizer1 = $this->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', 'password');
            $idOrganizer2 = $this->insertUser('Dae', 'Jane', 'test2@mail.fr', 'janedoe', 'password');

            $idFestival = $this->insertFestival('FestivalTest', 'DescriptionTest', 1, '2021-12-12', '2022-12-12', 1, 1, 'Lille', 59000);

            $this->insertOrganizer($idFestival, $idOrganizer1);
            $this->insertOrganizer($idFestival, $idOrganizer2);

            // WHEN: on récupère les organisateurs du festival
            $organizers = $this->festivalService->getOrganizerFestival($this->pdo, $idFestival);

            // on garde seulement les id des organisateurs
            $organizers = array_map(function ($organizer) {
                return $organizer['idUser'];
            }, $organizers);

            // THEN: on obtient un tableau de 2 organisateurs avec les bons id
            $this->assertCount(2, $organizers);
            $this->assertContains($idOrganizer1, $organizers);
            $this->assertContains($idOrganizer2, $organizers);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    public function testGetScenesFestival()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un festival avec 2 scènes
            $idFestival = $this->insertFestival('FestivalTest', 'DescriptionTest', 1, '2021-12-12', '2022-12-12', 1, 1, 'Lille', 59000);

            $idScene1 = $this->insertScene('Scene1', '3', 100, '50.629250, 3.057256');
            $idScene2 = $this->insertScene('Scene2', '2', 200, '50.629250, 3.057256');

            $this->insertSceneFestival($idFestival, $idScene1);
            $this->insertSceneFestival($idFestival, $idScene2);

            // WHEN: on récupère les scènes du festival
            $scenes = $this->festivalService->getScenesFestival($this->pdo, $idFestival);

            // on garde seulement les id des scènes
            $scenes = array_map(function ($scene) {
                return $scene['idScene'];
            }, $scenes);

            // THEN: on obtient un tableau de 2 scènes avec les bons id
            $this->assertCount(2, $scenes);
            $this->assertContains($idScene1, $scenes);
            $this->assertContains($idScene2, $scenes);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    public function testGetShowsFestival()
    {
        try {
            $this->pdo->beginTransaction();

            // Insérer un festival
            $idFestival = $this->insertFestival('FestivalTest', 'DescriptionTest', 1, '2021-12-12', '2022-12-12', 1, 1, 'Lille', 59000);

            // Insérer des spectacles
            $idShow1 = $this->insertShow('Show1', 'Description1', 1, '200', 1, 1);
            $idShow2 = $this->insertShow('Show2', 'Description2', 2, '300', 2, 2);

            // Associer les spectacles au festival
            $this->insertShowFestival($idFestival, $idShow1);
            $this->insertShowFestival($idFestival, $idShow2);

            // Récupérer les spectacles du festival
            $shows = $this->festivalService->getShowsFestival($this->pdo, $idFestival);

            // Transformer le tableau de spectacles pour ne garder que les id des spectacles
            $shows = array_map(function ($show) {
                return $show['idSpectacle'];
            }, $shows);

            // Vérifier que les bons spectacles sont renvoyés
            $this->assertCount(2, $shows);
            $this->assertContains($idShow1, $shows);
            $this->assertContains($idShow2, $shows);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }
}