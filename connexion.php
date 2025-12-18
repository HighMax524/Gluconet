<?php
$host = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($host, $username, $password) or die("erreur de connexion");

$namedb = "gluconet_db";
$db = mysqli_select_db($conn, $namedb) or die("erreur de connexion base");

mysqli_select_db($conn, $namedb) or die("erreur de connexion base");

$table = "utilisateur";

if (isset($_POST['email'], $_POST['mdp'])) {
    $clean_email = strip_tags($_POST['email']);
    $clean_mdp = strip_tags($_POST['mdp']);

    $email = $clean_email;
    $mdp = md5($clean_mdp);

    $requete = "SELECT * FROM $table WHERE email=? AND mdp=?";
    $reqpre = mysqli_prepare($conn, $requete);
    mysqli_stmt_bind_param($reqpre, "ss", $email, $mdp);

    mysqli_stmt_execute($reqpre);

    $result = mysqli_stmt_get_result($reqpre);

    if (mysqli_num_rows($result) === 1) {
        header('Location: track.php');
        exit();
    } else {
        header('Location: index.php?error=' . urlencode("Email ou mot de passe incorrect"));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="res/style.css">
    <title>Connexion</title>
</head>

<body>
    <?php include 'nav_bar.php'; ?>
    <div class="content_form">
        <img src="res/img_conn.png" alt="image glucometre" id="img_conn">
        <div class="form_inscr_conn_container">
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            <h1 class="titre_form">Connexion</h1>
            <form id="form_inscr" action="" method="post">
                <input type="email" id="email" name="email" placeholder="Adresse de courriel" required>

                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>

                <input type="submit" value="Se connecter" class="boutton_form">
            </form>
            <br>

            <div class="options_conn">
                <a href="">Mot de passe oublié ?</a>

                <div class="pas_de_compte">
                    <p>Pas de compte ?</p>
                    <a href="inscription.php">Inscrivez-vous</a>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include 'footer.php'; ?>

</html>