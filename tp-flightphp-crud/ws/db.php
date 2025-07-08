<?php
function getDB() {
    // $host = 'localhost';
    // // $dbname = 'tp_flight';
    // $dbname = 'banque';
    // $username = 'root';
    // $password = '';
    
    $host = 'localhost';
    $dbname = 'db_s2_ETU003366';
    $username = 'ETU003366';
    $password = 'JXxcy7s2';


    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die(json_encode(['error' => $e->getMessage()]));
    }
}
