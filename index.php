<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gluconet - Accueil</title>
    <!-- Fonts and Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- New Premium Styles -->
    <link rel="stylesheet" href="res/style.css">
    <?php include 'nav_bar.php'; ?>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Maîtrisez votre diabète<br>avec Gluconet</h1>
        <p>Une solution intelligente pour le suivi en temps réel de votre glycémie. Visualisez vos données, partagez-les
            avec votre médecin et vivez plus sereinement.</p>

        <div class="hero-actions">
            <a href="connexion.html" class="btn btn-primary">Se connecter</a>
            <a href="inscription.html" class="btn btn-secondary">Créer un compte</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="feature-card">
            <span class="material-symbols-outlined feature-icon">monitoring</span>
            <h3>Suivi Temps Réel</h3>
            <p>Enregistrez vos taux de glucose facilement et visualisez l'évolution sur des graphiques interactifs et
                clairs.</p>
        </div>

        <div class="feature-card">
            <span class="material-symbols-outlined feature-icon">medical_services</span>
            <h3>Lien Médical</h3>
            <p>Partagez automatiquement vos rapports avec votre professionnel de santé pour un suivi personnalisé.</p>
        </div>

        <div class="feature-card">
            <span class="material-symbols-outlined feature-icon">fitness_center</span>
            <h3>Activités & Santé</h3>
            <p>Corrélez votre glycémie avec vos activités physiques et votre alimentation pour mieux comprendre votre
                corps.</p>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>