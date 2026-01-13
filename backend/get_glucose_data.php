<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit();
}

$id_patient = $_SESSION['user_id'];

try {
    // 1. Trouver la date la plus récente avec des données ou aujourd'hui
    $stmtDate = $conn->prepare("SELECT DATE(date_heure) as d FROM mesureglycemie WHERE id_patient = ? ORDER BY date_heure DESC LIMIT 1");
    $stmtDate->execute([$id_patient]);
    $lastDateRow = $stmtDate->fetch(PDO::FETCH_ASSOC);

    $target_date = $lastDateRow ? $lastDateRow['d'] : date('Y-m-d');
    $display_date = date('d/m/Y', strtotime($target_date));

    // 2. Récupérer les données pour cette date
    $stmt = $conn->prepare("SELECT valeur, DATE_FORMAT(date_heure, '%H:%i') as heure FROM mesureglycemie WHERE id_patient = ? AND DATE(date_heure) = ? ORDER BY date_heure ASC");
    $stmt->execute([$id_patient, $target_date]);
    $resultats_jour = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $last_glucose = "N/A";
    $max_glucose_today = "-";
    $min_glucose_today = "-";
    $chart_labels = [];
    $chart_data = [];

    if (count($resultats_jour) > 0) {
        $valeurs = array_column($resultats_jour, 'valeur');
        $last_glucose = end($valeurs);
        $max_glucose_today = max($valeurs);
        $min_glucose_today = min($valeurs);

        foreach ($resultats_jour as $row) {
            $chart_labels[] = $row['heure'];
            $chart_data[] = $row['valeur'];
        }
    }

    echo json_encode([
        'success' => true,
        'date' => $display_date,
        'current_glucose' => $last_glucose,
        'max' => $max_glucose_today,
        'min' => $min_glucose_today,
        'labels' => $chart_labels,
        'data' => $chart_data
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>