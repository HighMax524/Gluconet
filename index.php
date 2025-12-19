<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/style.css">
    <title>Gluconet</title>
</head>

<body>
    <?php
    session_start();
    include 'nav_bar.php';
    ?>
    <div class="container">
        <?php if (isset($_SESSION['user_prenom'])): ?>
            <h1>Bonjour M.<?php echo htmlspecialchars($_SESSION['user_prenom']); ?>, faites place à autre chose!</h1>
        <?php else: ?>
            <h1>Faites place à autre chose!</h1>
        <?php endif; ?>

        <div class="images-section">

            <!-- Image gauche -->
            <div class="image-block">
                <img src="res/avant_glu.png" alt="Avant">
                <div class="overlay">Avant Gluconet</div>
            </div>

            <div class="arrow">→</div>

            <!-- Image droite -->
            <div class="image-block">
                <img src="res/apres_glu.png" alt="Après">
                <div class="overlay">Après Gluconet</div>
            </div>

        </div>

        <a href="track.php" class="welcome-btn">Bienvenue →</a>

    </div>
    <?php include 'footer.php'; ?>

</body>

</html>