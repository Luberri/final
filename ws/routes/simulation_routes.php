<?php
require_once __DIR__ . '/../controllers/SimulationController.php';
require_once __DIR__ . '/../controllers/ComparaisonController.php';

// Routes API pour les simulations
Flight::route('POST /api/simulations', ['SimulationController', 'create']);
Flight::route('GET /api/simulations', ['SimulationController', 'getAll']);
Flight::route('GET /api/simulations/details', ['SimulationController', 'getById']);
Flight::route('DELETE /api/simulations', ['SimulationController', 'delete']);
Flight::route('GET /api/simulations/client', ['SimulationController', 'getByClient']);

// Route pour la page d'affichage
Flight::route('/simulations', ['SimulationController', 'afficherPageSimulations']);

// Route pour la comparaison des simulations
Flight::route('POST /comparaison-simulations', ['ComparaisonController', 'afficherComparaison']);