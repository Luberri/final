<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController {
    private static function parseRequestData() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            return (object) $_POST;
        } else if ($method === 'PUT') {
            $rawData = file_get_contents("php://input");
            $data = [];
            parse_str($rawData, $data);
            return (object) $data;
        }
        return (object) [];
    }

    public static function create() {
        $data = self::parseRequestData();
        try {
            $id = Pret::create($data);
            Flight::json(['message' => 'Prêt ajouté', 'id' => $id]);
        } catch (Exception $e) {
            echo '<pre style="color:red;">' . $e->getMessage() . '</pre>';
            Flight::json(['error' => 'Erreur lors de l\'ajout du prêt : ' . $e->getMessage()], 500);
        }
    }
}
