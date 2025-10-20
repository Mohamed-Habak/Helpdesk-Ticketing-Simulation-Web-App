<?php
session_start();
require_once "config.php";

$error = "";

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Helpdesk System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4e73df, #1cc88a);
            min-height: 100vh;
        }
        .card-login {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            border-radius: 15px;
        }
        .lead-text {
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card shadow-lg card-login">
        <h2 class="text-center mb-4">Welcome to Helpdesk</h2>
        <p class="text-center lead lead-text">Submit your issues, track tickets, and get support efficiently.</p>

        <?php if (isset($_SESSION['user'])): ?>
            <div class="text-center mt-4">
                <p class="fw-bold">Hello, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>
                <a href="dashboard.php" class="btn btn-primary me-2">Go to Dashboard</a>
                <a href="logout.php" class="btn btn-outline-secondary">Logout</a>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
