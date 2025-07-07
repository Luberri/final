<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController {
    public static function getAll() {
        $prets = Pret::getAll();
        Flight::json($prets);
    }

    public static function getById($id) {
        $pret = Pret::getById($id);
        if ($pret) {
            Flight::json($pret);
        } else {
            Flight::json(['error' => 'Prêt non trouvé'], 404);
        }
    }

    public static function create() {
        $data = Flight::request()->data->getData();
        // Validation simple (à améliorer selon besoin)
        $required = ['client_id','montant','duree','type_remboursement_id','type_pret_id','status_pret_id'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                Flight::json(['error' => "Champ $field manquant"], 400);
                return;
            }
        }
        $id = Pret::create($data);
        Flight::json(['id' => $id, 'message' => 'Prêt ajouté avec succès']);
    }

    public static function update($id) {
        $data = Flight::request()->data->getData();
        $success = Pret::update($id, $data);
        if ($success) {
            Flight::json(['message' => 'Prêt modifié avec succès']);
        } else {
            Flight::json(['error' => 'Erreur lors de la modification'], 400);
        }
    }

    public static function delete($id) {
        $success = Pret::delete($id);
        if ($success) {
            Flight::json(['message' => 'Prêt supprimé avec succès']);
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression'], 400);
        }
    }
}
