<?php
require_once __DIR__ . '/../../core/core.php';
require_login();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Account</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="site-body auth-body">
    <!-- Shared navigation -->
    <?php require_once __DIR__ . '/../layout/header.php'; ?>

    <!-- Account summary -->
    <main class="auth-page home-panel">
        <h1>My Account</h1>
        <p class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'Customer'); ?>!</p>
    </main>

    <!-- Shared footer -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
