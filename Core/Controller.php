<?php
namespace App\Core;

class Controller {
    public function view($view, $data = []) {
        if (file_exists('../app/Views/' . $view . '.php')) {
            require_once '../app/Views/' . $view . '.php';
        } else {
            die("View does not exist: " . $view);
        }
    }
}
