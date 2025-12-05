<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\GlycemieModel;

class TrackController extends Controller {
    public function index() {
        $model = new GlycemieModel();
        $data = $model->getLatestData();
        $this->view('track/index', $data);
    }
}
