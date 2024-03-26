<?php

require_once "../festiplan/autoload.php";

use services\api\LoginService;
use yasmf\DataSource;

class TestLoginService extends \PHPUnit\Framework\TestCase
{
    private PDO $pdo;
    private LoginService $loginService;
    private OutilsTests $outilsTests;

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

        $this->loginService = new LoginService();

        // Création d'un nouvel objet OutilsTests
        $this->outilsTests = new OutilsTests();
    }

    public function testLoginWithCorrectCredentials()
    {
        try {
            $this->pdo->beginTransaction();

            $passwordHash = password_hash("password", PASSWORD_BCRYPT);

            // GIVEN: un utilisateur
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', $passwordHash);

            // WHEN: on appelle la méthode login avec les bons identifiants
            $user = $this->loginService->login($this->pdo, 'johndoe', 'password');

            // THEN: on obtient les informations de l'utilisateur et sa clé API
            if ($user === false) {
                $this->pdo->rollback();
                $this->fail("L'utilisateur n'a pas été trouvé");
            }
            $this->assertEquals($idUser, $user['idUser']);
            $this->assertEquals('Doe', $user['nomUser']);
            $this->assertEquals('John', $user['prenomUser']);
            $this->assertEquals('johndoe', $user['loginUser']);
            $this->assertEquals($passwordHash, $user['passwordUser']);
            $this->assertArrayHasKey('APIKey', $user);

            $this->pdo->rollBack();

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    public function testLoginWithIncorrectCredentials()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: la base de données
            // WHEN: on appelle la méthode login avec des identifiants incorrects
            $user = $this->loginService->login($this->pdo, 'johndoe', 'wrongpassword');

            // THEN: on obtient false
            $this->assertFalse($user);

            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    public function testLoginWithDatabaseFailure()
    {
        // GIVEN: un objet PDO qui lance une exception lorsqu'on prépare une requête
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->will($this->throwException(new PDOException()));

        // WHEN: on essaie de récupérer les spectacles du festival
        // THEN: on obtient une exception PDO
        $this->expectException(PDOException::class);
        $this->loginService->login($this->pdo, 'johndoe', 'passwordInconnu');
    }

    public function testAPIKeyGeneration()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un utilisateur sans clé API
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', password_hash("password", PASSWORD_BCRYPT));

            // WHEN: on appelle la méthode get_API_key
            $key = $this->loginService->get_API_key($this->pdo, $idUser);

            // THEN: on obtient une clé API
            $this->assertNotEmpty($key);

            $this->pdo->rollBack();

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }

    public function testExistingAPIKey()
    {
        try {
            $this->pdo->beginTransaction();

            // GIVEN: un utilisateur avec une clé API existante
            $idUser = $this->outilsTests->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', password_hash("password", PASSWORD_BCRYPT));
            $existingKey = $this->loginService->get_API_key($this->pdo, $idUser);

            // WHEN: on appelle la méthode get_API_key
            $key = $this->loginService->get_API_key($this->pdo, $idUser);

            // THEN: on obtient la même clé API
            $this->assertEquals($existingKey, $key);

            $this->pdo->rollBack();

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->fail($e->getMessage());
        }
    }
}