<?php
require_once __DIR__ . '/../db.php';

class Pret {
    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO pret (client_id, montant, duree, type_remboursement_id, type_pret_id, assurance) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->client_id,
            $data->montant,
            $data->duree,
            $data->type_remboursement_id,
            $data->type_pret_id,
            isset($data->assurance) ? $data->assurance : 0
        ]);
        $idPret = $db->lastInsertId();

        // Ajout dans status_pret avec la colonne delai
        $nom = isset($data->nom_type_pret) ? $data->nom_type_pret : null;
        $delai = isset($data->delai_premier) ? intval($data->delai_premier) : 0;
        $stmt2 = $db->prepare("INSERT INTO status_pret (id_pret, nom, date, delai) VALUES (?, ?, curdate(), ?)");
        $stmt2->execute([$idPret, $nom, $delai]);

        return $idPret;
    }
}
