<?php

require_once "../festiplan/autoload.php";

use services\api\LoginService;
use yasmf\DataSource;

class TestLoginService extends \PHPUnit\Framework\TestCase
{
    private PDO $pdo;
    private LoginService $loginService;

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

    public function testLoginWithCorrectCredentials()
    {
        try {
            $this->pdo->beginTransaction();

            $passwordHash = password_hash("password", PASSWORD_BCRYPT);

            // GIVEN: un utilisateur
            $idUser = $this->insertUser('Doe', 'John', 'test@mail.fr', 'johndoe', $passwordHash);

            // WHEN: on appelle la méthode login avec les bons identifiants
            $user = $this->loginService->login($this->pdo, 'johndoe', 'password');

            // THEN: on obtient les informations de l'utilisateur
            if ($user === false) {
                $this->pdo->rollback();
                $this->fail("L'utilisateur n'a pas été trouvé");
            }
            $this->assertEquals($idUser, $user['idUser']);
            $this->assertEquals('Doe', $user['nomUser']);
            $this->assertEquals('John', $user['prenomUser']);
            $this->assertEquals('johndoe', $user['loginUser']);
            $this->assertEquals($passwordHash, $user['passwordUser']);

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
}