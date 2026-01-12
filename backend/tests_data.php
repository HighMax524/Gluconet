<?php
require_once 'db_connect.php';

echo "Installation des données de test...\n";

try {
    $conn->beginTransaction();

    // 1. Création de l'utilisateur de test
    $email = 'test@gluconet.com';
    $password = 'password';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Vérifier si l'utilisateur existe déjà
    $stmt = $conn->prepare("SELECT id FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();

    if ($existing) {
        $user_id = $existing['id'];
        echo "L'utilisateur $email existe déjà (ID: $user_id). Mise à jour du mot de passe.\n";
        $upd = $conn->prepare("UPDATE utilisateur SET mdp = ? WHERE id = ?");
        $upd->execute([$hash, $user_id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO utilisateur (email, mdp, nom, prenom, date_inscription) VALUES (?, ?, 'Test', 'Patient', NOW())");
        $stmt->execute([$email, $hash]);
        $user_id = $conn->lastInsertId();
        echo "Utilisateur créé avec ID: $user_id\n";
    }

    // 2. Profil Patient
    $stmt = $conn->prepare("INSERT INTO patient (id_utilisateur, seuil_alerte_bas, seuil_alerte_haut, type_diabete, date_diagnostic, age, sexe, taille) 
                           VALUES (?, 0.70, 1.40, 'Type 1', '2024-01-01', 30, 'Homme', 180.0) 
                           ON DUPLICATE KEY UPDATE seuil_alerte_bas=0.70, seuil_alerte_haut=1.40");
    $stmt->execute([$user_id]);
    echo "Profil patient vérifié.\n";

    // 3. Nettoyage des données du jour pour cet utilisateur (pour éviter les doublons lors de tests multiples)
    $conn->prepare("DELETE FROM mesureglycemie WHERE id_patient = ? AND DATE(date_heure) = CURDATE()")->execute([$user_id]);
    $conn->prepare("DELETE FROM repas WHERE id_patient = ? AND DATE(date_heure) = CURDATE()")->execute([$user_id]);
    $conn->prepare("DELETE FROM activites WHERE id_patient = ? AND date = CURDATE()")->execute([$user_id]);

    // 4. Insertion Mesures Glycémie (Aujourd'hui)
    $stmt = $conn->prepare("INSERT INTO mesureglycemie (id_patient, valeur, date_heure) VALUES (?, ?, ?)");

    // Génération de mesures fictives pour la journée
    $mesures = [
        ['08:00:00', 0.85],
        ['09:30:00', 1.05],
        ['11:00:00', 0.95],
        ['12:30:00', 0.90],
        ['13:45:00', 1.45], // Pic après repas
        ['15:00:00', 1.25],
        ['17:00:00', 1.00],
        ['19:00:00', 0.95],
        ['21:00:00', 1.10]
    ];

    foreach ($mesures as $m) {
        $time = $m[0];
        $val = $m[1];
        // Création datetime aujourd'hui
        $datetime = date('Y-m-d') . ' ' . $time;
        $stmt->execute([$user_id, $val, $datetime]);
    }
    echo "Mesures de glycémie insérées.\n";

    // 5. Repas
    $stmt = $conn->prepare("INSERT INTO repas (id_patient, descriptions, calories, date_heure) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, 'Petit déjeuner complet', 550, date('Y-m-d') . ' 08:30:00']);
    $stmt->execute([$user_id, 'Déjeuner Poulet/Riz', 750, date('Y-m-d') . ' 12:45:00']);
    echo "Repas insérés.\n";

    // 6. Activités
    $stmt = $conn->prepare("INSERT INTO activites (id_patient, type, date, duree) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, 'Marche', date('Y-m-d'), 30]);
    $stmt->execute([$user_id, 'Vélo', date('Y-m-d'), 45]);
    echo "Activités insérées.\n";

    $conn->commit();
    echo "Succès ! Données de test injectées pour l'utilisateur : $email / mot de passe : $password\n";

} catch (Exception $e) {
    $conn->rollBack();
    echo "Erreur : " . $e->getMessage() . "\n";
}
?>