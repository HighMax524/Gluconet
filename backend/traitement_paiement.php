<?php
session_start();
require_once 'db_connect.php';

// --- CONFIGURATION STRIPE ---
// Note: Pour activer le mode réel, changez $mode_paiement à 'STRIPE'.
$mode_paiement = 'STRIPE'; // 'STRIPE' ou 'SIMULATION'

// -------------------------------------------------------

if (!isset($_SESSION['user_id'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données
    $offre = htmlspecialchars(trim($_POST['offre']));
    // $nom_titulaire = htmlspecialchars(trim($_POST['nom_titulaire'])); // Optionnel si on remet l'input

    // Validation
    $errors = [];
    if (empty($offre) || !in_array($offre, ['Standard', 'Premium'])) {
        $errors[] = "Offre invalide.";
    }

    if ($mode_paiement === 'SIMULATION') {
        // --- MODE SIMULATION ---
        if (empty($errors)) {
            // Simulation d'un délai bancaire (2 secondes)
            sleep(2);

            try {
                // Succès : Mise à jour en base
                $stmt = $conn->prepare("UPDATE utilisateur SET type_abonnement = ? WHERE id = ?");
                $stmt->execute([$offre, $_SESSION['user_id']]);

                header("Location: ../profil.php?success=paiement");
                exit();
            } catch (PDOException $e) {
                $error = "Erreur technique : " . $e->getMessage();
                header("Location: ../paiement.php?error=" . urlencode($error));
                exit();
            }
        }
    } elseif ($mode_paiement === 'STRIPE') {
        // --- MODE REEL (STRIPE) ---
        // Utilisation de Stripe Checkout Session via cURL (sans librairie externe)

        if (empty($errors)) {
            // Définition du montant (en centimes)
            $montant = ($offre == 'Premium') ? 700 : 400; // 7.00€ ou 4.00€

            // URL de retour
            $domain = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            $success_url = $domain . "/traitement_paiement.php?session_id={CHECKOUT_SESSION_ID}&offre_confirme=" . $offre;
            $cancel_url = $domain . "/../paiement.php?error=AnnulationStripe";

            // Appel API Stripe
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $stripe . ':' . '');

            $post_data = [
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Abonnement ' . $offre . ' - GlucoNet',
                            ],
                            'unit_amount' => $montant,
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => $success_url,
                'cancel_url' => $cancel_url,
                'client_reference_id' => $_SESSION['user_id'], // Pour tracer
                'customer_email' => $_SESSION['user_email'] ?? null,
            ];

            // Conversion tableau multidimensionnel pour curl
            $query_str = http_build_query($post_data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query_str);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                $errors[] = 'Erreur Stripe cURL: ' . curl_error($ch);
            }
            curl_close($ch);

            $response = json_decode($result, true);

            if (isset($response['id']) && isset($response['url'])) {
                // Redirection vers la page de paiement Stripe
                header("Location: " . $response['url']);
                exit();
            } else {
                $errors[] = "Erreur création session Stripe: " . ($response['error']['message'] ?? 'Inconnue');
            }
        }
    }

    // Gestion des erreurs (commune)
    if (!empty($errors)) {
        $error = implode(" ", $errors);
        header("Location: ../paiement.php?error=" . urlencode($error));
        exit();
    }

} elseif (isset($_GET['session_id']) && isset($_GET['offre_confirme'])) {
    // --- RETOUR DE STRIPE (Confirmation) ---
    // En production, il est recommandé de vérifier l'état de la session via l'API Stripe à nouveau
    // Ici, nous considérons le retour success_url comme valide pour simplifier.

    $offre_confirme = htmlspecialchars($_GET['offre_confirme']);

    try {
        $stmt = $conn->prepare("UPDATE utilisateur SET type_abonnement = ? WHERE id = ?");
        $stmt->execute([$offre_confirme, $_SESSION['user_id']]);

        header("Location: ../profil.php?success=paiement");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur maj base : " . $e->getMessage();
        header("Location: ../paiement.php?error=" . urlencode($error));
        exit();
    }

} else {
    // Accès direct interdit
    header("Location: ../paiement.php");
    exit();
}
?>