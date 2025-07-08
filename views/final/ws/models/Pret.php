<?php
require_once __DIR__ . '/../db.php';

class Pret
{
    public static function create($data)
    {
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

    /**
     * Récupère tous les prêts avec les informations du client et du type de prêt
     */
    public static function getAllWithDetails()
    {
        $db = getDB();
        $sql = "SELECT 
                    p.id,
                    p.montant,
                    p.duree,
                    c.nom as client_nom,
                    c.prenom as client_prenom,
                    c.mail as client_mail,
                    tp.nom as type_pret,
                    tp.taux,
                    tr.nom as type_remboursement,
                    tr.mois as frequence_remboursement,
                    ROUND(p.montant * (
                        (tp.taux / 12 / 100) / 
                        (1 - POWER(1 + (tp.taux / 12 / 100), -p.duree))
                    ), 2) as mensualite_constante,
                    ROUND(p.montant * tp.taux / 100 * p.duree / 12, 2) as total_interets_estime
                FROM pret p
                JOIN client c ON p.client_id = c.id
                JOIN type_pret tp ON p.type_pret_id = tp.id
                JOIN type_remboursement tr ON p.type_remboursement_id = tr.id
                ORDER BY p.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les détails d'un prêt spécifique
     */
    public static function getById($id)
    {
        $db = getDB();
        $sql = "SELECT 
                    p.*,
                    c.nom as client_nom,
                    c.prenom as client_prenom,
                    c.mail as client_mail,
                    c.date_naissance,
                    cd.profession,
                    cd.revenu_mensuel,
                    cd.charge_mensuelle,
                    tp.nom as type_pret,
                    tp.detail as type_pret_detail,
                    tp.taux,
                    tr.nom as type_remboursement,
                    tr.mois as frequence_remboursement
                FROM pret p
                JOIN client c ON p.client_id = c.id
                LEFT JOIN client_detail cd ON c.id = cd.client_id
                JOIN type_pret tp ON p.type_pret_id = tp.id
                JOIN type_remboursement tr ON p.type_remboursement_id = tr.id
                WHERE p.id = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le tableau d'amortissement pour un prêt
     */
    public static function getTableauAmortissement($id)
    {
        $db = getDB();

        // D'abord récupérer les info du prêt
        $pret = self::getById($id);
        if (!$pret) return [];

        $montant = $pret['montant'];
        $duree = $pret['duree'];
        $tauxAnnuel = $pret['taux'];
        $tauxMensuel = $tauxAnnuel / 12 / 100;

        // Calculer la mensualité constante
        if ($tauxMensuel > 0) {
            $mensualite = $montant * ($tauxMensuel / (1 - pow(1 + $tauxMensuel, -$duree)));
        } else {
            $mensualite = $montant / $duree;
        }

        $tableau = [];
        $capitalRestant = $montant;

        for ($mois = 1; $mois <= $duree; $mois++) {
            $interetsMois = $capitalRestant * $tauxMensuel;
            $capitalMois = $mensualite - $interetsMois;

            $tableau[] = [
                'numero_mois' => $mois,
                'capital_restant' => round($capitalRestant, 2),
                'interets_mois' => round($interetsMois, 2),
                'mensualite_constante' => round($mensualite, 2),
                'date_mois' => date('Y-m-d', strtotime("+$mois months"))
            ];

            $capitalRestant -= $capitalMois;
            if ($capitalRestant < 0) $capitalRestant = 0;
        }

        return $tableau;
    }

    /**
     * Récupère les statistiques d'un prêt
     */
    public static function getStatistiquesPret($id)
    {
        $tableau = self::getTableauAmortissement($id);

        if (empty($tableau)) {
            return [
                'nombre_echeances' => 0,
                'total_interets' => 0,
                'premiere_echeance' => null,
                'derniere_echeance' => null
            ];
        }

        $totalInterets = array_sum(array_column($tableau, 'interets_mois'));

        return [
            'nombre_echeances' => count($tableau),
            'total_interets' => round($totalInterets, 2),
            'premiere_echeance' => $tableau[0]['date_mois'],
            'derniere_echeance' => $tableau[count($tableau) - 1]['date_mois']
        ];
    }
}
