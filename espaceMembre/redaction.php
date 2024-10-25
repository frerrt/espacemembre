<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
$mode_edition = 0;



if (isset($_SESSION['id']) and !empty($_SESSION['id'])) {
    if (isset($_GET['edit']) and !empty($_GET['edit'])) {
        $mode_edition = 1;
        $edit_id = htmlspecialchars($_GET['edit']);

        $edit_article = $bdd->prepare('SELECT * FROM article WHERE id = ?');
        $edit_article->execute(array($edit_id));

        if ($edit_article->rowCount() >= 1) {
            $edit_article = $edit_article->fetch();
        } else {
            die('Erreur : l\'article n\'existe pas...');
        }




        if (isset($_POST['article_titre'], $_POST['article_contenu']) and !empty($_POST['article_titre']) and !empty($_POST['article_contenu'])) {
            //echo "ok";
            echo $_POST['article_titre'];
            echo $_POST['article_contenu'];
            $article_titre = htmlspecialchars($_POST['article_titre']);
            $article_contenu = htmlspecialchars($_POST['article_contenu']);
            // $miniature = htmlspecialchars($_POST['miniatures']);
            // echo "ok";
            if ($mode_edition == 0) {






                $message = 'Votre article a bien été posté';
            } else {
                echo "mode edition = 1";
                $update = $bdd->prepare('UPDATE article SET titre = ?, contenu = ?, date_time_edition = NOW(),images WHERE id = ?');
                $update->execute(array($article_titre, $article_contenu, $edit_id, $miniature));
                header('Location: http://127.0.0.1/espacemembre/article.php?id=' . $edit_id);

                $message = 'Votre article a bien été mis à jour !';
            }
        } else {
            $message = 'Veuillez remplir tous les champs';
        }
    }
?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Rédaction / Edition</title>
        <meta charset="utf-8">
    </head>

    <body>
        <form method="POST" action="ajout_ok.php" enctype="multipart/form-data">
            <input type="text" name="article_titre" placeholder="Titre" <?php if ($mode_edition == 1) { ?> value="<?= $edit_article['titre'] ?>" <?php } ?> /><br />
            <textarea name="article_contenu" placeholder="Contenu de l'article"><?php if ($mode_edition == 1) { ?><?= $edit_article['contenu'] ?><?php } ?></textarea><br />
            <?php if ($mode_edition == 0) { ?>
                <input type="file" name="image" /><br />
            <?php } ?>

            <input type="submit" value="Envoyer l'article" />
        </form>
        <br />
        <?php if (isset($message)) {
            echo $message;
        } ?>
        <a href="lesarticles.php?id=<?php echo  $edit_id ?>">Retour</a>&nbsp;&nbsp;&nbsp;&nbsp;
    </body>

    </html>
<?php
} else {
    echo "non";
}
?>