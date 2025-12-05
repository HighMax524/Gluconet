<?php
namespace App\Controllers;
use App\Core\Controller;

class ActivityController extends Controller {
    public function index() {
        $this->view('activities/index');
    }
}
