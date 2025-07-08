<?php


class FondsDisponiblesModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    /**
     * Récupère les fonds disponibles avec filtres de période
     */
    public function getFondsDisponiblesAvecFiltre($annee_debut = null, $mois_debut = null, $annee_fin = null, $mois_fin = null)
    {
        $sql = "SELECT 
                    annee,
                    mois,
                    SUM(montant_initial) as total_fonds_initiaux,
                    SUM(montant_prete) as total_prete,
                    SUM(montant_rembourse) as total_rembourse,
                    SUM(montant_initial) - SUM(montant_prete) + SUM(montant_rembourse) as montant_disponible
                FROM vue_fonds_disponibles
                WHERE 1=1";
        
        $params = [];

        if ($annee_debut) {
            if ($mois_debut) {
                $sql .= " AND (annee > :annee_debut OR (annee = :annee_debut AND mois >= :mois_debut))";
                $params[':mois_debut'] = $mois_debut;
            } else {
                $sql .= " AND annee >= :annee_debut";
            }
            $params[':annee_debut'] = $annee_debut;
        }

        if ($annee_fin) {
            if ($mois_fin) {
                $sql .= " AND (annee < :annee_fin OR (annee = :annee_fin AND mois <= :mois_fin))";
                $params[':mois_fin'] = $mois_fin;
            } else {
                $sql .= " AND annee <= :annee_fin";
            }
            $params[':annee_fin'] = $annee_fin;
        }

        $sql .= " GROUP BY annee, mois ORDER BY annee DESC, mois DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques globales des fonds
     */
    public function getStatistiquesFonds()
    {
        $sql = "SELECT 
                    SUM(montant_initial) as total_fonds_initiaux,
                    SUM(montant_prete) as total_prete,
                    SUM(montant_rembourse) as total_rembourse,
                    SUM(montant_initial) - SUM(montant_prete) + SUM(montant_rembourse) as montant_total_disponible,
                    AVG(montant_initial - montant_prete + montant_rembourse) as moyenne_mensuelle,
                    COUNT(*) as nombre_mois
                FROM vue_fonds_disponibles";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les détails par mois pour une période
     */
    public function getDetailsFonds($annee_debut = null, $mois_debut = null, $annee_fin = null, $mois_fin = null)
    {
        $sql = "SELECT * FROM vue_fonds_disponibles WHERE 1=1";
        $params = [];

        if ($annee_debut) {
            if ($mois_debut) {
                $sql .= " AND (annee > :annee_debut OR (annee = :annee_debut AND mois >= :mois_debut))";
                $params[':mois_debut'] = $mois_debut;
            } else {
                $sql .= " AND annee >= :annee_debut";
            }
            $params[':annee_debut'] = $annee_debut;
        }

        if ($annee_fin) {
            if ($mois_fin) {
                $sql .= " AND (annee < :annee_fin OR (annee = :annee_fin AND mois <= :mois_fin))";
                $params[':mois_fin'] = $mois_fin;
            } else {
                $sql .= " AND annee <= :annee_fin";
            }
            $params[':annee_fin'] = $annee_fin;
        }

        $sql .= " ORDER BY annee DESC, mois DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les années disponibles
     */
    public function getAnneesDisponibles()
    {
        $sql = "SELECT DISTINCT annee FROM vue_fonds_disponibles ORDER BY annee DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}