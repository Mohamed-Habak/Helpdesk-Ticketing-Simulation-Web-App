<?php
session_start();
require_once "../config.php";

// Only agents/admins can access
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['agent', 'admin'])) {
    die("Access denied.");
}

if (!isset($_GET['id'])) {
    die("Article ID missing.");
}

$articleId = (int)$_GET['id'];

// Delete article
$stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
$stmt->execute([$articleId]);

header("Location: articles.php");
exit;
