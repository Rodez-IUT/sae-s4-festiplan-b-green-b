<?php

namespace controllers;

use PDO;
use PDOException;
use services\InfoSpectacleService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable de l'affichage des informations d'un spectacle.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class InfoSpectacleController
{
    /** @var InfoSpectacleService $infoSpectacleServices Service pour les informations de spectacle. */
    private InfoSpectacleService $infoSpectacleServices;

    /**
     * Constructeur de la classe.
     *
     * @param InfoSpectacleService $param Service pour les informations de spectacle.
     */
    public function __construct(InfoSpectacleService $param)
    {
        $this->infoSpectacleServices = $param;
    }

    /**
     * Affiche les informations d'un spectacle.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue des informations du spectacle.
     */
    public function index(PDO $pdo): View
    {
        $id_spectacle = HttpHelper::getParam("idSpectacle");
        try {
            // Récupère les informations du spectacle.
            $searchStmt = $this->infoSpectacleServices->getSpectaclePresentation($pdo, $id_spectacle);

        } catch (PDOException) {
            // En cas d'erreur PDO, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération des données";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }
        // Initialise la vue avec les informations du spectacle.
        $view = new View("view/spectacle/infoSpectacle");
        $view->setVar('searchStmt', $searchStmt);
        $view->setVar('controller', 'Home');
        $view->setVar('titre', "Spectacle");
        $view->setVar('open', '');
        return $view;
    }

    /**
     * Affiche les informations du spectacle avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue des informations du spectacle avec le menu déroulant ouvert.
     */
    public function showMenu(PDO $pdo): View
    {
        // Affiche les informations du spectacle avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar('open', 'open');
        return $view;
    }
}
