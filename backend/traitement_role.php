<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['role']) && !empty($_POST['role'])) {
        $_SESSION['role'] = $_POST['role'];
        header("Location: ../information.php");
        exit();
    } else {
        // Si aucun rôle n'est sélectionné (ce qui ne devrait pas arriver avec 'required')
        header("Location: ../role.php?error=Veuillez sélectionner un rôle");
        exit();
    }
} else {
    header("Location: ../role.php");
    exit();
}
?>