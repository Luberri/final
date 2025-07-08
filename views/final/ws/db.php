<?php
function getDB() {
    $host = '172.60.0.18';
    $dbname = 'db_s2_ETU003192';
    $username = 'ETU003192';
    $password = 'SLdytGnN';

    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die(json_encode(['error' => $e->getMessage()]));
    }
}
