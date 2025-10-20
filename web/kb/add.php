<?php
session_start();
require_once "../config.php";

// Only agents/admins can access
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['agent', 'admin'])) {
    die("Access denied.");
}

$user = $_SESSION['user'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title && $content) {
        $stmt = $pdo->prepare("INSERT INTO articles (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);
        header("Location: articles.php");
        exit;
    } else {
        $message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Article - Knowledge Base</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="content-card">
        <h1 class="mb-4">Add New Article</h1>
        <a href="articles.php" class="btn btn-outline-secondary mb-3">Back to FAQ & Guides</a>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Title" required>
            </div>
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="8" placeholder="Content" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Add Article</button>
        </form>
    </div> <!-- End content-card -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
