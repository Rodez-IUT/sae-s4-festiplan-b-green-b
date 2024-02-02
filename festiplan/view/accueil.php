<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Spectacle</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="../other/css/style.css">

    </head>
    <body>



        <?php
            require("header.php");
            SetupHeadersAndDialog($titre, $controller, $open);
        ?>

        <br>

        <main class="container">
            <div class="row">

                <div class="col-12">
                    <a href="?controller=ListeFestival">
                        <button class="col-3">
                            Mes festivals
                        </button>
                    </a>
                    <a href="?controller=ListeSpectacle">
                        <button class="col-3">
                            Mes spectacles
                        </button>
                    </a>
                </div>


                <?php
                    if (isset($searchStmt) && !empty($searchStmt)) {
                        foreach ($searchStmt as $i => $row) {
                            if($i %2 == 0) {
                                echo '<div class="col-12"><br></div>';
                            }
                            // var_dump($row);
                            $image_name = $row["image_name"];
                ?>
                    <div class="col-6 center">
                        <div class="col-12 row bordure fond-primary">
                            <div class="col-12">&nbsp;</div>
                            <div class="col-6 col-xl-7 row">
                                <h2 class="col-12">
                                    <?php echo $row["nomFestival"]; ?>
                                </h2>


                                <!--spacing-->
                                 <div class="col-12">&nbsp;</div>

                                <div class="col-12">
                                    <p>
                                        Catégorie(s) :
                                    </p>
                                    <?php
                                        foreach ($row["categories"] as $cate) {
                                            echo $cate . " ";
                                        }
                                    ?>
                                </div>
                                <div class="col-12"></div>

                                <!--spacing-->
                                <div class="col-12">&nbsp;</div>

                                <div class="col-12">
                                    <p>
                                        Dates de déroulement :
                                    </p>
                                    <?php echo $row["dateDebutFestival"] . " au " . $row["dateFinFestival"]; ?>
                                </div>

                                <!--spacing-->
                                <div class="col-12">&nbsp;</div>

                                <div class="col-12 row">
                                    <p class="col-4">
                                        Ville :
                                    </p>
                                    <span class="col-8">
                                        <?php echo  $row["ville"]; ?>
                                    </span>
                                </div>

                                <div class="col-12 row">
                                    <p class="col-8">
                                        Code postal :
                                    </p>
                                    <span class="col-4">
                                        <?php echo $row["codePostal"]; ?>
                                    </span>
                                </div>

                            </div>

                            <div class="col-6 col-xl-5 row">

                                <div class="col-12">
                                    <img class="col-12 img-fest"
                                         src="../stockage/images/<?php echo $image_name; ?>"
                                         alt="<?php echo $row["nomFestival"]; ?>">
                                </div>

                                <div class="col-12">
                                    <p>
                                        Description :
                                    </p>
                                    <div class="description">
                                       <?php echo $row["descriptionFestival"]; ?>
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>
                        
                <?php
                }
                } else {
                    echo "Aucun festival à venir";
                }
        ?>

            </div>
        </main>

        <br><br>

        <?php require("footer.php"); ?>

    </body>
</html>