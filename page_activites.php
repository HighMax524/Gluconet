<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="res/style.css">
    <title>Activités physiques</title>
    <?php include 'nav_bar.php'; ?>
</head>

<body>

    <main>
        <section class="card">
            <img src="res/activites.jpeg" class="activity-img" alt="activités">
            <h1>Activités physiques</h1>

            <div class="grid">
                <button class="btn">Marche rapide</button>
                <button class="btn">Course à pied</button>
                <button class="btn">Natation</button>
                <button class="btn">Vélo</button>
                <button class="btn">Musculation</button>
                <button class="btn">Autres</button>
            </div>

            <div class="duration">
                <input type="text" placeholder="Durée">
            </div>

            <button class="btn">Calculer mes calories brûlées</button>

        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>