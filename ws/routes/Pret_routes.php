<?php
require_once __DIR__ . '/../controllers/PretController.php';

// Routes API pour les prêts
Flight::route('GET /api/prets', ['PretController', 'getAllPrets']);
Flight::route('GET /api/prets/details', ['PretController', 'getDetailsPret']);
Flight::route('GET /api/prets/pdf', ['PretController', 'genererPDF']);
Flight::route('POST /api/prets', ['PretController', 'create']);

// Route pour la page d'affichage
Flight::route('/prets', ['PretController', 'afficherListePrets']);
