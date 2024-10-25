<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if (isset($_GET['type']) and $_GET['type'] == 'membre') {
    if (isset($_GET['confirme']) and !empty($_GET['confirme'])) {
        $confirme = (int) $_GET['confirme'];
        $req = $bdd->prepare('UPDATE managers SET confirme = 1 WHERE id = ?');
        $req->execute(array($confirme));
    }
    if (isset($_GET['supprime']) and !empty($_GET['supprime'])) {
        $supprime = (int) $_GET['supprime'];
        $req = $bdd->prepare('DELETE FROM managers WHERE id = ?');
        $req->execute(array($supprime));
    }
} elseif (isset($_GET['type']) and $_GET['type'] == 'commentaire') {
    if (isset($_GET['approuve']) and !empty($_GET['approuve'])) {
        $approuve = (int) $_GET['approuve'];
        $req = $bdd->prepare('UPDATE commentaires SET approuve = 1 WHERE id = ?');
        $req->execute(array($approuve));
    }
    if (isset($_GET['supprime']) and !empty($_GET['supprime'])) {
        $supprime = (int) $_GET['supprime'];
        $req = $bdd->prepare('DELETE FROM commentaires WHERE id = ?');
        $req->execute(array($supprime));
    }
}

$membres = $bdd->query('SELECT * FROM managers ORDER BY id ASC LIMIT 0,5');
$commentaires = $bdd->query('SELECT * FROM managers ORDER BY id ASC LIMIT 0,5');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>espace commentaire</title>
</head>

<body>
    <ul>
        <?php while ($m = $membres->fetch()) { ?>
            <li><?= $m['id'] ?> : <?= $m['membres'] ?><?php if ($m['confirme'] == 0) { ?> - <a href="espaceCommentaire.php?type=membre&confirme=<?= $m['id'] ?>">Confirmer</a><?php } ?> - <a href="espaceCommentaire.php?type=membre&supprime=<?= $m['id'] ?>">Supprimer</a></li>
        <?php } ?>
    </ul>
    <ul>
        <?php while ($c = $commentaires->fetch()) { ?>
            <li><?= $c['id'] ?> : <?= $c['commentaires'] ?></li>
        <?php } ?>
    </ul>
</body>

</html>