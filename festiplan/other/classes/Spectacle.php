<?php

namespace other\classes;


class Spectacle
{

    /**
     * Le titre du spectacle.
     *
     * @var string $titre Le titre du spectacle.
     */
    private string $titre;

    /**
     * La description du spectacle.
     *
     * @var string $description La description du spectacle.
     */
    private string $description;

    /**
     * La taille de la scène pour le spectacle.
     *
     * @var int $tailleScene La taille de la scène pour le spectacle.
     */
    private int $tailleScene;

    /**
     * L'identifiant de l'image du spectacle.
     *
     * @var int $id_image L'identifiant de l'image du spectacle.
     */
    private int $id_image;

    /**
     * L'identifiant du responsable du spectacle.
     *
     * @var string $idResponsable L'identifiant du responsable du spectacle.
     */
    private string $idResponsable;

    /**
     * La durée du spectacle.
     *
     * @var string $duree La durée du spectacle.
     */
    private string $duree;

    /**
     * Les catégories associées au spectacle (liste des identifiants).
     *
     * @var array $categories Les catégories associées au spectacle (liste des identifiants).
     */
    private array $categories;


    /**
     * Constructeur de la classe Spectacle.
     *
     * @param string $titre Le titre du spectacle.
     * @param string $description La description du spectacle.
     * @param string $tailleScene La taille de la scène pour le spectacle.
     * @param int $id_image L'identifiant de l'image du spectacle.
     * @param string $idResponsable L'identifiant du responsable du spectacle.
     * @param string $duree La durée du spectacle.
     * @param array $categories Les catégories associées au spectacle (liste des identifiants).
     */
    public function __construct(
        string $titre,
        string $description,
        string $tailleScene,
        int $id_image,
        string $idResponsable,
        string $duree,
        array $categories,
    ) {
        $this->titre = $titre;
        $this->description = $description;
        $this->tailleScene = $tailleScene;
        $this->id_image = $id_image;
        $this->idResponsable = $idResponsable;
        $this->duree = $duree;
        $this->categories = $categories;
    }

    /**
     * Obtient le titre du spectacle.
     *
     * @return string Le titre du spectacle.
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * Obtient la description du spectacle.
     *
     * @return string La description du spectacle.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Obtient l'identifiant de l'image du spectacle.
     *
     * @return int L'identifiant de l'image du spectacle.
     */
    public function getIdImage(): int
    {
        return $this->id_image;
    }

    /**
     * Obtient la durée du spectacle.
     *
     * @return string La durée du spectacle.
     */
    public function getDuree(): string
    {
        return $this->duree;
    }

    /**
     * Obtient les catégories associées au spectacle.
     *
     * @return array Les catégories associées au spectacle (liste des identifiants).
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Obtient la taille de la scène pour le spectacle.
     *
     * @return int La taille de la scène pour le spectacle.
     */
    public function getTailleScene(): int
    {
        return $this->tailleScene;
    }

    /**
     * Obtient l'identifiant du responsable du spectacle.
     *
     * @return string L'identifiant du responsable du spectacle.
     */
    public function getIdResponsable(): string
    {
        return $this->idResponsable;
    }
}