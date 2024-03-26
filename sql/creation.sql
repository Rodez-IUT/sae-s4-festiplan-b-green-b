CREATE DATABASE IF NOT EXISTS festiplan;
use festiplan;


CREATE TABLE grilleJournaliere (
    idGriJ INT NOT NULL AUTO_INCREMENT,
    heureDebut TIME NOT NULL,
    heureFin TIME NOT NULL,
    dureeMinimaleEntreDeuxSpectacles INT NOT NULL, -- en minutes
    PRIMARY KEY (idGriJ)
);

CREATE TABLE users (
    idUser INT NOT NULL AUTO_INCREMENT,
    nomUser VARCHAR(100) NOT NULL,
    prenomUser VARCHAR(100) NOT NULL,
    emailUser VARCHAR(100) NOT NULL UNIQUE,
    loginUser VARCHAR(100) NOT NULL UNIQUE,
    passwordUser VARCHAR(255) NOT NULL, -- chiffre avec une fonction php
    PRIMARY KEY (idUser)
);

CREATE TABLE intervenants (
    idIntervenant INT NOT NULL AUTO_INCREMENT,
    nomIntervenant VARCHAR(100) NOT NULL,
    prenomIntervenant VARCHAR(100) NOT NULL,
    emailIntervenant VARCHAR(100) NOT NULL,
    estSurScene BOOLEAN NOT NULL,
    idCreateur INT NOT NULL, -- foreign key
    PRIMARY KEY (idIntervenant),
    CONSTRAINT fk_createur FOREIGN KEY (idCreateur) REFERENCES users(idUser)
);

CREATE TABLE images (
    idImage INT NOT NULL AUTO_INCREMENT,
    nomImage VARCHAR(100) NOT NULL,
    PRIMARY KEY (idImage)
);

CREATE TABLE categories (
    idCategorie INT NOT NULL AUTO_INCREMENT,
    nomCategorie VARCHAR(100) NOT NULL,
    PRIMARY KEY (idCategorie)
);

CREATE TABLE scenes (
    idScene INT NOT NULL AUTO_INCREMENT,
    nomScene VARCHAR(100) NOT NULL,
    tailleScene VARCHAR(10) NOT NULL, -- (petite, moyenne, grande)
    spectateurMax INT NOT NULL,
    coordonneesGPS VARCHAR(100) NOT NULL,
    PRIMARY KEY (idScene)
);

CREATE TABLE spectacles (
    idSpectacle INT NOT NULL AUTO_INCREMENT,
    titreSpectacle VARCHAR(100) NOT NULL,
    descriptionSpectacle VARCHAR(1000) NOT NULL,
    idImage INT, -- can be null
    dureeSpectacle INT NOT NULL, -- en minutes
    surfaceSceneRequise INT NOT NULL, -- (petite, moyenne, grande)
    idResponsableSpectacle INT NOT NULL, -- foreign key
    -- au moins une categorie dans une autre table
    -- une liste d'intervenants dans une autre table
        -- les intervenants peuvent être sur scene ou en coulisse
        -- mais ici ils sont considérés pareil
    FOREIGN KEY (idImage) references images(idImage),
    FOREIGN KEY (idResponsableSpectacle) references users(idUser),
    PRIMARY KEY (idSpectacle)
);


CREATE TABLE festivals (
    idFestival INT NOT NULL AUTO_INCREMENT,
    nomFestival VARCHAR(100) NOT NULL,
    descriptionFestival VARCHAR(1000) NOT NULL,
    idImage INT, -- can be null
    dateDebutFestival DATE NOT NULL,
    dateFinFestival DATE NOT NULL,
    idGriJ INT NOT NULL, -- foreign key 
    idResponsable INT NOT NULL, -- foreign key
    ville VARCHAR(100) NOT NULL,
    codePostal CHAR(5) NOT NULL, 
    -- au moins une categorie dans une autre table
    -- une liste de scenes dans une autre table
    -- une liste de membres dans une autre table
    -- une liste de spectacles dans une autre table
    FOREIGN KEY (idImage) references images(idImage),
    FOREIGN KEY (idGriJ) references grilleJournaliere(idGriJ),
    FOREIGN KEY (idResponsable) references users(idUser),
    PRIMARY KEY (idFestival)
);


CREATE TABLE accueillir (
    idFestival INT NOT NULL, -- foreign key
    idScene INT NOT NULL, -- foreign key
    FOREIGN KEY (idFestival) references festivals(idFestival),
    FOREIGN KEY (idScene) references scenes(idScene),
    PRIMARY KEY (idFestival, idScene)
);

CREATE TABLE organiser (
    idFestival INT NOT NULL, -- foreign key
    idUser INT NOT NULL, -- foreign key
    FOREIGN KEY (idFestival) references festivals(idFestival),
    FOREIGN KEY (idUser) references users(idUser),
    PRIMARY KEY (idFestival, idUser)
);

CREATE TABLE categorieSpectacle (
    idSpectacle INT NOT NULL, -- foreign key
    idCategorie INT NOT NULL, -- foreign key
    FOREIGN KEY (idSpectacle) references spectacles(idSpectacle),
    FOREIGN KEY (idCategorie) references categories(idCategorie),
    PRIMARY KEY (idSpectacle, idCategorie)
);

CREATE TABLE categorieFestival (
    idFestival INT NOT NULL, -- foreign key
    idCategorie INT NOT NULL, -- foreign key
    FOREIGN KEY (idFestival) references festivals(idFestival),
    FOREIGN KEY (idCategorie) references categories(idCategorie),
    PRIMARY KEY (idFestival, idCategorie)
);

CREATE TABLE intervenir (
    idSpectacle INT NOT NULL, -- foreign key
    idIntervenant INT NOT NULL, -- foreign key
    FOREIGN KEY (idSpectacle) references spectacles(idSpectacle),
    FOREIGN KEY (idIntervenant) references intervenants(idIntervenant),
    PRIMARY KEY (idSpectacle, idIntervenant)
);

CREATE TABLE composer (
    idSpectacle INT NOT NULL, -- foreign key
    idFestival INT NOT NULL, -- foreign key
    FOREIGN KEY (idSpectacle) references spectacles(idSpectacle),
    FOREIGN KEY (idFestival) references festivals(idFestival),
    PRIMARY KEY (idSpectacle, idFestival)
);

CREATE TABLE favoris (
    idUser INT NOT NULL, -- foreign key
    idFestival INT NOT NULL, -- foreign key
    FOREIGN KEY (idUser) references users(idUser),
    FOREIGN KEY (idFestival) references festivals(idFestival),
    PRIMARY KEY (idUser, idFestival)
);

CREATE TABLE api_keys (
    idUser INT NOT NULL, -- foreign key
    APIKey VARCHAR(32) NOT NULL,
    FOREIGN KEY (idUser) references users(idUser),
    PRIMARY KEY (idUser)
);