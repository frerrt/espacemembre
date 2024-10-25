<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');


if (isset($_GET['id']) and $_GET['id'] > 0) {
    $getid = intval($_GET['id']);
    $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
?>
    <html>

    <head>
        <title>Mon profil</title>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>

    <body>
        <?php

        $req = "SELECT A.nom,A.role
from administration A, membres M
WHERE A.idAdmin = M.id
AND A.role = 'administrateur'";
        $resultat = $bdd->query($req);

        foreach ($resultat as $zzz) {
            $role = $zzz['role'];
        }



        echo "
    
       

   
";
        $avatar = "avatar/user.jpg";
        // var_dump($avatar);

        if ($userinfo["pseudo"] == "afrid") {
            echo "<a class='btn btn-primary' href='administrateur.php?nom=$userinfo[pseudo]'>espace administrateur</a>";
            $logo = "<img height=35 widht=45 src='images/logoA.jpg'>";
        } else {
            echo "<a class='btn btn-primary' href='membre.php?nom=$userinfo[pseudo]'>espace membre</a>";
            $logo = "<img height=35 widht=45 src='images/member.jpg'>";
        }
        ?>
        <div align="center">
            <h2>Profil de <?php echo $userinfo['pseudo'];
                            echo "&nbsp";
                            echo $logo ?></h2>
            <br /><br />
            <?php
            if (!empty($userinfo['avatar'])) {
            ?>
            <?php
                if ($userinfo['pseudo'] == "vijay") {
                    echo "<img style='margin-left:165px;' src=$avatar  srcset='' width='150'>";
                }
            }
            ?>
            <img src="images/<?php echo $userinfo['pseudo'] ?>.jpg" alt="" srcset="" width="150">

            <br /><br />
            <?php
            if (isset($_SESSION['id']) and $_SESSION['id'] != $getid) {

                $isfollowingornot = $bdd->prepare("SELECT * FROM follow WHERE id_follower = ? AND id_following = ? ");
                $isfollowingornot->execute(array($_SESSION['id'], $getid));
                $isfollowingornot = $isfollowingornot->rowCount();
                if ($isfollowingornot = 1) {

            ?>

                    Vous suivez deja cette personne <br> <a href="follow.php?followedid=<?php echo $getid ?>">Ne plus suivre cette personne</a>
                    <br /><br />
                <?php }
            } else {

                ?>
                <a href="follow.php?followedid=<?php echo $getid ?>">Suivre cette personne</a>
                <br /><br />
            <?php
            }

            ?>

            Pseudo = <?php echo $userinfo['pseudo']; ?>
            <br />
            Mail = <?php echo $userinfo['mail']; ?>
            <br />
            <?php
            if (isset($_SESSION['id']) and $userinfo['id'] == $_SESSION['id']) {

            ?>

                <br />
                <a class="btn btn-info" href="editionprofil.php">Editer mon profil</a>
                <a class="btn btn-warning" href="reception.php">Mes messages</a>
                <a class="btn btn-danger" href="deconnexion.php">Se déconnecter</a>
                <a class="btn btn-success" href="lesarticles.php?id=<?php echo $_SESSION['id'] ?> ">mes articles </a>


            <?php
            }
            ?>
        </div>
    </body>

    </html>
<?php
} else {
    echo "erreur";
}
?>