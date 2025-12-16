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
            <img src="res/medicaments.jpg" class="medics-img" alt="médicaments">
            <h1>Médicaments</h1>

            <div class="grid">
                <button class="caseJour">Matin</button>
                <button class="btn">Ajouter/Afficher</button>
                <button class="caseJour">Midi</button>
                <button class="btn">Ajouter/Afficher</button>
                <button class="caseJour">Soir</button>
                <button class="btn">Ajouter/Afficher</button>
            </div>

            <button class="btn">Activer un rappel</button>

        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>