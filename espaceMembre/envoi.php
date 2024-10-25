<?php

session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
$lu = 0;

if (isset($_SESSION['id']) and !empty($_SESSION['id'])) {
    if (isset($_POST['envoi_message'])) {
        if (isset($_POST['destinataire'], $_POST['message'], $_POST['objet']) and !empty($_POST['destinataire']) and !empty($_POST['message']) and !empty($_POST['objet'])) {
            $destinataire = htmlspecialchars($_POST['destinataire']);
            $message = htmlspecialchars($_POST['message']);
            $objet = htmlspecialchars($_POST['objet']);
            $id_destinataire = $bdd->prepare('SELECT id FROM membres WHERE pseudo = ?');
            $id_destinataire->execute(array($destinataire));
            $dest_exist = $id_destinataire->rowCount();
            if ($dest_exist == 1) {
                $id_destinataire = $id_destinataire->fetch();
                $id_destinataire = $id_destinataire['id'];
                $ins = $bdd->prepare('INSERT INTO messages(id_expediteur,id_destinataire,objet,messages,lu) VALUES (?,?,?,?,?)');
                $ins->execute(array($_SESSION['id'], $id_destinataire, $objet, $message, $lu));
                $error = "Votre message a bien été envoyé !";
            } else {
                $error = "Cet utilisateur n'existe pas...";
            }
        } else {
            $error = "Veuillez compléter tous les champs";
        }
    }

    // Récupérer les destinataires (pseudos des membres)
    $destinataires = $bdd->query('SELECT pseudo FROM membres ORDER BY pseudo');
    if (isset($_GET['r']) and !empty($_GET['r'])) {
        $r = htmlspecialchars($_GET['r']);
    }
    if (isset($_GET['o']) and !empty($_GET['o'])) {
        $o = urldecode($_GET['o']);
        $o = htmlspecialchars($_GET['o']);
        if (substr($o, 0, 3) != 'RE:') {
            $o = "RE:" . $o;
        }
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Envoi de message</title>
        <meta charset="utf-8" />
    </head>

    <body>
        <form method="POST">
            <!-- Liste déroulante des destinataires -->
            <label>Destinataire :</label>
            <select name="destinataire">
                <option value="">Sélectionner un destinataire</option>
                <?php while ($d = $destinataires->fetch()) { ?>
                    <option value="<?= htmlspecialchars($d['pseudo']); ?>" <?php if (isset($r) && $r == $d['pseudo']) echo 'selected'; ?>>
                        <?= htmlspecialchars($d['pseudo']); ?>
                    </option>
                <?php } ?>
            </select>
            <br /><br />

            <label>Objet :</label>
            <input type="text" name="objet" <?php if (isset($o)) { echo 'value="' . $o . '"'; } ?> />
            <br /><br />
<!--  -->
            <textarea placeholder="Votre message" name="message"></textarea>
            <br /><br />

            <input type="submit" value="Envoyer" name="envoi_message" />
            <br /><br />

            <?php if (isset($error)) { echo '<span style="color:red">' . $error . '</span>'; } ?>
        </form>
        <br />
        <a href="reception.php">Boîte de réception</a>
    </body>

    </html>
<?php
}
?>
