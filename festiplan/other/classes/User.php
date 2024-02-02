<?php

namespace other\classes;

class User
{

    /**
     * Le nom de l'utilisateur.
     *
     * @var string $nom Le nom de l'utilisateur.
     */
    private string $nom;

    /**
     * Le prénom de l'utilisateur.
     *
     * @var string $prenom Le prénom de l'utilisateur.
     */
    private string $prenom;

    /**
     * L'adresse e-mail de l'utilisateur.
     *
     * @var string $email L'adresse e-mail de l'utilisateur.
     */
    private string $email;

    /**
     * L'identifiant de l'utilisateur.
     *
     * @var string $identifiant L'identifiant de l'utilisateur.
     */
    private string $identifiant;

    /**
     * Le mot de passe de l'utilisateur.
     *
     * @var string $password Le mot de passe de l'utilisateur.
     */
    private string $password;

    /**
     * Constructeur de la classe User.
     *
     * @param string $nom Le nom de l'utilisateur.
     * @param string $prenom Le prénom de l'utilisateur.
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @param string $identifiant L'identifiant de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     *
     * @throws \Exception Si un des champs est vide.
     */
    public function __construct(string $nom, string $prenom, string $email, string $identifiant, string $password)
    {
        // Vérifie si un des champs est vide et lance une exception le cas échéant
        if (empty($nom) || empty($prenom) || empty($identifiant) || empty($email) || empty($password)) {
            throw new \Exception("Un des champs est vide.");
        }

        // Initialise les propriétés de l'utilisateur
        $this->nom = htmlspecialchars($nom);
        $this->prenom = htmlspecialchars($prenom);
        $this->email = htmlspecialchars($email);
        $this->identifiant = htmlspecialchars($identifiant);
        $this->password = password_hash(htmlspecialchars($password), PASSWORD_DEFAULT);
    }

    /**
     * Récupère le nom de l'utilisateur.
     *
     * @return string Le nom de l'utilisateur.
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * Récupère le prénom de l'utilisateur.
     *
     * @return string Le prénom de l'utilisateur.
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * Récupère l'adresse e-mail de l'utilisateur.
     *
     * @return string L'adresse e-mail de l'utilisateur.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Récupère l'identifiant de l'utilisateur.
     *
     * @return string L'identifiant de l'utilisateur.
     */
    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    /**
     * Récupère le mot de passe de l'utilisateur.
     *
     * @return string Le mot de passe de l'utilisateur.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

}