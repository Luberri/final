<?php
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../controllers/FondController.php';
require_once __DIR__ . '/../controllers/TypePretController.php';

// Route pour la connexion (POST)
Flight::route('POST /login', function () {
    LoginController::login();
});

// Route pour vérifier l'authentification (GET)
Flight::route('GET /check-auth', function () {
    LoginController::checkAuth();
});

// Route pour la déconnexion (POST)
Flight::route('POST /logout', function () {
    LoginController::logout();
});

// Route alternative pour la déconnexion (GET)
Flight::route('GET /logout', function () {
    LoginController::logout();
});

// Route pour révoquer toutes les sessions (sécurité supplémentaire)
Flight::route('POST /revoke-all-sessions', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    Flight::json(['message' => 'Toutes les sessions ont été révoquées']);
});

// Route pour forcer l'expiration de session quand on revient à la page de connexion
Flight::route('POST /expire-session', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    Flight::json(['message' => 'Session expirée avec succès']);
});

// Route pour afficher le dashboard (GET)
Flight::route('GET /dashboard', function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
        Flight::redirect('../index.php');
        return;
    }
    Flight::render('dashboard');
});

Flight::route('POST /fonds', ['FondController', 'ajouterFond']);

// Route pour création de type de prêt
Flight::route('POST /typeprets', ['TypePretController', 'ajouter']);