<?php

namespace services;

/**
 * SpectacleAjoutsService - Service de gestion des intervenants et des spectacles.
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
class SpectacleAjoutsService
{

    /**
     * Récupère la liste de tous les intervenants.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @return array Tableau associatif des intervenants.
     */
    public function getIntervenants(\PDO $pdo)
    {
        $sql = "SELECT * FROM intervenants";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère la liste des intervenants présents dans un spectacle donné.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string $idSpectacle Identifiant du spectacle.
     * @return array Liste des identifiants d'intervenants présents dans le spectacle.
     */
    public function getIntervenantsPresent(\PDO $pdo, $idSpectacle)
    {
        // on selectionne les intervenants qui sont dans le spectacle
        $sql = "select idIntervenant from intervenir where idSpectacle = :idSpectacle";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":idSpectacle", $idSpectacle);
        $stmt->execute();

        $list = array();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            array_push($list, $row["idIntervenant"]);
        }

        return $list;
    }

    /**
     * Ajoute un intervenant à un spectacle.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string|null $idSpectacle Identifiant du spectacle.
     * @param string|null $idIntervenant Identifiant de l'intervenant.
     * @return void
     */
    public function ajouterIntervenant(\PDO $pdo, ?string $idSpectacle, ?string $idIntervenant)
    {
        $sql = "INSERT INTO intervenir (idSpectacle, idIntervenant) VALUES (:idSpectacle, :idIntervenant)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":idSpectacle", $idSpectacle);
        $stmt->bindParam(":idIntervenant", $idIntervenant);
        $stmt->execute();
    }

    /**
     * Retire un intervenant d'un spectacle.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string|null $idSpectacle Identifiant du spectacle.
     * @param string|null $idIntervenant Identifiant de l'intervenant.
     * @return void
     */
    public function retirerIntervenant(\PDO $pdo, ?string $idSpectacle, ?string $idIntervenant)
    {
        $sql = "DELETE FROM intervenir WHERE idSpectacle = :idSpectacle AND idIntervenant = :idIntervenant";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":idSpectacle", $idSpectacle);
        $stmt->bindParam(":idIntervenant", $idIntervenant);
        $stmt->execute();
    }

    /**
     * Effectue une recherche d'intervenants par nom ou prénom.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string $recherche Chaîne de recherche.
     * @return array Résultat de la recherche.
     */
    public function recherche(\PDO $pdo, string $recherche)
    {
        $recherche = "%" . $recherche . "%";
        $sql = "SELECT * FROM intervenants WHERE nomIntervenant LIKE :recherche1 OR prenomIntervenant LIKE :recherche2";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":recherche1", $recherche);
        $stmt->bindParam(":recherche2", $recherche);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouvel intervenant et l'ajoute à un spectacle.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string|null $nom Nom de l'intervenant.
     * @param string|null $prenom Prénom de l'intervenant.
     * @param string|null $mail Adresse email de l'intervenant.
     * @param int $estSurScene Indicateur de présence sur scène.
     * @param string|null $idCreateur Identifiant du créateur.
     * @param string|null $idSpectacle Identifiant du spectacle.
     * @return void
     */
    public function creerIntervenant(\PDO $pdo, ?string $nom, ?string $prenom, ?string $mail, int $estSurScene, ?string $idCreateur, ?string $idSpectacle)
    {
        $nom = htmlspecialchars($nom);
        $prenom = htmlspecialchars($prenom);
        $mail = htmlspecialchars($mail);


        $sql = "INSERT INTO intervenants (nomIntervenant, prenomIntervenant, emailIntervenant, estSurScene, idCreateur) 
                VALUES (:nom, :prenom, :mail, :estSurScene, :idCreateur)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":prenom", $prenom);
        $stmt->bindParam(":mail", $mail);
        $stmt->bindParam(":estSurScene", $estSurScene);
        $stmt->bindParam(":idCreateur", $idCreateur);
        $stmt->execute();

        $request = "INSERT INTO intervenir (idSpectacle, idIntervenant) VALUES (:idSpectcle, :idIntervenant)";
        $stmt = $pdo->prepare($request);
        $lastInsertId = $pdo->lastInsertId();
        $stmt->bindParam(":idSpectcle", $idSpectacle);
        $stmt->bindParam(":idIntervenant", $lastInsertId);
        $stmt->execute();

    }

    /**
     * Charge les données d'un fichier CSV pour créer des intervenants.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param mixed $file_name Nom du fichier CSV.
     * @param mixed $file_path Chemin du fichier CSV.
     * @return void
     */
    public function fromCSVFile(\PDO $pdo, mixed $file_name, mixed $file_path, string $idCreateur, string $idSpectacle)
    {
        $server_path = getenv("DOCUMENT_ROOT") . "/festiplan/stockage/images/" . $file_name;

        move_uploaded_file($file_path, $server_path);

        $file = fopen($server_path, "r");


        $compteur = 0;
        while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {

            if ($compteur == 0) {
                if ($data[0] != "nom" || $data[1] != "prenom" || $data[2] != "mail" || $data[3] != "estSurScene") {
                    throw new \PDOException("Le fichier CSV n'est pas au bon format");
                }
                $compteur++;
            } else {
                $nom = htmlspecialchars($data[0]);
                $prenom = htmlspecialchars($data[1]);
                $mail = htmlspecialchars($data[2]);
                $estSurScene = htmlspecialchars($data[3]);

                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    throw new \PDOException("Le fichier CSV n'est pas au bon format");
                }

                $this->creerIntervenant($pdo, $nom, $prenom, $mail, $estSurScene, $idCreateur, $idSpectacle);
            }

        }

    }

    /**
     * Modifie les informations d'un intervenant existant.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string $idIntervenant Identifiant de l'intervenant à modifier.
     * @param string $nom Nouveau nom de l'intervenant.
     * @param string $prenom Nouveau prénom de l'intervenant.
     * @param string $mail Nouvelle adresse email de l'intervenant.
     * @param int $estSurScene Nouvel indicateur de présence sur scène.
     * @return void
     */
    public function modifierIntervenant(\PDO $pdo, string $idIntervenant, string $nom, string $prenom, string $mail, int $estSurScene)
    {
        $sql = "UPDATE intervenants SET nomIntervenant = :nom, prenomIntervenant = :prenom, emailIntervenant = :mail, estSurScene = :estSurScene WHERE idIntervenant = :idIntervenant";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":prenom", $prenom);
        $stmt->bindParam(":mail", $mail);
        $stmt->bindParam(":estSurScene", $estSurScene);
        $stmt->bindParam(":idIntervenant", $idIntervenant);
        $stmt->execute();

    }

    /**
     * Récupère les informations d'un intervenant.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string $idIntervenant Identifiant de l'intervenant.
     * @return array Informations de l'intervenant.
     */
    public function getIntervenant($pdo, string $idIntervenant)
    {
        $requete = "SELECT * FROM intervenants where idIntervenant = :id";
        $stmt = $pdo->prepare($requete);
        $stmt-> bindParam(":id", $idIntervenant);
        $stmt->execute();

        return $stmt->fetch();

    }

    /**
     * Supprime un intervenant et les liens associés dans la base de données.
     *
     * @param \PDO $pdo Objet PDO représentant la connexion à la base de données.
     * @param string $idIntervenant Identifiant de l'intervenant à supprimer.
     * @return void
     */
    public function supprimerIntervenant(\PDO $pdo, string $idIntervenant)
    {
        $sql = array(
            "DELETE FROM intervenir WHERE idIntervenant = :idIntervenant",
            "DELETE FROM intervenants WHERE idIntervenant = :idIntervenant"
        );
        foreach ($sql as $s) {
            $stmt = $pdo->prepare($s);
            $stmt->bindParam(":idIntervenant", $idIntervenant);
            $stmt->execute();
        }
    }
}