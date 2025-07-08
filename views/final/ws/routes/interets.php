<?php

require_once 'controllers/InteretsController.php';

$interetsController = new InteretsController();

// Routes API pour les intérêts
Flight::route('GET /api/interets', [$interetsController, 'getInteretsAvecFiltre']);
Flight::route('GET /api/interets/detail', [$interetsController, 'getDetailInterets']);
Flight::route('GET /api/interets/statistiques', [$interetsController, 'getStatistiques']);
Flight::route('GET /api/interets/par-type', [$interetsController, 'getInteretsParType']);
Flight::route('GET /api/interets/annees', [$interetsController, 'getAnneesDisponibles']);

// Route pour la page d'affichage
Flight::route('/interets', [$interetsController, 'afficherPageInterets']);
