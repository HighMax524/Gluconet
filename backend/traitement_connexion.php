<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $mdp = $_POST['mdp'];

    if (!empty($email) && !empty($mdp)) {
        try {
            // Récupération de l'utilisateur
            // On récupère aussi 'id', 'nom', 'prenom', 'mdp'
            $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($mdp, $user['mdp'])) {
                // Mot de passe correct
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];

                // Vérifier si c'est un patient ou un médecin pour stocker le rôle en session si besoin
                // (Optionnel mais pratique pour la suite)
                $stmtPatient = $conn->prepare("SELECT id_utilisateur FROM patient WHERE id_utilisateur = ?");
                $stmtPatient->execute([$user['id']]);
                if ($stmtPatient->rowCount() > 0) {
                    $_SESSION['role'] = 'patient';
                } else {
                    $stmtMedecin = $conn->prepare("SELECT Utilisateur_id FROM medecin WHERE Utilisateur_id = ?");
                    $stmtMedecin->execute([$user['id']]);
                    if ($stmtMedecin->rowCount() > 0) {
                        $_SESSION['role'] = 'medecin';
                    }
                }

                header("Location: ../track.php");
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
                header("Location: ../connexion.php?error=" . urlencode($error));
            }
        } catch (PDOException $e) {
            $error = "Erreur technique : " . $e->getMessage();
            header("Location: ../connexion.php?error=" . urlencode($error));
            exit();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
        header("Location: ../connexion.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: ../connexion.php");
    exit();
}
?>