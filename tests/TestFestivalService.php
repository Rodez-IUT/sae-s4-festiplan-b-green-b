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

    private OutilsTests $outilsTests;

    /**
     * Fonction setUp
     *
     * Cette fonction est appelée avant chaque méthode de test. Elle prépare l'environnement pour les tests.
     * Elle initialise un objet PDO et un objet FestivalService.
     */
    public function setUp(): void
    {
        parent::setUp();
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

        // Création d'un nouvel objet OutilsTests
        $this->outilsTests = new OutilsTests();
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
            $this->fail($e->getMessage());
        }
    }

    public function testGetDetailsFestival()
    {
        try {
            $this->pdo->beginTransaction();
            // GIVEN: un festival
            $idFestival = $this->outilsTests->insertFestival('FestivalTest', 'DescriptionTest', 1, '2021-12-12', '2022-12-12', 1, 1, 'Lille', 59000);

            // WHEN: on récupère les détails du festival
            $stmt = $this->pdo->prepare("SELECT * FROM festivals WHERE idFestival = :idFestival");
            $stmt->bindParam(':idFestival', $idFestival);
            $stmt->execute();
            $festival = $stmt->fetch();

            // THEN: on obtient un tableau contenant les détails du festival
            $festivalFromService = $this->festivalService->getDetailsFestival($this->pdo, $idFestival);
            $this->assertEquals($festival, $festivalFromService[0]);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
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
            $this->fail($e->getMessage());
        }
    }

    public function testGetOrganizerFestival()
    {
        try {
            $this->pdo->beginTransaction();
            // GIVEN: un festival avec 2 organisateurs
            $idOrganizer1 = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', 'password');
            $idOrganizer2 = $this->outilsTests->insertUser('Dae', 'Jane', 'test2@mail.fr', 'janedoe', 'password');

            $idFestival = $this->outilsTests->insertFestival('FestivalTest', 'DescriptionTest', 1, '2021-12-12', '2022-12-12', 1, 1, 'Lille', 59000);

            $this->outilsTests->insertOrganizer($idFestival, $idOrganizer1);
            $this->outilsTests->insertOrganizer($idFestival, $idOrganizer2);

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
            $idFestival = $this->outilsTests->insertFestival('FestivalTest', 'DescriptionTest', 1, '2021-12-12', '2022-12-12', 1, 1, 'Lille', 59000);

            $idScene1 = $this->outilsTests->insertScene('Scene1', '3', 100, '50.629250, 3.057256');
            $idScene2 = $this->outilsTests->insertScene('Scene2', '2', 200, '50.629250, 3.057256');

            $this->outilsTests->insertSceneFestival($idFestival, $idScene1);
            $this->outilsTests->insertSceneFestival($idFestival, $idScene2);

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
            $idFestival = $this->outilsTests->insertFestival('FestivalTest', 'DescriptionTest', 1, '2021-12-12', '2022-12-12', 1, 1, 'Lille', 59000);

            // Insérer des spectacles
            $idShow1 = $this->outilsTests->insertShow('Show1', 'Description1', 1, '200', 1, 1);
            $idShow2 = $this->outilsTests->insertShow('Show2', 'Description2', 2, '300', 2, 2);

            // Associer les spectacles au festival
            $this->outilsTests->insertShowFestival($idFestival, $idShow1);
            $this->outilsTests->insertShowFestival($idFestival, $idShow2);

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

    public function testGetOrganizerFestivalWithIncorrectId()
    {
        // GIVEN: un id de festival incorrect
        $incorrectId = -1;

        // WHEN: on essaie de récupérer les organisateurs du festival avec l'id incorrect
        $organizers = $this->festivalService->getOrganizerFestival($this->pdo, $incorrectId);

        // THEN: on devrait obtenir un tableau vide
        $this->assertEmpty($organizers);
    }

    public function testGetdetailsFestivalWithIncorrectId()
    {
        // GIVEN: un id de festival incorrect
        $incorrectId = -1;

        // WHEN: on essaie de récupérer les détails du festival avec l'id incorrect
        $details = $this->festivalService->getDetailsFestival($this->pdo, $incorrectId);

        // THEN: on devrait obtenir un tableau vide
        $this->assertEmpty($details);
    }

    public function testGetScenesFestivalWithIncorrectId()
    {
        // GIVEN: un id de festival incorrect
        $incorrectId = -1;

        // WHEN: on essaie de récupérer les scènes du festival avec l'id incorrect
        $scenes = $this->festivalService->getScenesFestival($this->pdo, $incorrectId);

        // THEN: we should get an empty array
        $this->assertEmpty($scenes);
    }

    public function testGetDetailsFestivalWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie de récupérer les détails du festival
        // THEN: une exception PDO est levée
        $this->expectException(PDOException::class);
        $this->festivalService->getDetailsFestival($this->pdo, 1);
    }

    public function testGetShowsFestivalWithIncorrectId()
    {
        // GIVEN: un id de festival incorrect
        $incorrectId = -1;

        // WHEN: on essaie de récupérer les spectacles du festival avec l'id incorrect
        $shows = $this->festivalService->getShowsFestival($this->pdo, $incorrectId);

        // THEN: on devrait obtenir un tableau vide
        $this->assertEmpty($shows);
    }

    public function testGetOrganizerFestivalWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie de récupérer les organisateurs du festival
        // THEN: une exception PDO est levée
        $this->expectException(PDOException::class);
        $this->festivalService->getOrganizerFestival($this->pdo, 1);
    }

    public function testGetAllFestivalsWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie de récupérer tous les festivals
        // THEN: une exception PDO est levée
        $this->expectException(PDOException::class);
        $this->festivalService->getAllFestival($this->pdo);
    }

    public function testGetScenesFestivalWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie de récupérer les scènes du festival
        // THEN: une exception PDO est levée
        $this->expectException(PDOException::class);
        $this->festivalService->getScenesFestival($this->pdo, 1);
    }

    public function testGetShowsFestivalWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie de récupérer les spectacles du festival
        // THEN: une exception PDO est levée
        $this->expectException(PDOException::class);
        $this->festivalService->getShowsFestival($this->pdo, 1);
    }

}