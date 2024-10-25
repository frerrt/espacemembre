<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
if (isset($_GET['id']) and !empty($_GET['id'])) {
    $suppr_id = htmlspecialchars($_GET['id']);
    $suppr = $bdd->prepare('DELETE FROM article WHERE id = ?');
    $suppr->execute(array($suppr_id));
    header('Location: http://127.0.0.1/espacemembre/lesarticles.php');
}
