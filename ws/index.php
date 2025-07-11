<?php
require 'vendor/autoload.php';
require 'db.php';

// Démarrer la session
session_start();

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Route racine - redirection vers la page de login
Flight::route('/', function () {
    Flight::redirect('../index.php');
});

// Route pour tester l'API
Flight::route('/test', function () {
    Flight::json(['message' => 'API fonctionne correctement', 'timestamp' => date('Y-m-d H:i:s')]);
});

// Inclure les routes
require_once 'routes/login.php';
require_once 'routes/etudiant.php';

Flight::start();
