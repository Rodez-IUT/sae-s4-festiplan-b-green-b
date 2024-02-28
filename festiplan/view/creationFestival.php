<?php

if (!isset($_SESSION["session_id"])
    || $_SESSION["session_id"] != session_id()) {
    header("Location: ?controller=Authentification");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Créer un festival</title>

    <link rel="stylesheet" href="../festiplan/other/css/style.css">

</head>
<body>

        <?php
            require("header.php");
            SetupHeadersAndDialog($titre, $controller, $open);
        ?>

    <br><br>

    <form method="post" enctype="multipart/form-data"
          class="container" action="?">
        <input type="hidden" name="controller" value="CreationFestival">
        <input type="hidden" name="action" value="<?php echo $action_validation ?>">
        <input type="hidden" name="idFestival" value="<?php echo isset($idFestival) ? $idFestival : 0 ?>">
        <input type="hidden" name="responsable" value="<?php echo $_SESSION["user_id"] ?>">

        <div class="row">
            <div class="col-6">
                <div class="col-12 row fond-primary">
                    <div class="col-12">&nbsp;</div>
                    <div class="col-12">
                        <label for="nom" class="<?php echo $liste_classes['nom']?>">
                            Nom :
                        </label>
                        <input type="text" name="nom" class="col-12"
                            value="<?php echo $liste_valeurs['nom'] ?>">
                    </div>

                    <div class="col-12">&nbsp;</div>
                    <hr>

                    <div class="col-6">
                        <label for="ville" class="<?php echo $liste_classes['ville'] ?>">
                            Ville :
                        </label>
                        <input type="text" name="ville" class="col-12"
                            value="<?php echo $liste_valeurs['ville'] ?>">
                    </div>
                    
                    <div class="col-6">
                        <label for="codePostal" class="<?php echo $liste_classes['codePostal'] ?>">
                            Code Postal :
                        </label>
                        <input type="text" name="codePostal" class="col-12"
                            value="<?php echo $liste_valeurs['codePostal'] ?>">
                    </div>

                    <div class="col-12">&nbsp;</div>
                    <hr>

                    <div class="col-12">
                        <label for="description" class="<?php echo $liste_classes['description'] ?>">
                            Description : (1000 caractères max)
                        </label>
                        <textarea name="description" cols="30" rows="5" class="col-12"><?php echo $liste_valeurs['description'] ?></textarea>
                    </div>
                    <div class="col-12">&nbsp;</div>


                </div>
            </div>
            <div class="col-6">
                <div class="col-12 row fond-primary">
                    <div class="col-12">&nbsp;</div>

                    <div class="col-6">
                        <label for="dateDebut" class="<?php echo $liste_classes["dateDebut"] ?> ">
                            Debut :
                        </label>
                        <input type="date" name="dateDebut" class="col-12"
                            value="<?php echo $liste_valeurs["dateDebut"] ?>">
                    </div>

                    <div class="col-6">
                        <label for="dateFin" class="<?php echo $liste_classes["dateFin"] ?> ">
                            Fin :
                        </label>
                        <input type="date" name="dateFin" class="col-12"
                            value="<?php echo $liste_valeurs["dateFin"] ?>">
                    </div>

                    <div class="col-12">&nbsp;</div>
                    <hr>

                    <div class="col-12">
                        <label for="image" class="<?php echo $liste_classes["image"] ?>">
                            Image : (optionel | taille max 800px X 600px)
                        </label>
                        <input type="file" name="image" accept="image/png, image/jpeg, image/gif, image/jpg"
                               class="col-12" value="<?php echo $liste_valeurs["image"] ?>">
                    </div>

                    <div class="col-12">&nbsp;</div>
                    <hr>

                    <label for="categories[]" class="<?php echo $liste_classes["categories"] ?>">
                        Catégorie : (plusieurs choix possibles)
                    </label>
                    <div class="col-12">
                        <div class="col-12 fond-blanc grid-x-2">
                            <?php
                            $index = 0;
                            $size = count($liste_categories);
                            $fin = false;
                            ?>
                            <div>
                            <?php
                                for ($index; $index < 4 && !$fin; $index++) {
                                    if (isset($liste_categories[$index])) {
                                        $categorie = $liste_categories[$index];
                                        $checked = "";

                                        if (!empty($liste_valeurs["categories"])) {
                                            if (in_array($categorie["idCategorie"], $liste_valeurs["categories"])) {
                                                $checked = "checked";
                                            }
                                        }
                            ?>
                                <span class="inputs">
                                    <?php echo $categorie["nomCategorie"] ?>
                                    <input type="checkbox" name="categories[]"
                                        value="<?php echo $categorie["idCategorie"] ?>"
                                        <?php echo $checked ?>>
                                </span>

                            <?php
                                    } else {
                                        $fin = true;
                                    }
                                }


                            ?>
                            </div>
                            <div>
                            <?php

                            for ($index; $index < $size && !$fin; $index++) {
                                if (isset($liste_categories[$index])) {
                                    $categorie = $liste_categories[$index];
                                    $checked = "";

                                    if (!empty($liste_valeurs["categories"])) {
                                        if (in_array($categorie["idCategorie"], $liste_valeurs["categories"])) {
                                            $checked = "checked";
                                        }
                                    }
                            ?>

                                <span class="inputs">
                                    <?php echo $categorie["nomCategorie"] ?>
                                    <input type="checkbox" name="categories[]"
                                        value="<?php echo $categorie["idCategorie"] ?>"
                                        <?php echo $checked ?>>
                                </span>

                            <?php
                                } else {
                                    $fin = true;
                                }
                            }
                            ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">&nbsp;</div>
                </div>
            </div>

            <div class="col-12">&nbsp;</div>

            <div class="col-12 ">
                <div class="col-12 row fond-primary">
                <div class="col-12">&nbsp;</div>
                    <!-- les scenes -->
                    <div class="col-6">
                        <label for="scenes[]" class="<?php echo $liste_classes["scenes"] ?>">
                            Scènes : (plusieurs choix possibles)
                        </label>
                        <div class="col-12 fond-blanc">
                            <div class="creaGrij">

                            <?php

                            foreach ($liste_scenes as $scene) {
                                $checked = "";

                                if (!empty($liste_valeurs["scenes"])) {
                                    if (in_array($scene["idScene"], $liste_valeurs["scenes"])) {
                                        $checked = "checked";
                                    }
                                }
                            ?>

                                <span class="inputs">
                                    <?php echo $scene["nomScene"] ?>
                                    <input type="checkbox" name="scenes[]"
                                        value="<?php echo $scene["idScene"] ?>"
                                        <?php echo $checked ?>>
                                </span>

                            <?php
                            }

                            ?>
                            </div>
<!--                            <div class="col-12">&nbsp;</div>-->
<!--                            <a class="col-4 offset-7" href="?controller=CreationScene">-->
<!--                                <button class="col-4" type="button">-->
<!--                                    Créer +-->
<!--                                </button>-->
<!--                            </a>-->
<!--                            <div class="col-12">&nbsp;</div>-->

                        </div>
                    </div>

                    <div class="col-6">
                        <label for="grille">
                            Grille journaliere de contrainte : (Veuiilez en créer une avant)
                        </label>
                        <div class="col-12 fond-primary creaGrij">
                            <label for="debutGriJ" class="col-12 <?php echo isset($liste_classes["debutGriJ"]) ? $liste_classes["debutGriJ"] : "" ?>">
                                Heure de début :
                            </label>
                            <input type="time" name="debutGriJ" class="col-12"
                                   value="<?php echo isset($liste_valeurs["debutGriJ"]) ? $liste_valeurs["debutGriJ"] : "" ?>">

                            <label for="griJFin" class="col-12 <?php echo isset($liste_classes["finGriJ"]) ? $liste_classes["finGriJ"] : "" ?>">
                                Heure de fin :
                            </label>
                            <input type="time" name="finGriJ" class="col-12"
                                   value="<?php echo isset($liste_valeurs["finGriJ"]) ? $liste_valeurs["finGriJ"] : "" ?>">

                            <label for="dureeGriJ" class="col-12 <?php isset($liste_classes["dureeGriJ"]) ? $liste_classes["dureeGriJ"] : "" ?>">
                                Durée entre deux spectacles :
                            </label>
                            <input type="number" name="dureeGriJ" class="col-12"
                                   value="<?php echo isset($liste_valeurs["dureeGriJ"]) ? $liste_valeurs["dureeGriJ"] : "" ?>">
                        </div>
                    </div>

                    <div class="col-12">&nbsp;</div>
                </div>

            </div>

            <div class="col-12">&nbsp;</div>
            <div class="col-12">&nbsp;</div>

            <div class="col-12">
                <div class="col-12 row">
                    <a class="col-6" href="?controller=ListeFestival">
                        <button class="col-12 btn-annuler" type="button">
                            Annuler
                        </button>
                    </a>
                    <div class="col-6">
                        <button class="col-12 btn-valide" type="submit">
                            <?php echo $texte_bouton ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12">&nbsp;</div>
            <div class="col-12">&nbsp;</div>
        </div>
    </form>

    <?php require("footer.php"); ?>

    <script src="../festiplan/other/js/creationFestival.js"></script>
</body>
</html>