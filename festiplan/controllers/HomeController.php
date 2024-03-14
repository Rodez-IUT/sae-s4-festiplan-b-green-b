<?php

namespace controllers;

use PDO;
use PDOException;
use yasmf\View;
use services\AccueilService;

/**
 * Contrôleur responsable de la gestion de la page d'accueil.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class HomeController {

    /** @var AccueilService $accueilService Service de la page d'accueil. */
    private AccueilService $accueilService;

    /**
     * Constructeur de la classe.
     *
     * @param AccueilService $accueilService Service de la page d'accueil.
     */
    public function __construct(AccueilService $accueilService)
    {
        $this->accueilService = $accueilService;
    }

    /**
     * Affiche la page d'accueil avec la présentation des festivals.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page d'accueil.
     */
    public function index(PDO $pdo): View
    {
        try {
            // Récupère la présentation des festivals à afficher sur la page d'accueil.
            $searchStmt = $this->accueilService->getFestivalsPresentation($pdo);

        } catch (PDOException) {
            // En cas d'erreur, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération des données";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Initialise la vue avec les données récupérées.
        $view = new View("view/accueil");
        $view->setVar('searchStmt', $searchStmt);
        $view->setVar('controller', 'Home');
        $view->setVar('titre', 'Festiplan');
        $view->setVar('open', '');
        return $view;
    }

    /**
     * Affiche la page d'accueil avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page d'accueil avec le menu déroulant ouvert.
     */
    public function showMenu(PDO $pdo): View
    {
        // Affiche la page d'accueil avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar('open', 'open');
        return $view;
    }
}
