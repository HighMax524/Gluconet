<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../inscription.php");
    exit;
}

if (empty($_POST['g-recaptcha-response'])) {
    header("Location: ../inscription.php?error=Veuillez valider le captcha");
    exit;
}

$secretKey = "6LcHyVEsAAAAADorxJ2-SecXmiv3tnUtDn32IjE7";
$response = $_POST['g-recaptcha-response'];
$remoteIp = $_SERVER['REMOTE_ADDR'];

$verify = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$remoteIp"
);

$captchaSuccess = json_decode($verify);

if (!$captchaSuccess || !$captchaSuccess->success) {
    header("Location: ../inscription.php?error=Captcha invalide");
    exit;
}

// Vérification que le formulaire a bien été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $tel = htmlspecialchars(trim($_POST['tel'])); // Le téléphone n'est pas encore stocké dans la table utilisateur
    $mdp = $_POST['mdp'];
    $conf_mdp = $_POST['conf_mdp'];

    // Validation des champs
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($mdp) && !empty($conf_mdp)) {

        // Vérification de la correspondance des mots de passe
        if ($mdp === $conf_mdp) {

            // Vérification si l'email existe déjà
            $stmt = $conn->prepare("SELECT id FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() == 0) {
                // Hachage du mot de passe
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                // Insertion dans la base de données
                // Note: La table utilisateur n'a pas de colonne 'telephone' par défaut selon le schéma fourni.
                $insertStmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, email, mdp) VALUES (?, ?, ?, ?)");

                try {
                    $insertStmt->execute([$nom, $prenom, $email, $mdp_hash]);

                    // Connexion automatique après inscription
                    $user_id = $conn->lastInsertId();
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_nom'] = $nom;
                    $_SESSION['user_prenom'] = $prenom;

                    // Redirection vers la selection du rôle
                    header("Location: ../role.php");
                    exit();

                } catch (PDOException $e) {
                    $error = "Erreur technique lors de l'inscription : " . $e->getMessage();
                    header("Location: ../inscription.php?error=" . urlencode($error));
                    exit();
                }
            } else {
                $error = "Un compte existe déjà avec cet email.";
                header("Location: ../inscription.php?error=" . urlencode($error));
                exit();
            }
        } else {
            $error = "Les mots de passe ne correspondent pas.";
            header("Location: ../inscription.php?error=" . urlencode($error));
            exit();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
        header("Location: ../inscription.php?error=" . urlencode($error));
        exit();
    }
} else {
    // Si on tente d'accéder au script sans passer par le formulaire
    header("Location: ../inscription.php");
    exit();
}
?>