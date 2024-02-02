<?php

namespace other\classes;

/**
 * Classe contenant un festival
 * 
 * @author clement.denamiel
 * @author rafael.roma
 * @author lohan.vignals
 * @author antonin.veyre
 */
final class Festival
{
    
    /** @var string nom du festival */
    private string $nom;

    /** @var string description du festival */
    private string $description;

    /** @var int id de l'image du festival */
    private int $id_image;

    /** @var string date de début du festival */
    private string $date_debut;

    /** @var string date de fin du festival */
    private string $date_fin;

    /** @var string id du responsable du festival */
    private string $idResponsable;

    /** @var string la ville du festival */
    private string $ville;

    /** @var string le code postal du festival */
    private string $code_postal;

    /** @var array tableau des spectacles du festival (liste des id) */
    private array $spectacles;

    /** @var array tableau des catégories du festival (liste des id) */
    private array $categories;

    /** @var array tableau des scènes du festival (liste des id) */
    private array $scenes;

    /** @var string La date de début du festival pour GriJ. */
    private string $debutGriJ;

    /** @var string La date de fin du festival pour GriJ. */
    private string $finGriJ;

    /** @var string La durée du festival pour GriJ. */
    private string $dureeGriJ;

    /**
     * Constructeur de la classe Festival.
     *
     * @param string $nom               Le nom du festival.
     * @param string $description       La description du festival.
     * @param int    $id_image          L'ID de l'image associée au festival.
     * @param string $date_debut        La date de début du festival au format Y-m-d.
     * @param string $date_fin          La date de fin du festival au format Y-m-d.
     * @param string $idResponsable     L'ID du responsable du festival.
     * @param string $ville             La ville où se déroule le festival.
     * @param string $code_postal       Le code postal du lieu du festival.
     * @param array  $categories        Tableau des ID des catégories associées au festival.
     * @param array  $scenes            Tableau des ID des scènes du festival.
     */
    public function __construct(
        string $nom,
        string $description,
        int $id_image,
        string $date_debut,
        string $date_fin,
        string $idResponsable,
        string $ville,
        string $code_postal,
        array $categories,
        array $scenes,
        string $debutGriJ,
        string $finGriJ,
        string $dureeGriJ
        ) {

        $this->nom = $nom;
        $this->description = $description;
        $this->id_image = $id_image;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->idResponsable = $idResponsable;
        $this->ville = $ville;
        $this->code_postal = $code_postal;
        $this->categories = $categories;
        $this->scenes = $scenes;
        $this->debutGriJ = $debutGriJ;
        $this->finGriJ = $finGriJ;
        $this->dureeGriJ = $dureeGriJ;
    }

    /**
     * Obtient le nom du festival.
     *
     * @return string Le nom du festival.
     */
    public function getNom(): string {
        return $this->nom;
    }

    /**
     * Obtient la description du festival.
     *
     * @return string La description du festival.
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Obtient l'ID de l'image associée au festival.
     *
     * @return int L'ID de l'image du festival.
     */
    public function getIdImage(): int {
        return $this->id_image;
    }

    /**
     * Obtient la date de début du festival au format Y-m-d.
     *
     * @return string La date de début du festival.
     */
    public function getDateDebut(): string {
        return $this->date_debut;
    }

    /**
     * Obtient la date de fin du festival au format Y-m-d.
     *
     * @return string La date de fin du festival.
     */
    public function getDateFin(): string {
        return $this->date_fin;
    }

    /**
     * Obtient l'ID du responsable du festival.
     *
     * @return string L'ID du responsable du festival.
     */
    public function getIdResponsable(): string {
        return $this->idResponsable;
    }

    /**
     * Obtient la ville où se déroule le festival.
     *
     * @return string La ville du festival.
     */
    public function getVille(): string {
        return $this->ville;
    }

    /**
     * Obtient le code postal du lieu du festival.
     *
     * @return string Le code postal du lieu du festival.
     */
    public function getCodePostal(): string {
        return $this->code_postal;
    }

    /**
     * Obtient le tableau des spectacles du festival (liste des ID).
     *
     * @return array Tableau des ID des spectacles du festival.
     */
    public function getSpectacles(): array {
        return $this->spectacles;
    }

    /**
     * Obtient le tableau des catégories du festival (liste des ID).
     *
     * @return array Tableau des ID des catégories du festival.
     */
    public function getCategories(): array {
        return $this->categories;
    }

    /**
     * Obtient le tableau des scènes du festival (liste des ID).
     *
     * @return array Tableau des ID des scènes du festival.
     */
    public function getScenes(): array {
        return $this->scenes;
    }

    /**
     * @return string
     */
    public function getDebutGriJ(): string
    {
        return $this->debutGriJ;
    }

    /**
     * @return string
     */
    public function getFinGriJ(): string
    {
        return $this->finGriJ;
    }

    /**
     * @return string
     */
    public function getDureeGriJ(): string
    {
        return $this->dureeGriJ;
    }

}
