<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
$message = "";

// Handle ticket submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];
    $diagContent = trim($_POST['diagnostics']); // optional

    if ($title && $description) {
        // Insert ticket
        $stmt = $pdo->prepare("INSERT INTO tickets (user_id, title, description, priority) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user['id'], $title, $description, $priority]);
        $ticketId = $pdo->lastInsertId();

        // If diagnostics provided, insert as a comment
        if ($diagContent) {
            $stmt = $pdo->prepare("INSERT INTO comments (ticket_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->execute([$ticketId, $user['id'], "[Diagnostics Report] " . $diagContent]);
        }

        header("Location: list.php");
        exit;
    } else {
        $message = "Please fill in the title and description.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Ticket - Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4 shadow-sm">
            <h1>Create New Ticket</h1>
            <p>Logged in as <strong><?php echo htmlspecialchars($user['username']); ?></strong></p>
            <div class="mb-3">
                <a href="list.php" class="btn btn-outline-primary me-2">Back to Dashboard</a>
            </div>
            <hr>

            <?php if ($message): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="mb-3">
                    <label class="form-label">Ticket Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="5" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Diagnostics Report (Optional)</label>
                    <textarea id="diagnostics" name="diagnostics" rows="6" class="form-control" placeholder="Paste or generate diagnostics..."></textarea>
                    <div class="form-text">This is optional. It will be added as a diagnostics comment on your ticket.</div>
                    <div class="form-text">Click "Generate Diagnostics" to auto-fill this field with system info. This is purely for simulation purposes</div>
                </div>

                <button type="button" class="btn btn-warning mb-3" onclick="generateDiagnostics()">Generate Diagnostics</button>
                <br>
                <button type="submit" class="btn btn-primary">Submit Ticket</button>
            </form>
        </div>
    </div>

    <script>
        function generateDiagnostics() {
            const now = new Date();
            const diag = `
User: <?php echo htmlspecialchars($user['username']); ?>
Date/Time: ${now.toLocaleString()}
Browser: ${navigator.userAgent}
Platform: ${navigator.platform}
Language: ${navigator.language}
Screen Resolution: ${screen.width}x${screen.height}
Cookies Enabled: ${navigator.cookieEnabled ? 'Yes' : 'No'}
            `.trim();

            document.getElementById('diagnostics').value = diag;
        }
    </script>
</body>
</html>
