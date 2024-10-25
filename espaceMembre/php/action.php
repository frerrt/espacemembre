<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if (isset($_GET['t'], $_GET['id']) and !empty($_GET['t']) and !empty($_GET['id'])) {
    $getid = (int) $_GET['id'];
    $gett = (int) $_GET['t'];
    $sessionid = 6;
    $check = $bdd->prepare('SELECT id FROM article WHERE id = ?');
    $check->execute(array($getid));
    if ($check->rowCount() == 1) {
        if ($gett == 1) {
            $check_like = $bdd->prepare('SELECT id FROM likes WHERE id_article = ? AND id_membre = ?');
            $check_like->execute(array($getid, $sessionid));
            $del = $bdd->prepare('DELETE FROM dislikes WHERE id_article = ? AND id_membre = ?');
            $del->execute(array($getid, $sessionid));
            if ($check_like->rowCount() == 1) {
                $del = $bdd->prepare('DELETE FROM likes WHERE id_article = ? AND id_membre = ?');
                $del->execute(array($getid, $sessionid));
            } else {
                $ins = $bdd->prepare('INSERT INTO likes (id_article, id_membre) VALUES (?, ?)');
                $ins->execute(array($getid, $sessionid));
            }
        } elseif ($gett == 2) {
            $check_like = $bdd->prepare('SELECT id FROM dislikes WHERE id_article = ? AND id_membre = ?');
            $check_like->execute(array($getid, $sessionid));
            $del = $bdd->prepare('DELETE FROM likes WHERE id_article = ? AND id_membre = ?');
            $del->execute(array($getid, $sessionid));
            if ($check_like->rowCount() == 1) {
                $del = $bdd->prepare('DELETE FROM dislikes WHERE id_article = ? AND id_membre = ?');
                $del->execute(array($getid, $sessionid));
            } else {
                $ins = $bdd->prepare('INSERT INTO dislikes (id_article, id_membre) VALUES (?, ?)');
                $ins->execute(array($getid, $sessionid));
            }
        }
        header('Location: http://127.0.0.1/espacemembre/article.php?id=' . $getid);
    } else {
        exit('Erreur fatale. <a href="http://127.0.0.1/espacemembre/">Revenire à l\'accueil</a>');
    }
} else {
    exit('Erreur fatale. <a href="http://127.0.0.1/espacemembre/">Revenir à l\'accueil</a>');
}
