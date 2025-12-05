<?php
namespace App\Controllers;
use App\Core\Controller;

class PaymentController extends Controller {
    public function index() {
        $this->view('payment/index');
    }
}
