<?php

namespace services;

use PDO;

/**
 * La classe CreationCompteServices fournit des méthodes
 * pour la création de comptes utilisateurs.
 *
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class CreationGriJServices {

    const LISTE_CHAMPS = array(
        "heureDebut",
        "heureFin",
        "duree"
    );

    /**
     * Vérifie les entrées fournies et retourne un tableau des classes CSS associées à chaque champ.
     *
     * @param array $liste Tableau des données à vérifier.
     * @return array Tableau associatif des classes CSS pour chaque champ.
     */
    private function verif_inputs(array $liste): array {
        $liste_classes = [];
        foreach ($this::LISTE_CHAMPS as $key) {
            if (isset($liste[$key])) {
                $value = $liste[$key];
            } else {
                $value = null;
            }

            if ($value != null) {
                // on vérifie que la durée ne soit pas négative
                if($key == "duree") {
                    $liste_classes[$key] = $this->verif_duree($value) ? "ok" : "invalide";
                } else {
                    $liste_classes[$key] = "ok";
                }
                
            } else {
                $liste_classes[$key] = "";
            }
        }
        return $liste_classes;
        
    }

    /**
     * Vérifie la valeur de la durée pour s'assurer qu'elle n'est pas négative.
     *
     * @param mixed $value Valeur de la durée à vérifier.
     * @return bool Retourne true si la durée est valide (non négative), sinon false.
     */
    private function verif_duree($value) : bool {
        if($value < 0) {
            return false;
        }
        return true;
    }

    /**
     * Obtient un tableau des classes CSS associées à chaque champ en fonction des entrées fournies.
     *
     * @param array $liste Tableau des données à vérifier.
     * @return array Tableau des classes CSS associées à chaque champ.
     */
    public function getListeClasses(array $liste): array
    {
        $liste_classes = $this->verif_inputs($liste);

        if ($liste_classes == null) {
            foreach (self::LISTE_CHAMPS as $key) {
                $liste_classes[$key] = "";
            }
        }
        return $liste_classes;
    }

    /**
     * Obtient un tableau des valeurs pour chaque champ en fonction des entrées fournies.
     *
     * @param array $liste Tableau des données à traiter.
     * @return array Tableau des valeurs pour chaque champ.
     */
    public function getListeValeurs(array $liste): array  {
        $liste_valeurs = [];
        foreach ($this::LISTE_CHAMPS as $key) {
            if (isset($liste[$key])) {
                $value = $liste[$key];
                $liste_valeurs[$key] = $value;
            } else {
                $value = null;
                $liste_valeurs[$key] = "";
            }
        }

        //si liste vide alors on met des valeurs par défauts
        if ($liste_valeurs == null) {
            foreach (self::LISTE_CHAMPS as $key) {
                $liste_valeurs[$key] = "";
            }
        }
        return $liste_valeurs;
    }

    /**
     * Insère une nouvelle grille journalière en base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     * @param array $liste Tableau des données de la grille journalière à insérer.
     * @throws \PDOException En cas d'erreur lors de l'insertion en base de données.
     */
    public function insertGriJ(PDO $pdo, array $liste) {
        try{
            $requete = "INSERT INTO grilleJournaliere(heureDebut, heureFin,dureeMinimaleEntreDeuxSpectacles)
                        VALUES (:heureDeb, :heureFin, :dureePause)";
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':heureDeb', $liste["heureDebut"]);
            $stmt->bindParam(':heureFin', $liste["heureFin"]);
            $stmt->bindParam(':dureePause', $liste["duree"]);

            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
        
    }
}