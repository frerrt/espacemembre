<?php
session_start();

// Connexion à la base de données
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

// Inclusion du fichier d'en-tête
include_once("header/header_accueil.php");

if (isset($_POST['pseudo']) and isset($_POST['message']) and !empty($_POST['pseudo']) and !empty($_POST['message'])) {
    $pseudo =  htmlspecialchars($_POST['pseudo']);
    $message = htmlspecialchars($_POST['message']);

    // Insertion du message dans la base de données
    $insermsg = $bdd->prepare('INSERT INTO chat(pseudo,messages) VALUES(?,?)');
    $insermsg->execute(array($pseudo, $message));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tchatting</title>
</head>

<body>
    <br><br>
    <center>
        <form method="post" action="">
            <select name="pseudo" id="pseudo">
                <option value="">Sélectionner un pseudo</option>
                <?php
                // Récupération des pseudonymes de la table chat
                $allPseudos = $bdd->query('SELECT DISTINCT pseudo FROM chat ORDER BY pseudo ASC');
                while ($pseudo = $allPseudos->fetch()) {
                    echo '<option value="' . htmlspecialchars($pseudo['pseudo']) . '">' . htmlspecialchars($pseudo['pseudo']) . '</option>';
                }
                ?>
            </select><br><br>
            <textarea name="message" id="message" placeholder="message"></textarea><br><br>
            <input type="submit" value="Envoyer">
        </form>

        <?php
        // Récupération des 5 derniers messages
        $allmsg = $bdd->query("SELECT * FROM chat ORDER BY id DESC LIMIT 0,5");
        while ($msg = $allmsg->fetch()) {
            // Calcul du "grade" en fonction du nombre de messages envoyés
            $grade_req = $bdd->prepare("SELECT COUNT(*) AS message_count FROM chat WHERE pseudo = ?");
            $grade_req->execute(array($msg['pseudo']));
            $message_count = $grade_req->fetch()['message_count'];

            if ($message_count > 0 && $message_count < 10) {
                $grade = "membre junior";
            } else if ($message_count >= 10 && $message_count < 50) {
                $grade = "membre avancé";
            } else {
                $grade = "membre expert";
            }

            echo "<br>";
        ?>
            <b><?php echo htmlspecialchars($msg['pseudo']); ?></b>
            <?php echo '(', $grade, ')' ?>:
            <?php echo htmlspecialchars($msg['messages']) ?> <br />
        <?php
        }
        ?>
    </center>

</body>

</html>
