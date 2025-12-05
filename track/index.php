<?php

// Inclure le contrôleur
require_once 'controllers/TrackController.php';

// Créer une instance du contrôleur et appeler la méthode pour afficher la page
$controller = new TrackController();
$controller->showDashboard();