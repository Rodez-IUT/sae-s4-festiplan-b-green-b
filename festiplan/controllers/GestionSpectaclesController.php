<?php

namespace controllers;

use PDO;
use services\GestionSpectaclesService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable de la gestion des spectacles.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class GestionSpectaclesController
{
    /** @var GestionspectaclesService $gestionSpectaclesService Le service de gestion des spectacles. */
    private GestionspectaclesService $gestionSpectaclesService;

    /**
     * Constructeur de la classe.
     *
     * @param GestionSpectaclesService $param
     */
    public function __construct(GestionspectaclesService $param)
    {
        $this->gestionSpectaclesService = $param;
    }

    /**
     * Redirige vers la page de création de spectacle.
     *
     * @param PDO $pdo Connexion à la base de données.
     */
    public function index(PDO $pdo): void
    {
        header("Location: ?controller=CreationSpectacle");
    }

    /**
     * Affiche la vue de confirmation de suppression d'un spectacle.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de confirmation de suppression d'un spectacle.
     */
    public function confirmationSuppression(PDO $pdo): View
    {
        $view = new View("view/confirmation");

        // Récupère l'identifiant du spectacle à supprimer depuis les paramètres de la requête.
        $id = HttpHelper::getParam("idSpectacle");

        // Définit les variables nécessaires à la vue.
        $view->setVar("message", "Etes-vous sûr de vouloir supprimer ce spectacle ?");
        $view->setVar("controllerValider", "GestionSpectacles");
        $view->setVar("actionValider", "suppression");
        $view->setVar("controllerRetour", "ListeSpectacle");
        $view->setVar("actionRetour", "index");
        $view->setVar("id", $id);

        return $view;
    }

    /**
     * Supprime un spectacle de la base de données.
     *
     * @param PDO $pdo Connexion à la base de données.
     */
    public function suppression(PDO $pdo): void
    {
        // Récupère l'identifiant du spectacle à supprimer depuis les paramètres de la requête.
        $id = HttpHelper::getParam("id");

        // Supprime le spectacle de la base de données en utilisant le service approprié.
        $this->gestionSpectaclesService->supprimerSpectacle($pdo, $id);

        // Redirige vers la page de la liste des spectacles.
        header("Location: ?controller=ListeSpectacle");
    }
}
