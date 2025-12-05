<?php
namespace App\Models;


class GlycemieModel {

    /**
     * Récupère les dernières données de glycémie.
     * Dans une vraie application, cette méthode interrogerait la base de données.
     */
    public function getLatestData() {
        // Données factices pour l'exemple
        return [
            'current_glycemie' => 115,
            'unit' => 'mg/dL',
            'peak' => 140,
            'average' => 110,
            'calories_in' => 800,
            'calories_out' => 350,
            'alert_settings' => ['low' => 70, 'high' => 180],
            'chart_data' => [100, 110, 105, 120, 115, 140, 130] // Données pour le graphique
        ];
    }
}