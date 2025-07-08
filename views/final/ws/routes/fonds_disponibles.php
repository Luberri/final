<?php


require_once 'controllers/FondsDisponiblesController.php';

$fondsController = new FondsDisponiblesController();

// Routes API pour les fonds disponibles
Flight::route('GET /api/fonds-disponibles', [$fondsController, 'getFondsDisponiblesAvecFiltre']);
Flight::route('GET /api/fonds-disponibles/statistiques', [$fondsController, 'getStatistiquesFonds']);
Flight::route('GET /api/fonds-disponibles/details', [$fondsController, 'getDetailsFonds']);
Flight::route('GET /api/fonds-disponibles/annees', [$fondsController, 'getAnneesDisponibles']);

// Route pour la page d'affichage
Flight::route('/fonds-disponibles', [$fondsController, 'afficherPageFondsDisponibles']);