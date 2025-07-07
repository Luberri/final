<?php
require 'vendor/autoload.php';
require 'db.php';

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Route d'accueil personnalisée
// Flight::route('GET /localhost/final/ioty', function() {
//     echo '<h1>coucou</h1>';
//     echo '<a href="/ws/etudiants">Aller vers le CRUD des étudiants</a>';
// });

require 'routes/etudiant_routes.php';

Flight::start();
