<?php

namespace controllers;

use PDO;
use PDOException;
use services\PlanificationServices;
use yasmf\View;
use yasmf\HttpHelper;

/**
 * Contrôleur responsable de la planification des spectacles pour un festival.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class PlanificationController
{
    /** @var PlanificationServices $planificationServices Service pour la planification des spectacles. */
    private PlanificationServices $planificationServices;

    /**
     * Constructeur de la classe.
     *
     * @param PlanificationServices $param Service pour la planification des spectacles.
     */
    public function __construct(PlanificationServices $param)
    {
        $this->planificationServices = $param;
    }

    /**
     * Affiche la page de planification des spectacles pour un festival.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page de planification des spectacles.
     */
    public function index(PDO $pdo): View
    {
        // Identifiant du festival (à remplacer par une variable dynamique si nécessaire).
        $idFestival = HttpHelper::getParam("idFestival");
        // Initialisation de la variable pour stocker les résultats de la planification.

        try {
            // Récupération des données de planification pour le festival.
            $searchStmt = $this->planificationServices->getPlanification($pdo, $idFestival);
        } catch (PDOException $e) {
            // En cas d'erreur PDO, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération des spectacles";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Initialisation de la vue avec les résultats de la planification et d'autres variables nécessaires.
        $view = new View("view/planification/planification");
        $view->setVar("searchStmt", $searchStmt);
        $view->setVar("controller", "Planification");
        $view->setVar("titre", "Planification");
        $view->setVar("open", "");

        return $view;
    }

    /**
     * Affiche la page de planification avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page de planification avec le menu déroulant ouvert.
     */
    public function showMenu(PDO $pdo): View
    {
        // Affiche la page de planification avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar('open', 'open');
        return $view;
    }
}