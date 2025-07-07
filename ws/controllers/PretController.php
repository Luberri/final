<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController {
    public static function getAll() {
        echo json_encode(Pret::getAll());
    }

    public static function getById($id) {
        $pret = Pret::getById($id);
        if ($pret) {
            echo json_encode($pret);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Prêt non trouvé']);
        }
    }

    public static function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = Pret::create($data);
        echo json_encode(['id' => $id]);
    }

    public static function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $ok = Pret::update($id, $data);
        echo json_encode(['success' => $ok]);
    }

    public static function delete($id) {
        $ok = Pret::delete($id);
        echo json_encode(['success' => $ok]);
    }
}
