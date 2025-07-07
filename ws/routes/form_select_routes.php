<?php
require_once __DIR__ . '/../models/Client.php';

Flight::route('GET /clients', ['ClientController', 'getAll']);
Flight::route('GET /type_remboursements', function() {
    Flight::json(TypeRemboursement::getAll());
});
Flight::route('GET /type_prets', function() {
    Flight::json(TypePret::getAll());
});
