<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="res/style.css">
    <script src="res/activites.js" defer></script>
    <title>Activités physiques</title>
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
</body>

</html>