<?php
// config/db.php — single shared MySQL connection (PDO)

$DB_HOST = 'localhost';
$DB_NAME = 'campusfind';
$DB_USER = 'root';
$DB_PASS = '';   // XAMPP default: empty password for root

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}
