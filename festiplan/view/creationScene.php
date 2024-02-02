<?php

if (!isset($_SESSION["session_id"])
    || $_SESSION["session_id"] != session_id()) {
    header("Location: ?controller=Authentification");
    exit();
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Creation d'une scene</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>

    <?php
        require("header.php");
        SetupHeadersAndDialog($titre, $controller, $open);
    ?>

    <br><br><br>

    <form class="container" action="?" method="post">
        <input type="hidden" name="controller" value="CreationScene">
        <input type="hidden" name="action" value="confirmation">
        <input type="hidden" name="idCreateur" value="<?php echo $_SESSION["user_id"] ?>">

        <div class="row fond-primary">
            <div class="col-12">&nbsp;</div>
            <div class="col-6">
                <label for="nomScene">
                    <h3>Nom de la scene</h3>
                </label>
                <input type="text" name="nomScene" class="col-12" placeholder="Nom de la scene">

                <hr>

                <label for="capacite">
                    <h3>Capacite</h3>
                </label>
                <input type="number" name="capacite" class="col-12" placeholder="Capacite">

            </div>
            <div class="col-12">&nbsp;</div>
        </div>
    </form>

    <br><br><br>


    <?php
        require("footer.php");
    ?>
</body>
</html>