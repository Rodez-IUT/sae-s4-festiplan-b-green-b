<!DOCTYPE html>
<html lang="fr">
<head>
    
    <meta charset="UTF-8">
    <meta viewport="width=device-width, initial-scale=1">

    <title>Erreur</title>

    <link rel="stylesheet" href="../other/css/style.css">

</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Erreur</h1>

                <?php

                if (isset($message_erreur)) {
                    echo "<p>$message_erreur</p>";
                } else {
                    echo "<p>Erreur inconnue</p>";
                }

                ?>

                <a href="?controller=Home">
                    Revenir à l'accueil
                </a>

            </div>
        </div>
    </div>

</body>
</html>