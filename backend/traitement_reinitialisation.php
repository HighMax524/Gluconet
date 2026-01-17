<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $mdp = $_POST['mdp'];

    if (!empty($token) && !empty($mdp)) {
        // Vérifier le token et l'expiration
        $now = date("Y-m-d H:i:s");
        
        $stmt = $conn->prepare("SELECT id FROM utilisateur WHERE token_recuperation = ? AND token_expiration > ?");
        $stmt->execute([$token, $now]);
        
        if ($stmt->rowCount() > 0) {
            // Hachage du mot de passe
            $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
            
            // Mise à jour
            $update = $conn->prepare("UPDATE utilisateur SET mdp = ?, token_recuperation = NULL, token_expiration = NULL WHERE token_recuperation = ?");
            
            if ($update->execute([$mdp_hache, $token])) {
                header("Location: ../connexion.php?error=" . urlencode("Mot de passe modifié avec succès. Connectez-vous."));
                exit();
            } else {
                $error = "Erreur lors de la mise à jour.";
            }
        } else {
            $error = "Lien invalide ou expiré.";
        }
    } else {
        $error = "Veuillez remplir le formulaire.";
    }
}

if (isset($error)) {
    header("Location: ../reinitialisation_mdp.php?token=" . urlencode($token) . "&error=" . urlencode($error));
    exit();
}
?>
