<?php
require_once __DIR__ . '/../models/LoginModel.php';

class LoginController
{
    public static function login()
    {
        $data = self::parseRequestData();

        if (empty($data->nom) || empty($data->mdp)) {
            Flight::json(['error' => 'Nom et mot de passe requis'], 400);
            return;
        }

        try {
            // Utiliser la méthode authenticate qui vérifie nom ET mot de passe
            $admin = LoginModel::authenticate($data->nom, $data->mdp);

            if ($admin) {
                // Démarrer la session si elle n'existe pas
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_nom'] = $admin['nom'];
                $_SESSION['is_logged_in'] = true;

                Flight::json([
                    'success' => true,
                    'message' => 'Connexion réussie',
                    'admin' => [
                        'id' => $admin['id'],
                        'nom' => $admin['nom']
                    ]
                ]);
            } else {
                Flight::json(['error' => 'Nom d\'utilisateur ou mot de passe incorrect'], 401);
            }
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur de base de données: ' . $e->getMessage()], 500);
        }
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        Flight::json(['message' => 'Déconnexion réussie']);
    }

    public static function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            Flight::json([
                'authenticated' => true,
                'admin' => [
                    'id' => $_SESSION['admin_id'],
                    'nom' => $_SESSION['admin_nom']
                ]
            ]);
        } else {
            Flight::json(['authenticated' => false], 401);
        }
    }

    private static function parseRequestData()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            return (object) $_POST;
        } else if ($method === 'PUT' || $method === 'DELETE') {
            $rawData = file_get_contents("php://input");
            $data = [];
            parse_str($rawData, $data);
            return (object) $data;
        }

        return (object) [];
    }
}
