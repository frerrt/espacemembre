<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if (isset($_POST['formconnexion'])) {
    $mailconnect = htmlspecialchars($_POST['mailconnect']);
    $mdpconnect = sha1($_POST['mdpconnect']);
    if (!empty($mailconnect) and !empty($mdpconnect)) {
        $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motdepasse = ?");
        $requser->execute(array($mailconnect, $mdpconnect));
        $userexist = $requser->rowCount();
        if ($userexist == 1) {
            $userinfo = $requser->fetch();
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['pseudo'] = $userinfo['pseudo'];
            $_SESSION['mail'] = $userinfo['mail'];
            header("Location: profil.php?id=" . $_SESSION['id']);
        } else {
            $erreur = "Mauvais mail ou mot de passe !";
        }
    } else {
        $erreur = "Tous les champs doivent être complétés !";
    }
}
?>
<html>

<head>
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta charset="utf-8">

</head>

<body>
    <?php
    include_once("header/header_InsConn.php");

    ?>

    <div align="center">
        <h2>Connexion</h2>
        <br /><br />
        <form method="POST" action="">
            <input type="email" name="mailconnect" placeholder="Mail" />
            <input type="password" name="mdpconnect" placeholder="Mot de passe" />
            <br /><br />

            <input class="btn btn-primary" type="submit" name="formconnexion" value="Se connecter !" />
            <a class="btn btn-info" href="inscription.php">S'inscrire</a>
        </form>
        <?php
        if (isset($erreur)) {
            echo '<font color="red">' . $erreur . "</font>";
        }
        ?>
    </div>

</body>

</html>