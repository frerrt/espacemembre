<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

$avatar = "avatar/user.jpg";
if (isset($_POST['forminscription'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mail = htmlspecialchars($_POST['mail']);
    $mail2 = htmlspecialchars($_POST['mail2']);
    $mdp = sha1($_POST['mdp']);
    $mdp2 = sha1($_POST['mdp2']);
    if (!empty($_POST['pseudo']) and !empty($_POST['mail']) and !empty($_POST['mail2']) and !empty($_POST['mdp']) and !empty($_POST['mdp2'])) {
        $pseudolength = strlen($pseudo);
        if ($pseudolength <= 255) {
            if ($mail == $mail2) {
                if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
                    $reqmail->execute(array($mail));
                    $mailexist = $reqmail->rowCount();
                    if ($mailexist == 0) {
                        if ($mdp == $mdp2) {
                            $insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, motdepasse,avatar) VALUES(?, ?, ?,?)");
                            $insertmbr->execute(array($pseudo, $mail, $mdp, $avatar));
                            $erreur = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
                        } else {
                            $erreur = "Vos mots de passes ne correspondent pas !";
                        }
                    } else {
                        $erreur = "Adresse mail déjà utilisée !";
                    }
                } else {
                    $erreur = "Votre adresse mail n'est pas valide !";
                }
            } else {
                $erreur = "Vos adresses mail ne correspondent pas !";
            }
        } else {
            $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
        }
    } else {
        $erreur = "Tous les champs doivent être complétés !";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Inscription</title>
</head>

<body>

    <?php
    include_once("header/header_InsConn.php");

    ?>
    <div align="center">
        <h2>Inscription</h2>
        <br /><br />
        <form method="POST" action="">
            <table>
                <tr>
                    <td align="right">
                        <label for="pseudo">Pseudo :</label>
                    </td>
                    <td>
                        <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if (isset($pseudo)) {
                                                                                                            echo $pseudo;
                                                                                                        } ?>" />
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mail">Mail :</label>
                    </td>
                    <td>
                        <input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if (isset($mail)) {
                                                                                                        echo $mail;
                                                                                                    } ?>" />
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mail2">Confirmation du mail :</label>
                    </td>
                    <td>
                        <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" value="<?php if (isset($mail2)) {
                                                                                                                    echo $mail2;
                                                                                                                } ?>" />
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mdp">Mot de passe :</label>
                    </td>
                    <td>
                        <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mdp2">Confirmation du mot de passe :</label>
                    </td>
                    <td>
                        <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center">
                        <br />
                        <input class="btn btn-primary" type="submit" name="forminscription" value="Je m'inscris" />
                        <a class="btn btn-info" href="connexion.php">Se connecter</a>
                    </td>
                </tr>
            </table>
        </form>
        <?php
        if (isset($erreur)) {
            echo '<font color="red">' . $erreur . "</font>";
        }
        ?>
    </div>


</body>

</html>