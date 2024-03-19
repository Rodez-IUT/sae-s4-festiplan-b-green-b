<?php

namespace controllers;

use PDO;
use services\ModifInfoPersoService;
use yasmf\HttpHelper;
use yasmf\View;


/**
 * Contrôleur responsable de la modification des informations personnelles de l'utilisateur.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class ModifInfoPersoController
{
    
    /** @var ModifInfoPersoService $modifInfoService Service pour la modification des informations personnelles. */
    private ModifInfoPersoService $modifInfoService;

    /**
     * Constructeur de la classe.
     *
     * @param ModifInfoPersoService $param Service pour la modification des informations personnelles.
     */
    public function __construct(ModifInfoPersoService $param)
    {
        $this->modifInfoService = $param;
    }

    /**
     * Affiche le formulaire de modification des informations personnelles.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue du formulaire de modification des informations personnelles.
     */
    public function index(PDO $pdo): View
    {
        // Initialise la liste des valeurs.
        $liste_valeurs = array();
        // Récupère l'identifiant de l'utilisateur depuis les paramètres de la requête.
        $idUtilisateur = HttpHelper::getParam("user_id");

        try {
            // Récupère la liste des valeurs à afficher dans le formulaire.
            $liste_valeurs = $this->modifInfoService->getListeValeurs($pdo, $idUtilisateur);
        } catch (\PDOException $e) {
            // En cas d'erreur PDO, redirige vers la page d'erreur avec un message approprié.
            $message_erreur = "Erreur lors de la récupération des données";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        // Initialise la vue avec la liste des valeurs et d'autres variables nécessaires.
        $view = new View("view/compte_utilisateur/modifInformation");
        $view->setVar("liste_valeurs", $liste_valeurs);
        $view->setVar("titre", "Modification données personnelles");
        $view->setVar("controller", "ModifInfoPerso");
        $view->setVar("open", "");
        return $view;
    }

    /**
     * Effectue la modification des informations personnelles de l'utilisateur.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue de la page d'informations du compte ou de la page de modification en cas d'erreur.
     */
    public function changeAccount(PDO $pdo): View
    {
        // Récupère l'identifiant de l'utilisateur depuis les paramètres de la requête.
        $id_user = HttpHelper::getParam("user_id");
        // Vérifie les modifications et récupère la liste des classes pour afficher les erreurs si nécessaire.
        $liste_classes = $this->modifInfoService->verif_changes($pdo, $_POST, $id_user, $this->modifInfoService->getListeValeurs($pdo, $id_user));

        foreach ($liste_classes as $key => $value) {
            // En cas d'erreur, réaffiche le formulaire de modification des informations personnelles.
            if ($value != "ok") {
                $this->index($pdo);
            }
        }

        try {
            // Tente de mettre à jour les informations de l'utilisateur dans la base de données.
            $this->modifInfoService->updateUser($pdo, $_POST, $id_user);
        } catch (\PDOException $e) {
            // En cas d'erreur PDO, gère les erreurs spécifiques (identifiant unique, email unique).
            $view = $this->index($pdo);
            if (str_contains($e->getMessage(), "loginUser")) {
                $view->setVar("identifiant_unique", false);
            }

            if (str_contains($e->getMessage(), "emailUser")) {
                $view->setVar("email_unique", false);
            }

            return $view;
        }

        // Redirige vers la page d'informations du compte après la modification.
        header("Location: ?controller=InformationCompte");
        exit();
    }

    /**
     * Affiche le formulaire de modification des informations personnelles avec le menu déroulant ouvert.
     *
     * @param PDO $pdo Connexion à la base de données.
     * @return View Vue du formulaire de modification des informations personnelles avec le menu déroulant ouvert.
     */
    function showMenu($pdo): View
    {
        // Affiche le formulaire de modification des informations personnelles avec le menu déroulant ouvert.
        $view = $this->index($pdo);
        $view->setVar("open", "open");
        return $view;
    }
}