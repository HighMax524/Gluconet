<?php

// Define Base URL
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', $scriptName);

// Autoloader
spl_autoload_register(function ($className) {
    $className = str_replace('App\\', '', $className);
    $path = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
    
    if (file_exists($path)) {
        require_once $path;
    }
});

// Helper for views
function view($view, $data = []) {
    extract($data);
    require_once __DIR__ . '/Views/' . $view . '.php';
}
