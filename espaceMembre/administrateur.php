<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

$nom_connexion = $_GET["nom"];

$req = "SELECT A.nom,A.role
from administration A, membres M
WHERE A.idAdmin = M.id
AND A.role = 'administrateur'";
$resultat = $bdd->query($req);

foreach ($resultat as $zzz) {
    $role = $zzz['role'];
}


if ($nom_connexion == "afrid" and $role == "administrateur") {



?>



    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <title>administrateur</title>
    </head>

    <body>


        <?php
        include_once("header/header_administration.php");

        ?>
        <center>
            <h1>Bienvenu cher <?php echo $nom_connexion . ' (' . $role . ')' ?></h1>
        </center>


        espace dedie à l' <?php echo $role; ?>
    </body>

    </html>

<?php

} else {
    echo "<center><span style='color:red;font-size:25px;font-weight:700;'>Vous devez etre l'administrateur pour pouvoir acceder à ce site!</span></center>";
}
?>