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
    <style>
        .contact-hero {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.1) 0%, rgba(129, 199, 132, 0.2) 100%);
            border-radius: 0 0 50px 50px;
            margin-bottom: 40px;
        }

        .contact-hero h1 {
            color: var(--primary-dark);
            margin-bottom: 10px;
            font-size: 2.5rem;
        }

        .contact-hero p {
            color: #455a64;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto 60px;
            padding: 0 20px;
        }

        .contact-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            flex: 1;
            min-width: 250px;
            max-width: 350px;
            transition: transform 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(46, 125, 50, 0.15);
        }

        .contact-icon {
            width: 70px;
            height: 70px;
            background: #e8f5e9;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .contact-icon span {
            font-size: 32px;
        }

        .contact-card h3 {
            color: var(--primary-dark);
            margin-bottom: 10px;
            font-size: 1.25rem;
        }

        .contact-card p,
        .contact-card a {
            color: #555;
            text-decoration: none;
            font-size: 1rem;
            line-height: 1.5;
        }

        .contact-card a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .contact-form-container {
            max-width: 800px;
            margin: 0 auto 60px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .contact-form-container h2 {
            text-align: center;
            color: var(--primary-dark);
            margin-bottom: 30px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #455a64;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-family: inherit;
            background: white;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .form-group {
                margin-bottom: 20px;
            }
        }
    </style>
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