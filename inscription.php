<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="res/style.css">
    <title>Inscription</title>
    <?php include 'nav_bar.php'; ?>
</head>

<body>
    <div class="content_form">
        <img src="res/img_inscr.png" alt="image glucometre" id="img_inscr">
        <div class="form_inscr_conn_container">
            <h1 class="titre_form">S'inscrire</h1>
            <form id="form_inscr" action="role.php" method="post">
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