<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non authentifié']);
    exit;
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'] ?? 'patient';
$action = $_GET['action'] ?? '';

try {
    if ($action === 'get_contacts') {
        if ($userRole === 'patient') {
            $sql = "SELECT u.id, u.nom, u.prenom 
                    FROM relation_patient_medecin r
                    JOIN medecin m ON r.id_medecin = m.RPPS
                    JOIN utilisateur u ON m.Utilisateur_id = u.id
                    WHERE r.id_patient = ? AND r.statut = 'Approuve'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
        } else {
            $sql = "SELECT u.id, u.nom, u.prenom 
                    FROM relation_patient_medecin r
                    JOIN patient p ON r.id_patient = p.id_utilisateur
                    JOIN utilisateur u ON p.id_utilisateur = u.id
                    WHERE r.id_medecin = (SELECT RPPS FROM medecin WHERE Utilisateur_id = ?) 
                    AND r.statut = 'Approuve'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
        }

        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $contacts]);

    } elseif ($action === 'get_messages') {
        $contactId = $_GET['contact_id'] ?? null;

        if (!$contactId) {
            throw new Exception("ID contact manquant");
        }

        $sql = "SELECT m.*, 
                CASE WHEN m.id_emetteur = ? THEN 'sent' ELSE 'received' END as type
                FROM message m 
                WHERE (m.id_emetteur = ? AND m.id_destinataire = ?) 
                   OR (m.id_emetteur = ? AND m.id_destinataire = ?)
                ORDER BY m.date_heure ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId, $userId, $contactId, $contactId, $userId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $messages]);

    } elseif ($action === 'send_message') {
        $input = json_decode(file_get_contents('php://input'), true);
        $destinataireId = $input['destinataire_id'] ?? null;
        $contenu = $input['contenu'] ?? '';

        if (!$destinataireId || empty(trim($contenu))) {
            throw new Exception("Données invalides");
        }

        $sql = "INSERT INTO message (id_emetteur, id_destinataire, contenu, date_heure) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId, $destinataireId, $contenu]);

        echo json_encode(['status' => 'success']);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Action inconnue']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>