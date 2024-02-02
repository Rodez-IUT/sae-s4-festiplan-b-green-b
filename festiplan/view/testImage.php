<?php
// var_dump($_FILES);

require ('../utils/fonctions.php');

$path = "";

if (isset($_FILES["image"])) {
    $path = $_FILES["image"]["tmp_name"];
}

$size = get_image_size($path);

if (is_null($size)) {
    echo "fichier non fourni";
} else {
    echo $size["width"] . " x " . $size["height"];
    $name = htmlspecialchars($_FILES["image"]["name"]);

    if (!strpos($name, "/")) {
        $splitted_name = explode("/", $name);
        // var_dump($splitted_name);
    }

    $server_path = getenv("DOCUMENT_ROOT") . "/festiplan/stockage/images/" . $name;
    echo "<br>";
    echo $server_path;
    echo "<br>";
    echo "copie sur le serveur <br>";
    move_uploaded_file($path, $server_path);
    echo "copy done <br>";
    echo add_image_to_db($name);
}

echo "<br>";

?>

<!-- <img src="<?php echo $server_path; ?>" alt=""> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload Form</title>
</head>
<body>

<h2>Image Upload Form</h2>

<form action="testImage.php" method="post" enctype="multipart/form-data">
    <label for="image">Select an image:</label>
    <input type="file" name="image" id="image" accept="image/png, image/jpg" required>
    
    <br>
    
    <input type="submit" value="Upload Image">
</form>




</body>
</html>
