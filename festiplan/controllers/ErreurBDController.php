<?php

namespace controllers;

use PDO;
use services\ErreurBDService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur responsable de la gestion des erreurs de base de données.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ErreurBDController
{
    /**
     * Affiche la vue d'erreur de base de données avec le message d'erreur fourni.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue d'erreur de base de données.
     */
    public function index(PDO $pdo): View
    {
        // Récupère le message d'erreur depuis les paramètres de la requête HTTP.
        $message_erreur = HttpHelper::getParam("message_erreur");

        // Initialise la vue avec le message d'erreur.
        $view = new View("view/erreurBD");
        $view->setVar("message_erreur", $message_erreur);

        return $view;
    }
}
