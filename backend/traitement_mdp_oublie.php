<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));

    if (!empty($email)) {
        // Vérifier si l'email existe
        $stmt = $conn->prepare("SELECT id FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            // Générer un token
            $token = bin2hex(random_bytes(50));
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Sauvegarder dans la BDD
            $update = $conn->prepare("UPDATE utilisateur SET token_recuperation = ?, token_expiration = ? WHERE email = ?");
            if ($update->execute([$token, $expiry, $email])) {
                
                // --- ENVOI EMAIL DE PRODUCTION ---
                $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/Gluconet/reinitialisation_mdp.php?token=" . $token;
                
                $to = $email;
                $subject = "Réinitialisation de votre mot de passe Gluconet";
                
                // Message HTML
                $message = "
                <html>
                <head>
                    <title>Réinitialisation de mot de passe</title>
                </head>
                <body>
                    <h2>Bonjour,</h2>
                    <p>Vous avez demandé la réinitialisation de votre mot de passe sur Gluconet.</p>
                    <p>Veuillez cliquer sur le lien ci-dessous pour créer un nouveau mot de passe :</p>
                    <p><a href='" . $resetLink . "'>" . $resetLink . "</a></p>
                    <p>Ce lien est valable pendant 1 heure.</p>
                    <p>Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet email.</p>
                </body>
                </html>
                ";

                // Headers pour email HTML
                $from = $env['MAIL_FROM'] ?? 'noreply@gluconet.alwaysdata.net';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: Gluconet <" . $from . ">" . "\r\n";

                // Envoi
                if (mail($to, $subject, $message, $headers)) {
                     header("Location: ../mot_de_passe_oublie.php?msg=" . urlencode("Un email de réinitialisation a été envoyé à " . $email));
                     exit();
                } else {
                     // Fallback si l'envoi échoue (ex: localhost sans SMTP)
                     // On laisse le message d'erreur générique ou on loggue l'erreur
                     error_log("Mail sending failed for $email");
                     // En dev local, on peut vouloir garder le log fichier au cas où, mais l'utilisateur a demandé 'vrai système'
                     $error = "Echec de l'envoi de l'email. Vérifiez la configuration serveur.";
                }
            } else {
                $error = "Erreur base de données.";
            }
        } else {
            // Pour sécurité, on dit aussi que c'est envoyé même si l'email n'existe pas
            header("Location: ../mot_de_passe_oublie.php?msg=" . urlencode("Si cet email existe, un lien a été envoyé."));
            exit();
        }
    } else {
        $error = "Veuillez entrer une adresse email.";
    }
}

if (isset($error)) {
    header("Location: ../mot_de_passe_oublie.php?error=" . urlencode($error));
    exit();
}
?>
