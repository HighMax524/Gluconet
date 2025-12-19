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