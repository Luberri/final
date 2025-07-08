<?php

require_once 'models/FondsDisponiblesModel.php';

class FondsDisponiblesController
{
    private $model;

    public function __construct()
    {
        $this->model = new FondsDisponiblesModel();
    }

    /**
     * Récupère les fonds disponibles avec filtres
     */
    public function getFondsDisponiblesAvecFiltre()
    {
        try {
            $annee_debut = isset($_GET['annee_debut']) && $_GET['annee_debut'] !== '' ? (int)$_GET['annee_debut'] : null;
            $mois_debut = isset($_GET['mois_debut']) && $_GET['mois_debut'] !== '' ? (int)$_GET['mois_debut'] : null;
            $annee_fin = isset($_GET['annee_fin']) && $_GET['annee_fin'] !== '' ? (int)$_GET['annee_fin'] : null;
            $mois_fin = isset($_GET['mois_fin']) && $_GET['mois_fin'] !== '' ? (int)$_GET['mois_fin'] : null;

            $fonds = $this->model->getFondsDisponiblesAvecFiltre($annee_debut, $mois_debut, $annee_fin, $mois_fin);

            Flight::json([
                'success' => true,
                'data' => $fonds,
                'filters' => [
                    'periode_debut' => $annee_debut && $mois_debut ? "$mois_debut/$annee_debut" : null,
                    'periode_fin' => $annee_fin && $mois_fin ? "$mois_fin/$annee_fin" : null
                ]
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère les statistiques globales des fonds
     */
    public function getStatistiquesFonds()
    {
        try {
            $stats = $this->model->getStatistiquesFonds();
            Flight::json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère les détails des fonds
     */
    public function getDetailsFonds()
    {
        try {
            $annee_debut = $_GET['annee_debut'] ?? null;
            $mois_debut = $_GET['mois_debut'] ?? null;
            $annee_fin = $_GET['annee_fin'] ?? null;
            $mois_fin = $_GET['mois_fin'] ?? null;

            $details = $this->model->getDetailsFonds($annee_debut, $mois_debut, $annee_fin, $mois_fin);

            Flight::json([
                'success' => true,
                'data' => $details
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère les années disponibles
     */
    public function getAnneesDisponibles()
    {
        try {
            $annees = $this->model->getAnneesDisponibles();
            Flight::json([
                'success' => true,
                'data' => $annees
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Page d'affichage des fonds disponibles
     */
    public function afficherPageFondsDisponibles()
    {
        Flight::render('fonds_disponibles', [
            'title' => 'Fonds Disponibles - Tableau de Bord EF'
        ]);
    }
}