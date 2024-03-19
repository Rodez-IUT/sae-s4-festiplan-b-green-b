<?php

namespace controllers;

use PDO;
use services\CreationCompteServices;
use yasmf\View;

/**
 * Contrôleur responsable de la gestion de la création de comptes utilisateurs.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class CreationCompteController
{

    /** @var CreationCompteServices $creationCompteServices Le service de création de compte. */
    private CreationCompteServices $creationCompteServices;

    /**
     * Constructeur du contrôleur de création de compte.
     *
     * @param CreationCompteServices $creationCompteServices Le service de création de compte.
     */
    public function __construct(CreationCompteServices $creationCompteServices)
    {
        $this->creationCompteServices = $creationCompteServices;
    }

    /**
     * Affiche la vue de création de compte.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de création de compte.
     */
    public function index(PDO $pdo): View
    {
        try {
            // Récupère la liste des classes et des valeurs nécessaires pour le formulaire.
            $liste_classes = $this->creationCompteServices->getListeClasses($pdo, $_POST);
            $liste_valeurs = $this->creationCompteServices->getListeValeurs($_POST);

        } catch (\PDOException $e) {
            // En cas d'erreur PDO, redirige vers une page d'erreur.
            $message_erreur = "Erreur lors de la recuperation des donnees";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        $view = new View("view/compte_utilisateur/creationCompte");
        $view->setVar("liste_classes", $liste_classes);
        $view->setVar("liste_valeurs", $liste_valeurs);
        $view->setVar("oldController", "Home");
        return $view;
    }

    /**
     * Crée un compte utilisateur.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de création de compte ou redirection vers la page d'authentification.
     */
    public function createAccount(PDO $pdo): View
    {
        // Récupère la liste des classes nécessaires pour le formulaire.
        $liste_classes = $this->creationCompteServices->getListeClasses($pdo, $_POST);

        // Vérifie si toutes les valeurs sont "ok" dans la liste des classes.
        foreach ($liste_classes as $key => $value) {
            if ($value != "ok") {
                // Redirige vers la page de création de compte.
                $this->index($pdo);
            }
        }

        // Crée un utilisateur.
        $user = $this->creationCompteServices->createUser($_POST);

        if ($user == null) {
            // Si l'utilisateur n'est pas créé avec succès, retourne à la page de création de compte.
            return $this->index($pdo);
        } else {
            try {
                // Tente d'insérer l'utilisateur dans la base de données.
                $this->creationCompteServices->insertUser($pdo, $user);

            } catch (\PDOException $e) {
                // En cas d'erreur PDO lors de l'insertion, gère les erreurs spécifiques.
                $view = $this->index($pdo);

                // Vérifie si l'erreur est due à un identifiant utilisateur déjà existant.
                if (str_contains($e->getMessage(), "loginUser")) {
                    $view->setVar("identifiant_unique", false);
                }

                // Vérifie si l'erreur est due à une adresse e-mail déjà existante.
                if (str_contains($e->getMessage(), "emailUser")) {
                    $view->setVar("email_unique", false);
                }

                return $view;
            }

            // Redirige vers la page d'authentification après la création réussie.
            header("Location: ?controller=Authentification");
            exit();
        }
    }
}
