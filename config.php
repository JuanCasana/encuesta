<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'istcorac_encuesta_db');  
define('DB_USER', 'istcorac_user_encuesta');   
define('DB_PASS', 'Encuesta2025@');  

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
