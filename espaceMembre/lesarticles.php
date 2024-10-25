<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');



include_once("header/header_accueil.php");
$resultat = '';
if (isset($_POST["rechercher"]) and !empty($_POST["recherche"])) {

    $article_recherche = $_POST["recherche"];

    $sql = "Select * from article
    WHERE titre LIKE '%$article_recherche%' ";
    $resultat = $bdd->query($sql);
} else {
    echo "Veuillez rechercher votre article";
}
// echo $id;

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Accueil</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <center>
        <label>Rechercher votre article:</label><br>
        <form action="lesarticles.php" method="post">
            <input type="text" name="recherche" id="recherche">
            <input type="submit" name="rechercher" value="rechercher">
        </form>


    </center>

    <?php
    if (isset($_POST["rechercher"]) and !empty($_POST["recherche"])) {
        foreach ($resultat as $article) {
            echo "
        
        <div class='row'>
        <div class='col-md-6'>
            <img height=450 width=450 src='images/$article[images]'>
        </div>
        <div class='col-md-6'>
            Name:<p> $article[titre] </p>
            Description:<p>$article[contenu]</p>
            publié le:<p>$article[date_time_publication]</p>
            Vous pouver le modifier <a class='btn btn-primary' href='redaction.php?edit=$article[id]'>Modifer</a> ou le supprimer <a class='btn btn-danger' href='supprimer.php?supp=<?php echo $article[id]?>'>Supprimer</a>
        </div>
    </div>
        
        
        "
    ?>

    <?php
        }
    }
    ?>

    <a href="profil.php?id=<?php echo  $_SESSION['id'] ?>">Profil</a>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>