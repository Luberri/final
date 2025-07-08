<?php
require_once __DIR__ . '/../models/TypePret.php';

class TypePretController {
    public static function ajouter() {
        $data = json_decode(file_get_contents('php://input'), true);
        $nom = isset($data['nom']) ? $data['nom'] : null;
        $detail = isset($data['detail']) ? $data['detail'] : '';
        $taux = isset($data['taux']) ? $data['taux'] : null;
        if ($nom === null || $taux === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Nom et taux requis']);
            return;
        }
        $id = TypePret::ajouter($nom, $detail, $taux);
        echo json_encode(['success' => true, 'type_pret_id' => $id]);
    }
}
