<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
</head>

<body>
    <footer class="footer">
        <div class="footer-content">
            <!-- About Section -->
            <div class="footer-section">
                <h3>À propos de Gluconet</h3>
                <p>Suivez votre glycémie, analysez vos données et communiquez
                    facilement avec vos professionnels de santé.</p>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3>Liens Rapides</h3>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/home">Accueil</a></li>
                    <li><a href="<?= BASE_URL ?>/track">Tableau de bord</a></li>
                    <li><a href="<?= BASE_URL ?>/activities">Activités</a></li>
                    <li><a href="<?= BASE_URL ?>/auth/register">Inscription</a></li>
                </ul>
            </div>

            <!-- Contact/Support -->
            <div class="footer-section">
                <h3>Aide & Support</h3>
                <ul class="footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contactez-nous</a></li>
                    <li><a href="#">Mentions Légales</a></li>
                    <li><a href="#">Politique de Confidentialité</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Gluconet. Projet d'école d'ingénieur. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>
