<?php

require_once 'models/InteretsModel.php';

class InteretsController
{
    private $model;

    public function __construct()
    {
        $this->model = new InteretsModel();
    }

    /**
     * Récupère les intérêts avec filtres
     */
    

    public function getInteretsAvecFiltre()
    {
        try {
            // Récupération et validation des paramètres
            $annee_debut = isset($_GET['annee_debut']) && $_GET['annee_debut'] !== '' ? (int)$_GET['annee_debut'] : null;
            $mois_debut = isset($_GET['mois_debut']) && $_GET['mois_debut'] !== '' ? (int)$_GET['mois_debut'] : null;
            $annee_fin = isset($_GET['annee_fin']) && $_GET['annee_fin'] !== '' ? (int)$_GET['annee_fin'] : null;
            $mois_fin = isset($_GET['mois_fin']) && $_GET['mois_fin'] !== '' ? (int)$_GET['mois_fin'] : null;

            // Debug des paramètres reçus
            error_log("Paramètres reçus - Début: $mois_debut/$annee_debut, Fin: $mois_fin/$annee_fin");

            $interets = $this->model->getInteretsAvecFiltre($annee_debut, $mois_debut, $annee_fin, $mois_fin);

            Flight::json([
                'success' => true,
                'data' => $interets,
                'filters' => [
                    'periode_debut' => $annee_debut && $mois_debut ? "$mois_debut/$annee_debut" : null,
                    'periode_fin' => $annee_fin && $mois_fin ? "$mois_fin/$annee_fin" : null
                ],
                // Ajout pour debug
                'debug' => [
                    'annee_debut' => $annee_debut,
                    'mois_debut' => $mois_debut,
                    'annee_fin' => $annee_fin,
                    'mois_fin' => $mois_fin
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
     * Récupère le détail des intérêts
     */
    public function getDetailInterets()
    {
        try {
            $annee_debut = $_GET['annee_debut'] ?? null;
            $mois_debut = $_GET['mois_debut'] ?? null;
            $annee_fin = $_GET['annee_fin'] ?? null;
            $mois_fin = $_GET['mois_fin'] ?? null;

            $details = $this->model->getDetailInterets($annee_debut, $mois_debut, $annee_fin, $mois_fin);

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
     * Récupère les statistiques globales
     */
    public function getStatistiques()
    {
        try {
            $stats = $this->model->getStatistiquesGlobales();
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
     * Récupère les intérêts par type de prêt
     */
    public function getInteretsParType()
    {
        try {
            $annee_debut = $_GET['annee_debut'] ?? null;
            $mois_debut = $_GET['mois_debut'] ?? null;
            $annee_fin = $_GET['annee_fin'] ?? null;
            $mois_fin = $_GET['mois_fin'] ?? null;

            $interets = $this->model->getInteretsParType($annee_debut, $mois_debut, $annee_fin, $mois_fin);

            Flight::json([
                'success' => true,
                'data' => $interets
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
     * Page d'affichage des intérêts
     */
    public function afficherPageInterets()
    {
        Flight::render('interets', [
            'title' => 'Intérêts Gagnés - Tableau de Bord EF'
        ]);
    }
}
