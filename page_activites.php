<?php
session_start();

// Connexion BDD
$pdo = new PDO(
    "mysql:host=localhost;dbname=gluconet;charset=utf8",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// Récupération du poids utilisateur
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT poids FROM users WHERE id = ? AND date_heure = max(date_heure)");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$poids = $user['poids'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="res/style.css">
    <title>Activités physiques</title>
    <link href='res/logo_site.png' rel='icon'>
</head>

<body>

    <?php include 'nav_bar.php'; ?>

    <main>
        <section class="card">
            <img src="res/activites.jpeg" class="activity-img" alt="activités">
            <h1>Activités physiques</h1>

            <div class="grid">
                <button class="btn activity" data-activity="marche">Marche rapide</button>
                <button class="btn activity" data-activity="course">Course à pied</button>
                <button class="btn activity" data-activity="natation">Natation</button>
                <button class="btn activity" data-activity="velo">Vélo</button>
                <button class="btn activity" data-activity="musculation">Musculation</button>
                <button class="btn activity" data-activity="autres">Autres</button>
            </div>

            <div class="duration">
                <input type="number" id="duration" placeholder="Durée (en minutes)">
            </div>

            <button class="btn" id="calculate">Calculer mes calories brûlées</button>

            <p id="result"></p>

        </section>
    </main>

    <?php include 'footer.php'; ?>


<!-- Sécurité -->    
    <script>
        const userWeight = <?= json_encode($poids) ?>; // éviter l'injection SQL
    </script>

<!-- Javascript -->
    <script src="res/activites.js"></script>

</body>

</html>