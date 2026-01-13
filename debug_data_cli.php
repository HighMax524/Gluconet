<?php
require_once 'backend/db_connect.php';

echo "Current Date (PHP): " . date('Y-m-d H:i:s') . "\n";

echo "Checking 'mesureglycemie' table content:\n";
$stmt = $conn->query("SELECT * FROM mesureglycemie ORDER BY date_heure DESC LIMIT 5");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($res)) {
    echo "Table 'mesureglycemie' is EMPTY.\n";
} else {
    foreach ($res as $row) {
        print_r($row);
    }
}
?>