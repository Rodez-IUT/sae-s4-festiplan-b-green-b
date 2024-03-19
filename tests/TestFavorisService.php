<?php

require_once "../festiplan/autoload.php";

use services\api\FavorisService;
use yasmf\DataSource;

/**
 * Classe TestFavorisService
 * Cette classe contient des tests pour le service des favoris.
 */
class TestFavorisService extends \PHPUnit\Framework\TestCase
{
    private PDO $pdo;
    private FavorisService $favorisService;
    private OutilsTests $outilsTests;

    /**
     * Configuration initiale avant chaque test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $datasource = new DataSource(
            'localhost',
            '3306',
            'festiplan',
            'root',
            '',
            'utf8mb4');

        $this->pdo = $datasource->getPdo();

        $this->favorisService = new FavorisService();

        $this->outilsTests = new OutilsTests();
    }

    /**
     * Test de la récupération des favoris.
     */
    public function testGetFavoris()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un utilisateur et un festival
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', 'password');
            $idFestival = $this->outilsTests->insertFestival('Festival', 'Description', 1, '2023-07-01', '2023-07-03', 1, $idUser, 'City', 12345);

            // WHEN: l'utilisateur ajoute le festival à ses favoris
            $this->favorisService->addFavori($this->pdo, $idUser, $idFestival);

            // THEN: le festival est dans les favoris de l'utilisateur
            $favoris = $this->favorisService->getFavoris($this->pdo, $idUser);
            $this->assertContains($idFestival, array_column($favoris, 'idFestival'));

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    /**
     * Test de l'ajout d'un favori.
     */
    public function testAddFavori()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un utilisateur et un festival
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', 'password');
            $idFestival = $this->outilsTests->insertFestival('Festival', 'Description', 1, '2023-07-01', '2023-07-03', 1, $idUser, 'City', 12345);

            // WHEN: l'utilisateur ajoute le festival à ses favoris
            $result = $this->favorisService->addFavori($this->pdo, $idUser, $idFestival);

            // THEN: le festival est dans les favoris de l'utilisateur
            $this->assertTrue($result);
            $favoris = $this->favorisService->getFavoris($this->pdo, $idUser);
            $this->assertContains($idFestival, array_column($favoris, 'idFestival'));

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    /**
     * Test de la suppression d'un favori.
     */
    public function testDeleteFavori()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un utilisateur et un festival
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', 'password');
            $idFestival = $this->outilsTests->insertFestival('Festival', 'Description', 1, '2023-07-01', '2023-07-03', 1, $idUser, 'City', 12345);

            // WHEN: l'utilisateur ajoute le festival à ses favoris puis le supprime
            $this->favorisService->addFavori($this->pdo, $idUser, $idFestival);

            $result = $this->favorisService->deleteFavori($this->pdo, $idUser, $idFestival);

            // THEN: le festival n'est pas dans les favoris de l'utilisateur
            $this->assertTrue($result);
            $favoris = $this->favorisService->getFavoris($this->pdo, $idUser);

            $this->assertNotContains($idFestival, array_column($favoris, 'idFestival'));

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    /**
     * Test de la suppression d'un favori non existant.
     */
    public function testDeleteFavoriWithNonExistentFavori()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un utilisateur et un festival
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', 'password');
            $idFestival = $this->outilsTests->insertFestival('Festival', 'Description', 1, '2023-07-01', '2023-07-03', 1, $idUser, 'City', 12345);

            // WHEN: l'utilisateur essaie de supprimer un festival qui n'est pas dans ses favoris
            $result = $this->favorisService->deleteFavori($this->pdo, $idUser, $idFestival);

            // THEN: le résultat est false
            $this->assertFalse($result);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    /**
     * Test de l'ajout d'un favori avec un festival non existant.
     */
    public function testAddFavoriWithNonExistentFestival()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un utilisateur
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', 'password');
            // WHEN: l'utilisateur essaie d'ajouter un festival non existant à ses favoris
            $result = $this->favorisService->addFavori($this->pdo, $idUser, -1);
            // THEN: une exception est levée
            $this->pdo->rollBack();
            $this->fail("Une exception aurait dû être levée");

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->assertInstanceOf(PDOException::class, $e);
        }
    }

    /**
     * test d'ajout de favoris deja existant
     */
    public function testAddFavoriAlreadyExistent()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un utilisateur et un festival
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', 'password');
            $idFestival = $this->outilsTests->insertFestival('Festival', 'Description', 1, '2023-07-01', '2023-07-03', 1, $idUser, 'City', 12345);

            // WHEN: l'utilisateur ajoute le festival à ses favoris
            $this->favorisService->addFavori($this->pdo, $idUser, $idFestival);
            $result = $this->favorisService->addFavori($this->pdo, $idUser, $idFestival);
            // THEN: le résultat est une string
            $this->assertEquals("Favori déjà existant", $result);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    /**
     * Test de la récupération des favoris avec un utilisateur non existant.
     */
    public function testGetFavorisWithNonExistentUser()
    {
        // GIVEN: la base de données
        // WHEN: on appelle la méthode getFavoris avec un utilisateur inexistant
        $favoris = $this->favorisService->getFavoris($this->pdo, -1);

        // THEN: on obtient un tableau vide
        $this->assertEmpty($favoris);
    }

    /**
     * Test de la récupération des favoris avec une défaillance de la base de données.
     */
    public function testGetFavorisWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie de récupérer les favoris
        // THEN: une exception PDO est attendue
        $this->expectException(PDOException::class);
        $this->favorisService->getFavoris($this->pdo, 1);
    }

    /**
     * Test de l'ajout d'un favori avec une défaillance de la base de données.
     */
    public function testAddFavoriWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie d'ajouter un favori
        // THEN: une exception PDO est attendue
        $this->expectException(PDOException::class);
        $this->favorisService->addFavori($this->pdo, 1, 1);
    }

    /**
     * Test de la suppression d'un favori avec une défaillance de la base de données.
     */
    public function testDeleteFavoriWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie de supprimer un favori
        // THEN: une exception PDO est attendue
        $this->expectException(PDOException::class);
        $this->favorisService->deleteFavori($this->pdo, 1, 1);
    }
}