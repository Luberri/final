<?php
require_once __DIR__ . '/../db.php';

class Simulation
{
    /**
     * Créer une nouvelle simulation
     */
    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO simulation (client_id, montant, duree, type_pret_id, taux_mensuel, delai_premier, assurance, mensualite, total_interets, cout_total_assurance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data->client_id,
            $data->montant,
            $data->duree,
            $data->type_pret_id,
            $data->taux_mensuel,
            $data->delai_premier ?? 0,
            $data->assurance ?? 0,
            $data->mensualite,
            $data->total_interets,
            $data->cout_total_assurance
        ]);
        
        return $db->lastInsertId();
    }

    /**
     * Récupérer toutes les simulations avec détails
     */
    public static function getAllWithDetails()
    {
        $db = getDB();
        $sql = "SELECT 
                    s.*,
                    c.nom as client_nom,
                    c.prenom as client_prenom,
                    c.mail as client_mail,
                    tp.nom as type_pret_nom,
                    tp.taux as taux_annuel
                FROM simulation s
                JOIN client c ON s.client_id = c.id
                JOIN type_pret tp ON s.type_pret_id = tp.id
                ORDER BY s.date_simulation DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une simulation par ID
     */
    public static function getById($id)
    {
        $db = getDB();
        $sql = "SELECT 
                    s.*,
                    c.nom as client_nom,
                    c.prenom as client_prenom,
                    c.mail as client_mail,
                    tp.nom as type_pret_nom,
                    tp.taux as taux_annuel
                FROM simulation s
                JOIN client c ON s.client_id = c.id
                JOIN type_pret tp ON s.type_pret_id = tp.id
                WHERE s.id = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Supprimer une simulation
     */
    public static function delete($id)
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM simulation WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Récupérer les simulations par client
     */
    public static function getByClient($clientId)
    {
        $db = getDB();
        $sql = "SELECT 
                    s.*,
                    tp.nom as type_pret_nom,
                    tp.taux as taux_annuel
                FROM simulation s
                JOIN type_pret tp ON s.type_pret_id = tp.id
                WHERE s.client_id = ?
                ORDER BY s.date_simulation DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}