<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if (isset($_SESSION['id'])) {
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();
    if (isset($_POST['newpseudo']) and !empty($_POST['newpseudo']) and $_POST['newpseudo'] != $user['pseudo']) {
        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        $insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
        $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
        header('Location: profil.php?id=' . $_SESSION['id']);
    }
    if (isset($_POST['newmail']) and !empty($_POST['newmail']) and $_POST['newmail'] != $user['mail']) {
        $newmail = htmlspecialchars($_POST['newmail']);
        $insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
        $insertmail->execute(array($newmail, $_SESSION['id']));
        header('Location: profil.php?id=' . $_SESSION['id']);
    }
    if (isset($_POST['newmdp1']) and !empty($_POST['newmdp1']) and isset($_POST['newmdp2']) and !empty($_POST['newmdp2'])) {
        $mdp1 = sha1($_POST['newmdp1']);
        $mdp2 = sha1($_POST['newmdp2']);
        if ($mdp1 == $mdp2) {
            $insertmdp = $bdd->prepare("UPDATE membres SET motdepasse = ? WHERE id = ?");
            $insertmdp->execute(array($mdp1, $_SESSION['id']));
            header('Location: profil.php?id=' . $_SESSION['id']);
        } else {
            $msg = "Vos deux mdp ne correspondent pas !";
        }
    }


    if (isset($_FILES['avatar']) and !empty($_FILES['avatar']['name'])) {
        $tailleMax = 2097152;
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
        if ($_FILES['avatar']['size'] <= $tailleMax) {
            $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
            if (in_array($extensionUpload, $extensionsValides)) {
                $chemin = "membres/avatars/" . $_SESSION['id'] . "." . $extensionUpload;
                $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                if ($resultat) {
                    $updateavatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
                    $updateavatar->execute(array(
                        'avatar' => $_SESSION['id'] . "." . $extensionUpload,
                        'id' => $_SESSION['id']
                    ));
                    header('Location: profil.php?id=' . $_SESSION['id']);
                } else {
                    $msg = "Erreur durant l'importation de votre photo de profil";
                }
            } else {
                $msg = "Votre photo de profil doit être au format jpg, jpeg, gif ou png";
            }
        } else {
            $msg = "Votre photo de profil ne doit pas dépasser 2Mo";
        }
    }
?>
    <html>

    <head>
        <title>Edition du profil</title>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>

    <body>
        <div align="center">
            <h2>Edition de mon profil</h2>
            <div align="left">
                <form method="POST" action="" enctype="multipart/form-data">
                    <label>Pseudo :</label>
                    <input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo']; ?>" /><br /><br />
                    <label>Mail :</label>
                    <input type="text" name="newmail" placeholder="Mail" value="<?php echo $user['mail']; ?>" /><br /><br />
                    <label>Mot de passe :</label>
                    <input type="password" name="newmdp1" placeholder="Mot de passe" /><br /><br />
                    <label>Confirmation - mot de passe :</label>
                    <input type="password" name="newmdp2" placeholder="Confirmation du mot de passe" /><br /><br />
                    <label>Avatar : </label>
                    <input type="file" name="avatar"><br /><br />
                    <input class="btn btn-info" type="submit" value="Mettre à jour mon profil !" />

                </form>
                <?php if (isset($msg)) {
                    echo $msg;
                } ?>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    header("Location: connexion.php");
}
?>