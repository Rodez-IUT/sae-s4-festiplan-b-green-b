<?php

namespace services;

use other\classes\Spectacle;
use PDO;
use function utils\add_image_to_db;
use function utils\creer_Spectacle;
use function utils\get_image_size;
use function utils\insertion_Spectacle;
use function utils\is_image_valid;

include "utils/fonctions.php";

/**
 * La classe CreationSpectacleServices fournit des méthodes
 * pour la gestion des spectacles.
 *
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class CreationSpectacleServices
{

    const LISTE_CHAMPS = array(
        "titre",
        "tailleScene",
        "description",
        "image",
        "idResponsable",
        "duree",
        "categories",
        "intervenantScene",
        "intervenantHors",
    );

    public function getSpectacle(PDO $pdo, string $id): array
    {
        $requetes = array(
            "SELECT titreSpectacle as titre,surfaceSceneRequise as tailleScene, descriptionSpectacle as description, idImage as image, 
                       idResponsableSpectacle as responsable, dureeSpectacle as duree FROM spectacles WHERE idSpectacle = :id",
            "SELECT nomImage as image FROM images WHERE idImage = :id",
            "SELECT intervenir.idIntervenant as intervenantScene FROM intervenir INNER JOIN intervenants ON intervenants.idIntervenant = intervenir.idIntervenant
                    WHERE idSpectacle = :id AND intervenants.estSurScene = 1",
            "SELECT intervenir.idIntervenant as intervenantHors FROM intervenir INNER JOIN intervenants ON intervenants.idIntervenant = intervenir.idIntervenant
                    WHERE idSpectacle = :id AND intervenants.estSurScene = 0",
            "SELECT idCategorie as categories FROM categoriespectacle WHERE idSpectacle = :id",
        );


        $listeStatement = array();

        foreach ($requetes as $requete) {
            $stmt = $pdo->prepare($requete);
            $stmt->execute(array(":id" => $id));
            $listeStatement[] = $stmt;
        }

        while ($spectacle = $listeStatement[0]->fetch()) {
            $liste_valeurs = array(
                "titre" => $spectacle["titre"],
                "tailleScene" => $spectacle["tailleScene"],
                "description" => $spectacle["description"],
                "image" => $spectacle["image"],
                "duree" => $spectacle["duree"],
                "responsable" => $spectacle["responsable"],
            );
        }

        $categories = array();
        while ($categorie = $listeStatement[4]->fetch()) {
            $categories[] = $categorie["categories"];
        }

        $intervenantsScene = array();
        while ($intervenantScene = $listeStatement[2]->fetch()) {
            $intervenantScene[] = $intervenantsScene["intervenantScene"];
        }

        $intervenantsHors = array();
        while ($intervenantHors = $listeStatement[3]->fetch()) {
            $intervenantsHors[] = $intervenantHors["intervenantHors"];
        }


        $liste_valeurs["intervenantScene"] = $intervenantsScene;
        $liste_valeurs["intervenantHors"] = $intervenantsHors;
        $liste_valeurs["categories"] = $categories;

        return $liste_valeurs;
    }

    /**
     * Vérifie les entrées fournies et retourne un tableau des classes CSS associées à chaque champ.
     *
     * @param array $liste Tableau des données à vérifier.
     * @return array Tableau associatif des classes CSS pour chaque champ.
     */
    private function verif_inputs(array $liste): array
    {
        $liste_classes = [];
        foreach ($this::LISTE_CHAMPS as $key) {
            if (!isset($liste[$key])) {
                if (in_array($key, ["categories"])) {
                    $liste_classes[$key] = "invalide";
                } else if (in_array($key, ["intervenantScene", "intervenantHors"])) {
                    $liste_classes[$key] = "ok";
                } else {
                    $liste_classes[$key] = "";
                }
            } else {
                $liste_classes[$key] = $this->verif_input($key, $liste[$key]) ? "ok" : "invalide";
            }
        }
        return $liste_classes;

    }

    /**
     * Vérifie la validité d'une entrée en fonction de la clé et de sa valeur.
     *
     * @param string $key Clé de l'entrée.
     * @param mixed $value Valeur de l'entrée.
     * @return bool Retourne true si la valeur est valide pour la clé donnée, sinon false.
     */
    private function verif_input(string $key, mixed $value): bool
    {
        switch ($key) {
            case 'nom':
            case 'prenom':
            case 'identifiant':
            case 'motDePasse':
                return !ctype_space($value) && 0 < strlen($value) && strlen($value) < 100;

            case 'titre':
                return !ctype_space($value) && 0 < strlen($value) && strlen($value) < 20;

            case 'description':
                return !ctype_space($value) && 0 < strlen($value) && strlen($value) < 1000;

            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL);

            case 'tailleScene':
                return in_array(strtolower($value), ["1", "2", "3"]);

            case 'image':
                $taille_nom_image = strlen($value);
                return substr($value, $taille_nom_image - 4, $taille_nom_image) == ".jpg"
                    || substr($value, $taille_nom_image - 4, $taille_nom_image) == ".png"
                    || substr($value, $taille_nom_image - 5, $taille_nom_image) == ".jpeg"
                    || substr($value, $taille_nom_image - 4, $taille_nom_image) == ".gif";

            case'duree':
                return preg_match("/^[1-9][0-9]{0,100}$/", $value);

//            case 'categories':
//            case 'intervenantScene':
//            case 'intervenantHors':
//                $OK = true;
//                foreach ($value as $cat){
//                    if (!strlen($cat)<3 || !strlen($cat)>30){
//                        $OK = false;
//                    }
//                }
//                return $OK && count($value);

            default:
                return true;
        }
    }

    /**
     * Vérifie la validité d'une image.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return string Retourne l'ID de l'image si elle est valide, sinon une chaîne vide.
     */
    private function verif_image(PDO $pdo): string
    {
        $image_id = 1;
        if (isset($_FILES["image"])) {
            $complete_file_path = $_FILES["image"]["tmp_name"];
            $file_name = $_FILES["image"]["name"];

            $size = get_image_size($complete_file_path);

//            var_dump($size);

            $valid_size = is_image_valid($size);

            if ($valid_size) {
                $image_id = add_image_to_db($pdo, $file_name, $complete_file_path);
            }

        }
        return $image_id;
    }

    /**
     * Obtient un tableau des classes CSS associées à chaque champ en fonction des entrées fournies.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param array $liste Tableau des données à vérifier.
     * @return array Tableau des classes CSS associées à chaque champ.
     */
    public function getListeClasses(PDO $pdo, array $liste): array
    {
        $liste_classes = $this->verif_inputs($liste);

        if ($liste_classes == null) {
            foreach (self::LISTE_CHAMPS as $key) {
                $liste_classes[$key] = "";
            }
        }

        $liste_classes["image"] = "ok";

        return $liste_classes;
    }

    /**
     * Obtient un tableau des valeurs pour chaque champ en fonction des entrées fournies.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param array $liste Tableau des données à traiter.
     * @return array Tableau des valeurs pour chaque champ.
     */
    public function getListeValeurs(PDO $pdo, array $liste): array
    {
        $liste_valeurs = [];
        foreach ($this::LISTE_CHAMPS as $key) {

            $value = $liste[$key] ?? null;

            if (isset($value)) {
                if (gettype($value) == "array") {
                    $values = array();
                    foreach ($value as $v) {
                        $values[] = $v;
                    }
                    $liste_valeurs[$key] = $values;
                } else {
                    $liste_valeurs[$key] = $value;
                }
            } else {
                $liste_valeurs[$key] = "";
            }
        }

        if ($liste_valeurs == null) {
            foreach (self::LISTE_CHAMPS as $key) {
                $liste_valeurs[$key] = "";
            }
        }

        $liste_valeurs["image"] = $this->verif_image($pdo);

        return $liste_valeurs;
    }

    /**
     * Obtient la liste des catégories de spectacle depuis la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Liste des catégories de spectacle.
     */
    public function getCategoriesSpectacle(PDO $pdo): array
    {
        $requete = "SELECT * FROM categories";
        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        $categories = array();
        while ($categorie = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $categorie;
        }

        return $categories;
    }

    /**
     * Obtient la liste des intervenants sur scène depuis la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Liste des intervenants sur scène.
     */
    public function getIntervenantScene(PDO $pdo): array
    {
        $requete = "SELECT * FROM intervenants WHERE estSurScene = 1";
        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        $intervenantsScene = array();
        while ($intervenantScene = $stmt->fetch()) {
            $intervenantsScene[] = $intervenantScene;
        }

        return $intervenantsScene;
    }

    /**
     * Obtient la liste des intervenants hors scène depuis la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return array Liste des intervenants hors scène.
     */
    public function getIntervenantHors(PDO $pdo): array
    {
        $requete = "SELECT * FROM intervenants WHERE estSurScene = 0";
        $stmt = $pdo->prepare($requete);
        $stmt->execute();

        $intervenantsHors = array();
        while ($intervenantHors = $stmt->fetch()) {
            $intervenantsHors[] = $intervenantHors;
        }

        return $intervenantsHors;
    }

    /**
     * Crée une instance de Spectacle à partir des valeurs fournies.
     *
     * @param array $liste_valeurs Tableau des valeurs pour le spectacle.
     * @return Spectacle Instance de Spectacle créée à partir des valeurs.
     */
    public function create_Spectacle(array $liste_valeurs): Spectacle
    {
        return creer_Spectacle($liste_valeurs);
    }

    /**
     * Insère un spectacle dans la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param Spectacle $spectacle Instance de Spectacle à insérer.
     * @return void
     */
    public function insert_Spectacle(PDO $pdo, Spectacle $spectacle): void
    {
        insertion_Spectacle($pdo, $spectacle);
    }

    public function update_Spectacle($pdo, string $id, array $nouvelles): void
    {
        $spectacle = $this->create_Spectacle($nouvelles);
        $this->supprimerSpectacle($pdo, $id);

        insertion_Spectacle($pdo, $spectacle);
    }

    public function supprimerSpectacle($pdo, string $idSpectacle): void
    {
        $requete = "DELETE FROM spectacles WHERE idSpectacle = :id";

        $stmt = $pdo->prepare($requete);
        $stmt->bindParam(":id", $idSpectacle);
        $stmt->execute();
    }

    /**
     * Vérifie si toutes les données du spectacle sont valides.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @return bool Retourne true si toutes les données sont valides, sinon false.
     */
    public function getEverythingOK(PDO $pdo): bool
    {
        $everything_ok = true;

        foreach ($this->getListeClasses($pdo, $_POST) as $key => $value) {
            if ($value == "invalide") {
                $everything_ok = false;
            }
        }

        return $everything_ok;
    }


}