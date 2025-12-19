<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide et FAQ - GlucoNet</title>
    <link rel="stylesheet" href="res/style.css">
    <!-- Google Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        .faq-hero {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.1) 0%, rgba(129, 199, 132, 0.2) 100%);
            border-radius: 0 0 50px 50px;
            margin-bottom: 40px;
        }

        .faq-hero h1 {
            color: var(--primary-dark);
            margin-bottom: 10px;
            font-size: 2.5rem;
        }

        .faq-hero p {
            color: #455a64;
            max-width: 600px;
            margin: 0 auto;
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto 60px;
            padding: 0 20px;
        }

        .faq-item {
            background: white;
            border-radius: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            box-shadow: 0 6px 15px rgba(46, 125, 50, 0.1);
            transform: translateY(-2px);
        }

        .faq-question {
            padding: 20px 25px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 1.1rem;
            user-select: none;
        }

        .faq-question .icon {
            font-size: 24px;
            transition: transform 0.3s ease;
            color: var(--primary-light);
        }

        .faq-item.active .faq-question .icon {
            transform: rotate(180deg);
            color: var(--primary-color);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background-color: #fafafa;
        }

        .faq-answer p {
            padding: 0 25px 25px;
            color: #555;
            line-height: 1.6;
        }

        .support-cta {
            text-align: center;
            background: var(--glass-bg);
            padding: 40px;
            border-radius: 20px;
            margin-top: 60px;
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
        }

        .support-cta h3 {
            color: var(--primary-dark);
            margin-bottom: 15px;
        }

        .btn-contact {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
            transition: background 0.3s;
        }

        .btn-contact:hover {
            background: var(--primary-dark);
        }
    </style>
</head>

<body>

    <?php include 'nav_bar.php'; ?>

    <div class="faq-hero">
        <h1>Centre d'Aide & FAQ</h1>
        <p>Retrouvez ici les réponses aux questions les plus fréquentes sur l'utilisation de GlucoNet.</p>
    </div>

    <div class="faq-container">

        <div class="faq-item">
            <div class="faq-question">
                Comment enregistrer mes mesures de glycémie ?
                <span class="material-symbols-outlined icon">expand_more</span>
            </div>
            <div class="faq-answer">
                <p>Pour enregistrer une nouvelle mesure, allez sur votre "Tableau de bord" et utilisez le formulaire
                    d'ajout rapide ou la section détaillée des mesures. Vous pouvez entrer la valeur, la date et
                    l'heure.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                Comment suivre l'évolution de mon poids ?
                <span class="material-symbols-outlined icon">expand_more</span>
            </div>
            <div class="faq-answer">
                <p>Rendez-vous sur votre "Profil" (en cliquant sur votre nom ou icône en haut à droite). Vous y
                    trouverez une section "Suivi du Poids" où vous pouvez ajouter de nouvelles pesées et visualiser
                    l'historique sur un graphique.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                Mes données sont-elles sécurisées ?
                <span class="material-symbols-outlined icon">expand_more</span>
            </div>
            <div class="faq-answer">
                <p>Oui, la confidentialité de vos données de santé est notre priorité. Vos informations sont stockées de
                    manière sécurisée et ne sont accessibles que par vous et les professionnels de santé que vous
                    autorisez.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                Puis-je exporter mes données pour mon médecin ?
                <span class="material-symbols-outlined icon">expand_more</span>
            </div>
            <div class="faq-answer">
                <p>Cette fonctionnalité sera bientôt disponible. Actuellement, vous pouvez montrer votre tableau de bord
                    directement à votre médecin lors de la consultation.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                Comment changer mon mot de passe ?
                <span class="material-symbols-outlined icon">expand_more</span>
            </div>
            <div class="faq-answer">
                <p>Pour modifier votre mot de passe, contactez le support technique ou utilisez la procédure de "Mot de
                    passe oublié" sur la page de connexion.</p>
            </div>
        </div>

        <!-- Section Contact -->
        <div class="support-cta">
            <h3>Vous ne trouvez pas votre réponse ?</h3>
            <p>Notre équipe de support est là pour vous aider.</p>
            <a href="mailto:support@gluconet.fr" class="btn-contact">Contacter le support</a>
        </div>

    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Script simple pour l'accordéon
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', event => {
                const parent = item.parentNode;
                const answer = parent.querySelector('.faq-answer');

                // Toggle active class
                parent.classList.toggle('active');

                // Toggle Height
                if (parent.classList.contains('active')) {
                    answer.style.maxHeight = answer.scrollHeight + "px";
                } else {
                    answer.style.maxHeight = 0;
                }

                // Close other items (optional - accordion behavior)
                document.querySelectorAll('.faq-item').forEach(otherItem => {
                    if (otherItem !== parent && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.faq-answer').style.maxHeight = 0;
                    }
                });
            });
        });
    </script>

</body>

</html>