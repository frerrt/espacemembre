<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if (isset($_GET['followedid']) and !empty($_GET['followedid'])) {
    $getfollowedid = intval($_GET['followedid']);

    if ($getfollowedid != $_SESSION['id']) {
        $dejafollowed = $bdd->prepare("Select * From follow where id_follower = ? AND id_following = ?");
        $dejafollowed->execute(array($_SESSION['id'], $getfollowedid));

        $dejafollowed = $dejafollowed->rowCount();

        if ($dejafollowed == 0) {
            $addfollow = $bdd->prepare('INSERT INTO follow(id_follower,id_following) VALUES(?,?)');
            $addfollow->execute(array($_SESSION['id'], $getfollowedid));
        } elseif ($dejafollowed == 1) {
            $deletefollow = $bdd->prepare('DELETE FROM  follow WHERE id_follower = ? AND id_following = ?');
            $deletefollow->execute(array($_SESSION['id'], $getfollowedid));
        }
    }
}
header("Location:" . $_SERVER['HTTP_REFERER']);
