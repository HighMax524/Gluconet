<?php
session_start();
require_once "backend/db_connect.php";

// ID utilisateur depuis la session
$idUtilisateur = $_SESSION['user_id'];

// Récupération du dernier poids enregistré
$stmt = $conn->prepare("
    SELECT poids
    FROM poids
    WHERE id_utilisateur = ?
    ORDER BY date_heure DESC
    LIMIT 1
");

$stmt->execute([$idUtilisateur]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Sécurité si aucun poids trouvé
$poidsUtilisateur = $data ? $data['poids'] : null;
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

            <div class="calcul-btn">
                <button class="btn" id="calculate">Calculer mes calories brûlées</button>
            
                <!-- Bouton Apple Health (simulation) -->
                <button class="btn apple-health-btn" disabled>
                    Calcul automatique via Apple Health (bientôt disponible) 
                </button>
            </div>


            <!-- Résultat -->

            <p id="result"></p>

        </section>
    </main>

    <?php include 'footer.php'; ?>


<!-- Sécurité -->    
    <script>
        const userWeight = <?= json_encode($poidsUtilisateur) ?>; // éviter l'injection SQL
    </script>

<!-- Javascript -->
    <script src="res/activites.js"></script>

</body>

</html>
