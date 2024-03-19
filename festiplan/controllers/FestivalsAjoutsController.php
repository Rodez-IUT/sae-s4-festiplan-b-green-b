<?php

namespace controllers;

use services\FestivalsAjoutsService;
use yasmf\HttpHelper;
use yasmf\View;

/**
 * Contrôleur gérant les ajouts (spectacles, membres, scènes) pour un festival.
 *
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class FestivalsAjoutsController
{

    private FestivalsAjoutsService $ajoutsService;

    /**
     * @param FestivalsAjoutsService $param
     */
    public function __construct(FestivalsAjoutsService $param)
    {
        $this->ajoutsService = $param;
    }

    /**
     * Méthode pour afficher la page d'ajouts.
     *
     * @param \PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return View La vue correspondante à la page d'ajouts.
     */
    public function index(\PDO $pdo): View
    {
        $idFestival = HttpHelper::getParam("idFestival") ?: 0;

        try {
            if ($idFestival == 0) {
                throw new \Exception("idFestival non renseigné");
            }

            $spectacles_ajout = $this->ajoutsService->getSpectaclesPossibles($pdo, $idFestival);
            $membres_ajout = $this->ajoutsService->getMembresPossibles($pdo, $idFestival);
            $scenes_ajout = $this->ajoutsService->getScenesPossibles($pdo, $idFestival);
            $spectacles_tous = $this->ajoutsService->getSpectacles($pdo, $idFestival);
            $membres_tous = $this->ajoutsService->getMembres($pdo);
            $scenes_tous = $this->ajoutsService->getScenes($pdo, $idFestival);

        } catch (\Exception $e) {
            $message_erreur = "Erreur lors de la recuperation des donnees";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        $view = new View("view/planification/festivalsAjouts");
        $view->setVar("titre", "Ajouts");
        $view->setVar("controller", "Ajouts");
        $view->setVar("open", "");
        $view->setVar("idFestival", $idFestival);

        $view->setVar("spectacles", $spectacles_ajout);
        $view->setVar("membres", $membres_ajout);
        $view->setVar("scenes", $scenes_ajout);
        $view->setVar("spectacles_tous", $spectacles_tous);
        $view->setVar("membres_tous", $membres_tous);
        $view->setVar("scenes_tous", $scenes_tous);

        return $view;
    }

    /**
     * Méthode pour ajouter un spectacle.
     *
     * @param \PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return View La vue correspondante après l'ajout du spectacle.
     */
    public function ajouterSpectacle(\PDO $pdo): View
    {
        $idFestival = HttpHelper::getParam("idFestival");
        $idSpectacle = HttpHelper::getParam("idSpectacle");

        try {
            $this->ajoutsService->ajouterSpectacle($pdo, $idFestival, $idSpectacle);

        } catch (\Exception $e) {
            $message_erreur = "Erreur lors de l'ajout du spectacle";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        header("Location: ?controller=FestivalAjouts&idFestival=$idFestival");
        exit();
    }

    /**
     * Méthode pour ajouter un membre.
     *
     * @param \PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return View La vue correspondante après l'ajout du membre.
     */
    public function ajouterMembre(\PDO $pdo): View
    {
        $idFestival = HttpHelper::getParam("idFestival");
        $idUser = HttpHelper::getParam("idUser");

        try {
            $this->ajoutsService->ajouterMembre($pdo, $idFestival, $idUser);

        } catch (\Exception $e) {
            $message_erreur = "Erreur lors de l'ajout du membre";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        header("Location: ?controller=FestivalAjouts&idFestival=$idFestival");
        exit();
    }

    /**
     * Méthode pour ajouter une scène.
     *
     * @param \PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return View La vue correspondante après l'ajout de la scène.
     */
    public function ajouterScene(\PDO $pdo): View
    {
        $idFestival = HttpHelper::getParam("idFestival");
        $idScene = HttpHelper::getParam("idScene");
        try {
            $this->ajoutsService->ajouterScene($pdo, $idFestival, $idScene);

        } catch (\Exception $e) {
            $message_erreur = "Erreur lors de l'ajout de la scene";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        header("Location: ?controller=FestivalAjouts&idFestival=$idFestival");
        exit();
    }

    /**
     * Méthode pour retirer un spectacle.
     *
     * @param \PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return View La vue correspondante après la suppression du spectacle.
     */
    public function retirerSpectacle(\PDO $pdo): View
    {
        $idFestival = HttpHelper::getParam("idFestival");
        $idSpectacle = HttpHelper::getParam("idSpectacle");

        try {
            $this->ajoutsService->retirerSpectacle($pdo, $idFestival, $idSpectacle);

        } catch (\Exception $e) {
            $message_erreur = "Erreur lors de la suppression du spectacle";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        header("Location: ?controller=FestivalAjouts&idFestival=$idFestival");
        exit();
    }

    /**
     * Méthode pour retirer un membre.
     *
     * @param \PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return View La vue correspondante après la suppression du membre.
     */
    public function retirerMembre(\PDO $pdo): View
    {
        $idFestival = HttpHelper::getParam("idFestival");
        $idUser = HttpHelper::getParam("idUser");
        try {
            $this->ajoutsService->retirerMembre($pdo, $idFestival, $idUser);

        } catch (\Exception $e) {
            $message_erreur = "Erreur lors de la suppression du membre";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        header("Location: ?controller=FestivalAjouts&idFestival=$idFestival");
        exit();
    }

    /**
     * Méthode pour retirer une scène.
     *
     * @param \PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return View La vue correspondante après la suppression de la scène.
     */
    public function retirerScene(\PDO $pdo): View
    {
        $idFestival = HttpHelper::getParam("idFestival");
        $idScene = HttpHelper::getParam("idScene");

        try {
            $this->ajoutsService->retirerScene($pdo, $idFestival, $idScene);

        } catch (\Exception $e) {
            $message_erreur = "Erreur lors de la suppression de la scene";
            header("Location: ?controller=ErreurBD&message_erreur=$message_erreur");
            exit();
        }

        header("Location: ?controller=FestivalAjouts&idFestival=$idFestival");
        exit();
    }

    /**
     * Méthode pour afficher le menu d'ajouts.
     *
     * @param \PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return View La vue correspondante au menu d'ajouts.
     */
    public function showMenu(\PDO $pdo): View
    {
        $view = $this->index($pdo);
        $view->setVar('open', 'open');
        return $view;
    }

}