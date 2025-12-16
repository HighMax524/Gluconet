<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gluconet – Informations supplémentaires</title>

    <!-- Police (facultatif) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="res/style.css">
</head>

<body>
    <?php include 'nav_bar.php'; ?>



    <!-- Carte principale -->
    <main class="page-wrapper">
        <section class="card">
            <h1 class="card-title">
                <span>Informations</span>
                <span>supplémentaires</span>
            </h1>

            <div class="content-grid">

                <!-- Silhouette gauche (Homme) -->
                <div class="silhouette">
                    <img src="res/icone_homme.png" class="silhouette-img" alt="image silhouette homme">
                </div>

                <!-- Formulaire central -->
                <div class="form-section">

                    <!-- Sexe -->
                    <div>
                        <p class="section-title">Sexe</p>
                        <div class="gender-choices">
                            <button class="gender-btn" data-gender="male" aria-label="Homme">♂</button>
                            <button class="gender-btn" data-gender="female" aria-label="Femme">♀</button>
                        </div>
                    </div>

                    <!-- Taille -->
                    <div class="input-group">
                        <label class="input-label" for="taille">Taille (cm)</label>
                        <input id="taille" type="number" class="text-input" placeholder="Entrez votre taille" />
                    </div>

                    <!-- Poids -->
                    <div class="input-group">
                        <label class="input-label" for="poids">Poids (kg)</label>
                        <input id="poids" type="number" class="text-input" placeholder="Entrez votre poids" />
                    </div>

                    <!-- Type diabète -->
                    <div class="diabetes-section">
                        <p class="diabetes-label">Type de diabète</p>
                        <div class="diabetes-choices">
                            <button class="diabetes-btn" data-type="1">1</button>
                            <button class="diabetes-btn" data-type="2">2</button>
                        </div>
                    </div>

                </div>

                <!-- Silhouette droite (Femme) -->
                <div class="silhouette">
                    <img src="res/icone_femme.png" class="silhouette-img" alt="image silhouette femme">
                </div>

            </div>
        </section>
    </main>

    <!-- JS -->
    <script src="res/informations.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>