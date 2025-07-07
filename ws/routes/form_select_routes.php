<?php
require_once __DIR__ . '/../db.php';

// Retourne tous les clients
Flight::route('GET /form/clients', function() {
    $db = getDB();
    $res = $db->query('SELECT id, nom, prenom FROM client');
    Flight::json($res->fetchAll(PDO::FETCH_ASSOC));
});
// Retourne tous les types de remboursement
Flight::route('GET /form/types_remboursement', function() {
    $db = getDB();
    $res = $db->query('SELECT id, nom FROM type_remboursement');
    Flight::json($res->fetchAll(PDO::FETCH_ASSOC));
});
// Retourne tous les types de prêt
Flight::route('GET /form/types_pret', function() {
    $db = getDB();
    $res = $db->query('SELECT id, nom FROM type_pret');
    Flight::json($res->fetchAll(PDO::FETCH_ASSOC));
});
// Retourne tous les statuts de prêt
Flight::route('GET /form/status_pret', function() {
    $db = getDB();
    $res = $db->query('SELECT id, nom FROM status_pret');
    Flight::json($res->fetchAll(PDO::FETCH_ASSOC));
});
