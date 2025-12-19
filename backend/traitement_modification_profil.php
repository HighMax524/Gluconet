<?php
session_start();
require_once 'db_connect.php';

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    // Récupération et nettoyage des données
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? ''); // Optionnel : vérifier si l'email existe déjà si on change l'email

    // Champs patient
    $type_diabete = $_POST['type_diabete'] ?? null;
    $age = !empty($_POST['age']) ? intval($_POST['age']) : null;
    $sexe = $_POST['sexe'] ?? null;
    $taille = !empty($_POST['taille']) ? floatval($_POST['taille']) : null;
    $date_diagnostic = !empty($_POST['date_diagnostic']) ? $_POST['date_diagnostic'] : null;

    if (empty($nom) || empty($prenom) || empty($email)) {
        header("Location: ../profil.php?error=" . urlencode("Le nom, le prénom et l'email sont obligatoires."));
        exit();
    }

    try {
        $conn->beginTransaction();

        // 1. Mise à jour de la table utilisateur
        $stmtUser = $conn->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ? WHERE id = ?");
        $stmtUser->execute([$nom, $prenom, $email, $user_id]);

        // Mise à jour de la session pour l'affichage immédiat
        $_SESSION['user_prenom'] = $prenom;

        // 2. Mise à jour ou création dans la table patient
        // Vérifier si le patient existe déjà
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM patient WHERE id_utilisateur = ?");
        $stmtCheck->execute([$user_id]);
        $exists = $stmtCheck->fetchColumn() > 0;

        if ($exists) {
            $stmtPatient = $conn->prepare("
                UPDATE patient 
                SET type_diabete = ?, age = ?, sexe = ?, taille = ?, date_diagnostic = ? 
                WHERE id_utilisateur = ?
            ");
            $stmtPatient->execute([$type_diabete, $age, $sexe, $taille, $date_diagnostic, $user_id]);
        } else {
            // Création si n'existe pas (ne devrait pas arriver souvent si créé à l'inscription, mais par sécurité)
            $stmtPatient = $conn->prepare("
                INSERT INTO patient (id_utilisateur, type_diabete, age, sexe, taille, date_diagnostic) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmtPatient->execute([$user_id, $type_diabete, $age, $sexe, $taille, $date_diagnostic]);
        }

        $conn->commit();
        header("Location: ../profil.php?success_update=1");
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        $error = "Erreur lors de la mise à jour : " . $e->getMessage();
        header("Location: ../profil.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: ../profil.php");
    exit();
}
?>