<?php
extract($_POST);


$nom_image = basename($_FILES["image"]['name']);
$chemin_destination = 'images/' . $nom_image;
$resultat = move_uploaded_file($_FILES['image']['tmp_name'], $chemin_destination);



$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

$sql = "INSERT INTO article (titre, contenu, date_time_publication,date_time_edition,images) VALUES ('$article_titre', '$article_contenu', '2022-12-31 17:00:59','2022-12-31 17:00:59','$nom_image')";

//execution de la requête
$resultat = $bdd->exec($sql);

echo "<div class='alert alert-danger' role='alert'>
 Le produit a été ajouté :)
</div> </div>";

header('Location:lesarticles.php');
//requête	affichage
$req = "select * from article order by id desc limit 0,1";

//execution de la requête
$resultat = $bdd->query($req);

//affichage des resultats dans un objet

while ($produit = $resultat->fetch(PDO::FETCH_OBJ)) {

    echo "<div class='card text-center' style='width: 15rem;'>
                <img class='card-img-top' src='images/" . $produit->images . "' >
                <div class='card-body'>
                    <h5 class='card-title'>" . $produit->titre . "</h5>
                    <p class='card-text'> " . $produit->contenu . " €</p>
                    <a id='mywish' href='ajout_panier.php?ajout=" . $produit->id . "' class='btn btn-danger'>Ajouter au <i class='fas fa-cart-plus'></i></a>
                </div>
            </div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Confirm Insersion</title>
</head>

<body>
    <a href="lesarticles.php">Page admin</a>
</body>

</html>