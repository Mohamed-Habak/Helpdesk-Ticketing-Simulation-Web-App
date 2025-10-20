<?php
session_start();
require_once "../config.php";

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];

// Fetch all articles
$stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Knowledge Base</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="content-card p-4 shadow-sm rounded">
        <h1 class="mb-4">FAQ & Guides</h1>
        <a href="../dashboard.php" class="btn btn-outline-secondary mb-3">Back to Dashboard</a>

        <!-- Show Add Article button only for agents/admins -->
        <?php if ($user['role'] === 'agent' || $user['role'] === 'admin'): ?>
            <a href="add.php" class="btn btn-success mb-3">➕ Add New Article</a>
        <?php endif; ?>

        <?php if ($articles): ?>
            <div class="list-group">
                <?php foreach ($articles as $a): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="view.php?id=<?php echo $a['id']; ?>" class="text-decoration-none">
                                <h5 class="mb-1"><?php echo htmlspecialchars($a['title']); ?></h5>
                            </a>
                            <small class="text-muted">Created on <?php echo $a['created_at']; ?></small>
                        </div>

                        <!-- Edit/Delete buttons for agents/admins -->
                        <?php if ($user['role'] === 'agent' || $user['role'] === 'admin'): ?>
                            <div class="btn-group">
                                <a href="edit.php?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-primary me-1">Edit</a>
                                <a href="delete.php?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-primary"
                                   onclick="return confirm('Are you sure you want to delete this article?');">Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-3">No articles found.</div>
        <?php endif; ?>
    </div> <!-- End content-card -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
