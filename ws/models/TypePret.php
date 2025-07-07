<?php
require_once __DIR__ . '/../db.php';
class TypePret {
    public static function ajouter($nom, $detail, $taux) {
         $db = getDB();
        try {
            $stmt = $db->prepare('INSERT INTO type_pret (nom, detail, taux) VALUES (?, ?, ?)');
            $stmt->execute([$nom, $detail, $taux]);
            return $db->lastInsertId();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => "Erreur lors de l'ajout du type de prêt : " . $e->getMessage()]);
            exit;
        }
    }
}
