<?php

require_once __DIR__ . '/../models/GlycemieModel.php';

class TrackController {

    public function showDashboard() {
        // Créer une instance du modèle
        $glycemieModel = new GlycemieModel();

        // Récupérer les données (pour l'instant, des données factices)
        $data = $glycemieModel->getLatestData();

        // Charger la vue et lui passer les données
        require __DIR__ . '/../views/bar_nav.html';
        require __DIR__ . '/../views/trackView.php';
    }
}