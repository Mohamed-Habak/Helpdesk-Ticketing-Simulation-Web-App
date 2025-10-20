<?php
require_once "config.php";

$newPass = "admin123"; // default admin password
$hash = hashPassword($newPass);

$stmt = $pdo->prepare("UPDATE users SET password=? WHERE username='admin'");
$stmt->execute([$hash]);

echo "Admin password set to: " . $newPass;
