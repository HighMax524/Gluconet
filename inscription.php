<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/sign.css">
    <title>Inscription</title>
    <link href='res/logo_site.png' rel='icon'>
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
            <form id="form_inscr" action="backend/traitement_inscription.php" method="post">
                <input type="text" id="nom" name="nom" placeholder="Nom" required>

                <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>

                <input type="email" id="email" name="email" placeholder="Adresse de courriel" required>

                <input type="tel" id="tel" name="tel" placeholder="Téléphone" required>

                <div class="password-field">
                    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
                    <span class="afficheMdp" onclick="afficheMdp('mdp', this)">
                        <img src="res/oeil.png" alt="icone oeil">
                    </span>
                </div>

                <div class="password-field">
                    <input type="password" id="conf_mdp" name="conf_mdp" placeholder="Confirmer mot de passe" required>
                    <span class="afficheMdp" onclick="afficheMdp('conf_mdp', this)">
                        <img src="res/oeil.png" alt="icone oeil">
                    </span>
                </div>

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

<script src="JS/connexion.js"></script>

</html>