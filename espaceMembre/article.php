<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
if (isset($_GET['id']) and !empty($_GET['id'])) {
    $get_id = htmlspecialchars($_GET['id']);
    $article = $bdd->prepare('SELECT * FROM article WHERE id = ?');
    $article->execute(array($get_id));
    if ($article->rowCount() == 1) {
        $article = $article->fetch();
        $id = $article['id'];
        $titre = $article['titre'];
        $contenu = $article['contenu'];
        $images = $article['images'];
        $likes = $bdd->prepare('SELECT id FROM likes WHERE id_article = ?');
        $likes->execute(array($id));
        $likes = $likes->rowCount();
        $dislikes = $bdd->prepare('SELECT id FROM dislikes WHERE id_article = ?');
        $dislikes->execute(array($id));
        $dislikes = $dislikes->rowCount();
    } else {
        die('Cet article n\'existe pas !');
    }
} else {
    die('Erreur');
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Accueil</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <img src="images/<?= $images ?>" width="400" />
    <h1><?= $titre ?></h1>
    <p><?= $contenu ?></p>
    <a href="php/action.php?t=1&id=<?= $id ?>">J'aime</a> (<?= $likes ?>)
    <br />
    <a href="php/action.php?t=2&id=<?= $id ?>">Je n'aime pas</a> (<?= $dislikes ?>) 
</body>

</html>