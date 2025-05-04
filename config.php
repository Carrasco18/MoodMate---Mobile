<?php
// Start PHP session at the top of every page
session_start();

// MySQL credentials – update these to match your setup
define('DB_HOST', 'localhost');
define('DB_NAME', 'auth_demo');
define('DB_USER', 'root');          // XAMPP default: root
define('DB_PASS', '');              // XAMPP default: (empty)

// Create a PDO instance for secure database access
try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    // If connection fails, show the error and stop
    exit("Database connection failed: " . $e->getMessage());
}
?>
