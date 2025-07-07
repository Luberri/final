<?php
require 'vendor/autoload.php';
require 'db.php';

// Configuration des vues Flight
Flight::set('flight.views.path', __DIR__ . '/views');

// Démarrer la session
session_start();

// Configuration de Flight pour les vues
Flight::set('flight.views.path', __DIR__ . '/views');

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

// Route pour la page d'ajout de fonds
Flight::route('/ajout_fond.php', function() {
    Flight::render('ajout_fond');
});

// Route pour la page d'ajout de prêts
Flight::route('/ajout_pret.php', function() {
    Flight::render('ajout_pret');
});

// Inclure les routes
require_once 'routes/login.php';
require_once 'routes/etudiant.php';
require_once 'routes/interets.php';
require_once 'routes/form_select_routes.php';

Flight::start();
