<?php
// Routes pour la gestion des étudiants
// TODO: Créer EtudiantController

// Middleware pour vérifier l'authentification
Flight::route('GET|POST|PUT|DELETE /etudiants*', function () {
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
        Flight::json(['error' => 'Accès non autorisé'], 401);
        return false;
    }
    return true;
});

// Routes CRUD pour les étudiants (protégées)
Flight::route('GET /etudiants', function () {
    // TODO: EtudiantController::getAll();
    Flight::json(['message' => 'Route étudiants - à implémenter']);
});

Flight::route('GET /etudiants/@id', function ($id) {
    // TODO: EtudiantController::getById($id);
    Flight::json(['message' => 'Route étudiant par ID - à implémenter', 'id' => $id]);
});

Flight::route('POST /etudiants', function () {
    // TODO: EtudiantController::create();
    Flight::json(['message' => 'Route création étudiant - à implémenter']);
});

Flight::route('PUT /etudiants/@id', function ($id) {
    // TODO: EtudiantController::update($id);
    Flight::json(['message' => 'Route mise à jour étudiant - à implémenter', 'id' => $id]);
});

Flight::route('DELETE /etudiants/@id', function ($id) {
    // TODO: EtudiantController::delete($id);
    Flight::json(['message' => 'Route suppression étudiant - à implémenter', 'id' => $id]);
});
