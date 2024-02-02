<?php

if (!isset($_SESSION["session_id"])
    || $_SESSION["session_id"] != session_id()) {
    header("Location: ?controller=Authentification");
    exit();
}

?>


<!DOCTYPE html>
<html lang="fr²">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../other/css/style.css">
    
    <title>Créer un spectacle</title>
</head>
<body>

    <?php
    require("header.php");
    SetupHeadersAndDialog($titre, $controller, $open);
    ?>

    <br><br><br>
    <form method="post" enctype="multipart/form-data"
      class="container" action="?">
    <input type="hidden" name="controller" value="CreationSpectacle">
        <input type="hidden" name="action" value="<?php echo $action_validation ?>">
        <input type="hidden" name="idSpectacle" value="<?php echo isset($idSpectacle) ? $idSpectacle : 0 ?>">
        <input type="hidden" name="responsable" value="<?php echo $_SESSION["user_id"] ?>">
        <div class="row">
            <div class="col-6">
                <div class="col-12 row fond-primary">
                    <div class="col-12">&nbsp;</div>
                    <div class="col-12">
                        <label for="titre" class="<?php echo $liste_classes["titre"]?>">
                            Titre :
                        </label>
                        <input type="text" name="titre" placeholder="Entrez un titre" value="<?php echo $liste_valeurs["titre"] ?>">
                    </div>
                    <div class="col-12">&nbsp;</div>
                    <hr>

                    <div class="col-12">
                        <label for="tailleScene" class="col-4 <?php echo $liste_classes["tailleScene"]?>">
                            Taille de la scène :
                        </label>
                        <select class="col-7" name="tailleScene">
                            <option value="1" <?php if ($liste_valeurs["tailleScene"] == 1){echo "selected";} ?>>Petite</option>
                            <option value="2" <?php if ($liste_valeurs["tailleScene"] == 2){echo "selected";} ?>>Moyenne</option>
                            <option value="3" <?php if ($liste_valeurs["tailleScene"] == 3){echo "selected";} ?>>Grande</option>
                        </select>
                    </div>

                    <div class="col-12">&nbsp;</div>
                    <hr>

                    <div class="col-12">
                        <label for="description" class="<?php echo $liste_classes["description"]?>">
                            Description : (1000 caractères max)
                        </label>
                        <textarea name="description" cols="30" rows="6" ><?php echo $liste_valeurs["description"] ?></textarea>
                    </div>
                    <div class="col-12">&nbsp</div>
                </div>
            </div>
            <div class="col-6">
                <div class="row col-12 fond-primary">
                    <div class="col-12">&nbsp;</div>
                        <div class="col-12">
                            <label for="image" class="<?php echo $liste_classes["image"]?>">
                                Image (facultatif) :
                            </label>
                            <input type="file" name="image"
                                   accept="image/png image/jpg image/jpeg image/gif" value="<?php echo $liste_valeurs["image"] ?>">
                        </div>
                        <div class="col-12">&nbsp;</div>
                        <hr>
                        <div class="col-12">
                            <label for="duree" class="<?php echo $liste_classes["duree"]?>">
                                Durée : (en minutes)
                            </label>
                            <input class="col-12" type="text" name="duree" value="<?php echo $liste_valeurs["duree"] ?>">
                        </div>
                        <div class="col-12">&nbsp;</div>
                        <hr>
                        <div class="col-12">
                            <label for="categorie" >
                                Catégorie(s) : (plusieurs choix possibles)
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
                                                    } else {
                                                        $checked = "";
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
                        </div>
                    <div class="col-12">&nbsp</div>
                </div>
            </div>
            <div class="col-12">&nbsp</div>
            <div class="col-12">&nbsp</div>
            
            <div class="col-12">&nbsp;</div>
            <div class="col-12">
                <div class="col-12 row">
                    <a class="col-6" href="?controller=ListeSpectacle">
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
        </div>
    </form>
    
    <?php require("footer.php"); ?>

</body>
</html>