DELIMITER //
DROP FUNCTION IF EXISTS calculeDureeJournee//
CREATE FUNCTION calculeDureeJournee (heureDebut TIME, heureFin TIME) RETURNS TIME
DETERMINISTIC
BEGIN
	DECLARE dureeJournee TIME DEFAULT '0:00:00';
    IF heureFin > heureDebut THEN
		SET dureeJournee = heureFin - heureDebut;
	ELSEIF heureDebut > heureFin THEN
		SET dureeJournee = '24:00:00' - heureDebut + heureFin;
	END IF;
	RETURN dureeJournee;
END //
DELIMITER ;

SELECT calculeDureeJournee("15:00:00", "19:00:00");



DELIMITER //
DROP FUNCTION IF EXISTS soustraireDuree //
CREATE FUNCTION soustraireDuree(duree1 TIME, duree2 TIME) RETURNS TIME
DETERMINISTIC
BEGIN
    DECLARE totalHours INT;
    DECLARE totalMinutes INT;
    DECLARE totalSeconds INT;
    DECLARE resultat TIME;

	IF duree1 > duree2 THEN
		SET totalHours = HOUR(duree1) - HOUR(duree2);
		SET totalMinutes = MINUTE(duree1) - MINUTE(duree2);
		SET totalSeconds = SECOND(duree1) - SECOND(duree2);
	ELSE
		SET totalHours = HOUR(duree2) - HOUR(duree1);
		SET totalMinutes = MINUTE(duree2) - MINUTE(duree1);
		SET totalSeconds = SECOND(duree2) - SECOND(duree1);
	END IF;

    IF totalSeconds < 0 THEN
        SET totalSeconds = totalSeconds + 60;
        SET totalMinutes = totalMinutes - 1;
    END IF;

    IF totalMinutes < 0 THEN
        SET totalMinutes = totalMinutes + 60;
        SET totalHours = totalHours - 1;
    END IF;

    SET resultat = MAKETIME(
        GREATEST(totalHours, 0),
        GREATEST(totalMinutes, 0),
        GREATEST(totalSeconds, 0)
    );

    RETURN resultat;
END //

DELIMITER ;

SELECT soustraireDuree('05:30:00', '20:00:00');



DELIMITER //
DROP FUNCTION IF EXISTS ajouterDuree//
CREATE FUNCTION ajouterDuree(duree1 TIME, duree2 TIME) RETURNS time
DETERMINISTIC
BEGIN
	DECLARE resultat TIME;
	DECLARE totalHours TIME;
	DECLARE totalMinutes TIME;
	DECLARE totalSec TIME;
	SET totalHours = HOUR(duree1) + HOUR(duree2) + FLOOR((MINUTE(duree1) + MINUTE(duree2)) / 60);
	SET totalMinutes = (MINUTE(duree1) + MINUTE(duree2)) % 60;
	SET totalSec = SECOND(duree1) + SECOND(duree2);

	SET resultat = MAKETIME(totalHours, totalMinutes, totalSec);

	return resultat;
END //

DELIMITER ;

SELECT ajouterDuree("18:30:00", "00:45:00");


DROP TRIGGER IF EXISTS delete_festival;
DELIMITER //
CREATE TRIGGER delete_festival
BEFORE DELETE ON festivals
FOR EACH ROW
BEGIN

    DELETE FROM categorieFestival WHERE idFestival = OLD.idFestival;
    DELETE FROM composer WHERE idFestival = OLD.idFestival;
    DELETE FROM organiser WHERE idFestival = OLD.idFestival;
    DELETE FROM accueillir WHERE idFestival = OLD.idFestival;

END//


DROP TRIGGER IF EXISTS delete_spectacle;
DELIMITER //
CREATE TRIGGER delete_spectacle
BEFORE DELETE ON spectacles
FOR EACH ROW
BEGIN

    DELETE FROM categorieSpectacle WHERE idSpectacle = OLD.idSpectacle;
    DELETE FROM composer WHERE idSpectacle = OLD.idSpectacle;
    DELETE FROM intervenir WHERE idSpectacle = OLD.idSpectacle;

END//

DROP TRIGGER IF EXISTS delete_scene;
DELIMITER //
CREATE TRIGGER delete_scene
BEFORE DELETE ON scenes
FOR EACH ROW
BEGIN

    DELETE FROM accueillir WHERE idScene = OLD.idScene;

END//