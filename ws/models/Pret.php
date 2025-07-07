<?php
require_once __DIR__ . '/../db.php';

class Pret {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query('SELECT * FROM pret');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM pret WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO pret (client_id, montant, duree, type_remboursement_id, type_pret_id, status_pret_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['client_id'],
            $data['montant'],
            $data['duree'],
            $data['type_remboursement_id'],
            $data['type_pret_id'],
            $data['status_pret_id']
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare('UPDATE pret SET client_id = ?, montant = ?, duree = ?, type_remboursement_id = ?, type_pret_id = ?, status_pret_id = ? WHERE id = ?');
        return $stmt->execute([
            $data['client_id'],
            $data['montant'],
            $data['duree'],
            $data['type_remboursement_id'],
            $data['type_pret_id'],
            $data['status_pret_id'],
            $id
        ]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM pret WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
