<?php
$host = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($host, $username, $password) or die("erreur de connexion");

$namedb = "gluconet_db";
$db = mysqli_select_db($conn, $namedb) or die("erreur de connexion base");

mysqli_select_db($conn, $namedb) or die("erreur de connexion base");

$table = "utilisateur";

if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['tel'], $_POST['mdp'], $_POST['conf_mdp'])) {
    $clean_nom = strip_tags($_POST['nom']);
    $clean_prenom = strip_tags($_POST['prenom']);
    $clean_email = strip_tags($_POST['email']);
    $clean_tel = strip_tags($_POST['tel']);
    $clean_mdp = strip_tags($_POST['mdp']);
    $clean_conf_mdp = strip_tags($_POST['conf_mdp']);

    if ($clean_mdp !== $clean_conf_mdp) {
        header('Location: inscription.php?error=Les mots de passe ne correspondent pas.');
        exit();
    }

    $nom = $clean_nom;
    $prenom = $clean_prenom;
    $email = $clean_email;
    $tel = $clean_tel;
    $mdp = md5($clean_mdp);

    $requete = "INSERT INTO $table (nom, prenom, email, tel, mdp) VALUES (?, ?, ?, ?, ?)";
    $reqpre = mysqli_prepare($conn, $requete);
    mysqli_stmt_bind_param($reqpre, "sssss", $nom, $prenom, $email, $tel, $mdp);

    if (mysqli_stmt_execute($reqpre)) {
        header('Location: role.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="res/style.css">
    <title>Inscription</title>
</head>

<body>
    <?php include 'nav_bar.php'; ?>
    <div class="content_form">
        <img src="res/img_inscr.png" alt="image glucometre" id="img_inscr">
        <div class="form_inscr_conn_container">
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            <h1 class="titre_form">S'inscrire</h1>
            <form id="form_inscr" action="" method="post">
                <input type="text" id="nom" name="nom" placeholder="Nom" required>

                <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>

                <input type="email" id="email" name="email" placeholder="Adresse de courriel" required>

                <input type="tel" id="tel" name="tel" placeholder="Téléphone" required>

                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>

                <input type="password" id="conf_mdp" name="conf_mdp" placeholder="Confirmer mot de passe" required>

                <input type="submit" value="S'inscrire" class="boutton_form">

            </form>
            <div class="deja_un_compte">
                <p>Vous avez déja un compte ?</p>
                <a href="connexion.php">Connectez-vous</a>
            </div>
            <br>

        </div>

    </div>
    <?php include 'footer.php'; ?>
</body>

</html>