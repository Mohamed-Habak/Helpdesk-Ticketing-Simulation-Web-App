<?php
// config.php
$host = "localhost";
$db   = "helpdesk";
$user = "root";   // default in XAMPP
$pass = "";       // default in XAMPP (empty password)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// Helper: secure password hashing
function hashPassword($plain) {
    return password_hash($plain, PASSWORD_BCRYPT);
}

// Helper: verify password
function verifyPassword($plain, $hash) {
    return password_verify($plain, $hash);
}
?>
