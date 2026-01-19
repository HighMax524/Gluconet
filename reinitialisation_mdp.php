<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/sign.css">
    <link href='res/logo_site.png' rel='icon'>
    <title>Réinitialisation du mot de passe</title>
</head>
<body>
    <?php include 'nav_bar.php'; ?>
    <div class="content_form">
        <img src="res/img_conn.png" alt="image glucometre" id="img_conn">
        <div class="form_inscr_conn_container">
            <h1 class="titre_form">Nouveau mot de passe</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php 
            $token = $_GET['token'] ?? '';
            if (empty($token)) {
                echo '<p style="color:red; text-align:center;">Lien invalide ou expiré.</p>';
            } else {
            ?>
            <form id="form_inscr" action="backend/traitement_reinitialisation.php" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="password-field">
                    <input type="password" id="mdp" name="mdp" placeholder="Nouveau mot de passe" required>
                </div>
                
                <input type="submit" value="Changer le mot de passe" class="boutton_form">
            </form>
            <?php } ?>
            
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
