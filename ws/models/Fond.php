<?php
require_once __DIR__ . '/../db.php';

class Fond {
    public static function ajouterFond($montant, $detail) {
        $db = getDB(); // Correction ici
        try {
            $db->beginTransaction();
            $stmt = $db->prepare('INSERT INTO fond (montant) VALUES (?)');
            $stmt->execute([$montant]);
            $fond_id = $db->lastInsertId();

            $stmt2 = $db->prepare('INSERT INTO fond_detail (fond_id, detail) VALUES (?, ?)');
            $stmt2->execute([$fond_id, $detail]);
            $fond_detail_id = $db->lastInsertId();

            $stmt3 = $db->prepare('INSERT INTO fond_historique (fond_detail_id, montant) VALUES (?, ?)');
            $stmt3->execute([$fond_detail_id, $montant]);

            $db->commit();
            return $fond_id;
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout du fond : ' . $e->getMessage()]);
            exit;
        }
    }
}
