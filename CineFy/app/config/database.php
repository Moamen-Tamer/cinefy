<?php

if (session_status() === PHP_SESSION_NONE) session_start();

DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'cinefy');
DEFINE('DB_USER', 'root');
DEFINE('DB_PASS', '');

function db(): ?PDO {
    static $pdo = false;

    if ($pdo !== false) return $pdo;

    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    } catch (PDOException $ex) {
        $pdo = null;
    }

    return $pdo;
}

?>