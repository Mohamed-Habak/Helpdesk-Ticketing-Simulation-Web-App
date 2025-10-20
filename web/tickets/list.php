<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];

if ($user['role'] === 'agent' || $user['role'] === 'admin') {
    $stmt = $pdo->query("SELECT t.*, u.username 
                         FROM tickets t 
                         JOIN users u ON t.user_id = u.id 
                         ORDER BY t.created_at DESC");
} else {
    $stmt = $pdo->prepare("SELECT t.*, u.username 
                           FROM tickets t 
                           JOIN users u ON t.user_id = u.id 
                           WHERE t.user_id = ? 
                           ORDER BY t.created_at DESC");
    $stmt->execute([$user['id']]);
}

$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

function statusBadge($status) {
    $status = strtolower($status);
    $colors = [
        "open" => "warning",       // soft orange
        "assigned" => "info",      // soft blue
        "resolved" => "success",   // soft green
        "closed" => "secondary"    // gray
    ];
    $color = $colors[$status] ?? "dark";
    return "<span class='badge bg-$color'>" . ucfirst($status) . "</span>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tickets - Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="content-card p-4 shadow-sm rounded">
        <h1 class="mb-3">Tickets</h1>
        <p class="mb-3">Logged in as <strong><?php echo htmlspecialchars($user['username']); ?></strong> (<?php echo htmlspecialchars($user['role']); ?>)</p>
        
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="../dashboard.php" class="btn btn-outline-primary">Back to Dashboard</a>
        </div>

        <?php if (count($tickets) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Created</th>
                        <th>By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo $ticket['id']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['title']); ?></td>
                        <td><?php echo statusBadge($ticket['status']); ?></td>
                        <td><?php echo ucfirst($ticket['priority']); ?></td>
                        <td><?php echo $ticket['created_at']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['username']); ?></td>
                        <td>
                            <a href="view.php?id=<?php echo $ticket['id']; ?>" class="btn btn-sm btn-outline-info">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="alert alert-info">No tickets found.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
