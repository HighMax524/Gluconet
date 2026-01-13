<?php
// Vérification de l'abonnement utilisateur
// Ce fichier doit être inclus au début des pages protégées, après session_start()

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// On ne vérifie que si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {

    // Nom du script actuel pour éviter les boucles de redirection
    $current_script = basename($_SERVER['PHP_SELF']);

    // Liste des pages autorisées même sans paiement
    // paiement.php : pour payer
    // deconnexion.php : pour pouvoir sortir si bloqué
    // traitement_paiement.php : pour traiter le formulaire
    $allowed_scripts = ['paiement.php', 'traitement_paiement.php', 'deconnexion.php', 'traitement_connexion.php'];

    if (!in_array($current_script, $allowed_scripts)) {

        // Connexion BDD nécessaire
        require_once __DIR__ . '/db_connect.php';

        // On vérifie le rôle. Si c'est un patient, on vérifie l'abonnement.
        

        $user_id = $_SESSION['user_id'];
        $is_patient = false;

        // Check rôle via session si dispo, sinon BDD
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'patient') {
            $is_patient = true;
        } else {
            // Securité : check DB si session role absente
            // (Optionnel car traitement_connexion met le rôle en session, mais prudence)
            $stmtCheck = $conn->prepare("SELECT 1 FROM patient WHERE id_utilisateur = ?");
            $stmtCheck->execute([$user_id]);
            if ($stmtCheck->fetch()) {
                $is_patient = true;
                $_SESSION['role'] = 'patient';
            }
        }

        if ($is_patient) {
            // Vérification du type d'abonnement
            $stmtSub = $conn->prepare("SELECT type_abonnement FROM utilisateur WHERE id = ?");
            $stmtSub->execute([$user_id]);
            $res = $stmtSub->fetch(PDO::FETCH_ASSOC);

            // Si pas d'abonnement ou 'Gratuit' -> Redirection
            if (!$res || empty($res['type_abonnement']) || $res['type_abonnement'] === 'Gratuit') {
            

                // Détection du chemin relatif
                if (file_exists('paiement.php')) {
                    header("Location: paiement.php");
                } else {
                    header("Location: ../paiement.php");
                }
                exit();
            }
        }
    }
}
?>