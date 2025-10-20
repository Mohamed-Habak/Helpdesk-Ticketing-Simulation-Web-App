<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];

if (!isset($_GET['id'])) {
    die("No ticket ID provided.");
}
$ticketId = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT t.*, u.username 
                       FROM tickets t 
                       JOIN users u ON t.user_id = u.id 
                       WHERE t.id = ?");
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    die("Ticket not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['content'])) {
    $content = trim($_POST['content']);
    $stmt = $pdo->prepare("INSERT INTO comments (ticket_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$ticketId, $user['id'], $content]);
    header("Location: view.php?id=" . $ticketId);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_status']) && $user['role'] !== 'user') {
    $newStatus = $_POST['new_status'];
    $stmt = $pdo->prepare("UPDATE tickets SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $ticketId]);
    header("Location: view.php?id=" . $ticketId);
    exit;
}

$stmt = $pdo->prepare("SELECT c.*, u.username 
                       FROM comments c 
                       JOIN users u ON c.user_id = u.id 
                       WHERE c.ticket_id = ? 
                       ORDER BY c.created_at ASC");
$stmt->execute([$ticketId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

function statusBadge($status) {
    $colors = [
        "open" => "warning",       // soft orange
        "assigned" => "info",      // soft blue
        "resolved" => "success",   // soft green
        "closed" => "secondary"    // gray
    ];
    $color = $colors[strtolower($status)] ?? "dark";
    return "<span class='badge bg-$color'>" . ucfirst($status) . "</span>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Ticket - Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="content-card p-4 shadow-sm rounded">
        <h2>Ticket #<?php echo $ticket['id']; ?> - <?php echo htmlspecialchars($ticket['title']); ?></h2>
        <p>
            <strong>Status:</strong> <?php echo statusBadge($ticket['status']); ?> |
            <strong>Priority:</strong> <?php echo ucfirst(htmlspecialchars($ticket['priority'])); ?> |
            <strong>Created by:</strong> <?php echo htmlspecialchars($ticket['username']); ?> |
            <strong>Date:</strong> <?php echo $ticket['created_at']; ?>
        </p>

        <h4>Description</h4>
        <div class="p-3 mb-3 bg-white border rounded shadow-sm">
            <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
        </div>

        <h4 class="mt-4">Comments</h4>
        <?php if ($comments): ?>
            <ul class="list-unstyled">
                <?php foreach ($comments as $c): ?>
                    <li class="mb-3 p-3 bg-white border rounded shadow-sm">
                        <strong><?php echo htmlspecialchars($c['username']); ?></strong>
                        <small class="text-muted">(<?php echo $c['created_at']; ?>)</small>
                        <div class="mt-2">
                            <?php
                            if (strpos($c['content'], '[Diagnostics Report]') === 0) {
                                $diag = trim(str_replace('[Diagnostics Report]', '', $c['content']));
                                ?>
                                <div class="p-3 border-start border-4 border-info bg-light text-dark"
                                     style="font-family: monospace; white-space: pre-wrap;">
                                    <strong>Diagnostics Report:</strong><br>
                                    <?php echo htmlspecialchars($diag); ?>
                                </div>
                            <?php
                            } else {
                                echo nl2br(htmlspecialchars($c['content']));
                            }
                            ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info">No comments yet.</div>
        <?php endif; ?>

        <h5 class="mt-4">Add a Comment</h5>
        <form method="post">
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post Comment</button>
        </form>

        <?php if ($user['role'] !== 'user'): ?>
            <h5 class="mt-4">Update Ticket Status</h5>
            <form method="post" class="d-flex gap-2 align-items-center">
                <select name="new_status" class="form-select w-auto" required>
                    <option value="">-- Select Status --</option>
                    <option value="open">Open</option>
                    <option value="assigned">Assigned</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
                <button type="submit" name="update_status" class="btn btn-outline-secondary">Update</button>
            </form>
        <?php endif; ?>

        <div class="mt-4 d-flex gap-2">
            <a href="list.php" class="btn btn-outline-primary">Back to Tickets</a>
            <a href="../dashboard.php" class="btn btn-outline-secondary">Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
