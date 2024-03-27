<?php

if (!isset($_SESSION["session_id"])
    || $_SESSION["session_id"] != session_id()) {
    header("Location: ?controller=Authentification");
    exit();
}
/** @noinspection PhpUndefinedVariableInspection */
$_SESSION["organisateur"] = $organisateur;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Festivals</title>

    <link rel="stylesheet" href="../festiplan/other/css/style.css">


</head>
<body>
    <div id="messageReussite" <?php

    if (!isset($cree) || !$cree) {
        echo "hidden=true";
    }

    $cree = false;
    unset($cree);

    ?>>
        Votre festival a bien été créé !
    </div>
    
        <?php
            require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/header.php");
            SetupHeadersAndDialog($titre, $controller, $open);
        ?>
    
    <br><br><br>
    <div class="container">
        <div class="row">
            
            <div class="col-8 offset-2 row liste fond-primary">

                <?php

                if (!isset($liste_festivals) || empty($liste_festivals)) {
                    echo "<div class='col-12 text-center fond-background'>Aucun festival</div>";
                    echo "<div class='col-12'>&nbsp;</div>";
                } else {
                    foreach ($liste_festivals as $festival) {
                        ?>
                        <div class='col-8 liste-item fond-background nom'>
                            <?php echo $festival["nomFestival"] ?>
                        </div>
                        <form class='col-1 liste-item text-center'>
                            <input type="hidden" name="controller" value="Planification">
                            <input type="hidden" name="idFestival" value="<?php echo $festival["idFestival"] ?>">
                            <?php 
                            if(isset($festival["spectacles"]) && !empty($festival["spectacles"])) { ?>
                                <button type="submit" class="col-12">
                                <span class="fa fa-calendar "></span>
                                </button>
                                 <?php
                            }
                            else { ?>
                                <span class="fa fa-calendar icone-grise"></span> 
                                <?php
                            } ?>
                        </form>
                            

                        <form class="col-1 liste-item text-center">
                            <input type="hidden" name="controller" value="FestivalAjouts">
                            <input type="hidden" name="idFestival" value="<?php echo $festival["idFestival"] ?>">
                            <button type="submit" class="col-12">
                                <span class="fa fa-plus"></span>
                            </button>

                        </form>

                        <form class="col-1 liste-item text-center">
                            <input type="hidden" name="controller" value="GestionFestivals">
                            <input type="hidden" name="action" value="modifier">
                            <input type="hidden" name="idFestival" value="<?php echo $festival["idFestival"] ?>">
                            <button type="submit" class="col-6">
                                <span class="fa fa-pen-to-square"></span>
                            </button>
                        </form>

                        <form class="col-1 liste-item text-center">
                            <input type="hidden" name="controller" value="GestionFestivals">
                            <input type="hidden" name="action" value="confirmationSupressionFestival">
                            <input type="hidden" name="idFestival" value="<?php echo $festival["idFestival"] ?>">
                            <button type="submit" class="col-6">
                                <span class="fa fa-trash-can"></span>
                            </button>

                        </form>

                        <div class='col-12'>&nbsp;</div>

                <?php
                    }
                }
                ?>

                <a href="?controller=CreationFestival" class="col-3 offset-9">
                    <button class="col-12 btn-creer">
                        Créer    
                    </button>
                </a>

            </div>

            
        </div>
        

    </div>
    <br><br><br><br><br><br><br><br><br>

    <?php
        require($_SERVER['DOCUMENT_ROOT'] . "/sae-s4-festiplan-b-green-b/festiplan/view/footer.php");
    ?>

</body>
</html>