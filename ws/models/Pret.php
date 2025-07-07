<?php
require_once __DIR__ . '/../db.php';

class Pret {
    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO pret (client_id, montant, duree, type_remboursement_id, type_pret_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->client_id,
            $data->montant,
            $data->duree,
            $data->type_remboursement_id,
            $data->type_pret_id
        ]);
        return $db->lastInsertId();
    }
}
