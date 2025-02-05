<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');


if (isset($_SESSION['id']) and !empty($_SESSION['id'])) {
    if (isset($_GET['id']) and !empty($_GET['id'])) {
        $id_message = intval(($_GET['id']));


        $msg = $bdd->prepare('SELECT * FROM  messages WHERE id = ? AND id_destinataire=?');
        $msg->execute(array($_GET['id'], $_SESSION['id']));

        $msg_nbr = $msg->rowCount();
        $m = $msg->fetch();

        $p_exp = $bdd->prepare('SELECT pseudo FROM membres WHERE id = ?');
        $p_exp->execute(array($m['id_expediteur']));
        $p_exp = $p_exp->fetch();
        $p_exp = $p_exp['pseudo'];
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Lecture du message #<?= $id_message ?></title>
        </head>

        <body>
            <a href="reception.php">Boîte de réception</a>    <a href="envoi.php?r=<?= $p_exp ?>&o=<?= urlencode($m['objet']) ?>">Répondre</a>    <a href="suppMessage.php?id=<?= $m['id'] ?>">Supprimer</a><br /><br /><br />

            <h3 align="center">Lecture du message #<?= $id_message ?></h3>
            <div align="center">
                <?php if ($msg_nbr == 0) {
                    echo "Erreur";
                } else { ?>
                    <b><?= $p_exp ?></b> vous a envoyé: <br /><br />
                    <b>Objet:</b> <?= $m['objet'] ?>
                    <br /><br />
                    <?= nl2br($m['messages']) ?><br />
                <?php } ?>
            </div>



        </body>

        </html>
<?php
        $lu = $bdd->prepare('UPDATE messages SET lu = 1 WHERE id = ?');
        $lu->execute(array($m['id']));
    }
}
?>