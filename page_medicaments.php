<?php
require_once 'backend/check_subscription.php';

// Restriction Premium
if (!isset($_SESSION['type_abonnement']) || $_SESSION['type_abonnement'] !== 'Premium') {
    echo "<script>alert('Cette fonctionnalité est réservée aux membres Premium.'); window.location.href='abonnement.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="res/style.css">
<title>Médicaments</title>
<link href='res/logo_site.png' rel='icon'>
</head>
<body>
    <?php include 'nav_bar.php'; ?>

    <main>
        <section class="card center-card">
            <img src="res/medicaments.jpg" class="medics-img" alt="médicaments">
            <h1>Médicaments</h1>

            <!-- Formulaire centré -->
            <div class="form">
                <div class="form-row">
                    <label>Heure :
                        <input type="time" id="heure">
                    </label>
                    <label>Fréquence :
                        <select id="frequence">
                            <option value="Quotidien">Quotidien</option>
                            <option value="Hebdomadaire">Hebdomadaire</option>
                            <option value="Mensuel">Mensuel</option>
                        </select>
                    </label>

                    <label id="jourSemaineLabel" style="display:none;">
                        Jour :
                        <select id="jourSemaine">
                            <option value="Lundi">Lundi</option>
                            <option value="Mardi">Mardi</option>
                            <option value="Mercredi">Mercredi</option>
                            <option value="Jeudi">Jeudi</option>
                            <option value="Vendredi">Vendredi</option>
                            <option value="Samedi">Samedi</option>
                            <option value="Dimanche">Dimanche</option>
                        </select>
                    </label>

                    <label id="dateMensuelleLabel" style="display:none;">
                        Date :
                        <input type="number" id="dateMensuelle" min="1" max="31">
                    </label>

                    
                </div>
                <button class="btn" id="saveRappel">Enregistrer</button>
                <p id="result"></p>
            </div>

            <!-- Liste des rappels -->
            <table id="listeRappels" class="rappel-table">
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Fréquence</th>
                        <th>Jour / Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="JS/medicaments.js"></script>
</body>
</html>