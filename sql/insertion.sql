-- Intervenants table
INSERT INTO intervenants (nomIntervenant, prenomIntervenant, emailIntervenant, estSurScene)
VALUES 
    ('Doe', 'John', 'john.doe@example.com', 1),
    ('Smith', 'Jane', 'jane.smith@example.com', 0),
    ('Johnson', 'Alice', 'alice.johnson@example.com', 1),
    ('Williams', 'Emily', 'emily.williams@example.com', 1),
    ('Brown', 'Christopher', 'chris.brown@example.com', 0),
    ('Lee', 'Sophia', 'sophia.lee@example.com', 1);

-- GrilleJournaliere table
INSERT INTO grilleJournaliere (heureDebut, heureFin, dureeMinimaleEntreDeuxSpectacles)
VALUES 
    ('08:00:00', '12:00:00', 30),
    ('14:00:00', '18:00:00', 45),
    ('20:00:00', '23:00:00', 60),
    ('10:00:00', '14:00:00', 60),
    ('16:30:00', '20:30:00', 45),
    ('22:00:00', '01:00:00', 75);

-- Users table
INSERT INTO users (nomUser, prenomUser, emailUser, loginUser, passwordUser)
VALUES 
    ('Johnson', 'Michael', 'michael.johnson@example.com', 'michaelj', 'hashed_password'),
    ('Garcia', 'Maria', 'maria.garcia@example.com', 'mariag', 'hashed_password'),
    ('Chen', 'David', 'david.chen@example.com', 'davidc', 'hashed_password'),
    ('Gonzalez', 'Daniel', 'daniel.gonzalez@example.com', 'danig', 'hashed_password'),
    ('Martinez', 'Olivia', 'olivia.martinez@example.com', 'oliviam', 'hashed_password'),
    ('Liu', 'Kevin', 'kevin.liu@example.com', 'kevinl', 'hashed_password');

-- Images table
INSERT INTO images (nomImage)
VALUES 
    ('image1.jpg'),
    ('image2.jpg'),
    ('image3.jpg'),
    ('image4.jpg'),
    ('image5.jpg'),
    ('image6.jpg');

-- Categories table
INSERT INTO categories (nomCategorie)
VALUES 
    ('Musique'),
    ('Danse'),
    ('Theatre'),
    ('Humour'),
    ('Magie'),
    ('Cirque'),
    ('Cinema');

-- Scenes table
INSERT INTO scenes (nomScene, tailleScene, spectateurMax, coordonneesGPS)
VALUES 
    ('Scene Principale', '3', 1000, '40.7128° N, 74.0060° W'),
    ('Petite Scene', '1', 200, '34.0522° N, 118.2437° W'),
    ('Scene Exterieur', '2', 500, '51.5074° N, 0.1278° W'),
    ('Scene Secondaire', '2', 800, '52.5200° N, 13.4050° E'),
    ('Theatre Interieur', '3', 1200, '48.8566° N, 2.3522° E'),
    ('Amphitheatre', '3', 1500, '37.9838° N, 23.7275° E');

-- Spectacles table
INSERT INTO spectacles (titreSpectacle, descriptionSpectacle, idImage, dureeSpectacle, surfaceSceneRequise, idResponsableSpectacle)
VALUES
    ('Concert Nocturne', 'Une soirée de performances musicales', 1, 120, 1, 1),
    ('Spectacle de Danse', 'Une performance de danse hypnotisante', 2, 90, 2, 2),
    ('Theatre', 'Une production théâtrale captivante', 1, 150, 3, 1),
    ('Stand-up Comedy', 'Une nuit remplie de rire', 1, 90, 2, 2),
    ('Magic Show', 'Une présentation enchanteresse de tours de magie', 2, 120, 1, 1),
    ('Circus Performance', 'Une extravagance de cirque palpitante', 1, 150, 3, 2);

INSERT INTO festivals(nomFestival, descriptionFestival, idImage, dateDebutFestival, dateFinFestival, idGriJ, idResponsable, ville, codePostal)
VALUES
    ('estivada', 'festival tah les fous de rodez', 1, '2023-06-21', '2023-06-28', 1, 1, 'Rodez', '12000'),
    ('pause guitare', 'festival de musique a albi', 2, '2023-07-05', '2023-07-10', 1, 1, 'Albi', '81000'),
    ('les ardentes', 'festival de rap en belgique', 1, '2023-08-14', '2023-08-18', 1, 1, 'Bruxelles','45896');

INSERT INTO categorieFestival(idFestival, idCategorie)
VALUES
    (1,1),
    (2,1),
    (3,1);