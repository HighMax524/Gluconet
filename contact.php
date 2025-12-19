<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - GlucoNet</title>
    <link rel="stylesheet" href="res/style.css">
    <!-- Google Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>

    <?php include 'nav_bar.php'; ?>

    <div class="contact-hero">
        <h1>Contactez-nous</h1>
        <p>Une question ? Un problème ? Notre équipe est à votre écoute.</p>
    </div>

    <div class="contact-cards">
        <!-- Adresse -->
        <div class="contact-card">
            <div class="contact-icon">
                <span class="material-symbols-outlined">location_on</span>
            </div>
            <h3>Notre Adresse</h3>
            <p>GlucoNet France<br>12 Avenue de la Santé<br>75000 Paris</p>
        </div>

        <!-- Téléphone -->
        <div class="contact-card">
            <div class="contact-icon">
                <span class="material-symbols-outlined">call</span>
            </div>
            <h3>Par Téléphone</h3>
            <p>Du lundi au vendredi, 9h-18h</p>
            <a href="tel:+33123456789" style="font-weight: bold; font-size: 1.1rem;">01 23 45 67 89</a>
        </div>

        <!-- Email -->
        <div class="contact-card">
            <div class="contact-icon">
                <span class="material-symbols-outlined">mail</span>
            </div>
            <h3>Par Email</h3>
            <p>Réponse sous 24h</p>
            <a href="mailto:contact@gluconet.fr" style="font-weight: bold; font-size: 1.1rem;">contact@gluconet.fr</a>
        </div>
    </div>

    <!-- Formulaire de contact -->
    <div class="contact-form-container">
        <h2>Envoyez-nous un message</h2>
        <form action="#" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required placeholder="Votre nom">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="votre@email.com">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="sujet">Sujet</label>
                <input type="text" id="sujet" name="sujet" required placeholder="L'objet de votre demande">
            </div>
            <div class="form-group" style="margin-bottom: 30px;">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required
                    placeholder="Comment pouvons-nous vous aider ?"></textarea>
            </div>
            <button type="submit" class="btn-submit">Envoyer le message</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>