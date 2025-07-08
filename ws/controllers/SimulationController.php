<?php
require_once __DIR__ . '/../models/Simulation.php';

class SimulationController
{
    /**
     * Parser les données de la requête
     */
    private static function parseRequestData()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (strpos($contentType, 'application/json') !== false) {
                $rawData = file_get_contents("php://input");
                $data = json_decode($rawData, false);
                return $data ?: (object)[];
            } else {
                return (object) $_POST;
            }
        }
        return (object) [];
    }

    /**
     * Créer une nouvelle simulation
     */
    public static function create()
    {
        try {
            $data = self::parseRequestData();
            
            // Validation des données requises
            if (!isset($data->client_id) || !isset($data->montant) || !isset($data->duree) || !isset($data->type_pret_id)) {
                Flight::json([
                    'success' => false,
                    'error' => 'Données manquantes : client_id, montant, duree et type_pret_id sont requis'
                ], 400);
                return;
            }

            // Validation des valeurs numériques
            if (!is_numeric($data->client_id) || !is_numeric($data->montant) || !is_numeric($data->duree) || !is_numeric($data->type_pret_id)) {
                Flight::json([
                    'success' => false,
                    'error' => 'Les valeurs doivent être numériques'
                ], 400);
                return;
            }

            $id = Simulation::create($data);
            
            Flight::json([
                'success' => true,
                'message' => 'Simulation sauvegardée avec succès',
                'simulation_id' => $id
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => 'Erreur lors de la sauvegarde de la simulation : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer toutes les simulations
     */
    public static function getAll()
    {
        try {
            $simulations = Simulation::getAllWithDetails();
            Flight::json([
                'success' => true,
                'data' => $simulations
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer une simulation par ID
     */
    public static function getById()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                Flight::json([
                    'success' => false,
                    'error' => 'ID de simulation requis'
                ], 400);
                return;
            }

            $simulation = Simulation::getById($id);
            if (!$simulation) {
                Flight::json([
                    'success' => false,
                    'error' => 'Simulation non trouvée'
                ], 404);
                return;
            }

            Flight::json([
                'success' => true,
                'data' => $simulation
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une simulation
     */
    public static function delete()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                Flight::json([
                    'success' => false,
                    'error' => 'ID de simulation requis'
                ], 400);
                return;
            }

            $deleted = Simulation::delete($id);
            if ($deleted) {
                Flight::json([
                    'success' => true,
                    'message' => 'Simulation supprimée avec succès'
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'error' => 'Simulation non trouvée'
                ], 404);
            }
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les simulations par client
     */
    public static function getByClient()
    {
        try {
            $clientId = $_GET['client_id'] ?? null;
            if (!$clientId) {
                Flight::json([
                    'success' => false,
                    'error' => 'ID client requis'
                ], 400);
                return;
            }

            $simulations = Simulation::getByClient($clientId);
            Flight::json([
                'success' => true,
                'data' => $simulations
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher la page de liste des simulations
     */
    public static function afficherPageSimulations()
    {
        Flight::render('simulations', [
            'title' => 'Liste des Simulations - EF Dashboard'
        ]);
    }
}