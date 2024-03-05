<?php

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
            $host = 'all_users_db',
            $port = 3306,
            $db_name = 'all_users',
            $user = 'all_users',
            $pass = 'all_users',
            $charset = 'utf8mb4'
        );

        // Obtention de l'objet PDO à partir de l'objet DataSource
        $this->pdo = $datasource->getPdo();

        // Et un service utilisateur
        // Création d'un nouvel objet FestivalService
        $this->festivalService = new FestivalService();
    }


}