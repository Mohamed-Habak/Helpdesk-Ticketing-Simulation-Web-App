<?php
session_start();
require_once "config.php";

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Ticket counts
if ($user['role'] === 'user') {
    // Only tickets created by this user
    $totalTickets = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE user_id = ?");
    $totalTickets->execute([$user['id']]);
    $totalTickets = $totalTickets->fetchColumn();

    $openTickets = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE user_id = ? AND status='open'");
    $openTickets->execute([$user['id']]);
    $openTickets = $openTickets->fetchColumn();

    $resolvedTickets = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE user_id = ? AND status='resolved'");
    $resolvedTickets->execute([$user['id']]);
    $resolvedTickets = $resolvedTickets->fetchColumn();
} else {
    // All tickets for agents/admins
    $totalTickets = $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
    $openTickets  = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status='open'")->fetchColumn();
    $resolvedTickets = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status='resolved'")->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="content-card p-4 shadow-sm rounded">
        <h1 class="mb-3">Dashboard</h1>

        <div class="d-flex flex-wrap gap-2 navigation-buttons">
            <a href="tickets/list.php" class="btn btn-outline-secondary">View All Tickets</a>
            <a href="tickets/new.php" class="btn btn-outline-secondary">Create New Ticket</a>
            <a href="kb/articles.php" class="btn btn-outline-secondary">FAQ & Guides</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-dark">Logout</a>
            </div>
        </div>

        <h2 class="mt-4 mb-3">Ticket Stats</h2>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card text-center bg-light text-dark shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Tickets</h5>
                        <p class="card-text fs-4"><?php echo $totalTickets; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-light text-dark shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Open Tickets</h5>
                        <p class="card-text fs-4"><?php echo $openTickets; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-light text-dark shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Resolved Tickets</h5>
                        <p class="card-text fs-4"><?php echo $resolvedTickets; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End content-card -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
