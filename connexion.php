<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="res/style.css">
    <link href='res/logo_site.png' rel='icon'>
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
            <form id="form_inscr" action="backend/traitement_connexion.php" method="post">
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