<?php

class PretModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    /**
     * Récupère tous les prêts avec les informations du client et du type de prêt
     */
    public function getAllPrets()
    {
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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les détails d'un prêt spécifique
     */
    public function getPretById($id)
    {
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
                WHERE p.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le tableau d'amortissement pour un prêt
     */
    public function getTableauAmortissement($id)
    {
        $sql = "SELECT 
                    numero_mois,
                    capital_restant,
                    interets_mois,
                    mensualite_constante,
                    date_mois
                FROM vue_interets_mensuels
                WHERE pret_id = :id
                ORDER BY numero_mois";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques d'un prêt
     */
    public function getStatistiquesPret($id)
    {
        $sql = "SELECT 
                    COUNT(*) as nombre_echeances,
                    SUM(interets_mois) as total_interets,
                    MIN(date_mois) as premiere_echeance,
                    MAX(date_mois) as derniere_echeance
                FROM vue_interets_mensuels
                WHERE pret_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
