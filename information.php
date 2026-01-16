<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gluconet – Informations supplémentaires</title>

    <!-- Police (facultatif) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="res/main.css">
    <link rel="stylesheet" href="style/info_patient.css">
    <link href='res/logo_site.png' rel='icon'>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: role.php");
        exit();
    }
    $role = $_SESSION['role'];
    ?>
    <?php include 'nav_bar.php'; ?>

    <!-- Carte principale -->
    <main class="page-wrapper">
        <section class="card">
            <h1 class="card-title">
                <span>Informations</span>
                <span>supplémentaires</span>
            </h1>

            <div class="content-grid">

                <?php if ($role === 'patient'): ?>
                    <!-- Silhouette gauche (Homme) -->
                    <div class="silhouette">
                        <img src="res/icone_homme.png" class="silhouette-img" alt="image silhouette homme">
                    </div>

                    <!-- Formulaire Patient -->
                    <div class="form-section">
                        <form action="backend/traitement_information.php" method="POST">
                            <input type="hidden" name="role_form" value="patient">

                            <!-- Sexe -->
                            <div>
                                <p class="section-title">Sexe</p>
                                <input type="hidden" id="sexe" name="sexe" required>
                                <div class="gender-choices">
                                    <button type="button" class="gender-btn" data-gender="Homme"
                                        aria-label="Homme">♂</button>
                                    <button type="button" class="gender-btn" data-gender="Femme"
                                        aria-label="Femme">♀</button>
                                </div>
                            </div>

                            <!-- Age -->
                            <div class="input-group">
                                <label class="input-label" for="age">Age</label>
                                <input id="age" name="age" type="number" class="text-input" placeholder="Votre âge"
                                    required />
                            </div>

                            <!-- Taille -->
                            <div class="input-group">
                                <label class="input-label" for="taille">Taille (cm)</label>
                                <input id="taille" name="taille" type="number" step="0.1" class="text-input"
                                    placeholder="Entrez votre taille" required />
                            </div>

                            <!-- Poids -->
                            <div class="input-group">
                                <label class="input-label" for="poids">Poids (kg)</label>
                                <input id="poids" name="poids" type="number" step="0.1" class="text-input"
                                    placeholder="Entrez votre poids" required />
                            </div>

                            <!-- Date Diagnostic -->
                            <div class="input-group">
                                <label class="input-label" for="date_diagnostic">Date de diagnostic</label>
                                <input id="date_diagnostic" name="date_diagnostic" type="date" class="text-input"
                                    required />
                            </div>

                            <!-- Type diabète -->
                            <div class="diabetes-section">
                                <p class="diabetes-label">Type de diabète</p>
                                <input type="hidden" id="type_diabete" name="type_diabete" required>
                                <div class="diabetes-choices">
                                    <button type="button" class="diabetes-btn" data-type="Type 1">1</button>
                                    <button type="button" class="diabetes-btn" data-type="Type 2">2</button>
                                </div>
                            </div>

                            <div style="margin-top: 20px;">
                                <button type="submit" class="boutton_form" style="width:100%;">Valider</button>
                            </div>
                        </form>
                    </div>

                    <!-- Silhouette droite (Femme) -->
                    <div class="silhouette">
                        <img src="res/icone_femme.png" class="silhouette-img" alt="image silhouette femme">
                    </div>

                <?php elseif ($role === 'medecin'): ?>
                    <!-- Formulaire Medecin -->
                    <div class="silhouette">
                        <!-- Placeholder ou image spécifique médecin si dispo -->
                        <img src="res/medecin.png" class="silhouette-img" alt="image medecin" style="max-height: 200px;">
                    </div>

                    <div class="form-section">
                        <form action="backend/traitement_information.php" method="POST">
                            <input type="hidden" name="role_form" value="medecin">

                            <!-- RPPS -->
                            <div class="input-group">
                                <label class="input-label" for="rpps">Numéro RPPS</label>
                                <input id="rpps" name="rpps" type="text" class="text-input" placeholder="Votre numéro RPPS"
                                    required />
                            </div>

                            <!-- Etablissement -->
                            <div class="input-group">
                                <label class="input-label" for="etablissement">Etablissement</label>
                                <input id="etablissement" name="etablissement" type="text" class="text-input"
                                    placeholder="Nom de l'établissement" required />
                            </div>

                            <!-- Adresse Pro -->
                            <div class="input-group">
                                <label class="input-label" for="adresse_pro">Adresse Professionnelle</label>
                                <input id="adresse_pro" name="adresse_pro" type="text" class="text-input"
                                    placeholder="Adresse complète" required />
                            </div>

                            <!-- Téléphone Pro -->
                            <div class="input-group">
                                <label class="input-label" for="telephone_pro">Téléphone Professionnel</label>
                                <input id="telephone_pro" name="telephone_pro" type="tel" class="text-input"
                                    placeholder="Téléphone" required />
                            </div>

                            <!-- Spécialité (Simplifié pour l'exemple, pourrait être une liste) -->
                            <!-- Note: Le schéma a une table medecin_specialite, on simplifie ici ou on ajoute un select -->

                            <div style="margin-top: 20px;">
                                <button type="submit" class="boutton_form" style="width:100%;">Valider</button>
                            </div>
                        </form>
                    </div>
                    <div class="silhouette"></div>
                <?php endif; ?>

            </div>
        </section>
    </main>

    <script src="JS/informations.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>