<?php
require_once __DIR__ . '/../db.php';

class LoginModel {
    public static function authenticate($nom, $mdp) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM admin WHERE nom = ? AND mdp = ?");
        $stmt->execute([$nom, $mdp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findAdminByNom($nom) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM admin WHERE nom = ?");
        $stmt->execute([$nom]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function verifyPassword($inputPassword, $storedPassword) {
        // Comparaison simple pour l'instant
        return $inputPassword === $storedPassword;
    }
    
    // Méthode alternative plus sécurisée
    public static function authenticateSecure($nom, $mdp) {
        $admin = self::findAdminByNom($nom);
        if ($admin && self::verifyPassword($mdp, $admin['mdp'])) {
            return $admin;
        }
        return false;
    }
}