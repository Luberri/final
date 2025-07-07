<?php

require_once 'controllers/PretController.php';

$pretController = new PretController();

// Routes API pour les prêts
Flight::route('GET /api/prets', [$pretController, 'getAllPrets']);
Flight::route('GET /api/prets/details', [$pretController, 'getDetailsPret']);
Flight::route('GET /api/prets/pdf', [$pretController, 'genererPDF']);

// Route pour la page d'affichage
Flight::route('/prets', [$pretController, 'afficherListePrets']);
