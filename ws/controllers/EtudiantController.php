<?php
require_once __DIR__ . '/../models/Etudiant.php';
require_once __DIR__ . '/../helpers/Utils.php';

class EtudiantController
{

    // Fonction helper pour parser les données POST/PUT
    private static function parseRequestData()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            // Pour POST, utiliser $_POST directement
            return (object) $_POST;
        } else if ($method === 'PUT') {
            // Pour PUT, lire le corps de la requête
            $rawData = file_get_contents("php://input");
            $data = [];
            parse_str($rawData, $data);
            return (object) $data;
        }

        return (object) [];
    }
    public static function getAll()
    {
        $etudiants = Etudiant::getAll();
        Flight::json($etudiants);
    }

    public static function getById($id)
    {
        $etudiant = Etudiant::getById($id);
        Flight::json($etudiant);
    }

    public static function create()
    {
        $data = self::parseRequestData();
        $id = Etudiant::create($data);
        Flight::json(['message' => 'Étudiant ajouté', 'id' => $id]);
    }

    public static function update($id)
    {
        $data = self::parseRequestData();
        Etudiant::update($id, $data);
        Flight::json(['message' => 'Étudiant modifié']);
    }

    public static function delete($id)
    {
        Etudiant::delete($id);
        Flight::json(['message' => 'Étudiant supprimé']);
    }
}
