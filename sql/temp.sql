DROP PROCEDURE IF EXISTS delete_account;
DELIMITER //
CREATE PROCEDURE delete_account(IN user_id VARCHAR(255), OUT success BOOLEAN)
BEGIN
    DECLARE festival_id INT;
    DECLARE spectacle_id INT;

    DECLARE festivals_cursor CURSOR FOR
        SELECT idFestival
        FROM festivals
        WHERE idResponsable = user_id;

    DECLARE spectacles_cursor CURSOR FOR
        SELECT idSpectacle
        FROM spectacles
        WHERE idResponsableSpectacle = user_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET success = TRUE;
    SET success = FALSE;

    START TRANSACTION;

    OPEN festivals_cursor;
    festivals_loop: LOOP
        FETCH festivals_cursor INTO festival_id;
            IF success THEN
                LEAVE festivals_loop;
        END IF;

        BEGIN
            DELETE FROM categorieFestival WHERE idFestival = festival_id;
            DELETE FROM composer WHERE idFestival = festival_id;
            DELETE FROM organiser WHERE idFestival = festival_id;
            DELETE FROM accueillir WHERE idFestival = festival_id;
            DELETE FROM festivals WHERE idFestival = festival_id;
        END;

    END LOOP;
    CLOSE festivals_cursor;

    OPEN spectacles_cursor;
    spectacles_loop: LOOP
        FETCH spectacles_cursor INTO spectacle_id;
            IF success THEN
                LEAVE spectacles_loop;
        END IF;

        BEGIN
            DELETE FROM intervenir WHERE idSpectacle = spectacle_id;
            DELETE FROM categorieSpectacle WHERE idSpectacle = spectacle_id;
            DELETE FROM spectacles WHERE idSpectacle = spectacle_id;
        END;

        END LOOP;
    CLOSE spectacles_cursor;

    DELETE FROM users WHERE idUser = user_id;

    IF success THEN
        COMMIT;
    ELSE
        ROLLBACK;
    END IF;
END//
DELIMITER ;


# alter table intervenants add COLUMN idCreateur int;
# update intervenants set idCreateur = 1;
# alter table intervenants add CONSTRAINT fk_createur FOREIGN KEY (idCreateur) REFERENCES users(idUser)






