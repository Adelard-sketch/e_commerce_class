<?php
require_once "../core/core.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-body">
    <div class="auth-page">
        <h1>Login</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="auth-error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form id="loginForm" class="auth-form" action="../actions/login_action.php" method="POST" novalidate>
            <div class="form-group">
                <label for="login_email">Email</label>
                <input type="email" name="customer_email" id="login_email" placeholder="Enter your email">
                <div class="field-error" data-error-for="customer_email"></div>
            </div>
            <div class="form-group">
                <label for="login_pass">Password</label>
                <input type="password" name="customer_pass" id="login_pass" placeholder="Enter your password">
                <div class="field-error" data-error-for="customer_pass"></div>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
        </form>

        <p class="auth-link-text">
            Need an account? <a href="register.php">Register here</a>
        </p>
    </div>

    <script src="../js/validate.js"></script>
</body>
</html>
