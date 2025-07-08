<?php
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/TypePret.php';

// Route pour la liste des clients
Flight::route('GET /clients', function() {
    Flight::json(Client::getAll());
});
Flight::route('GET /api/clients', function() {
    Flight::json(Client::getAll());
});

// Route pour la liste des types de remboursement
require_once __DIR__ . '/../models/Client.php'; // TypeRemboursement est dans Client.php
Flight::route('GET /type_remboursements', function() {
    Flight::json(TypeRemboursement::getAll());
});
Flight::route('GET /api/type_remboursements', function() {
    Flight::json(TypeRemboursement::getAll());
});

// Route pour la liste des types de prêt
Flight::route('GET /type_prets', function() {
    Flight::json(TypePret::getAll());
});
Flight::route('GET /api/type_prets', function() {
    Flight::json(TypePret::getAll());
});
