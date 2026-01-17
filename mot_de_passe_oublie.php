<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/sign.css">
    <link href='res/logo_site.png' rel='icon'>
    <title>Mot de passe oublié</title>
</head>
<body>
    <?php include 'nav_bar.php'; ?>
    <div class="content_form">
        <img src="res/img_conn.png" alt="image glucometre" id="img_conn">
        <div class="form_inscr_conn_container">
            <h1 class="titre_form">Récupération</h1>
            
            <?php if (isset($_GET['msg'])): ?>
                <div style="color: green; text-align: center; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <p style="text-align:center; padding: 0 20px;">
                Entrez votre adresse email pour recevoir un lien de réinitialisation.
            </p>

            <form id="form_inscr" action="backend/traitement_mdp_oublie.php" method="post">
                <input type="email" id="email" name="email" placeholder="Votre adresse email" required>
                <input type="submit" value="Envoyer le lien" class="boutton_form">
            </form>
            
            <div class="options_conn">
                <div class="pas_de_compte">
                    <a href="connexion.php">Retour à la connexion</a>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
