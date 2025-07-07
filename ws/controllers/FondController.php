<?php
require_once __DIR__ . '/../models/Fond.php';

class FondController {
    public static function ajouterFond() {
        $data = json_decode(file_get_contents('php://input'), true);
        $montant = isset($data['montant']) ? $data['montant'] : null;
        $detail = isset($data['detail']) ? $data['detail'] : '';
        if ($montant === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Montant requis']);
            return;
        }
        $id = Fond::ajouterFond($montant, $detail);
        echo json_encode(['success' => true, 'fond_id' => $id]);
    }
}
