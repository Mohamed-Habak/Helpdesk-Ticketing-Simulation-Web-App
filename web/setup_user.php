<?php
require_once "config.php";

$newPass = "user123"; // default admin password
$hash = hashPassword($newPass);

$stmt = $pdo->prepare("UPDATE users SET password=? WHERE username='user1'");
$stmt->execute([$hash]);

echo "user1 password set to: " . $newPass;
