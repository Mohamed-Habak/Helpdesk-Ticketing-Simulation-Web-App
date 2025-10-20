<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];

if (!isset($_GET['id'])) {
    die("Article ID not provided.");
}

$articleId = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die("Article not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($article['title']); ?> - FAQ & Guides</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="content-card">
        <h1 class="mb-2"><?php echo htmlspecialchars($article['title']); ?></h1>
        <small class="text-muted">Created on <?php echo $article['created_at']; ?></small>
        <hr>
        <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
        <a href="articles.php" class="btn btn-secondary mt-3">Back to FAQ & Guides</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
