<?php
require_once __DIR__ . '/../controllers/EtudiantController.php';
require_once __DIR__ . '/../controllers/FondController.php';
require_once __DIR__ . '/../controllers/TypePretController.php';

Flight::route('GET /etudiants', ['EtudiantController', 'getAll']);
Flight::route('GET /etudiants/@id', ['EtudiantController', 'getById']);
Flight::route('POST /etudiants', ['EtudiantController', 'create']);
Flight::route('PUT /etudiants/@id', ['EtudiantController', 'update']);
Flight::route('DELETE /etudiants/@id', ['EtudiantController', 'delete']);

// Route pour ajout de fond (admin)
Flight::route('POST /fonds', ['FondController', 'ajouterFond']);

// Route pour création de type de prêt
Flight::route('POST /typeprets', ['TypePretController', 'ajouter']);
