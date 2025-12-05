<?php
namespace App\Controllers;
use App\Core\Controller;

class AuthController extends Controller {
    public function login() {
        $this->view('auth/login');
    }

    public function register() {
        $this->view('auth/register');
    }

    public function role() {
        $this->view('auth/role');
    }
}
