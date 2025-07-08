<?php

class InteretsModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    /**
     * Récupère les intérêts gagnés avec filtres de période
     */
  public function getInteretsAvecFiltre($annee_debut = null, $mois_debut = null, $annee_fin = null, $mois_fin = null)
{
    $sql = "SELECT 
                annee, 
                mois, 
                SUM(interets_mois) as total_interets_mois, 
                COUNT(DISTINCT pret_id) as nombre_prets_actifs
            FROM vue_interets_mensuels vm
            WHERE 1=1";
    $params = [];

    if ($annee_debut !== null && $annee_debut !== '') {
        if ($mois_debut !== null && $mois_debut !== '') {
            $sql .= " AND (annee > :annee_debut OR (annee = :annee_debut AND mois >= :mois_debut))";
            $params[':mois_debut'] = (int)$mois_debut;
        } else {
            $sql .= " AND annee >= :annee_debut";
        }
        $params[':annee_debut'] = (int)$annee_debut;
    }

    if ($annee_fin !== null && $annee_fin !== '') {
        if ($mois_fin !== null && $mois_fin !== '') {
            $sql .= " AND (annee < :annee_fin OR (annee = :annee_fin AND mois <= :mois_fin))";
            $params[':mois_fin'] = (int)$mois_fin;
        } else {
            $sql .= " AND annee <= :annee_fin";
        }
        $params[':annee_fin'] = (int)$annee_fin;
    }

    $sql .= " GROUP BY annee, mois ORDER BY annee DESC, mois DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    /**
     * Récupère le détail des intérêts par prêt pour une période
     */
    public function getDetailInterets($annee_debut = null, $mois_debut = null, $annee_fin = null, $mois_fin = null)
    {
        $sql = "SELECT * FROM vue_interets_mensuels WHERE 1=1";
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

        $sql .= " ORDER BY annee DESC, mois DESC, nom, prenom";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques globales des intérêts
     */
    public function getStatistiquesGlobales()
    {
        $sql = "SELECT 
                    SUM(total_interets_mois) as total_interets_global,
                    AVG(total_interets_mois) as moyenne_mensuelle,
                    COUNT(*) as nombre_mois,
                    MIN(CONCAT(annee, '-', LPAD(mois, 2, '0'))) as premiere_periode,
                    MAX(CONCAT(annee, '-', LPAD(mois, 2, '0'))) as derniere_periode
                FROM vue_interets_par_periode";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les intérêts par type de prêt
     */
    public function getInteretsParType($annee_debut = null, $mois_debut = null, $annee_fin = null, $mois_fin = null)
    {
        $sql = "SELECT 
                    vm.pret_id,
                    tp.nom as type_pret,
                    tp.taux,
                    SUM(vm.interets_mois) as total_interets,
                    COUNT(DISTINCT vm.pret_id) as nombre_prets,
                    AVG(vm.capital_emprunte) as volume_moyen
                FROM vue_interets_mensuels vm
                JOIN pret p ON p.id = vm.pret_id
                JOIN type_pret tp ON tp.id = p.type_pret_id
                WHERE 1=1";
        $params = [];

        if ($annee_debut) {
            if ($mois_debut) {
                $sql .= " AND (vm.annee > :annee_debut OR (vm.annee = :annee_debut AND vm.mois >= :mois_debut))";
                $params[':mois_debut'] = $mois_debut;
            } else {
                $sql .= " AND vm.annee >= :annee_debut";
            }
            $params[':annee_debut'] = $annee_debut;
        }

        if ($annee_fin) {
            if ($mois_fin) {
                $sql .= " AND (vm.annee < :annee_fin OR (vm.annee = :annee_fin AND vm.mois <= :mois_fin))";
                $params[':mois_fin'] = $mois_fin;
            } else {
                $sql .= " AND vm.annee <= :annee_fin";
            }
            $params[':annee_fin'] = $annee_fin;
        }

        $sql .= " GROUP BY tp.nom, tp.taux ORDER BY total_interets DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les années disponibles dans les données
     */
    public function getAnneesDisponibles()
    {
        $sql = "SELECT DISTINCT annee FROM vue_interets_mensuels ORDER BY annee DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
