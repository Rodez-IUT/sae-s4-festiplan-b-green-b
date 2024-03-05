<?php

require_once "festiplan/autoload.php";

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
}